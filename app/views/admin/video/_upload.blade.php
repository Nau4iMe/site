<h4>Добавяне на видео</h4>
{{ Form::open(array('route' => array('admin.video.store', $content->id))) }}
<div class="form-group" id="uploader">
    <div class="upload-form">
        {{ Form::open(array('route' => 'admin.content.video')) }}
        <div class="col-md-5">
            <label for="title">URL/Заглавие (незадължително)</label>
            <input id="title" type="text" name="name" class="form-control" />
            <p class="alert alert-info">
                В случаи, когато видеото се хоства извън YouTube.
            </p>
        </div>
        <div class="col-md-5">
            <label for="youtube">YouTube ID</label>
            <input id="youtube" type="text" name="youtube" class="form-control" />
            <p class="alert alert-info">
                Въведете YouTube видео ID:<br/> https://www.youtube.com/watch?v=<strong>WodbdCxrISo</strong>
            </p>
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
