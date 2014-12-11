@extends('layouts.admin.auth')

@section('content')

<a class="btn btn-success" href="{{ URL::to('/admin/category/create') }}">добавяне на нова категория</a>
@if(count($categories) > 0)
<table class="table table-hover">
    <thead>
        <tr>
            <th></th>
            <th width="20%"></th>
            <th width="5%" colspan="2" class="text-center">подредба</th>
            <th width="5%"></th>
            <th width="5%"></th>
            <th width="5%"></th>
        </tr>
    </thead>
    @foreach($categories as $key => $cat)
    <tr>
        <td>
            <a href="{{ URL::to('/admin/category/' . $cat->id) . '/edit' }}">
                {{ str_repeat('——', $cat->depth - 1) , '&nbsp;' , $cat->title }}
            </a>
        </td>
        <td>
            {{ $cat->type }}
        </td>
        <td>
            <a class="btn btn-warning" href="{{ URL::to('/admin/category/' . $cat->id) . '/up' }}">
                <span class="glyphicon glyphicon-arrow-up"></span>
            </a>
        </td>
        <td>
            <a class="btn btn-warning" href="{{ URL::to('/admin/category/' . $cat->id) . '/down' }}">
                <span class="glyphicon glyphicon-arrow-down"></span>
            </a>
        </td>
        <td>
            <a class="btn btn-info" href="{{ URL::to('/admin/category/' . $cat->id) . '/edit' }}">промени</a>
        </td>
        <td>
            {{ Form::open(array('route' => array('admin.category.destroy', $cat->id), 'method' => 'delete')) }}
            {{ Form::submit('изтрий', array('class' => 'btn btn-danger')) }}
            {{ Form::close() }}
        </td>
    </tr>
    @endforeach
</table>
@else
<div class="alert alert-warning">
    <p>Не намирам никакви добавени категории... :(</p>
</div>
@endif
@stop