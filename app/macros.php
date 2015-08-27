<?php

/**
 * Generate flowplayer - back into business
 *
 * @param string $text
 *
 * @return string
 */
HTML::macro('flowplayer', function($name, $legacy = null) {
    if (!$name || $legacy === 1) {
        return false;
    }

    $info = pathinfo($name);
    $video_types = array(
        'flv'   => 'video/flash',
        'webm'  => 'video/webm',
        'mp4'   => 'video/mp4',
        'ogv'   => 'video/ogv',
    );

    $data = '<div class="player-holder">';
    $data .= '<div class="flowplayer" data-swf="';
    $data .= URL::asset('packages/flowplayer-5.4.6/flowplayer.swf');
    $data .= '" data-ratio="0.8">';
    $data .= '<video>' . PHP_EOL;
    foreach ($video_types as $ext => $type) {
        $data .= '<source type="' . $type . '" src="' . $info['dirname'] . '/' .
            $info['filename'] . '.' . $ext . '">' . PHP_EOL;
    }
    $data .= '</video>' . PHP_EOL;
    $data .= '</div>';
    $data .= '</div>';

    return $data;
});

/**
 * Strip deprecated flowplayer tags
 *
 * @param string $text
 *
 * @return string
 */
HTML::macro('strip_flowplayer', function($text) {
    if (!$text) {
        return false;
    }

    $text = preg_replace('~{flowplayer}(.*){/flowplayer}~', '', $text);

    return $text;
});

/**
 * Generates neccessary video tags
 *
 * @param string $text
 *
 * @return string
 */
HTML::macro('video', function($id) {
    if (!$id)
        return false;

    $data = '<div class="player-holder">';
    $data .= '<div class="embed-responsive embed-responsive-16by9">';
    $data .= '<iframe type="text/html" class="embed-responsive-item" autoplay="false" allowfullscreen
            src="http://www.youtube.com/embed/' . $id . '?theme=light&hl=bg">
        </iframe>';
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
    $html .= urldecode(URL::route('content', array($data['path'], $data['id'] . '/' . $data['slug'])));
    $html .= '"';
    $html .= ' title="' . (isset($data['atitle']) ? $data['atitle'] : $data['title']) . '" ';
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
