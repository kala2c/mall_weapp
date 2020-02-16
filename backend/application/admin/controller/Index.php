<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch('index/login');
    }

    public function welcome()
    {
        return $this->fetch();
    }

    public function help()
    {
        return $this->fetch();
    }

    public function reload()
    {
        return "<script>parent.window.open('/admin', '_self')</script>";
    }
}