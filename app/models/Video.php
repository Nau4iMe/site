<?php

class Video extends Eloquent {

    protected $table = 'videos';
    protected $allowed_extensions = array('mp4');

    public function user()
    {
        return $this->hasOne('User', 'id_member');
    }

    public static function remove(Video $video)
    {
        if (!Session::get('is_admin')) {
            if ($video->user_id !== Auth::user()->id_member) {
                return false;
            }
        }

        $video->delete();

        return true;
    }

}
