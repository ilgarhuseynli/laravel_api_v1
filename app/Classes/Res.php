<?php

namespace App\Classes;

class Res
{
    public static function success($data = [],$description = 'Success',$title = 'Success'){
        return \response()->json([
            'status'=>'success',
            'data'=>$data,
            'title'=>$title,
            'description'=>$description
        ]);
    }

    public static function error($title = 'Error',$description = '',$errorCode = 422){
        return \response()->json([
            'status'=>'error',
            'title'=>$title,
            'description'=>$description,
            'message'=>$description,
            'error_code'=>$errorCode,
        ],$errorCode);
    }

    public static function custom($data = []){
        return \response()->json($data);
    }
}
