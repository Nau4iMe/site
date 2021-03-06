<?php
namespace admin;

use View;
use Video;
use Redirect;
use URL;
use File;
use Session;
use Auth;
use Input;
use Content;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use User;
use Sanitizer;

class VideoController extends \BaseController {

    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->data['active'] = 'video';
    }

    public function index()
    {
        $user_table = User::getUsersTable();

        // Ugly authentication filter
        if (Session::get('is_admin')) {
            $this->data['videos'] = Video::select(
                'videos.id',
                'videos.name',
                $user_table . '.member_name',
                'content.id as content_id',
                'content.title'
            )->leftJoin($user_table,  $user_table . '.id_member', '=', 'videos.user_id')
            ->leftJoin('content', 'content.id', '=', 'videos.content_id')
            ->orderBy('videos.id', 'desc')->paginate(20, array('videos.id', 'videos.name', $user_table . '.member_name'));
        } else {
            $this->data['videos'] = Video::select(
                'videos.id',
                'videos.name',
                $user_table . '.member_name',
                'content.id as content_id',
                'content.title'
            )->leftJoin($user_table,  $user_table . '.id_member', '=', 'videos.user_id')
            ->leftJoin('content', 'content.id', '=', 'videos.content_id')
            ->where('videos.user_id', Auth::user()->id_member)
            ->orderBy('videos.id', 'desc')->paginate(20, array('videos.id', 'videos.name', $user_table . '.member_name'));
        }
        return View::make('admin.video.index', $this->data);
    }

    public function store($id)
    {
        $validation = new Video();
        if ($validation->validate(Input::all())) {
            $validation->user_id = Auth::user()->id_member;
            $validation->name = Input::get('name');
            $validation->youtube = Input::get('youtube');
            $validation->content_id = (int) $id;
            $validation->save();

            return Redirect::back()->with('global_success', 'Видеото е добавено успешно.');
        }
        return Redirect::back()->withErrors('global_error', 'Невалидно YouTube видео ID!');
    }

    public function edit($id)
    {
        $this->data['video'] = Video::FindOrFail($id);

        return View::make('admin.video.edit', $this->data);
    }

    public function update($id)
    {
        try {
            $video = Video::findOrFail($id);
        } catch (Exception $e) {
            App::abort(404);
        }

        $sanitize = new Sanitizer(Input::only('youtube', 'name'));
        Input::merge($sanitize->get());

        if ($video->validate(Input::only('youtube', 'name'))) {
            if ($video->update(Input::only('youtube', 'name'))) {
                return Redirect::back()->with('global_success', 'Видеото променено успешно!');
            }
        }

        return Redirect::route('admin.video.edit', $id)->with('global_error', 'Грешка моля опитайте отново.');
    }

    public function destroy($id)
    {
        try {
            $video = Video::FindOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Redirect::route('admin.video.index')->with('global_error', 'Грешка: ' . $e->getMessage());
        }
        if (Video::remove($video)) {
            return Redirect::route('admin.video.user.index')->with('global_success', 'Видеото изтрито.');
        }
        return Redirect::route('admin.video.index')->with('global_error', 'Грешка при опит за изтриване!');
    }

    public function ajaxDestroy($id)
    {
        try {
            $video = Video::FindOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Response::json('<div class="alert alert-danger">Грешка: ' . $e->getMessage() . '</div>');
        }
        if (Video::remove($video)) {
            return Response::json('<div class="alert alert-success">Видеото изтрито!</div>');
        }
        return Response::json('<div class="alert alert-danger">Грешка!</div>');
    }

    public function ajaxGetVideos()
    {
        $id = (int) Input::get('content_id');
        $videos = Video::where('content_id', $id)->get();
        $array = array();
        foreach ($videos as $key => $video) {
            $array[$key]['id'] = $video['id'];
            $array[$key]['name'] = $video['name'];
        }
        return Response::json($array);
    }

}
