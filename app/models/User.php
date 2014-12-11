<?php

use Illuminate\Auth\UserInterface;

class User extends Eloquent implements UserInterface {

    public static $enough_posts = 0;

    public $timestamps = false;

    protected $primaryKey = 'id_member';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'smf_members';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
    * Grants access to the API of Simple Machines Forum
    * @return string
    */
    public static function getSmfApiPath()
    {
        return public_path() . '/forum/api/smf_2_api.php';
    }

    public static function getUserParam($user, $param)
    {
        require_once self::getSmfApiPath();
        return smfapi_getUserData($user)[$param];
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }


    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

}
