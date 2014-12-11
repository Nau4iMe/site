@extends('layouts.admin.auth')

@section('content')

@if (count($videos))
    <table class="table table-hover">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th width="5%"></th>
                <th width="5%"></th>
            </tr>
        </thead>
    @foreach($videos as $video)
        <tr>
            <td>
                <a href="{{ URL::route('admin.video.' .
                    (!Session::get('is_admin') ? 'user.' : null) .
                'show', $video->id) }}" >{{ $video->name }}</a>
            </td>
            <td>{{ $video->member_name }}</td>
            <td><a href="{{ URL::route('admin.video.' .
                    (!Session::get('is_admin') ? 'user.' : null) .
                'show', $video->id) }}" class="btn btn-info">преглед</a></td>
            <td>
                {{ Form::open(array('route' => array('admin.video.' .
                        (!Session::get('is_admin') ? 'user.' : null)
                    . 'destroy', $video->id), 'method' => 'delete')) }}
                {{ Form::submit('изтрий', array('class' => 'btn btn-danger')) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
    </table>
@endif

<div class="text-center">
{{ $videos->links() }}
</div>
@stop