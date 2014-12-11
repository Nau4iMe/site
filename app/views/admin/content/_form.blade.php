<h3>Урок</h3>

<div class="form-group">
    <label for="title">Заглавие на урок: </label>
    {{ Form::text('title', null, array('id' => 'title', 'required', 'class' => 'form-control')) }}
    @if ($errors->has('title'))
        <div class="alert alert-danger">
            {{ $errors->first('title') }}
        </div>
    @endif
</div>

{{-- Admins only --}}
@if (Session::get('is_admin'))

<div class="form-group">
    <label for="created_by_alias">Автор: </label>
    {{ Form::text('created_by_alias', null, array('id' => 'created_by_alias', 'class' => 'form-control')) }}
    @if ($errors->has('created_by_alias'))
        <div class="alert alert-danger">
            {{ $errors->first('created_by_alias') }}
        </div>
    @endif
</div>

<div class="form-group">
    <label for="state">Показвай: </label>
    {{ Form::select('state', array(1 => 'да', '0' => 'не'), null, array('id' => 'state', 'class' => 'form-control')) }}
    @if ($errors->has('state'))
        <div class="alert alert-danger">
            {{ $errors->first('state') }}
        </div>
    @endif
</div>
{{-- end of Admins only --}}
@endif

<div class="form-group">
    <label for="catid">Категория: </label>
        {{ Form::select('catid', $categories, null, array('id' => 'catid', 'required', 'class' => 'form-control')) }}
    @if ($errors->has('catid'))
        <div class="alert alert-danger">
            {{ $errors->first('catid') }}
        </div>
    @endif
</div>

<div class="form-group">
    <label for="introtext">Въвеждащ текст: </label>
    @if ($errors->has('introtext'))
        <div class="alert alert-danger">
            {{ $errors->first('introtext') }}
        </div>
    @endif
</div>
{{ Form::textarea('introtext', null, array('id' => 'introtext')) }}
<script type="text/javascript">CKEDITOR.replace('introtext')</script>

<div class="form-group">
    <div class="alert alert-info">
        Текстът, който да се показва при прелистване на категорията на урока. Показва се и в самия урок
    </div>
</div>

<div class="form-group">
    <label for="fullcontent">Съдържание: </label>
    @if ($errors->has('fullcontent'))
        <div class="alert alert-danger">
            {{ $errors->first('fullcontent') }}
        </div>
    @endif
</div>
{{ Form::textarea('fullcontent', null, array('id' => 'fullcontent')) }}
<script type="text/javascript">CKEDITOR.replace('fullcontent')</script>
<div class="form-group">
    <div class="alert alert-info">Съдържанието на урока, ако има такова.
    </div>
</div>
