@extends('layouts.public.extendable')

@section('content')
<div class="alert alert-info">
    <p><i class="glyphicon glyphicon-pushpin"></i>&nbsp;Здравейте и добре дошли в Научи ме!</p>
    <p>Тук ще намерите голям брой видео и текстови уроци. Главно, но не изцяло, на тема програмиране.</p>
    <p>Имате въпрос, питане или просто не знаете колко е часа? Моля, обърнете се към в нашия <a href="http://nau4i.me/forum">форум</a>.</p>
</div>
<?php $x = 0; ?>
@foreach($contents as $v)
<?php $x++; ?>
<div class="content-block col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h2 class="panel-title text-capitalize">
                {{ HTML::link_to_content(array('path' => $v->path, 'id' => $v->id,
                                                'title' => $v->title, 'slug' => $v->slug)) }}
            </h2>
        </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>добавен:</dt>
                <dd>{{ $v->created_at }}</dd>

                <dt>прегледи:</dt>
                <dd>{{ $v->hits }}</dd>

                <dt>автор:</dt> 
                <dd><a href="#">{{ $v->created_by_alias }}</a></dd>
            </dl>
            <p>{{ strip_tags(stripslashes(htmlspecialchars_decode($v->introtext))) }}</p>

        {{ HTML::link_to_content(array('path' => $v->path, 'id' => $v->id,
                'title' => 'към урока <i class="fa fa-graduation-cap"></i>',
                'slug' => $v->slug, 'class' => 'btn btn-default btn-lg btn-block', 'atitle' => $v->title)) }}
        </div>
    </div>
</div>
<?php if ($x == 2) : ?>
    <div class="clearfix"></div>
    <?php $x = 0; ?>
<?php endif; ?>
    @endforeach
<div class="clearfix"></div>
<div class="text-center">
    {{ $contents->links() }}
</div>
@stop
