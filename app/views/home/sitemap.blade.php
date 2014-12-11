@extends('layouts.public.extendable')

@section('content')
    <div id="sitemap">
        <ol class="breadcrumb">
            <li><i class="fa fa-terminal"></i></li>
            <li>{{ HTML::link('/', 'Начало') }}</li>
            <li>{{ HTML::link('sitemap', 'Карта на сайта') }}</li>
        </ol>
        {{ $html }}
    </div>
@stop