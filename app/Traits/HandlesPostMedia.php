<?php

namespace App\Traits;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait HandlesPostMedia
{
    protected function handleMediaUpload(
        array &$targetMedia,
        array &$newMedia,
        string $errorBag
    ): void {
        $existingImages = collect($targetMedia)
            ->filter(fn($m) => is_string($m) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $m))
            ->count();

        $existingVideos = collect($targetMedia)
            ->filter(fn($m) => is_string($m) && preg_match('/\.(mp4|webm|ogg)$/i', $m))
            ->count();

        foreach ($newMedia as $file) {
            if (! $file instanceof TemporaryUploadedFile) {
                continue;
            }

            $mime = $file->getMimeType();
            $isImage = str_starts_with($mime, 'image/');
            $isVideo = str_starts_with($mime, 'video/');

            if ($isImage && $existingImages >= 4) {
                $this->addError($errorBag, 'Máximo 4 imágenes.');
                continue;
            }

            if ($isVideo && $existingVideos >= 2) {
                $this->addError($errorBag, 'Máximo 2 vídeos.');
                continue;
            }

            $targetMedia[] = $file;

            if ($isImage) {
                $existingImages++;
            }

            if ($isVideo) {
                $existingVideos++;
            }
        }

        $newMedia = [];
    }

    protected function storeMedia(array $media): ?array
    {
        return collect($media)
            ->map(function ($file) {
                return $file instanceof TemporaryUploadedFile
                    ? $file->store('posts', 'public')
                    : $file;
            })
            ->values()
            ->all() ?: null;
    }
}
