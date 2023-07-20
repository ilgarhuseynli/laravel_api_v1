<?php

namespace App\Classes;

class File
{

    public static function getFileObject($file){

        if ($file){
            $res = [
                'id' => $file->id,
                'uuid' => $file->uuid,
                'file_name' => $file->file_name,
                'mime_type' => $file->mime_type,
                'thumbnail' => $file->getUrl('thumb'),
                'medium' => $file->getUrl('medium'),
                'large' => $file->getUrl('large'),
                'original' => $file->original_url,
                'url' => $file->getUrl(),
            ];
        }else{
            $res = self::noImgRes();
        }

        return $res;
    }


    public static function noImgRes(){
        $noImg = config('app.url').'/images/users/noimg-3.png';

        return [
            'thumbnail' => $noImg,
            'medium' => $noImg,
            'large' => $noImg,
            'original' => $noImg,
            'url' => $noImg,
        ];
    }



}
