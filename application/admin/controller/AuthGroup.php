<?php


namespace app\admin\controller;


use app\admin\validate\UpdateAuthGroup;

class AuthGroup extends AdminBase
{
    protected $authGroupModel;

    protected $menuModel;

    public function __construct(\app\admin\model\AuthGroup $authGroup, \app\admin\model\Menu $menu)
    {
        parent::__construct();
        $this->authGroupModel = $authGroup;
        $this->menuModel = $menu;
    }

    public function index()
    {
        $auth_group_list = $this->authGroupModel->select();

        return $this->fetch('index', [
            'auth_group_list' => $auth_group_list,
        ]);
    }

    public function add()
    {
        return $this->fetch();
    }

    public function save()
    {
        $data = $this->request->post();
        $validate = new \app\admin\validate\AuthGroup();
        if (!$validate->check($data)) {
            return errorJson($validate->getError());
        }
        // 数据入库
        if (!$this->authGroupModel->allowField(true)->save($data)) {
            return errorJson('保存失败');
        }

        return publicJson('保存成功', url('admin/auth_group/index'));
    }

    public function edit($id)
    {
        $auth_group = $this->authGroupModel->find($id);

        return $this->fetch('edit', ['auth_group' => $auth_group]);
    }

    public function update($id)
    {
        $data = $this->request->post();
        $validate = new \app\admin\validate\AuthGroup();
        if (!$validate->check($data)) {
            return errorJson($validate->getError());
        }
        // 数据入库
        if (!$this->authGroupModel->allowField(true)->save($data, $id)) {
            return errorJson('更新失败');
        }

        return publicJson('更新成功', url('admin/auth_group/index'));
    }

    public function auth($id)
    {
        return $this->fetch('auth', ['id' => $id]);
    }

    /**
     * AJAX获取规则数据
     * @param $id
     * @return mixed
     */
    public function getJson($id)
    {
        $auth_group_data = $this->authGroupModel->find($id)->toArray();
        $auth_rules = explode(',', $auth_group_data['rules']);
        $auth_rule_list = $this->menuModel->field('id,pid,title')->select();

        foreach ($auth_rule_list as $key => $value) {
            in_array($value['id'], $auth_rules) && $auth_rule_list[$key]['checked'] = true;
        }

        return $auth_rule_list;
    }


    public function updateAuthGroupRule()
    {
        $data = $this->request->post();
        $validate = new UpdateAuthGroup();
        if (!$validate->check($data)) {
            return errorJson($validate->getError());
        }
        $group_data['id'] = $data['id'];
        $group_data['rules'] = is_array($data['auth_rule_ids']) ? implode(',', $data['auth_rule_ids']) : '';

        if ($this->authGroupModel->save($group_data, $data['id']) === false) {
            return errorJson('授权失败');
        }

        return publicJson('授权成功', url('admin/auth_group/index'));
    }

    public function delete($id)
    {
        if ($id == 1) {
            return errorJson('技术部不可删除');
        }
        if (!$this->authGroupModel->destroy($id)) {
            return errorJson('删除失败');
        }

        return publicJson('删除成功', url('admin/auth_group/index'));
    }
}