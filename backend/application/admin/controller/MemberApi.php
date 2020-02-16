<?php


namespace app\admin\controller;
use app\common\model\Bill as BillModel;
use app\common\model\User as UserModel;
use think\Controller;
use think\Validate;

class MemberApi extends Controller
{
    public function edit()
    {
        $query = $this->request->post();
        $validate = Validate::make([
            'id' => 'require|number',
            'realname' => 'require',
            'phone' => 'require|length:11'
        ])->message([
            'id.require' => '缺少id',
            'realname.require' => '请填写用户姓名',
            'phone.require' => '请填写手机号',
            'id.number' => 'id错误',
            'phone.length' => '手机号格式不正确'
        ]);
        if (!$validate->check($query)) {
            return json(err($validate->getError()))->code(400);
        }
        $member = UserModel::get($query['id']);
        if (!$member) {
            return json(err('用户不存在'))->code(404);
        }
        $member->realname = $query['realname'];
        $member->phone = $query['phone'];
        if ($member->save()) {
            return json(msg('修改成功'));
        } else {
            return json(err(500))->code(500);
        }
    }

    public function points()
    {
        $query = $this->request->post();
        $validate = Validate::make([
            'id' => 'require|number',
            'points' => 'require|number|min:0',
        ])->message([
            'id.require' => '缺少id',
            'points.require' => '请填写积分',
            'id.number' => 'id错误',
            'points.number' => '积分格式不正确',
            'points.min' => '积分不能小于0',
        ]);
        if (!$validate->check($query)) {
            return json(err($validate->getError()))->code(400);
        }
        $member = UserModel::get($query['id']);
        if (!$member) {
            return json(err('用户不存在'))->code(404);
        }
        $points_old = $member->points;
        if ($points_old == $query['points']) {
            return json(msg('没有调整'));
        }
        $price = $query['points'] - $points_old;
        if ($price > 0) {
            $type = BillModel::TYPE_ADMIN_INC;
            $info = '系统赠送';
        } else {
            $type = BillModel::TYPE_ADMIN_DEC;
            $info = '系统调整';
        }
//        $type = $price > 0 ? BillModel::TYPE_INC : BillModel::TYPE_DEC;
        $member->points = $query['points'];
        $info = !$query['info'] ? $info : $query['info'];
        if ($member->save()) {
            $bill = BillModel::create([
                'user_id' => $member->id,
                'type' => $type,
                'price' => abs($price),
                'info' => $info
            ]);
            if (!$bill) {
                return json(err('服务繁忙，稍后再试'))->code(500);
            }
            return json(msg('调整成功'));
        } else {
            return json(err(500))->code(500);
        }
    }
}