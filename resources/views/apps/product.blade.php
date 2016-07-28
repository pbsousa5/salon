@extends('apps.master')

@section('h_title')
    <title>{{ $product_name}}</title>
@stop

@section('content')
	<div>
	
	    <div class="custom-title text-left">
	        <h2 class="title">商品名称:{{ $product_name }}</h2>
	    </div>
	</div>
    <!-- 富文本内容 -->
   <div class="custom-richtext">{!! $rich_desc !!}</div>
@stop