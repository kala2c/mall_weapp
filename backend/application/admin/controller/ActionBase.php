<?php


namespace app\admin\controller;
use Firebase\JWT\JWT;

class ActionBase extends Base
{
    protected $self_info;
    protected $token;
    public function initialize()
    {
        parent::initialize();
        $token = session('token');
//        未登录 跳转登录页面
        if (!$token) {
            $this->assign('unauth', 1);
            $this->error('请先登录','/admin/index/reload', '', '2');
        }

        $self_info = JWT::decode($token, SECRET_KEY, ['HS256']);
        $this->self_info = (array) $self_info;
        $this->token = $token;
    }
}