<?php


namespace app\admin\controller;

use think\Controller;
use think\Validate;
use think\Exception;
use app\common\model\Admin as AdminModel;
use Firebase\JWT\JWT;
use think\facade\Cache;

class Auth extends Controller
{
    public function login()
    {
        $formdata = $this->request->post();
//        参数校验
        $validate = Validate::make([
            'username' => 'require',
            'password' => 'require|max:8|min:6'
        ])->message([
            'username.require' => '请填写管理员账号',
            'password.require' => '请填写密码',
            'password.max' => '密码长度过长',
            'password.min' => '密码长度过短',
        ]);
        if (!$validate->check($formdata)) {
            return json(err($validate->getError()))->code(400);
        }
//        查找用户
        $map = [
            'username' => $formdata['username'],
            'status' => 0
        ];
        try {
            $admin = AdminModel::where($map)->find();
        } catch (Exception $exception) {
//            记log
            return json(err('服务器错误'))->code(500);
        }
        if (!$admin) {
            return json(err('管理员不存在'))->code(403);
        }
//        核对密码
        if ($admin->password != sha1($formdata['password'])) {
            dump($admin->password);
            dump(sha1($formdata['password']));
            return json(err('密码错误'))->code(403);
        } else {
//          生成token
            $userinfo = [
                'uid' => $admin->id,
                'admin_name' => $admin->username,
            ];
            $token = JWT::encode($userinfo, SECRET_KEY);
//          缓存 session 双写入
            Cache::set($token, '1');
            session('token', $token);
            return json(['token' => $token]);
        }
    }
}