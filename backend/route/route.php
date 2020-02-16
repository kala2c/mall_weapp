<?php
//小程序端api 使用快捷路由

Route::controller('api/member', 'api/member');
Route::controller('api/goods', 'api/goods');
Route::controller('api/wechat', 'api/wechat');
Route::controller('api/orders', 'api/orders');
return [
//    action-url
    'login'                         => 'admin/index/index',
    'administrator'                 => 'admin/layout/layout',
    'action/welcome'                => 'admin/index/welcome',
    'action/member/manage'          => 'admin/member/manage',
    'action/member/editinfo'        => 'admin/member/edit',
    'action/member/editpoints'      => 'admin/member/points',
    'action/goods/manage'           => 'admin/goods/manage',
    'action/goods/add'              => 'admin/goods/add',
    'action/goods/edit'             => 'admin/goods/edit',
    'action/order/manage'           => 'admin/order/manage',
//   api
    'admin/login'                   => 'admin/auth/login',
    'admin/loginout'                => 'admin/apiBase/loginout',
    'admin/member/editinfo'         => 'admin/memberApi/edit',
    'admin/member/editpoints'       => 'admin/memberApi/points',
    'admin/goods/add'               => 'admin/goodsApi/add',
    'admin/goods/delete'            => 'admin/goodsApi/delete',
    'admin/upload/img'              => 'admin/upload/uploadImg',
    'admin/order/cancel'            => 'admin/orderApi/cancel',
    'admin/order/cancelsend'        => 'admin/orderApi/cancelSend',
    'admin/order/send'              => 'admin/orderApi/send',
    'admin/order/rewait'              => 'admin/orderApi/rewait',
];
