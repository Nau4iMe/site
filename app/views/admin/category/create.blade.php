@extends('layouts.admin.auth')

@section('content')
{{ Form::open(array('route' => array('admin.category.store'), 'role' => 'form', 'class' => 'form-category')) }}

@include('admin.category._form')

{{ Form::submit('добави', array('class' => 'btn btn-success')) }}
{{ Form::close() }}

@stop