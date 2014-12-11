<script type="text/javascript">
    $(function() {
        $('#is_link').click(function(e) {
            if ($(this).is(':checked')) {
                $('#display_path').css({ display: 'block' });
            } else {
                $('#display_path').css({ display: 'none' });
                {{ (isset($route) && $route == 'edit') ? null : "$('#path').val('');" }}
            }
            
        });
    })
</script>

<div class="form-group">
    <label for="title">Заглавие на категория: </label>
    {{ Form::text('title', null, array('id' => 'title', 'required', 'class' => 'form-control')) }}

    @if ($errors->has('title'))
        <div class="alert alert-danger">
            {{ $errors->first('title') }}
        </div>
    @endif
</div>

<div class="form-group">
    {{ Form::checkbox('is_link', 1, false, array('id' => 'is_link')) }} 
    <label for="is_link">Линк към ресурс?</label>
</div>

<div class="form-group" {{ (isset($category->is_link) && $category->is_link == 1) ? null : 'style="display: none"' }} id="display_path">
    <label for="path">Цял път: </label>
    {{ Form::text('path', null, array('id' => 'path', 'class' => 'form-control')) }}

    @if ($errors->has('path'))
        <div class="alert alert-danger">
            {{ $errors->first('path') }}
        </div>
    @endif
    <br/>
    <div class="alert alert-info">
        Оставете празно, освен ако не искате да добавите външна препратка, като:
        <strong>forum</strong>, <strong>css</strong> или <strong>http://site.com</strong>.
    </div>
</div>

<div class="form-group">
    <label for="type">Тип категория: </label>
    {{ Form::select('type', $categorytypes, null, array('id' => 'type', 'required', 'class' => 'form-control')) }}

    @if ($errors->has('type'))
        <div class="alert alert-danger">
            {{ $errors->first('type') }}
        </div>
    @endif
</div>

<div class="form-group">
    <label for="parent">Подкатегория на: </label>
    {{ Form::select('parent_id', $categories, null, array('id' => 'parent', 'required', 'class' => 'form-control')) }}

    @if ($errors->has('parent'))
        <div class="alert alert-danger">
            {{ $errors->first('parent') }}
        </div>
    @endif
</div>