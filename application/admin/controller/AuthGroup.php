<?php


namespace app\admin\controller;


use app\admin\validate\UpdateAuthGroup;

/**
 * 权限组类
 * Class AuthGroup
 * @package app\admin\controller
 */
class AuthGroup extends AdminBase
{
    /**
     * 权限表模型
     * @var \app\admin\model\AuthGroup
     */
    protected $authGroupModel;

    /**
     * 菜单表模型
     * @var \app\admin\model\Menu
     */
    protected $menuModel;

    /**
     * AuthGroup constructor.
     * @param \app\admin\model\AuthGroup $authGroup
     * @param \app\admin\model\Menu $menu
     */
    public function __construct(\app\admin\model\AuthGroup $authGroup, \app\admin\model\Menu $menu)
    {
        parent::__construct();
        $this->authGroupModel = $authGroup;
        $this->menuModel = $menu;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $auth_group_list = $this->authGroupModel->select();

        return $this->fetch('index', [
            'auth_group_list' => $auth_group_list,
        ]);
    }

    /**
     * @return mixed
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->post();
        $validate = new \app\admin\validate\AuthGroup();
        if (!$validate->check($data)) {
            return error_json($validate->getError());
        }
        // 数据入库
        if (!$this->authGroupModel->allowField(true)->save($data)) {
            return error_json('保存失败');
        }

        return public_json('保存成功', url('admin/auth_group/index'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $auth_group = $this->authGroupModel->find($id);

        return $this->fetch('edit', ['auth_group' => $auth_group]);
    }

    /**
     * @param $id
     * @return \think\response\Json
     */
    public function update($id)
    {
        $data = $this->request->post();
        $validate = new \app\admin\validate\AuthGroup();
        if (!$validate->check($data)) {
            return error_json($validate->getError());
        }
        // 数据入库
        if (!$this->authGroupModel->allowField(true)->save($data, $id)) {
            return error_json('更新失败');
        }

        return public_json('更新成功', url('admin/auth_group/index'));
    }

    /**
     * @param $id
     * @return mixed
     */
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


    /**
     * @return \think\response\Json
     */
    public function updateAuthGroupRule()
    {
        $data = $this->request->post();
        $validate = new UpdateAuthGroup();
        if (!$validate->check($data)) {
            return error_json($validate->getError());
        }
        $group_data['id'] = $data['id'];
        $group_data['rules'] = is_array($data['auth_rule_ids']) ? implode(',', $data['auth_rule_ids']) : '';

        if ($this->authGroupModel->save($group_data, $data['id']) === false) {
            return error_json('授权失败');
        }

        return public_json('授权成功', url('admin/auth_group/index'));
    }

    /**
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        if ($id == 1) {
            return error_json('技术部不可删除');
        }
        if (!$this->authGroupModel->destroy($id)) {
            return error_json('删除失败');
        }

        return public_json('删除成功', url('admin/auth_group/index'));
    }
}