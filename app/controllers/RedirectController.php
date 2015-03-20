<?php

class RedirectController extends BaseController
{

    public function content($id)
    {
        try {
            $content = Content::findOrFail($id);
            return Redirect::to('/' . $content->category->path . '/' . $content->id . '/' . $content->slug);
        } catch (Exception $e) {
            App::abort(404);
        }
    }

}
