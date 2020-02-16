<?php

namespace app\admin\controller;

class Layout extends ActionBase
{
    //    渲染大布局视图
    public function layout()
    {
//        头部导航
        $topBarConfig = [
            'goods' => '商品管理',
            'order' => '订单管理',
            'member' => '用户管理',
        ];
        $this->assign('topBarConfig', $topBarConfig);
//        根据target判断当前用户点击的头部导航项
        $target = $this->request->get('target') ?? 'welcome';
        switch ($target) {
//          首页
            case 'welcome':
                $leftBarConfig = [
                    '欢迎页面' => '/action/welcome',
                    // '使用说明' => '/action/help'
                ];
                break;
//          商品管理
            case 'goods':
                $leftBarConfig = [
                    '商品列表' => '/action/goods/manage',
                    '添加商品' => '/action/goods/add',
                ];
                break;
//          订单管理
            case 'order':
                $leftBarConfig = [
                    '订单列表' => '/action/order/manage'
                ];
                break;
//          成员管理
            case 'member':
                $leftBarConfig = [
                    '成员列表' => '/action/member/manage',
                    // '积分排行' => '/action/member/',
                ];
                break;
        }
        $this->assign([
            'leftBarConfig' => $leftBarConfig,
            'defaultAction' => $leftBarConfig[array_keys($leftBarConfig)[0]]
        ]);
//        判定用户能不能切换组织
        $this->assign([
//          用户名
            'username' => $this->self_info['admin_name'],
        ]);
        return $this->fetch();
    }
}