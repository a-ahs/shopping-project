<?php

    namespace App\Utilities;

    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\File;

    class imageUploader
    {
        public static function upload($image, $path, $disk = 'local_storage')
        {
            Storage::disk($disk)->put($path, File::get($image));
        }

        public static function uploadMany(array $images, $path, $disk= 'public_storage')
        {
            $imagesPath = [];

            foreach($images as $key => $image)
            {
                $fullPath = $path . $key . '_' . $image->getClientOriginalName();
                Storage::disk($disk)->put($fullPath, File::get($image));

                $imagesPath += [$key => $fullPath];
            }

            return $imagesPath;
        }
    }

?>