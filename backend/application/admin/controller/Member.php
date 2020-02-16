<?php


namespace app\admin\controller;


use app\common\model\User as UserModel;
use think\Exception;
use think\Session;

class Member extends ActionBase
{
    public function manage()
    {
        $map = [];
//        $realName = $this->request->post('realname') ?? session('manage_realname');
        $realName = $this->request->post('realname') ?? null;
//        $phone = $this->request->post('phone') ?? session('manage_phone');
        $phone = $this->request->post('phone') ?? null;
        if ($realName) {
//            session('manage_realname', $realName);
            $map[] = ['realname', 'like', '%'.$realName.'%'];
        }
        if ($phone) {
//            session('manage_phone', $phone);
            $map[] = ['phone', 'like', '%'.$phone.'%'];
        }
        $this->assign('realname', $realName);
        $this->assign('phone', $phone);

        $map[] = ['status', 'eq', 0];
        //        页码信息
        $page      = $this->request->get('page') ?? 1;
        $pageCount = 20;
        $offset    = ($page - 1) * $pageCount;
//        $map[]     = ['', '', 0];
        $total     = UserModel::where($map)->count();
        $pageMax   = ceil($total / $pageCount);
        try {
            $memberList =
                UserModel::
                    where($map)
                    ->order('create_time desc')
                    ->limit($offset, $pageCount)
                    ->select();
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
        $this->assign([
            'page'       => $page,
            'pageMax'    => $pageMax,
            'total'      => $total,
            'pageurl'    => $this->request->baseUrl(),
            'param'      => '', // 虽然为空，但是你去不掉他 看看page.html
            'list' => $memberList,
        ]);
        return $this->fetch();
    }
//修改用户信息
    public function edit()
    {
        $id = $this->request->get('id');
        if (!$id) {
            return '参数错误';
        }
        $member = UserModel::get($id);
        if (!$member) {
            return '用户不存在';
        }
        $this->assign([
            'member' => $member
        ]);
        return $this->fetch();
    }
//    修改用户积分
    public function points()
    {
        $id = $this->request->get('id');
        if (!$id) {
            return '参数错误';
        }
        $member = UserModel::get($id);
        if (!$member) {
            return '用户不存在';
        }
        $this->assign([
            'member' => $member
        ]);
        return $this->fetch();
    }
}