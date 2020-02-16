<?php


namespace app\admin\controller;
use app\common\model\Goods as GoodsModel;
use app\common\model\Orders as OrdersModel;
use think\Exception;


class Goods extends ActionBase
{
    public function manage()
    {
        $map = [];
        $page      = $this->request->get('page') ?? 1;
        $pageCount = 15;
        $offset    = ($page - 1) * $pageCount;
        $map[]     = ['status', 'eq', 0];
        $total     = GoodsModel::where($map)->count();
        $pageMax   = ceil($total / $pageCount);
        try {
            $goods =
                GoodsModel::
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
            'list' => $goods
        ]);
        return $this->fetch();
    }

    public function add()
    {
    	return $this->fetch();
    }

    public function edit()
    {
        $id = $this->request->get('id') ?? null;
        if (!$id) {
            return '错误的请求';
        }

        $goods = GoodsModel::where('status', 'eq', '0')->get($id);
        if (!$goods) {
            return '商品不存在';
        } 

        $this->assign('edit', 1);
        $this->assign('goods', $goods);
        return $this->fetch('goods/add');
    }

}