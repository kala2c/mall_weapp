<?php
/**
 * Created by PhpStorm.
 * User: clw
 * Date: 19-1-24
 * Time: 上午12:17
 */

namespace app\api\controller;

use app\common\model\Bill as BillModel;
use app\common\model\User as MemberModel;
use think\Validate;
use think\Exception;

class Member extends AuthBase
{
//    获取个人信息
    public function getselfinfo()
    {
        $map   = ['openid' => $this->self_info['openid']];
        $field = ['phone', 'nickname', 'points', 'headpic', 'realname'];
//        $map2 = [
//            'id' => $this->self_info['uid'],
//            'status' => 0
//        ];
        try {
            $ret = MemberModel::where('status', 0)->field($field)->get($map);
        } catch (Exception $exception) {
            return json(err(500))->code(500);
        }
        if (!$ret) {
            return json(err('尚未完善个人信息'))->code(403);
        }
        return json($ret);
    }

//    积分明细 即账单
    public function getbilllist()
    {
        $map = ['user_id' => $this->self_info['uid']];

        try {
            $billList = BillModel::where($map)
                ->order('update_time desc')
                ->select();
        } catch (Exception $exception) {
            return json(err(500))->code(500);
        }

        return json($billList);
    }

}