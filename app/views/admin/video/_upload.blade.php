<h4>Добавяне на видео</h4>
{{ Form::open(array('route' => array('admin.video.store', $content->id))) }}
<div class="form-group" id="uploader">
    <p class="alert alert-info">
        Въведете YouTube видео ID - https://www.youtube.com/watch?v=<strong>WodbdCxrISo</strong>
    </p>
    <div class="upload-form">
        {{ Form::open(array('route' => 'admin.content.video')) }}
        <div class="col-md-10">
            <input type="text" name="youtube" required="required" class="form-control" />
        </div>
        <div class="col-md-2">
            {{ Form::submit('добави видео', array('class' => 'btn btn-success')) }}
        </div>
        {{ Form::close() }}
    </div>
</div>
{{ Form::close() }}
<div class="clearfix"></div>
<hr />
