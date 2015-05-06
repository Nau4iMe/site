@extends('layouts.admin.auth')

@section('content')

@if (count($videos))
    <table class="table table-hover">
        <thead>
            <tr>
                <th>видео</th>
                <th>урок</th>
                <th>добавил</th>
                <th width="5%"></th>
                <th width="5%"></th>
            </tr>
        </thead>
    @foreach($videos as $video)
        <tr>
            <td>
                <a href="{{ URL::route('admin.video.edit', $video->id) }}" >{{ $video->name }}</a>
            </td>
            <td>
                <a href="{{ URL::route('admin.content.user.edit', $video->content_id) }}">
                    {{ $video->title }}
                </a>
            </td>
            <td>{{ $video->member_name }}</td>
            <td>
                <a href="{{ URL::route('admin.video.edit', $video->id) }}" class="btn btn-info">промяна</a>
            </td>
            <td>
                {{ Form::open(array('route' => array('admin.video.user.destroy', $video->id), 'method' => 'delete')) }}
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