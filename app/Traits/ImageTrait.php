<?php

namespace App\Traits;

trait ImageTrait
{
    private function createPreview($image, $exp, $bg_color = null) {
        return $this->image_tools->createPreview([
            'url_origin' => $this->path_image.$image, 'url_save' => $this->path_image, 'exp' => $exp,
            'width' => $this->preview_width, 'height' => $this->preview_height,
            'position' => 'center', 'bg_color' => $bg_color
        ]);
    }
}