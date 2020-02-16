<?php
namespace app\index\controller;
use app\common\model\User;
use app\common\RandName;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->redirect('/admin');
        return;
    }

    public function insert()
    {
        $name = new RandName();
//        return $name->getName();

        $data = [];
        $phone1 = ['133', '156', '155', '178', '187', '150'];
        for ($i = 0; $i < 435; $i ++) {
            $data[] = [
                'openid' => md5(rand(1, 9).rand(100,200).rand(1, 999)),
                'phone' => $phone1[rand(0, 5)].rand(6553, 6778).rand(1234, 9999),
                'realname' => $name->getName(),
                'points' => rand(12, 99)
            ];
        }

        $user = new User();
        $result = $user->saveAll($data);
        dump($result);
    }
}
