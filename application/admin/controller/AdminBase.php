<?php


namespace app\admin\controller;


use app\admin\org\Auth;
use think\Controller;
use think\Db;
use think\facade\Session;
use think\facade\View;
use think\Loader;

/**
 * 后台控制器基类
 * 实现权限判断跟获取侧边菜单信息
 * Class AdminBase
 * @package app\admin\controller
 */
class AdminBase extends Controller
{
    /**
     * AdminBase constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();
        $this->getMenu();
        // 输出当前请求控制器（配合后台侧边菜单选中状态）
        $this->assign('controller', Loader::parseName($this->request->controller()));
        // 输出前端资源路径
        View::share([
            'js_path' => '/static/js',
            'css_path' => '/static/css',
            'static_path' => '/static',
            'app_name' => env('app_name'),
            'year'  => date('Y')
        ]);
    }
    /**
     * 获取侧边栏菜单
     */
    protected function getMenu()
    {
        $menu = [];
        $admin_id = Session::get('admin_id');
        $auth = new Auth();

        $auth_rule_list = Db::name('auth_rule')->where('status', 1)->order(['id' => 'ASC'])->select();

        foreach ($auth_rule_list as $value) {
            if ($auth->check($value['name'], $admin_id) || $admin_id == 1) {
                $menu[] = $value;
            }
        }
        $menu = !empty($menu) ? array2tree($menu) : [];
        $this->assign('menu', $menu);
    }

    /**
     * 权限检查
     */
    protected function checkAuth()
    {
        if (!Session::has('admin_id')) {
            $this->redirect('admin/login/index');
        }
        $module = $this->request->module();
        $controller = $this->request->controller();
        $action = $this->request->action();
        // 排除权限
        $not_check = ['admin/Index/index', 'admin/AuthGroup/getjson', 'admin/System/clear'];
        if (!in_array($module . '/' . $controller . '/' . $action, $not_check)) {
            $auth = new Auth();
            $admin_id = Session::get('admin_id');
            if (!$auth->check($module . '/' . $controller . '/' . $action, $admin_id) && $admin_id != 1) {
                $this->error('没有权限');
            }
        }
    }
}