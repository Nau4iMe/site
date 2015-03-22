@extends('layouts.admin.auth')

@section('content')

<div class="form-content">
@include('admin.video._upload')
@include('admin.content._videos')
@include('admin.content._forum')

{{ Form::model($content, array(
    'route' => array('admin.content.' . (Session::get('is_admin') === false ? 'user.' : null)
                    . 'update', $content->id),
    'method' => 'put',
    'role' => 'form'
)) }}

@include('admin.content._form', array('id' => $content->id))

{{ Form::submit('промени', array('class' => 'btn btn-success')) }}
{{ Form::close() }}
</div>
@stop