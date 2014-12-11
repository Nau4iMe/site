<?php

return array(
    'build_from'      => 'title',
    'save_to'         => 'slug',
    'max_length'      => 255,
    'method'          => function($slug, $separator) {
        return Category::slug($slug, $separator);
    },
    'separator'       => '-',
    'unique'          => true,
    'include_trashed' => false,
    'on_update'       => false,
    'reserved'        => null,
    'use_cache'       => false,
);