<?php
/**
 * Created by PhpStorm.
 * User: clw
 * Date: 19-1-24
 * Time: 上午12:16
 */

namespace app\api\controller;

use app\common\model\Goods as GoodsModel;
use think\Exception;
use think\Validate;

class Goods extends Base
{
//  获取商品列表
    public function getlist()
    {
//        参数校验
        $query    = $this->request->param();
        $validate = Validate::make([
            'page'       => 'number'
        ]);
        if (!$validate->check($query)) {
            return json(err('参数错误'))->code(400);
        }
//        筛选条件
        $map[] = ['status', '=', GoodsModel::STATUS_NORMAL];
        // $map[] = ['number', '>', 0];
        $page   = isset($query['page']) ? $query['page'] : 1;
        $count  = 8;
        $total  = GoodsModel::where($map)->count();
        $offset = ($page - 1) * $count;
        $pageMax   = ceil($total / $count);
        $host = $this->request->host();
        try {
            $ret =
                GoodsModel::
                withAttr('banner', function ($value, $data) {
                   return 'https://' . $this->request->host() . '/uploads/' . $value;
                })
                    ->where($map)
                    ->limit($offset, $count)
                    ->order('id desc')
                    ->select()
                    ->toArray();
//                $ret->hidden()
        } catch (Exception $exception) {
            return json(err('服务器错误，稍后再试'))->code(500);
        }
        if ($pageMax >= $page) {
            $ext = ['hasNext' => $pageMax-$page];
        } else {
            $ext = ['hasNext' => 0]; 
        }
        if (!$ret) {
            return json(err('没有更多数据了'))->code(404);
        }
        return json([
            'list' => $ret,
            'ext'  => $ext
        ]);
    }

//    获取商品详情
    public function getdetail()
    {
        $query = $this->request->param();
        if (!isset($query['id'])) {
            return json(err('参数错误'))->code(403);
        }
        try {
            $goods =
                GoodsModel::
                withAttr('banner', function ($value, $data) {
                   return 'https://' . $this->request->host() . '/uploads/' . $value;
                })
                    ->where('id', 'eq', $query['id'])
                    ->find();
        } catch (Exception $exception) {
            return json(err('服务器错误，稍后再试'))->code(500);
        }
        if (!$goods) {
            return json(err('数据不存在'))->code(404);
        }
        return json($goods);
    }

}
