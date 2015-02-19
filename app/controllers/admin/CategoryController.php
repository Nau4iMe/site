<?php
namespace admin;

use Category;
use CategoryTypes;
use View;
use Redirect;
use Validation;
use Input;
use Str;
use Exception;
use App;
use Sanitizer;

class CategoryController extends \BaseController {

    /**
     * Cross Site Request Forgery protection
     */
    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->data['active'] = 'category';
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $this->data['categories'] = Category::getAllNodes();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return View::make('admin.category.index', $this->data);
    }

    public function create()
    {       
        $this->data['categories'] = Category::intendCategories(Category::getCategories());
        $this->data['categorytypes'] = CategoryTypes::where('type', '!=', 'system')->lists('title', 'type');
        $this->data['route'] = 'edit';
        return View::make('admin.category.create', $this->data);
    }

    public function store()
    {
        $sanitize = new Sanitizer(Input::all());
        Input::merge($sanitize->get());

        $validation = new Category();
        if ($validation->validate(Input::all())) {

            $validation->fill(Input::except('path'));
            $validation->save();

            if (Input::get('is_link') == 1 && strlen(Input::get('path')) > 2) {
                // Should it be a link to an external page?
                $validation->path = Input::get('path');
            } else {
                // Or should it be a link to an internal page?
                $path = Category::setPath($validation->parent_id);
                $validation->path = (strlen($path) ? $path . '/' : null ) . Category::slug($validation->slug);
            }

            if ($this->saveSafely($validation)) {
                return Redirect::route('admin.category.index')->with('global_success', 'Успешно добавяне.');
            }
            return Redirect::route('admin.category.create')->with('global_error', 'Грешка, моля опитайте отново.');
        }
        return Redirect::route('admin.category.create')->withErrors($validation->getErrors())->withInput();
    }

    public function edit($id)
    {
        try {
            $this->data['category'] = Category::findOrFail($id);
        } catch (Exception $e) {
            App::abort(404);
        }
        $this->data['categories'] = Category::intendCategories(Category::getCategories());
        $this->data['categorytypes'] = CategoryTypes::lists('title', 'type');
        $this->data['route'] = 'edit';

        return View::make('admin.category.edit', $this->data);

    }

    public function update($id)
    {
        try {
            $category = Category::findOrFail($id);  
        } catch (Exception $e) {
            App::abort(404);
        }

        $sanitize = new Sanitizer(Input::all());
        Input::merge($sanitize->get());
        
        if (Input::get('parent_id') == $category->id) {
            // VERY IMPORTANT CHECK
            $category->setGuarded(array('parent_id'));
        }

        if ($category->validate(Input::except('path'))) {
            // Preparing the data to be submitted
            $data = Input::all();
            $data['is_link'] = Input::get('is_link');
            $category->update($data);

            if (Input::get('is_link') == 1 && strlen(Input::get('path')) > 2) {
                // Should it be a link to an external page?
                $category->path = Input::get('path');
            } else {
                // Or should it be a link to an internal page?
                $path = Category::setPath($category->parent_id);
                $category->path = (strlen($path) ? $path . '/' : null ) . Category::slug(Input::get('title'));

                // Ugly workaround for dirty validation of path :(
                $exists = Category::select('path')
                    ->where('path', $category->path)->where('id', '<>', $id)
                    ->count();
                if ($exists === 1) {
                    return Redirect::route('admin.category.edit', $id)->withErrors(array('title' => 'Полето цял път вече съществува'));
                }
            }

            // Should everything be ok, save it.
            if ($this->saveSafely($category)) {
                $ancestros = $category->getAncestors();
                $path = $ancestros[count($ancestros) - 1]->path;

                $descendants = $category->getDescendants();
                foreach($descendants as $v) {
                    $v->path = $category->path . '/' . $v->slug;
                    $v->save();
                }
                return Redirect::route('admin.category.index')
                    ->with('global_success', 'Данните бяха променени успешно.');
            }
        }
        return Redirect::route('admin.category.edit', $id)->withErrors($category->getErrors());
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Transaction. Using it is a must
        return $category->getConnection()->transaction(function () use ($category)
        {
            $response = Redirect::route('admin.category.index');

            if ($category->delete()) {
                $response->with('global_success', 'Съдържанието изтрито.');
            } else {
                $response->with('global_error', 'Грешка при опит за изтриване!');
            }
            return $response;
        });
    }

    /**
    * Ajax requests
    */
    public function ajaxPath()
    {
        // dynamically generating paths
        $id = Input::get('id');
        $current_id = Input::get('current_id');

        $sanitize = new Sanitizer(Input::all());
        Input::merge($sanitize->get());

        $cat = Category::ancestorsOf($id)->withoutRoot()->get();

        $current = Category::select('id', 'slug')->where('id', $current_id)->get();
        $return = null;
        foreach ($cat as $v) {
            $return .= $v->slug . '/';
        }
        $return .= $current[0]->slug;
        return $return;
    }

    /**
    * ajaxTitle()
    * Used for both category and content - Only contents accept digits though
    */
    public function ajaxTitle()
    {
        return Category::slug(Input::get('title'), '-', Input::get('digits'));
    }
    // No more Ajax requests further down


    /**
     * Saving information using transaction.
     *
     * @param  Page $model
     *
     * @return boolean
     */
    protected function saveSafely(Category $model)
    {
        $connection = $model->getConnection();

        return $connection->transaction(function () use ($model) {
            return $model->save();
        });
    }

    public function up($id)
    {
        return $this->move($id, 'before');
    }

    public function down($id)
    {
        return $this->move($id, 'after');
    }

    /**
    * Moves nodes in given direction
    * 
    * @param $id - which category to move
    *
    * @param $direction - can either be before or after (up or down in hierarchy)
    */
    protected function move($id, $direction)
    {
        try {
            $category = Category::findOrFail($id);  
        } catch (Exception $e) {
            App::abort(404);
        }
        
        if (!$category->isRoot()) {
            $sibling = $direction === 'before' ? $category->getPrevSibling() : $category->getNextSibling();
            if ($sibling) {
                $category->$direction($sibling)->save();
                return Redirect::route('admin.category.index')
                    ->with('global_success', 'Катеогирята ' . $category->title . ' преместена '
                    . ($direction === 'before' ? 'нагоре' : 'надолу') . '.');
            } else {
                return Redirect::route('admin.category.index')
                    ->with('global_error', 'Категорията ' . $category->title . ' не е преместена.');
            }
        }
    }

}
