<?php


namespace app\admin\controller;

use app\common\model\Goods as GoodsModel;
use think\Validate;

class GoodsApi extends ApiBase
{
    public function add()
    {
        $param = $this->request->post();
        $validate = Validate::make([
            'title' => 'require',
            'content' => 'require',
            'price' => 'require|number',
            'number' => 'require|number',
            'banner' => 'require'
        ])->message([
            'title.require' => '缺少标题',
            'content.require' => '缺少简介',
            'price.require' => '缺少价格',
            'price.number' => '价格格式不正确',
            'number.number' => '库存格式不正确',
            'number.require' => '缺少库存',
            'banner.require' => '缺少封面图',            
        ]);

        if (!$validate->check($param)) {
            return json(err('参数错误'))->code(400);
        }

        if (isset($param['id'])) {
            $rlt = GoodsModel::update([
                'title'   => $param['title'],
                'price'   => $param['price'],
                'number'  => $param['number'],
                'content' => $param['content'],
                'banner'  => $param['banner'],
                'update_time' => time()
            ], ['id' => $param['id']]);
        } else {
            $rlt = GoodsModel::create([
                'title'   => $param['title'],
                'price'   => $param['price'],
                'number'  => $param['number'],
                'content' => $param['content'],
                'banner'  => $param['banner'],
                'create_time' => time(),
                'update_time' => time()
            ]);
        }

        if ($rlt) {
            return json(msg('操作成功'));
        } else {
            return json(err(500))->code(500);
        }
    }

    public function delete()
    {
        $id = $this->request->get('id') ?? null;
        if (!$id) {
            return json(err('参数错误'))->code(400);
        }

        $goods = GoodsModel::where('status', 'eq', 0)->get($id);
        if (!$goods) {
            return json('商品已删除或不存在')->code(404);
        }

        $goods->status = 1;

        if ($goods->save()) {
            return json(msg("操作成功"));
        } else {
            return json(err(500))->code(500);
        }
    }
}