<?php

/**
 * Generate flowplayer - deprecated
 *
 * @param string $text
 *
 * @return string
 */
HTML::macro('flowplayer', function($text) {

    preg_match('/{flowplayer}(.+){\/flowplayer}/', $text, $m);
    
    if (!empty($m)) {
        $data = null;
        $data .= '<div class="flowplayer-holder">';
        $data .= '<div class="flowplayer" data-swf="';
        $data .= URL::asset('packages/flowplayer-5.4.6/flowplayer.swf');
        $data .= '" data-ratio="0.8">';
        $data .= '<video>' . PHP_EOL;
        // fix for flash videos
        if (mb_substr($m[1], -3, 3) == 'flv') {
            $data .= '<source type="video/flash" src="${1}">' . PHP_EOL;
        } else {
            $data .= '<source type="video/webm" src="${1}">' . PHP_EOL;
            $data .= '<source type="video/mp4" src="${1}">' . PHP_EOL;
            $data .= '<source type="video/ogv" src="${1}">' . PHP_EOL;
        }
        $data .= '</video>' . PHP_EOL;
        $data .= '</div>';
        $data .= '</div>';
        $text = preg_replace('~{flowplayer}(.*){/flowplayer}~', '', $text);

        $text = preg_replace('/(href="(..\/)?\/?forum\/)/', 'href="http://nau4i.me/forum/', $text);
    }
    return $text;
});

/**
 * Generates neccessary video tags
 *
 * @param string $text
 *
 * @return string
 */
HTML::macro('video', function($url) {
    $data = null;
    $data .= '<div class="flowplayer-holder">';
    $data .= '<div class="flowplayer" data-swf="';
    $data .= URL::asset('packages/flowplayer-5.4.6/flowplayer.swf');
    $data .= '" data-ratio="0.8">';
    $data .= '<video>' . PHP_EOL;
    // fix for flash videos
    if (mb_substr($url, -3, 3) == 'flv') {
        $data .= '<source type="video/flash" src="' . $url . '">' . PHP_EOL;
    } else {
        $data .= '<source type="video/webm" src="' . $url . '">' . PHP_EOL;
        $data .= '<source type="video/mp4" src="' . $url . '">' . PHP_EOL;
        $data .= '<source type="video/ogv" src="' . $url . '">' . PHP_EOL;
    }
    $data .= '</video>' . PHP_EOL;
    $data .= '</div>';
    $data .= '</div>';

    return $data;
});

/**
 * Checks whether category URL redirects to internal resource
 *
 */
HTML::macro('page_or_link', function($resource, $text, $params = array()) {
    $data = '<a href="';
    if (filter_var($resource, FILTER_VALIDATE_URL) == true) {
        $data .= urldecode($resource);
    } else {
        $data .= urldecode(URL::route('page', $resource));
    }
    $data .= '" title="' . $text . '"';
    foreach ($params as $param => $value) {
        $data .= ' ' . $param . '=" ' . $value . '" ';
    }
    $data .= '>' . $text . '</a>';
    return $data;
});

/**
 * Generates content URLs
 *
 */
HTML::macro('link_to_content', function($data = array()) {
    $html = '<a href="';
    $html .= urldecode(URL::route('content', array($data['path'], $data['id'] . '-' . $data['slug'] . '-t')));
    $html .= '"';
    $html .= ' title ="' . (isset($data['atitle']) ? $data['atitle'] : $data['title']) . '" ';
    $html .= isset($data['class']) ? ' class="' . $data['class'] . '"' : null;
    $html .= '>' . $data['title'] . '</a>';
    return $html;
});

/**
 * Render multi-level navigation.
 *
 * @param  array  $data
 *
 * @return string
 */
HTML::macro('nav', function($data, $level = null, $active = null) {

    if (empty($data))
        return '';

    $html = null;
    foreach ($data as $item) {
        
        $html .= '<ul class="';
        if ($level > 0) {
            $html .= 'sub';
        }
        if (isset($item->children))
        $html .= 'category">';
        $html .= '<li';
        
        if ($item->id == $active)
            $html .= ' class="active"';

        $html .= '>';
        $html .= HTML::page_or_link($item->path, $item->title);
        if (isset($item->children))
            $html .= HTML::nav($item->children, $level + 1, $active);
        $html .= '</li>';
        $html .= '</ul>';
    }

    return $html;
});
