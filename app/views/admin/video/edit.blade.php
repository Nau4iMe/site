@extends('layouts.admin.auth')

<script type="text/javascript" src="{{ URL::asset('js/scripts.js') }}"></script>

@section('content')
{{ Form::open(array('route' => array('admin.video.user.destroy', $video->id), 'method' => 'delete')) }}
{{ Form::submit('изтрий това видео', array('class' => 'btn btn-danger')) }}
{{ Form::close() }}

{{ Form::model($video, array(
    'route' => array('admin.video.update', $video->id),
    'method' => 'put',
    'role' => 'form'
)) }}

<!-- Edit form -->
<div class="form-group">
    <label for="youtube">Youtube видео ID:</label>
    {{ Form::text('youtube', null, array('id' => 'youtube', 'class' => 'form-control')) }}
    @if ($errors->has('youtube'))
        <div class="alert alert-danger">
            {{ $errors->first('youtube') }}
        </div>
    @endif
</div>
<div class="form-group">
    <label for="name">Заглавие/описание (незадължително):</label>
    {{ Form::text('name', null, array('id' => 'name', 'class' => 'form-control')) }}
    @if ($errors->has('name'))
        <div class="alert alert-danger">
            {{ $errors->first('name') }}
        </div>
    @endif
</div>

{{ Form::submit('промени', array('class' => 'btn btn-success')) }}

{{ Form::close() }}

<!-- <div class="alert alert-info">
    {{ HTML::video($video->youtube) }}
</div> -->

@stop
