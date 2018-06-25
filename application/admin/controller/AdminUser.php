<?php


namespace app\admin\controller;



use app\admin\model\AuthGroupAccess;

/**
 * 后台用户类
 * Class AdminUser
 * @package app\admin\controller
 */
class AdminUser extends AdminBase
{
    /**
     * @var \app\admin\model\AdminUser
     */
    protected $userModel;

    /**
     * @var \app\admin\model\AuthGroup
     */
    protected $authGroupModel;

    /**
     * @var AuthGroupAccess
     */
    protected $authGroupAccessModel;

    /**
     * AdminUser constructor.
     * @param \app\admin\model\AdminUser $user
     * @param \app\admin\model\AuthGroup $authGroup
     * @param AuthGroupAccess $authGroupAccess
     */
    public function __construct(
        \app\admin\model\AdminUser $user,
        \app\admin\model\AuthGroup $authGroup,
        AuthGroupAccess $authGroupAccess)
    {
        parent::__construct();
        $this->userModel = $user;
        $this->authGroupModel = $authGroup;
        $this->authGroupAccessModel = $authGroupAccess;
    }

    /**
     * @param string $keyword
     * @param int $page
     * @return mixed
     */
    public function index($keyword = '', $page = 1)
    {
        $map = [];
        if ($keyword) {
            $map['username|phone'] = ['like', "%{$keyword}%"];
        }
        $user_list = $this->userModel
            ->whereOr('username', 'like', "%{$keyword}%")
            ->whereOr('phone', 'like', "%{$keyword}%")
            ->paginate(15, false, ['page' => $page]);

        return $this->fetch('index', [
            'user_list' => $user_list,
            'keyword' => $keyword
        ]);
    }

    /**
     * @return mixed
     */
    public function add()
    {
        $auth_group_list = $this->authGroupModel->select();

        return $this->fetch('add', [
            'auth_group_list' => $auth_group_list
        ]);
    }

    /**
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->post();
        $validate = new \app\admin\validate\AdminUser();
        $data['created_time'] = date('Y-m-d H:i:s');
        if (!$validate->check($data)) {
            return error_json($validate->getError());
        }
        $data['password'] = get_password($data['password']);
        // 数据入库
        if (!$this->userModel->allowField(true)->save($data)) {
            return error_json('保存失败');
        }
        $this->authGroupAccessModel->uid = $this->userModel->id;
        $this->authGroupAccessModel->group_id = $data['group_id'];
        if (!$this->authGroupAccessModel->allowField(true)->save()) {
            $this->userModel->delete();
            return error_json('保存失败');
        }

        return public_json('保存成功', url('admin/admin_user/index'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $user = $this->userModel->find($id);
        $auth_group_list = $this->authGroupModel->select();
        $query = $this->authGroupAccessModel->where('uid', $id)->find();
        $user['group_id'] = $query['group_id'];

        return $this->fetch('edit', [
            'user' => $user,
            'auth_group_list' => $auth_group_list
        ]);
    }

    /**
     * @param $id
     * @return \think\response\Json
     */
    public function update($id)
    {
        $data = $this->request->post();
        $validate = new \app\admin\validate\AdminUser();
        if (!$validate->check($data)) {
            return error_json($validate->getError());
        }
        $user           = $this->userModel->find($id);
        $user->id       = $id;
        $user->username = $data['username'];
        $user->phone    = $data['phone'];
        $user->status   = $data['status'];
        if (!empty($data['password']) && !empty($data['confirm_password'])) {
            $user->password = get_password($data['password']);
        }
        $user->save();
        // 在权限表为空的时候该查询可能为空
        $query = $this->authGroupAccessModel->where('uid', $id)->find();
        if ($query) {
            $query->group_id = $data['group_id'];
            $query->save();
        }

        return public_json('更新成功', url('admin/admin_user/index'));
    }

    /**
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        if ($id == 1) {
            return error_json('超级管理员不可删除');
        }
        if (!$this->userModel->destroy($id)) {
            return error_json('删除失败');
        }
        if (!$this->authGroupAccessModel->where('uid', $id)->delete()) {
            return error_json('删除失败');
        }

        return public_json('删除成功', url('admin/admin_user/index'));
    }
}