@extends('layouts.admin.auth')

@section('content')

<div class="col-md-2">
    <a class="btn btn-success" href="{{ URL::route('admin.content.user.create') }}">Добавяне на нов урок</a>
</div>

{{ Form::open(array('route' => 'admin.content.search', 'method' => 'get')) }}
<div class="col-md-9">
    {{ Form::text('find', null, array('class' => 'form-control', 'placeholder' => 'търси')) }}
</div>
<div class="col-md-1">
    {{ Form::submit('търси', array('class' => 'btn btn-primary')) }}
</div>
{{ Form::close() }}
<div class="clearfix"></div>

@if(count($contents) > 0)
<table class="table table-hover">
    <thead>
        <tr>
            <th></th>
            <th width="5%"></th>
            <th width="5%"></th>
        </tr>
    </thead>
    @foreach($contents as $content)
    <tr class="{{ ($content->state == 0 ? 'list-group-item-danger' : null) }}">
        <td>
            <a href="{{ URL::route('admin.content' .
                    (Session::get('is_admin') === false ? '.user' : null) .
                '.edit', $content->id) }}">{{ $content->title }}
            </a>
        </td>
        <td>
            <a class="btn btn-info" href="{{ URL::route('admin.content' .
                    (Session::get('is_admin') === false ? '.user' : null) .
                '.edit', $content->id) }}">промени
            </a>
        </td>
        <td>
            {{ Form::open(array('route' => array('admin.content.' .
                    (!Session::get('is_admin') ? 'user.' : null)
                . 'destroy', $content->id), 'method' => 'delete')) }}
            {{ Form::submit('изтрий', array('class' => 'btn btn-danger')) }}
            {{ Form::close() }}
        </td>
    </tr>
    @endforeach
</table>
@else
<p></p>
<div class="alert alert-warning">
    <p>Не намирам никакви уроци... :(</p>
</div>
@endif

<div class="text-center">
{{ $contents->links() }}
</div>
@stop