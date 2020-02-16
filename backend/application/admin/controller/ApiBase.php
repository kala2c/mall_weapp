<?php


namespace app\admin\controller;
use think\facade\Cache;
use Firebase\JWT\JWT;

class ApiBase extends Base
{
    protected $self_info;
    protected $token;

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
            echo json_encode(err('未登录'));
            exit;
        }

        $this->token = $token;
        $self_info = JWT::decode($token, SECRET_KEY, ['HS256']);
        $this->self_info = (array) $self_info;
    }

    public function loginout()
    {
        $token = $this->request->header('authorization');
        Cache::rm($token);
        session(null);
        return json(msg('操作成功'));
    }
}