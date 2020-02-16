<?php
/**
 * Created by PhpStorm.
 * User: clw
 * Date: 19-1-31
 * Time: 下午8:13
 */

namespace app\api\controller;

use think\facade\Cache;
use Firebase\JWT\JWT;

class AuthBase extends Base
{
    protected $self_info;

    public function initialize()
    {
        parent::initialize();
        $token = $this->request->header('authorization') ?? null;
        if (isset($token)) {
            $token = explode(' ', $token);
            if (count($token) > 1) {
                if ($token[0] = 'Bearer') {
                    $token = $token[1];
                } else {
                    $token = null;
                }
            } else {
                $token = null;
            }
        }
        $local_key = !$token ? false : Cache::get($token);
        if (!$token || !$local_key) {
            header('HTTP/1.1 401 Unauthorized');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(err('未登录 请先授权或完善个人信息'));
            exit;
        }

        $self_info       = JWT::decode($token, '2nd', ['HS256']);
        $this->self_info = (array)$self_info;
    }
}