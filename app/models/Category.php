<?php

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Category extends \Kalnoy\Nestedset\Node implements SluggableInterface {

    use Validation;
    use SluggableTrait;

    protected $table = 'categories';
    protected $guarded = array();

    protected $sluggable = array(
        'build_from'    => 'title',
        'save_to'       => 'slug',
    );

    private $rules = array(
        'title'     => 'required|min:2|max:255|alpha_num_spaces',
        'parent_id' => 'required|min:1|integer',
        'path'      => 'max:255|unique:categories',
        'is_link'   => 'max:1|integer',
        'type'      => 'required|min:1|max:255',
    );

    /**
    * RELATIONSHIPS
    */
    public function categoryType()
    {
        return $this->hasOne('CategoryTypes', 'type');
    }

    public function contents()
    {
        return $this->hasMany('Content', 'catid')->select('id', 'title', 'slug')->where('state', 1);
    }


    public function setGuarded($guarded = array())
    {
        foreach ($guarded as $v) {
            $this->guarded[] = $v;
        }
    }

    public function setRule($rule, $value)
    {
        $this->rules[$rule] = $value;
    }

    public static function addCategory($appendTo, $data = array())
    {
        try {           
            $node = new Category($data);
            $node->appendTo($appendTo)->save();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function getCategory($id)
    {
        return self::find($id, array('id', 'title', 'slug', 'path', '_lft', '_rgt'));
    }

    public static function buildCategoryByType($type)
    {
        return Category::select(array('id', 'title', 'slug', 'path', '_lft', '_rgt', 'parent_id'))
            ->withoutRoot()
            ->where('type', $type)
            ->withDepth()
            ->get()
            ->toTree();
    }

    public static function getAllNodes()
    {
        return Category::select('categories.id as id', 'categories.title as title', 'category_types.title as type')
           ->withoutRoot('id', 'title', 'slug')
           ->withDepth()
           ->join('category_types', 'categories.type', '=', 'category_types.type')
           ->get();
    }

    public static function getCategories()
    {
        return self::select('id', 'title')->withDepth()->get();
    }

    public static function getCategoriesWithoutRoot()
    {
        return self::select('id', 'title')->withDepth()->withoutRoot()->get();
    }

    public static function intendCategories($nodes)
    {
        $result = array();
        foreach ($nodes as $node) {
            $title = $node->title;
            if ($node->depth > 0)
                $title = str_repeat('—', ($node->depth)) . ' ' . $title;

            $result[$node->id] = $title;
        }

        return $result;
    }

    public static function setPath($id)
    {
        $path = null;
        $ancestors = Category::ancestorsOf($id)->withoutRoot()->lists('slug');
        foreach ($ancestors as $v) {
            $path .= $v . '/';
        }
        return rtrim($path, '/');
    }

    /**
    * Extending Sluggifying, could be better to be achieved in a separate class
    */
    public static function slug($title, $separator = '-')
    {
        if (mb_strlen($title, 'utf-8') > 240) {
            $title = mb_substr($title, 0, 240, 'utf-8');
        }
        $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title, 'utf-8'));
        $flip = $separator == '-' ? '_' : '-';
        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        return trim($title, $separator);
    }

}
