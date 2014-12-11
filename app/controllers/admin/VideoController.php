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

class VideoController extends \BaseController {

    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->data['active'] = 'video';
    }

    public function index()
    {
        if (Session::get('is_admin')) {
            $this->data['videos'] = Video::leftJoin('smf_members', 'smf_members.id_member', '=', 'videos.user_id')
                ->orderBy('id', 'desc')->paginate(20, array('videos.id', 'videos.name', 'smf_members.member_name'));
        } else {
            $this->data['videos'] = Video::leftJoin('smf_members', 'smf_members.id_member', '=', 'videos.user_id')
                ->where('videos.user_id', Auth::user()->id_member)
                ->orderBy('id', 'desc')->paginate(20, array('videos.id', 'videos.name', 'smf_members.member_name'));
        }
        return View::make('admin.video.index', $this->data);
    }

    public function show($id)
    {
        $this->data['video'] = Video::FindOrFail($id);
        
        return View::make('admin.video.show', $this->data);
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
