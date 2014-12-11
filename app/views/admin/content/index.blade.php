@extends('layouts.admin.auth')

@section('content')
<a class="btn btn-success" href="{{ URL::route('admin.content.user.create') }}">Добавяне на нов урок</a>

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
            <a href="{{ URL::to('admin/content/' . (Session::get('is_admin') === false ? 'user/' : null)
                                . $content->id . '/edit') }}">
                {{ $content->title }}
            </a>
        </td>
        <td>
            <a class="btn btn-info" href="{{ URL::to(
                'admin/content/'. (Session::get('is_admin') === false ? 'user/' : null) . $content->id . '/edit'
            ) }}">
                промени
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