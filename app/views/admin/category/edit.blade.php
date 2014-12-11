@extends('layouts.admin.auth')

@section('content')

{{ Form::model($category, array(
    'route' => array('admin.category.update', $category->id),
    'method' => 'put',
    'role' => 'form', 'class' => 'form-category'
)) }}
@include('admin.category._form', array('id' => $category->id))

{{ Form::submit('промени', array('class' => 'btn btn-success')) }}
{{ Form::close() }}
@stop