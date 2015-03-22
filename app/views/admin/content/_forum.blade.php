<hr/>
<h4>Интеграция с форума</h4>
<div class="">
    <div class="alert alert-info">
        <p>
            Въведете ID на темата за дискусия от форума - http://nau4i.me/forum/index.php/topic,<strong>12584</strong>.msg47720.html
        </p>
    </div>
    {{ Form::open(array('route' => array('admin.content.forum', $content->id))) }}
    <div class="col-md-9">
        <input type="text" id="forum" name="id_msg" class="form-control" value="{{ $content->id_msg }}" />
    </div>
    <div class="col-md-3">
        {{ Form::submit('ID на тема от форума', array('class' => 'btn btn-success')) }}
    </div>
    {{ Form::close() }}
</div>
<div class="clearfix"></div>
<hr/>
