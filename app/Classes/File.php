<?php

namespace App\Classes;

class File
{

    public static function getFileObject($file,$type = 'item'){

        if ($file){
            $res = [
                'id' => $file->id,
                'uuid' => $file->uuid,
                'file_name' => $file->file_name,
                'mime_type' => $file->mime_type,
                'thumb' => $file->getUrl('thumb'),
                'thumbnail' => $file->getUrl('thumb'),
                'medium' => $file->getUrl('medium'),
                'large' => $file->getUrl('large'),
                'original' => $file->original_url,
                'url' => $file->getUrl(),
            ];
        }else{
            $res = self::noImgRes($type);
        }

        return $res;
    }


    public static function noImgRes($type = 'item'){
        if ($type == 'user'){
            $noImg = config('app.url').'/images/users/noimg-3.png';
        }else{
            $noImg = config('app.url').'/images/users/noimg-item.png';
        }

        return [
            'thumb' => $noImg,
            'thumbnail' => $noImg,
            'medium' => $noImg,
            'large' => $noImg,
            'original' => $noImg,
            'url' => $noImg,
        ];
    }



}
