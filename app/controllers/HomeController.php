<?php

use Illuminate\Routing\Controllers\Controller;

class HomeController extends BaseController {

    public function __construct()
    {
        $this->defaults();
    }

    /**
    * defaults() - loads and builds the basic structure of the navigation
    * @return void
    */
    private function defaults()
    {
        $this->data['categories'] = Category::buildCategoryByType('main');
        $this->data['topmenu'] = Category::select('id', 'title', 'slug', 'path')->where('type', 'top')->get();
        $this->data['links'] = Category::buildCategoryByType('links');
    }

    /**
    * home() - Shows the home page [Last added content]
    * @return void
    */
    public function home()
    {
        $this->data['contents'] = Content::select(
            'content.id', 'content.title', 'content.slug', 'introtext', 'catid', 'created_by', 'created_by_alias',
            'content.hits', 'content.created_at', 'categories.path')
            ->join('categories', 'content.catid', '=', 'categories.id')
            ->orderBy('id', 'desc')
            ->where('state', 1)
            ->paginate(10);

        // Prepare data intended to go within the head tags
        $this->data['active'] = 'home';

        return View::make('home.index', $this->data);
    }

    /**
    * page() - handles displaying categories
    * @param string $path - the unique path column from the database
    * @return View
    */
    public function page($path = '/')
    {
        // Attempting to find a category corresponding to the given path
        try {
            $this->data['category'] = Category::select('id', 'title', 'slug', 'path')
                ->where('path', $path)->firstOrFail();
        } catch (Exception $e) {
            App::abort(404);
        }
        
        $id = $this->data['category']->id;

        // If something found, search for contents
        if (count($id)) {
            $this->data['order'] = Input::get('sort');
            // $by = (Input::get('by') !== null) ? Input::get('by') : 'asc';
            if (Input::get('by') == 'asc') {
                $this->data['by'] = 'desc';
            } elseif (Input::get('by') == 'desc') {
                $this->data['by'] = 'asc';
            } else {
                $this->data['by'] = 'desc';
            }
            $this->data['contents'] = Content::select('id', 'title', 'slug', 'hits', 'created_at', 'created_by_alias')
                ->where('catid', $id)->where('state', 1)
                ->Order($this->data['order'], $this->data['by'])
                ->paginate(40)
                ->appends(Input::only('sort', 'by'));

            // Let's find our where are exactly in the tree structure of the categories
            //$node = Category::find($id, array('id', 'title', 'slug', 'path', '_lft', '_rgt'));
            $node = Category::getCategory($id);

            $this->data['descendants'] = $node->getDescendants(array('id', 'title', 'slug', 'path'));
            $this->data['ancestors'] = $node->ancestorsOf($id)->get();

            // First item is always root, but 'Начало' looks better, doesn't it?
            $this->data['ancestors'][0]->title = 'Начало';

            // Just to make the active highlight for the menus
            $this->data['active'] = $id;

            // Prepare data intended to go within the head tags
            $this->data['page_title'] = ucfirst($this->data['category']->title);
            foreach ($this->data['ancestors'] as $k => $v) {
                // first key (0) will generate the keyword 'начало', nobody needs this!
                if ($k != 0) {
                    $this->data['page_keywords'] .= $v->title . ', ';
                }
            }
            return View::make('home.page', $this->data);
        }
        return Redirect::to('/');
    }

    /**
    * content() - handles displaying a content
    * @param string $path - The path column of the category assigned to this content
    * @param mixed $content - XXX-title, XXX corresponds to the ID of the content,
    *                           and title is... the title + added -t at the end
    * @return View
    */
    public function content($path, $content)
    {
        // We need to separate the ID from the title in order to find the exact content from the database
        $id = explode('-', $content)[0];
        try {
            $this->data['content'] = Content::select('id', 'title', 'slug', 'introtext', 'fullcontent', 'catid',
                'created_by', 'created_by_alias', 'hits', 'created_at')
                ->where('state', 1)
                ->findOrFail($id);
        } catch (Exception $e) {
            App::abort(404);
        }

        // Increasing the amount of views for this content
        $this->data['content']->hits += 1;
        $this->data['content']->save();

        // Join videos
        $this->data['videos'] = $this->data['content']->videos;

        // Additional information - the score of this content
        $this->data['content_rating'] = $this->data['content']->rating;
        if ($this->data['content_rating']) {
            $this->data['content_rating']['rate'] = round(
                    $this->data['content_rating']['rating_sum'] / $this->data['content_rating']['rating_count']
                );
        }

        $this->data['rate_tooltip'] = array(1 => '1 - много зле', '2 - зле', '3 - средно', '4 - добре', '5 - отлично');

        // Additional information - the exact location of the content in the tree structure     
        $node = Category::getCategory($this->data['content']->catid);
        $this->data['ancestors'] = $node->ancestorsOf($this->data['content']->catid)->get();
        
        // First item is always root, but 'Начало' looks better, doesn't it?
        $this->data['ancestors'][0]->title = 'Начало';

        // Just to make the active highlight for the menus
        $this->data['active'] = $this->data['content']->catid;

        // Prepare data intended to go within the head tags
        $this->data['page_title'] = ucfirst($this->data['content']->title);
        $this->data['page_desc'] = $this->data['content']->title;
        foreach ($this->data['ancestors'] as $k => $v) {
            // first key (0) will generate the keyword 'начало', nobody needs this!
            if ($k != 0) {
                $this->data['page_keywords'] .= $v->title . ', ';
            }
        }

        return View::make('home.content', $this->data);
    }

    /**
    * siteMap()
    * @return all pages available in the site
    */
    public function siteMap()
    {
        $sitemap = App::make('sitemap');

        // set cache (key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean))
        // by default cache is disabled
        // $sitemap->setCache('laravel.sitemap', 3600);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached())
        {
            // add item to the sitemap (url, date, priority, freq)
            $sitemap->add(URL::to('/'), \Carbon\Carbon::now()->toRfc2822String(), '1.0', 'weekly');
            $sitemap->add(URL::to('forum'), '2012-08-26T12:30:00+02:00', '1.0', 'hourly');

            $contents = Content::select('content.id', 'content.slug', 'categories.path')
                ->join('categories', 'content.catid', '=', 'categories.id')
                ->orderBy('id', 'desc')
                ->get();

            foreach ($contents as $content) {
                $sitemap->add(
                    URL::to('/') . '/' . $content->path . '/' . $content->id . '/' . $content->slug . '/',
                    null,
                    '0.5',
                    'weekly'
                );
            }
        }

        return $sitemap->render('xml');
    }

    public function about()
    {
        $this->data['active'] = 'about';
        $this->data['page_title'] = 'За нас';
        return View::make('home.about', $this->data);
    }

    /**
    * search()
    * @return search results
    */  
    public function search()
    {
        $sanitize = new Sanitizer(Input::only('find'));
        Input::merge($sanitize->get());

        $find = Input::get('find');

        $this->data['result'] = Content::search($find);
        $this->data['result']->appends(array('find' => $find))->links();

        // Prepare data intended to go within the head tags
        $this->data['page_title'] = 'Търсене';
        return View::make('home.search', $this->data);
    }

    /**
    * rateContent() - handles rating a content
    * @param int @id - which content
    * @return void
    */
    public function rateContent($id)
    {
        try {
            $content = Content::select('id')->findOrFail($id);
        } catch (Exception $e) {
            App::abort(404);
        }

        $rating = ContentRating::select('id', 'rating_sum', 'rating_count', 'lastip')
            ->where('content_id', $id)->first();

        $sanitize = new Sanitizer(Input::all());
        Input::merge($sanitize->get());

        $validate = new ContentRating();
        if ($validate->validate(Input::all())) {
            if ($rating) {
                // Update and save
                if (Request::getClientIp() != $rating->lastip) {
                    $rating->rating_sum =  ($rating->rating_sum + Input::get('rate'));
                    $rating->rating_count = ($rating->rating_count + 1);
                    $rating->lastip = Request::getClientIp();
                    $rating->save();    
                    //return with success message
                } else {
                    return Redirect::back()->withErrors(array('failure' => 'Вече сте гласували за този урок.'));
                }
            } else {
                // First time voting for this content, we have to make a new record in the database
                $validate->content_id = $id;
                $validate->rating_sum = Input::get('rate');
                $validate->rating_count = 1;
                $validate->lastip = Request::getClientIp();
                $validate->save();
            }
            return Redirect::back()->withErrors(array('success' => 'Вашият глас беше записан успешно.'));
        }
        return Redirect::back()->withErrors(array('failure' => 'Грешка, моля опитайте отново.'));
    }

}
