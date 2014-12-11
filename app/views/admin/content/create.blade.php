@extends('layouts.admin.auth')

@section('content')

<div class="form-content">
{{ Form::open(array('route' => array('admin.content.store'), 'role' => 'form')) }}

@include('admin.content._form')

{{ Form::submit('запиши урок и добави видео', array('class' => 'btn btn-success')) }}
{{ Form::close() }}
</div>
@stop