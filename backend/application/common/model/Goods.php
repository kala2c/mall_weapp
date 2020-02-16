<?php


namespace app\common\model;
use think\Model;

class Goods extends Model
{
    const STATUS_NORMAL = 0;

    public function getBannerUrlAttr($value, $data)
    {
        return $this->getBannerUrlListAttr($value, $data)[0];
    }

    public function getBannerUrlListAttr($value, $data)
    {
        return array_map('self::str2url', explode(",", $data['banner']));
    }

    public static function str2url($str)
    {
        return '/uploads/'.$str;
    }
}