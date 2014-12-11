<?php

class BaseController extends Controller {

    /*
    * Use this property in order to send data to views
    */
    protected $data = array(
        'active' => null,
        'page_title' => 'Начало',
        'page_keywords' => null,
        'page_desc' => null,
        'page_image' => 'http://nau4i.me/i/og_image.png'
    );

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }

}
