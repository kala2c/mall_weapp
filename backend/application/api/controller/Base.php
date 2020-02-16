<?php
/**
 * Created by PhpStorm.
 * User: clw
 * Date: 19-2-14
 * Time: 下午10:36
 */

namespace app\api\controller;
//use app\common\Log;
use think\Controller;


class Base extends Controller
{
    protected function enlog($sql = '', $errMsg = '')
    {
//        Log::create($this->request, $sql, $errMsg);
    }
}