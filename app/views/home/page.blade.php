@extends('layouts.public.extendable')

@section('content')

@if (count($ancestors))
<ol class="breadcrumb">
    <li><i class="fa fa-terminal"></i></li>
    @foreach($ancestors as $v)
    <li>
    {{ HTML::page_or_link($v->path, $v->title) }}
    </li>
    @endforeach
</ol>
@endif

@if (count($descendants))
<hr />
<div class="list-group">
    <div class="list-group-item active">
        <h4>
            <span class="fa fa-folder-o"></span>&nbsp;&nbsp;
            {{ count($descendants) }} {{ count($descendants) > 1 ? 'подкатегории намерени' : 'подкатегория намерена' }}
            в категория <strong>{{ $v->title }}</strong>
        </h4>
    </div>

@foreach ($descendants as $v)
    {{ HTML::page_or_link($v->path, $v->title, array('class' => 'list-group-item')) }}
@endforeach
</div>


<hr/>
@endif


@if (count($contents) > 0)
<div class="alert alert-success">
    <h5>
        <span class="fa fa-folder-open-o"></span>
        {{ count($contents) }} {{ count($contents) > 1 ? 'урока' : 'урок' }}
        в категория <strong>{{ $category->title }}</strong>
    </h5>
</div>
<table class="table">
    <thead>
        <th>
            <a href="?sort=added&amp;by={{ $by }}">
                добавен {{ isset($order) && $order == 'added'
                    ? '<i class="fa fa-sort-amount-' . ($by == 'desc' ? 'desc' : 'asc') . '"></i>'
                    : null }}
            </a>
        </th>
        <th>
            <a href="?sort=title&amp;by={{ $by }}">
                име {{ isset($order) && $order == 'title'
                    ? '<i class="fa fa-sort-alpha-' . ($by == 'desc' ? 'desc' : 'asc') . '"></i>'
                    : null }}
            </a>
        </th>
        <th class="text-center">
            <a href="?sort=author&amp;by={{ $by }}">
                автор {{ isset($order) && $order == 'author'
                    ? '<i class="fa fa-sort-alpha-' . ($by == 'desc' ? 'desc' : 'asc') . '"></i>'
                    : null }}
            </a>
        </th>
        <th class="text-center">
            <a href="?sort=hits&amp;by={{ $by }}">
                гледания {{ isset($order) && $order == 'hits'
                    ? '<i class="fa fa-sort-numeric-' . ($by == 'desc' ? 'desc' : 'asc') . '"></i>'
                    : null }}
            </a>
        </th>
    </thead>
    @foreach($contents as $v)
    <tr>
        <td>
            {{ $v->created_at->format('Y-m-d') }}
        </td>
        <td>
            {{ HTML::link_to_content(array('path' => $category->path, 'id' => $v->id,
                                            'title' => $v->title, 'slug' => $v->slug)) }}
        </td>
        <td class="text-center">
            {{ $v->created_by_alias }}
        </td>
        <td class="text-center">
            {{ $v->hits }}
        </td>
    </tr>
    @endforeach
</table>
@endif

<div class="text-center">
    {{ $contents->links() }}
</div>
@stop