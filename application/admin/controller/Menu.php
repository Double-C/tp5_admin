<?php


namespace app\admin\controller;


class Menu extends AdminBase
{
    protected $menuModel;

    public function __construct(\app\admin\model\Menu $menu)
    {
        parent::__construct();
        $this->menuModel = $menu;
    }

    public function index()
    {
        $admin_menu_list = $this->menuModel->order(['sort' => 'DESC', 'id' => 'ASC'])->select();

        return $this->fetch('index', [
            'admin_menu_list' => array2level($admin_menu_list)
        ]);
    }

    public function add($pid = 0)
    {
        $admin_menu_list = $this->menuModel->order(['sort' => 'DESC', 'id' => 'ASC'])->select();

        return $this->fetch('add', [
            'admin_menu_list' => array2level($admin_menu_list),
            'pid'             => $pid
        ]);
    }

    public function save()
    {
        $data = $this->request->post();
        $validate = new \app\admin\validate\Menu();
        if (!$validate->check($data)) {
            return errorJson($validate->getError());
        }
        // 数据入库
        if (!$this->menuModel->allowField(true)->save($data)) {
            return errorJson('保存失败');
        }

        return publicJson('保存成功', url('admin/menu/index'));
    }

    public function edit($id)
    {
        $admin_menu = $this->menuModel->find($id);
        $admin_menu_list = $this->menuModel->order(['sort' => 'DESC', 'id' => 'ASC'])->select();

        return $this->fetch('edit', [
            'admin_menu' => $admin_menu,
            'admin_menu_list' => array2level($admin_menu_list),
        ]);
    }

    public function update($id)
    {
        $data = $this->request->post();
        $validate = new \app\admin\validate\Menu();
        if (!$validate->check($data)) {
            return errorJson($validate->getError());
        }
        // 数据入库
        if (!$this->menuModel->allowField(true)->save($data, $id)) {
            return errorJson('更新失败');
        }

        return publicJson('更新成功', url('admin/menu/index'));
    }

    public function delete($id)
    {
        if (!$this->menuModel->destroy($id)) {
            return errorJson('删除失败');
        }

        return publicJson('删除成功', url('admin/menu/index'));
    }
}