<?php

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Content extends Eloquent implements SluggableInterface {

    use Validation;
    use SluggableTrait;

    protected $table = 'content';
    protected $guarded = array();

    protected $sluggable = array(
        'build_from'    => 'title',
        'save_to'       => 'slug',
    );

    private $rules = array(
        'title'             =>  'required|between:1,255|alpha_dash|alpha_num',
        'catid'             =>  'required|integer|min:1',
        'introtext'         =>  'required|min:1',
        'fullcontent'       =>  'required|min:1',
        'state'             =>  'integer',
        'created_by'        =>  'integer|min:1',
        'created_by_alias'  =>  'between:1,255|alpha_dash|alpha_num'
    );

    public function rating()
    {
        return $this->hasOne('ContentRating')->select(array('content_id', 'rating_sum', 'rating_count'));
    }

    public function category()
    {
        return $this->belongsTo('Category');
    }

    public function videos()
    {
        return $this->hasMany('Video');
    }

    /**
    * Scope handling the ORDER BY clauses
    */
    public function scopeOrder($query, $order, $by = 'desc')
    {
        $by = strtoupper($by);
        switch ($order) {
            case 'created_at':
                return $query->orderBy('created_at', $by);
            case 'title':
                return $query->orderBy('title', $by);
            case 'author':
                return $query->orderBy('created_by_alias', $by);
            case 'hits':
                return $query->orderBy('hits', $by);
            default:
                return $query->orderBy('id', $by);
        }
    }

    /**
    * Search
    */
    public static function userSearch($what, $limit = 20)
    {
        $term = explode(' ', $what);

        $query = Content::select('content.id', 'content.title', 'content.slug', 'introtext', 'catid', 'created_by', 'created_by_alias', 'content.hits', 'content.created_at', 'categories.path');
        $query->where('state', 1)->where('content.title', 'LIKE', "%$what%");
        foreach ($term as $v) {
            $query->orWhere('content.title', 'LIKE', "%$v%");
            $query->orWhere('introtext', 'LIKE', "%$v%");
            $query->orWhere('fullcontent', 'LIKE', "%$v%");
        }
        return $query->join('categories', 'content.catid', '=', 'categories.id')->orderBy('id', 'desc')->paginate($limit);
    }

    /** 
    * Search using FULLTEXT index 
    * @uses MySQL v5.6+
    */
    // public static function userSearch($what, $limit = 20)
    // {
    //  return Content::select('content.id', 'content.title', 'content.slug', 'introtext', 'catid', 'created_by', 'created_by_alias', 'content.hits', 'content.created_at', 'categories.path')
    //      ->whereRaw(
    //          "MATCH(`content`.`title`,`introtext`,`fullcontent`) AGAINST(? IN BOOLEAN MODE)",
    //          array($what))
    //      ->join('categories', 'content.catid', '=', 'categories.id')
    //      ->orderBy('id', 'desc')
    //      ->where('state', 1)->paginate($limit);
    // }

}
