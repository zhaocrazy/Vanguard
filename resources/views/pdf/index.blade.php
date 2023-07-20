@extends('layouts.app')

@section('page-title', trans('app.permissions'))
@section('page-heading', trans('app.permissions'))

@section ('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('app.permissions')
    </li>
@stop

@section('content')

@include('partials.messages')


{!! Form::open(['route' => 'pdf.edit', 'files' => true, 'id' => 'pdf-form']) !!}

<div class="card">
    <div class="card-body">

        <div class="row mb-6 pb-6 border-bottom-light">
            <div class="col-lg-6">
                <div class="text-center" >
                    <button href="" class="btn btn-primary">
                        <i class="bi bi-wrench"></i>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wrench" viewBox="0 0 16 16">
                            <path d="M.102 2.223A3.004 3.004 0 0 0 3.78 5.897l6.341 6.252A3.003 3.003 0 0 0 13 16a3 3 0 1 0-.851-5.878L5.897 3.781A3.004 3.004 0 0 0 2.223.1l2.141 2.142L4 4l-1.757.364L.102 2.223zm13.37 9.019.528.026.287.445.445.287.026.529L15 13l-.242.471-.026.529-.445.287-.287.445-.529.026L13 15l-.471-.242-.529-.026-.287-.445-.445-.287-.026-.529L11 13l.242-.471.026-.529.445-.287.287-.445.529-.026L13 11l.471.242z"/>
                        </svg>
                        Pdf Tool
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6" style="margin-top: 30px">

            <div class="form-group" data-toggle="modal"  data-target="#choose-modal">
                <label for="name"><p class="font-weight-bold"> Click Here To Upload PDF File </p></label>

                <p>LAST UPLOAD AT:&nbsp;&nbsp;&nbsp;&nbsp;{{$created_at}} &nbsp;&nbsp;&nbsp;&nbsp;FILE NAME:&nbsp;&nbsp;&nbsp;&nbsp;{{$name}}</p>
                <input type="hidden" id="filename" name="file" value="{{$name}}">
                <button  type="button" class="btn btn-danger form-control" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                        <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                    </svg>
                </button>
{{--                <input id="pdf-upload" type="file" accept="application/pdf" class="btn btn-danger form-control" οnChange="UploadFile()" name="pdfname" >--}}
            </div>

            {!! Form::open(['route' => 'pdf.upload', 'files' => true, 'id' => 'originPdf-form']) !!}
            @include('pdf.upload',['uploadUrl' => route('pdf.upload')])
            {!! Form::close() !!}

            <div class="form-group">
                <label for="product_id">Product ID</label>
                <input type="text" class="form-control" id="product_id"
                       name="product_id" placeholder="Product ID" value="">
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="text" class="form-control" id="quantity"
                       name="quantity" placeholder="Quantity" value="">
            </div>
            <div class="form-group">
                <label for="stock_location">Stock Location</label>
                <input type="text" class="form-control" id="stock_location"
                       name="stock_location" placeholder="Stock Location" value="">
            </div>
            <div class="form-group">
                <label for="ean_number">EAN Number</label>
                <input type="text" class="form-control" id="ean_number"
                       name="ean_number" placeholder="EAN Number" value="">
            </div>

            <div class="form-group" style="margin: 55px 0">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                         role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                         style="width: 0%">
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-6 d-inline-flex  text-center">
            <div class="row" style="margin: 0 40px">
                <div class="col-md-2" data-url="{{ $downUrl  }}">
                    <button type="button" class="btn btn-danger" id="downloadPDF">Download current PDF</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2" style="margin: 0 40px" data-url="{{ $editUrl  }}">
                    <button type="submit" value="Submit"  class="btn btn-warning" id="editPDF">Edit PDF</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2" style="margin: 0 40px" data-url="{{ $downUrl  }}">
                    <button type="button" class="btn btn-primary" id="downloadEeditPDF">Download Edited PDF</button>
                </div>
            </div>
        </div>

    </div>
</div>



{!! Form::close() !!}
@stop

@section('scripts')
    {!! HTML::script('assets/js/jquery.form-4.3.0.min.js') !!}
    {!! HTML::script('assets/js/as/pdf.js') !!}
    {!! JsValidator::formRequest('Vanguard\Http\Requests\Pdf\EditPdfRequest', '#pdf-form') !!}
@stop

