<?php

namespace App\Traits;

use App\Models\Hashtag;

trait HandlesHashtags
{
    protected function syncHashtags($model, string $content): void
    {
        preg_match_all('/#(\w+)/', $content, $matches);

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
