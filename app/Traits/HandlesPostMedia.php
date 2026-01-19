<?php

namespace App\Traits;

trait HandlesPostMedia
{
    protected function handleMediaUpload(
        array &$targetMedia,
        array &$newMedia,
        string $errorBag
    ): void {
        foreach ($newMedia as $file) {
            $mime = $file->getMimeType();

            $isImage = str_starts_with($mime, 'image/');
            $isVideo = str_starts_with($mime, 'video/');

            $imagesCount = collect($targetMedia)
                ->filter(fn($m) => str_starts_with($m->getMimeType(), 'image/'))
                ->count();

            $videosCount = collect($targetMedia)
                ->filter(fn($m) => str_starts_with($m->getMimeType(), 'video/'))
                ->count();

            if ($isImage && $imagesCount >= 4) {
                $this->addError($errorBag, 'Máximo 4 imágenes.');
                continue;
            }

            if ($isVideo && $videosCount >= 2) {
                $this->addError($errorBag, 'Máximo 2 vídeos.');
                continue;
            }

            $targetMedia[] = $file;
        }

        $newMedia = [];
    }

    protected function storeMedia(array $media): ?array
    {
        return collect($media)
            ->map(fn($file) => $file->store('posts', 'public'))
            ->values()
            ->all() ?: null;
    }
}
