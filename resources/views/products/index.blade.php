@extends('layouts.app')

@section('page-title', trans('app.permissions'))
@section('page-heading', trans('app.permissions'))

@section ('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('app.potential_product')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <form action="" method="GET" id="products-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Input::get('search') }}"
                               placeholder="search_for_products">

                        <span class="input-group-append">
                                @if (Input::has('search') && Input::get('search') != '')
                                <a href="{{ route('products.index') }}"
                                   class="btn btn-light d-flex align-items-center text-muted"
                                   role="button">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-users-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('products.index','excel=1') }}"  class="btn btn-primary  float-right">
                        <i class="bi bi-download "> download all</i>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                        </svg>
                    </a>
                </div>
            </div>

        </form>

        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-striped table-borderless">
                <thead>
                    <tr>
                        <th class="min-width-80">@lang('app.product_id')</th>
                        <th class="min-width-80">@lang('app.product_rank')</th>
                        <th class="min-width-80">@lang('app.product_thumbnail')</th>
                        <th class="min-width-80">@lang('app.product_url')</th>
                        <th class="min-width-80">@lang('app.product_price')</th>
                        <th class="min-width-100">@lang('app.product_original_title')</th>
                        <th class="min-width-200">@lang('app.product_original_description')</th>
                        <th class="min-width-100">@lang('app.product_chinese_title')</th>
                        <th class="min-width-200">@lang('app.product_chinese_description')</th>
{{--                        <th class="max-width-0">@lang('app.product_image')</th>--}}
                        <th class="text-center min-width-240">@lang('app.action')</th>
                    </tr>
                </thead>

                <tbody>
                @if (count($products))
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->rank }}</td>
                            <td>
                                <img style="height: 80px; width: 80px;"
                                     onmouseover="this.style.cursor='pointer';this.style.cursor='hand'"
                                         onmouseout="this.style.cursor='default'"
                                         src="{{ $product->thumbnail }}"
                                         onclick="javascript:showimage('{{ $product->thumbnail }}')" />
                            </td>
                            <td><button type="button" class="btn btn-link" >
                                    <a href={{ $product->url }}>detail</a></button>
                            </td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->original_title }}</td>
                            <td>{{ $product->original_description }}</td>
                            <td>{{ $product->chinese_title }}</td>
                            <td>{{ $product->chinese_description }}</td>
                            <td class="text-center">

                                <a type="button" class="btn btn-icon" data-toggle="popover" title="Product link"
                                        data-content="{{ $product->url }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                </a>


                                <a href="{{ route('products.downloadImage','url='.$product->thumbnail) }}"  class="btn btn-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                    </svg>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4"><em>@lang('app.no_records_found')</em></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
{!! $products->render() !!}
@stop

<div class="modal fade bs-example-modal-lg text-center" id="imgModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" >
    <div class="modal-dialog modal-lg" style="display: inline-block; width: auto;">
        <div class="modal-content">
            <img  id="imgInModalID"
                  class="carousel-inner img-responsive img-rounded"
                  onclick="closeImageViewer()"
                  onmouseover="this.style.cursor='pointer';this.style.cursor='hand'"
                  onmouseout="this.style.cursor='default'"
            />
        </div>
    </div>
</div>
<script type="text/javascript">
    //显示大图
    function showimage(source)
    {
        $("#imgModal").find("#imgInModalID").attr("src",source);
        $("#imgModal").modal();
    }
    //关闭
    function closeImageViewer(){
        $("#imgModal").modal('hide');
    }
</script>
