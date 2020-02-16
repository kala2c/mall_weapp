<?php
/**
 * Created by PhpStorm.
 * User: clw
 * Date: 19-1-24
 * Time: 上午12:17
 */

namespace app\api\controller;

use app\common\model\Bill as BillModel;
use app\common\model\Orders as OrdersModel;
use app\common\model\Goods as GoodsModel;
use app\common\model\User as MemberModel;
use Firebase\JWT\JWT;
use think\Exception;
use think\Validate;

class Orders extends AuthBase
{
    public function postexchange()
    {
        $query    = $this->request->post();
        $validate = Validate::make([
            'id' => 'require|number'
        ]);
        if (!$validate->check($query)) {
            return json(err('参数错误'))->code(400);
        }
        $goods = GoodsModel::get(['id' => $query['id']]);
        if (!$goods) {
            return json(err('商品不存在'))->code(404);
        }
//        判断商品是否有余量
        if ($goods->number < 1) {
            return json(err('商品已售空'))->code(400);
        }
        $uid = $this->self_info['uid'];
        $orderno = OrdersModel::createNo();
        $info = '兑换【'.$goods->title.'】';
        $user = MemberModel::get($uid);
//        判断用户积分是否够
        if ($user->points < $goods->price) {
            return json(err('积分不足'))->code(403);
        }
//生成账单信息
        $bill = BillModel::create([
            'user_id' => $uid,
            'orderno' => $orderno,
            'type' => BillModel::TYPE_DEC,
            'price' =>$goods->price,
            'info' => $info
        ]);
        if (!$bill) {
            return json(err('服务繁忙，稍后再试'))->code(500);
        }
//        扣除积分
        $user->points -= $goods->price;
        $user->save();
//        生成订单
        $order = OrdersModel::create([
            'user_id' => $this->self_info['uid'],
            'goods_id' => $query['id'],
            'price' => $goods->price,
            'orderno' => $orderno
        ]);
        if (!$order) {
            return json(err('系统繁忙稍后再试'))->code(500);
        }
//        商品减一
        $goods->number -= 1;
        $goods->save();
        return json(msg('兑换成功'));

    }
//    订单列表
    public function getlist()
    {
        $query = $this->request->param();
        $validate = Validate::make([
            'page' => 'require',
            'target' => 'require'
        ]);
        if (!$validate->check($query)) {
            return json(err('参数错误'))->code(400);
        }
//        查询参数
        $map = ['user_id' => $this->self_info['uid']];
//        分别查询几种状态
        switch ($query['target']) {
            case 'wait':
                $map2 = ['status' => OrdersModel::STATUS_WAIT];
                break;
            case 'sent':
                $map2 = ['status' => OrdersModel::STATUS_SENT];
                break;
//            case 'success':
//                $map2 = ['status', ['=', OrdersModel::STATUS_SUCCESS], ['=', OrdersModel::STATUS_CANCEL], 'or'];
//                break;
        }
        $page   = isset($query['page']) ? $query['page'] : 1;
        $count  = 5;
        $offset = ($page - 1) * $count;
//        查询
        try {
            if ($query['target'] == 'success') {
                $total  = OrdersModel::where($map)
                    ->where('status', ['=', OrdersModel::STATUS_SUCCESS], ['=', OrdersModel::STATUS_CANCEL], 'or')
                    ->count();
                $pageMax   = ceil($total / $count);
                $orders = OrdersModel::
                withAttr('status', function ($value, $data) {
                    return $value;
                })->append(['status_text'])->
                with(['user', 'goods'])
                    ->where($map)
                    ->where('status', ['=', OrdersModel::STATUS_SUCCESS], ['=', OrdersModel::STATUS_CANCEL], 'or')
                    ->order('update_time desc')
                    ->limit($offset, $count)
                    ->select()
                    ->toArray();
            } else {
                $total  = OrdersModel::where($map)
                    ->where($map2)
                    ->count();
                $pageMax   = ceil($total / $count);
                $orders = OrdersModel::
                withAttr('status', function ($value, $data) {
                    return $value;
                })->append(['status_text'])->
                with(['user', 'goods'])
                    ->where($map)
                    ->where($map2)
                    ->order('update_time desc')
                    ->limit($offset, $count)
                    ->select()
                    ->toArray();
            }
        } catch (Exception $exception) {
            dump($exception->getMessage());
            return json(err('服务器错误，稍后再试'))->code(500);
        }
        if ($pageMax >= $page) {
            $ext = ['hasNext' => $pageMax-$page];
        } else {
            $ext = ['hasNext' => 0]; 
        }

        $ext['pageMax'] = $pageMax;

        return json([
            'list' => $orders,
            'ext'  => $ext
        ]);
    }
//确认收到
    public function postconfirm()
    {
        $query = $this->request->post();

        $validate = Validate::make([
            'id' => 'require|number',
        ]);

        if (!$validate->check($query)) {
            return json(err('参数错误'))->code(400);
        }
        OrdersModel::updateStatus($query['id'], OrdersModel::STATUS_SUCCESS);

        return json(msg('操作成功'));
    }
}