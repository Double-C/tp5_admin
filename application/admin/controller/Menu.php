<?php


namespace app\admin\controller;


/**
 * 菜单管理类
 * Class Menu
 * @package app\admin\controller
 */
class Menu extends AdminBase
{
    /**
     * 菜单表模型
     * @var \app\admin\model\Menu
     */
    protected $menuModel;

    /**
     * Menu constructor.
     * @param \app\admin\model\Menu $menu
     */
    public function __construct(\app\admin\model\Menu $menu)
    {
        parent::__construct();
        $this->menuModel = $menu;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $admin_menu_list = $this->menuModel->order(['sort' => 'DESC', 'id' => 'ASC'])->select();

        return $this->fetch('index', [
            'admin_menu_list' => array2level($admin_menu_list)
        ]);
    }

    /**
     * @param int $pid
     * @return mixed
     */
    public function add($pid = 0)
    {
        $admin_menu_list = $this->menuModel->order(['sort' => 'DESC', 'id' => 'ASC'])->select();

        return $this->fetch('add', [
            'admin_menu_list' => array2level($admin_menu_list),
            'pid'             => $pid
        ]);
    }

    /**
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->post();
        $validate = new \app\admin\validate\Menu();
        if (!$validate->check($data)) {
            return error_json($validate->getError());
        }
        if (!$this->menuModel->allowField(true)->save($data)) {
            return error_json('保存失败');
        }

        return public_json('保存成功', url('admin/menu/index'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $admin_menu = $this->menuModel->find($id);
        $admin_menu_list = $this->menuModel->order(['sort' => 'DESC', 'id' => 'ASC'])->select();

        return $this->fetch('edit', [
            'admin_menu' => $admin_menu,
            'admin_menu_list' => array2level($admin_menu_list),
        ]);
    }

    /**
     * @param $id
     * @return \think\response\Json
     */
    public function update($id)
    {
        $data = $this->request->post();
        $validate = new \app\admin\validate\Menu();
        if (!$validate->check($data)) {
            return error_json($validate->getError());
        }
        if (!$this->menuModel->allowField(true)->save($data, $id)) {
            return error_json('更新失败');
        }

        return public_json('更新成功', url('admin/menu/index'));
    }

    /**
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        if (!$this->menuModel->destroy($id)) {
            return error_json('删除失败');
        }

        return public_json('删除成功', url('admin/menu/index'));
    }
}