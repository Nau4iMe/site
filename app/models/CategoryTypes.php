<?php

class CategoryTypes extends Eloquent {

    protected $guarded = array();
    protected $table = 'category_types';

    public static $rules = array();

    public function category($category_id)
    {
        return $this->belongsTo('Category');
    }

}
