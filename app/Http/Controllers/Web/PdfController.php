<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanguard\Pdflog;
use function GuzzleHttp\Promise\all;
use setasign\Fpdi\Fpdi;
use Ordinary9843\Ghostscript;

class PdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $userId = \Auth::user()->id;

        $last = Pdflog::where([['user_id','=', $userId],['type','=',1]])->orderby('created_at', 'desc')->first();

        if(!empty($last)){
            $last = $last->toArray();
            return view('pdf.index', [
                'created_at' => $last['created_at'], 'name' => $last['name'],
                "downUrl" => route('pdf.download'),
                "editUrl" => route('pdf.edit')]);
        }else{
            return view('pdf.index', [
                'created_at' =>'', 'name' => '',
                "downUrl" => route('pdf.download'),
                "editUrl" => route('pdf.edit')]);
        }

    }

    public function upload(Request $request)
    {

        if (!$request->hasFile('file')) {
            return false;
        }

//        $result = request()->validate(['file' => 'file|size:100']);
//        if (!$result) { return "File is too large"; }

        $userId = \Auth::user()->id;

        $filenameWithExt = $request->file('file')->getClientOriginalName();

        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

        $extension = $request->file('file')->getClientOriginalExtension();

        $fileNameToStore = $filename . '-' . $userId . '-' . time() . '.' . $extension;
        // save pdf
        $request->file('file')->move('upload/pdf', $fileNameToStore);

        try {
            // save pdf log
            $pdflog = new Pdflog;
            $pdflog->user_id = $userId;
            $pdflog->name = $fileNameToStore;
            $pdflog->type = 1; // upload type
            $pdflog->save();

        } catch (\Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        return response()->json(['success'=>'You have successfully upload file.']);
    }

    public function download(Request $request)
    {
        $userId = \Auth::user()->id;

        $filename = $request->get('file');

        $file_path = public_path('upload/pdf/') . $filename;

        $type = $request->get('type');  // 1 Edited

        //dd($filename);
        //download fileEdited
        if($type){
            $basename = basename($filename,'.pdf');

            $filenameEdited = $basename . '-Edited' . '.pdf';

            $file_path = public_path('upload/pdf/') .$filenameEdited;

            $editData = Pdflog::where([['user_id','=', $userId],
                ['name','=',$filenameEdited],['type','=',2]])->first();

            if(empty($editData)){
                $errors = 'File edited data can not find';
                return redirect()->route('pdf.index')->withErrors($errors);
                //return view('pdf.index',compact('errors'));
                //response()->json(['errors'=>'File edited data can not find']);
            }

        }

        if (file_exists($file_path)) {
            //download file
            return response()->download($file_path);

        } else{
            //return response()->json(['errors'=>'File is not exist']);
            $errors = 'File is not exist';
            return redirect()->route('pdf.index')->withErrors($errors);
        }
    }

    public function edit(Request $request)
    {
        $data= $request->all();
        //dd($data);
        $userId = \Auth::user()->id;

        $filePath = public_path("upload/pdf/".$data['file']);

        $basename = basename($data['file'],'.pdf');

        $outputFilePath = public_path("upload/pdf/".$basename.'-Edited'.'.pdf');

        $this->fillPDFFile($filePath, $outputFilePath, $data);

        try {
        $pdflog = new Pdflog;
        $pdflog->user_id = $userId;
        $pdflog->name = $basename.'-Edited'.'.pdf';
        $pdflog->type = 2; // upload type
        $pdflog->product_id = $data['product_id'];
        $pdflog->quantity = $data['quantity'];
        $pdflog->stock_location = $data['stock_location'];
        $pdflog->ean_number = $data['ean_number'];
        $pdflog->save();

        } catch (\Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        $success = 'You have successfully Edit file';
        return redirect()->route('pdf.index')->withSuccess($success);
    }


    public function fillPDFFile($file, $outputFilePath, $data)
    {
        //fix  different pdf versions  STABLE_VERSION default 1.4  and adjust  according enviroment
        //$binPath ='C:\Program Files\gs\gs10.01.2\bin\gswin64c'; //Window enviroment need to test
        $binPath = '/usr/bin/gs';    //uniix enviroment test is ok
        $tmpPath = public_path("upload/pdf/tmp");
        $ghostscript = new Ghostscript($binPath, $tmpPath);
        $ghostscript->convert($file, Ghostscript::STABLE_VERSION);
        // $ghostscript->deleteTmpFile();

        $fpdi = new FPDI;

        $count = $fpdi->setSourceFile($file);

        for ($i=1; $i<=$count; $i++) {

            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $fpdi->SetFont("helvetica", "", 15);
            $fpdi->SetTextColor(153,0,153);

            $left = 10;
            $top = 160;
            $text = "ProductID:".$data['product_id'];
            $fpdi->Text($left,$top,$text);

            $left = 80;
            $top = 160;
            $text = "Quantity:".$data['quantity'];
            $fpdi->Text($left,$top,$text);

            $left = 120;
            $top = 160;
            $text = "Stock_Location:".$data['stock_location'];
            $fpdi->Text($left,$top,$text);

            $imageUrl = "https://barcode.tec-it.com/barcode.ashx?data=".$data['ean_number']."&code=Code128&translate-esc=true";

            $object = file_get_contents($imageUrl);  //red remote img
            $image_path = public_path("upload/pdf/barcode/".$data['ean_number'].".gif");  //save path use origin type
            file_put_contents($image_path, $object); // save on local from img url

            $fpdi->Image($image_path, 120, 210);
        }

        return $fpdi->Output($outputFilePath, 'F');
    }

}