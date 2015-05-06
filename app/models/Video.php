<?php

class Video extends Eloquent {

    use Validation;

    protected $table = 'videos';
    protected $fillable = array('content_id', 'user_id', 'youtube', 'name');

    private $rules = array(
        'youtube' => 'required|youtube',
        'name' => 'alpha_num_spaces|between:0,255'
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
