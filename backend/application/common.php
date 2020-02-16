<?php

// 应用公共文件
const HOST_NAME = '';
const SECRET_KEY = 'chunyun';

// 应用公共文件
function err($msg = 'error')
{
    $msg = $msg == 500 ? '服务器出错了，请稍后重试' : $msg;
//    return ['err' => "(>_<)".$msg];
    return ['err' => $msg];
}
function msg($msg = 'success')
{
    return ['msg' => $msg];
}
function signpwd($password) {
    return sha1($password);
}