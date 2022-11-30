<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Images
{
    /**
     * @param $request_image
     * @return string
     */
    public static function fileName($request_image)
    {
        $getFileName = pathinfo($request_image->getClientOriginalName(), PATHINFO_FILENAME);
        $getFileExtension = pathinfo($request_image->getClientOriginalExtension(), PATHINFO_FILENAME);
        return $getFileName . '-' . Str::random(10) . '.' . $getFileExtension;
    }

    /**
     * @param $filename
     * @return mixed
     */
    public static function get_image($filename)
    {
        return route('gallery.show.image', $filename);
    }
}
