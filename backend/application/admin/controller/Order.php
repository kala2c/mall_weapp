<?php

namespace app\admin\controller;
use app\common\model\Goods as GoodsModel;
use app\common\model\Orders as OrdersModel;
use think\Exception;

/**
 * 
 */
class Order extends ActionBase
{
	
	public function manage()
    {
    	$map = [];
        $map[] = ['status', '>', -2];
        //        页码信息
        $page      = $this->request->get('page') ?? 1;
        $pageCount = 15;
        $offset    = ($page - 1) * $pageCount;
        $total     = OrdersModel::where($map)->count();
        $pageMax   = ceil($total / $pageCount);
        try {
            $list =
                OrdersModel::
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
        	'list'       => $list
        ]);
        return $this->fetch();
    }
}