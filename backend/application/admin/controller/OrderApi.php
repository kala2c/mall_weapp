<?php


namespace app\admin\controller;

use app\common\model\Goods as GoodsModel;
use app\common\model\Orders as OrdersModel;
use think\Validate;

class OrderApi extends ApiBase
{

    public function cancel()
    {
        $id = $this->request->get('id') ?? null;
        if (!$id) {
            return json(err('参数错误'))->code(400);
        }

        $order = OrdersModel::where('status', 'eq', OrdersModel::STATUS_WAIT)->get($id);
        if (!$order) {
            return json(err('订单已取消或不存在'))->code(404);
        }

        $order->status = OrdersModel::STATUS_CANCEL;

        if ($order->save()) {
            return json(msg("操作成功"));
        } else {
            return json(err(500))->code(500);
        }
    }

    public function send()
    {
        $id = $this->request->get('id') ?? null;
        if (!$id) {
            return json(err('参数错误'))->code(400);
        }

        $order = OrdersModel::where('status', 'eq', OrdersModel::STATUS_WAIT)->get($id);
        if (!$order) {
            return json(err('订单已标记或不存在'))->code(404);
        }

        $order->status = OrdersModel::STATUS_SENT;

        if ($order->save()) {
            return json(msg("操作成功"));
        } else {
            return json(err(500))->code(500);
        }
    }

    public function cancelSend()
    {
        $id = $this->request->get('id') ?? null;
        if (!$id) {
            return json(err('参数错误'))->code(400);
        }

        $order = OrdersModel::where('status', 'eq', OrdersModel::STATUS_SENT)->get($id);
        if (!$order) {
            return json(err('订单已取消标记或不存在'))->code(404);
        }

        $order->status = OrdersModel::STATUS_WAIT;

        if ($order->save()) {
            return json(msg("操作成功"));
        } else {
            return json(err(500))->code(500);
        }
    }

    public function rewait()
    {
        $id = $this->request->get('id') ?? null;
        if (!$id) {
            return json(err('参数错误'))->code(400);
        }

        $order = OrdersModel::where('status', 'eq', OrdersModel::STATUS_CANCEL)->get($id);
        if (!$order) {
            return json(err('订单已恢复或不存在'))->code(404);
        }

        $order->status = OrdersModel::STATUS_WAIT;

        if ($order->save()) {
            return json(msg("操作成功"));
        } else {
            return json(err(500))->code(500);
        }
    }
}