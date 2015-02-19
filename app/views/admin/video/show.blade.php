@extends('layouts.admin.auth')

<!-- player skin -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/flowplayer-5.4.6/skin/playful.css') }}">

<!-- flowplayer depends on jQuery 1.7.1+ (for now) -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('js/scripts.js') }}"></script>

<!-- include flowplayer -->
<script type="text/javascript" src="{{ URL::asset('packages/flowplayer-5.4.6/flowplayer.min.js') }}"></script>

@section('content')
{{ Form::open(array('route' => array('admin.video.user.destroy', $video->id), 'method' => 'delete')) }}
{{ Form::submit('изтрий това видео', array('class' => 'btn btn-danger')) }}
{{ Form::close() }}
<div class="alert alert-info">
    {{ HTML::video($video->youtube) }}
</div>
@stop