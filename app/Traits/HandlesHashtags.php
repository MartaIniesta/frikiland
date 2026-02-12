<?php

namespace App\Traits;

use App\Models\Hashtag;

trait HandlesHashtags
{
    protected function syncHashtags($model, string $content): void
    {
        preg_match_all('/#([\p{L}\p{N}_]+)/u', $content, $matches);

        $ids = collect($matches[1])
            ->map(function ($tag) {
                return Hashtag::firstOrCreate([
                    'name' => strtolower($tag)
                ])->id;
            })
            ->toArray();

        $model->hashtags()->sync($ids);
    }
}
