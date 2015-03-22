<?php

class ContentForum extends Eloquent {

    use Validation;

    protected $guarded = array();
    protected $table = 'content_forum_pivot';

    private $rules = array(
        'id_msg'  => 'integer|max:4294967295'
    );

}
