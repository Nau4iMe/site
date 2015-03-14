<?php

class Video extends Eloquent {

    use Validation;

    protected $table = 'videos';
    protected $allowed_extensions = array('mp4');

    private $rules = array(
        'youtube' => 'required|youtube',
    );

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
