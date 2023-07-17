<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\PotentialProducts;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\PotentialProduct\PotentialProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Translate;

class PotentialProductController extends Controller
{
    /**
     * @var PotentialProductRepository
     */
    private $potentialProduct;

    /**
     * PotentialProductController constructor.
     * @param PotentialProductRepository $potentialProduct
     */
    public function __construct(PotentialProductRepository $potentialProduct)
    {
        $this->middleware('auth');
        $this->potentialProduct = $potentialProduct;
    }
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $products = $this->potentialProduct->paginate($perPage = 5, Input::get('search'));

        $excel = $request->get('excel');
        $data = $products->toArray();
        //dd($data);
        if($excel && $data['total'] != 0) {
            $columns = Schema::getColumnListing('potential_products');
            $this->download($columns, $data['data']);
            exit();
        }

        return view('products.index',compact('products'));

    }

    /**
     * download the specified resource from storage.
     */
    public function download($title=array(),$data=array())
    {
        $file_path = base_path() . "\storage\productInfo.csv";
        $file = fopen($file_path,"w");
        fwrite($file, chr(0xEF).chr(0xBB).chr(0xBF)); // 添加 BOM
        //csv header title
        fputcsv($file, $title);
        // csv content
        foreach ($data as $v){
            fputcsv($file, $v);
        }
        fclose($file);

        //download csv
        $file = fopen($file_path,"rb");
        header("Content-type:application/octet-stream");
        header("Content-Disposition:attachment;");
        header("Content-Transfer-Encoding: bytes");
        Header("Content-Disposition: attachment; filename=productInfo.csv");
        echo fread($file, filesize($file_path));
        fclose($file);
    }

    /**
     * download the image  from storage.
     * @param \Illuminate\Http\Request  $request
     *
     */
    public function downloadImage(Request $request)
    {

        $url = $request->get('url');

//       img 403 issue
//       $opts = array(
//            'http'=>array(
//                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
//                'method'=>"GET",
//                'header'=> implode("\r\n", array(
//                    'Content-type: text/plain;'
//                ))
//            )
//        );
//        $context = stream_context_create($opts);
//        $object = file_get_contents($url,false,$context);

        $object = file_get_contents($url);  //red remote img
        if(!$object)
        {
            exit("open image is false");
        }
        $file_path = base_path() . '/storage/product_temp.jpg';  //save path
        file_put_contents($file_path, $object); // save on local from img url

        //download csv
        $file = fopen($file_path,"rb");
        header("Content-type:application/octet-stream");
        header("Content-Disposition:attachment;");
        header("Content-Transfer-Encoding: bytes");
        Header("Content-Disposition: attachment; filename=productInfo.csv");
        echo fread($file, filesize($file_path));
        fclose($file);

    }

    /**
     * Crawl potential product resource from third website.
     */
    public function spider()
    {
        // https://allegro.pl/  https://www.fruugo.us/  those websites  i  cant crawl because 403 Forbidden
        // even though wasting lots of time and  i have tried  to use some VPS and  buy ip servers , cant fix it
        // so i give up and use https://movie.douban.com/top250 to show

        $targetUrl = "https://movie.douban.com/top250";

        //fake browser user-agent
        $headers = [
            'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
        ];
        $client = new Client([
            'timeout' => 20,
            'headers' => $headers
        ]);

        //send request to get pages' content
        $response = $client->request('GET', $targetUrl)->getBody()->getContents();

        $data = [];
        $crawler = new Crawler();
        $crawler->addHtmlContent($response);

        $nodes = $crawler->filterXPath('//*[@id="content"]/div/div[1]/ol[@class="grid_view"]/li');
        dump('node number：'.$nodes->count());
        sleep(2);
        if($nodes->count() != 0) {
            try {
                $crawler->filterXPath('//*[@id="content"]/div/div[1]/ol[@class="grid_view"]/li')
                    ->each(function (Crawler $node, $i) use (&$data) {
                        sleep(2);
                        $item = [
                            'rank'=> $node->filterXPath('//div[@class="item"]//em')->text(),
                            'thumbnail' => $node->filterXPath('//div[@class="item"]//a/img')->attr('src'),
                            'url' => $node->filterXPath('//div[@class="item"]//a')->attr('href'),
                            'price'=> $node->filterXPath('//span[@class="rating_num"]')->text(),
                            'original_title' => $node->filterXPath('//div[@class="info"]/div[@class="hd"]/a/span[1]')->text(),
                            'original_description'=> $node->filterXPath('//p[@class="quote"]//span')->text(),
                            'chinese_title' => $node->filterXPath('//div[@class="info"]/div[@class="hd"]/a/span[1]')->text(),
                            'chinese_description'=> $node->filterXPath('//p[@class="quote"]//span')->text(),
                            //'english_title' => Translate::setDriver('baidu')->translate($node->filterXPath('//div[@class="info"]/div[@class="hd"]/a/span[1]')->text()),
                            //'english_description'=> Translate::setDriver('baidu')->translate($node->filterXPath('//p[@class="quote"]//span')->text()),
                            'created_at'=> Carbon::now(),
                        ];

                        $detailUrl = $node->filterXPath('//div[@class="item"]//a')->attr('href');
                        if(!empty($detailUrl)){
                            $item['image'] = $this->spiderExtraImage($detailUrl);
                        }
                        $data[] = $item;
                    });

            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }

        //print_r($data);
        //PotentialProducts::truncate();
        $res = PotentialProducts::insert($data);

        dump($res ? 'crawl data is successful' : 'crawl data is fail');
    }


    public function spiderExtraImage($detailUrl)
    {
        $headers = [
            'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
        ];
        $client = new Client([
            'timeout' => 20,
            'headers' => $headers
        ]);

        //send request to get pages' content
        $response = $client->request('GET', $detailUrl)->getBody()->getContents();

        $data = [];
        $crawler = new Crawler();
        $crawler->addHtmlContent($response);

        $nodes = $crawler->filterXPath('//*[@id="celebrities"]/ul/li');
        //dump('child node number：'.$nodes->count());
        if($nodes->count() != 0){
            try {
                $crawler->filterXPath('//*[@id="celebrities"]/ul/li')
                    ->each(function (Crawler $node, $i) use (&$data) {

                        $style = $node->filterXPath('//a/div')->attr('style');
                        preg_match('/https?:\/{2}[-A-Za-z0-9+&@#\/\%?=~_|!:,.;]+[-A-Za-z0-9+&@#\/\%=~_|]/i', $style, $item);
                        $data[] = $item[0];
                    });

            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }

        return empty($data) ? '' : implode(';',$data);

    }

}
