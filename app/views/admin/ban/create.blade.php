@extends('layouts.admin.auth')

@section('content')

<script type="text/javascript">
    $(document).ready(function() {
        $('#find_user').click(function() {
            $('#results').html('зареждам');
            $.ajax({
                url: "{{ URL::route('admin.ban.findUser') }}",
                type: "POST",
                dataType: "json",
                data: { search : $('#user').val(), '_token' : "<?php echo csrf_token() ?>" }
            }).success(function(data) {
                $('#results').html('{{ Form::open(array("route" => array("admin.ban.store"))) }}' +
                    '<p class="alert alert-success text-left"><span class="glyphicon glyphicon-screenshot"></span> ' +
                    '<strong>' + data.username + '</strong></p>' +
                    '<input type="hidden" name="user_id" value="' + data.id + '">' +
                    '<label class="col-md-2 control-label">причина: </label>' +
                    '<div class="col-md-7">' +
                    '<input placeholder="причина" type="text" name="reason" class="form-control" /> ' +
                    '</div><input type="submit" class="btn btn-danger" value="бан"/>{{ Form::close() }}');
            }).fail(function() {
                $('#results').html('<p class="alert alert-danger">Няма намерени потребители</p>');
            });
        })
    });
</script>
<div class="form-ban">
    <div class="col-md-10">
        {{ Form::text('user', '', array('id' => 'user', 'class' => 'form-control', 'placeholder' => 'търси потребител')) }}
    </div>
    {{ Form::button('търси', array('class' => 'btn btn-success col-md-2', 'id' => 'find_user')) }}
    <div class="clearfix"></div>
    <div id="results"></div>
</div>

@stop