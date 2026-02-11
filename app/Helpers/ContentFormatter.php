<?php

namespace App\Helpers;

class ContentFormatter
{
    public static function format($content)
    {
        $content = e($content);

        $content = preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
            $content
        );

        $content = preg_replace_callback('/#(\w+)/', function ($matches) {
            $tag = strtolower($matches[1]);

            return '<a href="' . route('hashtag.show', $tag) . '" class="hashtag">#' . $tag . '</a>';
        }, $content);

        return $content;
    }
}
