<?php


namespace app\admin\controller;


use app\admin\model\AdminUser;
use think\Controller;
use think\facade\Session;
use think\Request;
use think\facade\View;

/**
 * 登录类
 * Class Login
 * @package app\admin\controller
 */
class Login extends Controller
{
    /**
     * 登录页面
     * @return mixed
     */
    public function index()
    {
        if (Session::has('admin_id')) {
            $this->redirect('admin/index/index');
        }
        View::share([
            'js_path' => '/static/js',
            'css_path' => '/static/css',
            'static_path' => '/static',
        ]);

        return $this->fetch('index');
    }

    /**
     * 执行登陆操作
     * @param Request $request
     * @param AdminUser $user
     * @return \think\response\Json
     */
    public function login(Request $request, AdminUser $user)
    {
        $data = $request->param();
        // 实例化表单验证器验证数据
        $validate = new \app\admin\validate\Login();
        if (!$validate->check($data)) {
            return error_json($validate->getError());
        }
        $query = $user->whereOr('username', $data['username'])
            ->whereOr('phone', $data['username'])
            ->find();
        if (!$query) {
            return error_json('用户不存在');
        }
        if (get_password($data['password']) != $query['password']) {
            return error_json('密码错误');
        }
        Session::set('admin_id', $query['id']);
        Session::set('admin_name', $query['username']);
        // 记录登录时间和登陆ip地址
        $query->last_login_time = date('Y-m-d H:i:s');
        $query->last_login_ip = $this->request->ip();
        $query->save();

        return public_json('登录成功', url('admin/index/index'));
    }

    /**
     * 执行退出登录
     * 使用tp自带的跳转
     */
    public function logout()
    {
        Session::delete('admin_id');
        Session::delete('admin_name');

        $this->success('退出成功', 'admin/login/index', '', 1);
    }
}