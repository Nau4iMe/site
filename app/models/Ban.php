<?php

class Ban extends Eloquent {

    use Validation;

    protected $table = 'ban';

    private $rules = array(
        'user_id'   => 'required|integer|min:1',
        'reason'    => 'required|min:2|max:255|alpha_dash|alpha_num',
    );

}
