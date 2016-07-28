@extends('apps.master')

@section('h_title')
    <title>{{ $title }}</title>
@stop

@section('content')
	<div>
	
	    <div class="custom-title text-left">
	        <h2 class="title">{{ $title }}</h2>
	        <p class="sub_title">发布日期:{{ $created_at }}</p>
	    </div>
	</div>
    <!-- 富文本内容 -->
   <div class="custom-richtext">{!! $banner_info !!}</div>
@stop