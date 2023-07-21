<?php

/**
 * Help functions used at project
 */

namespace App\Classes;

use Illuminate\Support\Str;
use Modules\Common\Entities\Category;

class Helpers
{
    /**
     * get youtube link, return embed link
     * @param $url
     * @return string
     */
    public static function createEmbedStrin($url)
    {
        $urlParts = explode('/', $url);
        $videoid = explode('&', str_replace('watch?v=', '', end($urlParts)));

        return 'https://www.youtube.com/embed/' . $videoid[0];
    }


    public static function getImageUrl($baseUrl, $type = 'original')
    {
        $path = $baseUrl;
        $url = explode('/', $baseUrl);
        if (count($url) == 0 || $url[0]=='')
            return asset('images/no-img.png');

        if (in_array($type, ['car_small', 'car_medium','car_large','autohall_profile','autohall_cover','small','large'])) {
            $path = '';
            for ($i = 0; $i < count($url); $i++) {
                $path .= (isset($url[$i + 1])) ? $url[$i] . '/' : $type . '/' . $url[$i];
            }
        }


        if (!file_exists('uploads/' . $path)) {

//            return asset('images/no-img.png');

            if(file_exists('uploads/'.$baseUrl))
            {
                return asset('uploads/'.$baseUrl);
            }
            else {
                return asset('img/no-img.png');
            }
        }

        return asset('uploads/'.$path);
    }


    public static function getImageDirectory($image)
    {
        $pathArray = explode('/',$image);
        array_pop($pathArray);

        $imgFolderPath = implode('/',$pathArray);

        return ['dirpath'=>$imgFolderPath,'pathArray'=>$pathArray];
    }



    public static function closetags($html) {
        preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i=0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</'.$openedtags[$i].'>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }


    public static function stringWithoutTags($string,$limit){
//        $string = preg_replace('/<strong>(.*?)<\/strong>/', '$1', $string);

        $string = strip_tags( $string,'<p><a><span>');

        $stringall=strlen($string);
        $striphtml = strip_tags($string);
        $stringnohtml=strlen($striphtml);
        $differ=($stringall-$stringnohtml);
        $stringsize=($differ + $limit);
//        $limitedString =  self::closetags(Str::limit($string,$stringsize));
        $limitedString =  Str::limit($string,$stringsize);


        return $limitedString;
    }

    public static function most_frequent_words($string, $stop_words = [], $limit = 5) {
        $string = strtolower($string); // Make string lowercase
        $words = str_word_count($string, 1); // Returns an array containing all the words found inside the string
        if(count($stop_words) > 0)
            $words = array_diff($words, $stop_words); // Remove black-list words from the array
        $words = array_count_values($words); // Count the number of occurrence
        arsort($words); // Sort based on count

        $resultArray = [];
        foreach ($words as $word=>$num){
            $resultArray[] = $word;
        }

        $resultArray = array_filter($resultArray, function($val){
            return strlen($val) > 3; // filter words having length > 3 in array
        });
        return array_slice($resultArray, 0, $limit); // Limit the number of words and returns the word array
    }


    /**
     * @param $array
     * @param $number
     * @return array
     */
    public static function randomArrayValues($array,$number)
    {
        if(count($array) <= $number)
            $number = count($array);

        $resultArray= [];
        for ($i=0;$i<$number;$i++)
        {
            $keyActualArray = rand(0,count($array)-1);

            $key = array_keys($array)[$keyActualArray];

            $resultArray[] = $array[$key];

            unset($array[$key]);
        }

        return $resultArray;
    }


    public static function filterPhoneNumber($phone,$deletePrefix = true){
        $justNums = preg_replace("/[^0-9]/", '', $phone);

        if ($deletePrefix){
            if (strlen($justNums) == 11){
                $justNums = preg_replace("/^1/", '',$justNums);
            }
            if (strlen($justNums) == 10){
                return $justNums;
            }
        }else{
            if (strlen($justNums) == 11){
                return $justNums;
            }
        }


        return false;
//        return preg_replace('/\D/', '', $phone);
    }

    public static function formatUsNumber($number){
        $result = '';

        $number = self::filterPhoneNumber($number);
        if(preg_match( '/^(\d{3})(\d{3})(\d{4})$/', $number,  $matches )){
            $result = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
        }
        return $result;

    }

    public static function localize_us_number($phone) {
        $numbers_only = preg_replace("/[^\d]/", "", $phone);
        return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $numbers_only);
    }


    public static function getOrderListUrl($status =false){

        $dataRange = false;
        if (Helpers::isTechnician() && !$status){
            $dataRange = date('Y:m:d') .'-'. date('Y:m:d');
            if (date('H') > 19){
                $tomorrow = date('Y:m:d',strtotime('tomorrow'));
                $dataRange = $tomorrow .'-'. $tomorrow;
            }
        }

        if(Helpers::isTechnician()){
            $orderListUrl = route("admin.orders.cardlist").'?daterange='.$dataRange;
        }else{
            $orderListUrl = route("admin.orders.index").'?daterange='.$dataRange;
        }

        if ($status){
            $orderListUrl = $orderListUrl.'&status='.$status;
        }

        return $orderListUrl;
    }


    public static function roundFloatValue($price, $decimal = 2, $round = 2)
    {
        $decimalPoint = '%0.' . $decimal . 'f';

        return (float)sprintf($decimalPoint, round($price, $round));
    }


    public static function manageLimitRequest($limit, $min = 50, $max = 200)
    {
        $limit = (int)$limit;

        if ($limit < 5) {
            $limit = $min;
        } else if ($limit > 200) {
            $limit = $max;
        }

        return $limit;
    }


    public static function manageSortRequest($sortField, $sortType, $fields = false, $defaultParams = [])
    {
        $defaultSortField = @$defaultParams['sort_field'] ?: 'created_at';
        $defaultSortType = @$defaultParams['sort_type'] ?: 'desc';
        $sortType = $sortType ?: $defaultSortType;

        $fields = is_array($fields) ? $fields : ['id', 'created_at', 'title'];

        $sort_field = trim(strtolower($sortField));

        if (@$fields[$sort_field]){
            $sort_field = $fields[$sort_field];
        }elseif(!in_array($sort_field, $fields)){
            $sort_field = $defaultSortField;
        }

        $sortType = $sortType == 'asc' ? 'asc' : 'desc';

        return [$sort_field,$sortType];
    }


    public static function safe_b64encode($string) {
        $data = base64_encode($string);
        return str_replace(array('+','/','='),array('-','_',''),$data);
    }

    public static function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public static function containsOnlyNull(array $array): bool
    {
        foreach ($array as $value) {
            if (!$value) {
                return true;
            }
        }
        return false;
    }


    public static function getTempFileUrl($fileName = false){
        if ($fileName){
            return storage_path('tmp/uploads/' . $fileName);
        }else{
            return storage_path('tmp/uploads');
        }
    }

    public static function getMinlistData($class,$binds,$key = 'title'){
        $list = $class::where($binds)->orderBy($key)->skip(0)->take(50)->get();

        $res = [];
        foreach ($list as $item){
            $res[] = [
                'label' => $item->{$key},
                'value' => $item->id,
            ];
        }

        return $res;
    }
}
