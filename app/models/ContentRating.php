<?php

class ContentRating extends Eloquent {

    use Validation;

    public $timestamps = false;

    protected $guarded = array();
    protected $table = 'content_rating';

    private $rules = array(
        'rate'  => 'required|integer|between:1,5'
    );

    public function content($content_id)
    {
        return $this->belongsTo('Content');
    }

}
