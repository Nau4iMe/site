@extends('layouts.public.extendable')

@section('content')
<div id="sitemap">
    <ol class="breadcrumb">
        <li><i class="fa fa-terminal"></i></li>
        <li>{{ HTML::link('/', 'Начало') }}</li>
        <li>{{ HTML::link('sitemap', 'Карта на сайта') }}</li>
    </ol>
@if (count($nodes))
    @foreach ($nodes as $node)
    <div class="level-{{ $node->depth }}">
    <h3><i class="fa fa-sitemap"></i>
        {{ HTML::page_or_link($node->path, $node->title) }}
    </h3>
    @foreach ($node->contents as $content)
    <p>
        <i class="fa fa-share-square"></i>
        {{ HTML::link_to_content(
            array('id' => $content->id, 'title' => $content->title, 'slug' => $content->slug, 'path' => $node->path)
        ) }}
    </p>
    @endforeach
    </div>
    @endforeach
@endif
</div>
@stop