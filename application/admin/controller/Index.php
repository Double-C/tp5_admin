<?php
namespace app\admin\controller;

/**
 * 后台首页 纯静态页面
 * Class Index
 * @package app\admin\controller
 */
class Index extends AdminBase
{
    /**
     * 后台首页，提供常用功能的快速入口
     * @return mixed
     */
    public function index()
    {
        return $this->fetch('index');
    }
}
