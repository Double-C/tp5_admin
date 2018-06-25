<?php

namespace app\admin\validate;

use think\Validate;

class Menu extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'pid'   => 'require',
        'title' => 'require',
        'name'  => 'require',
//        'sort'  => 'require|number'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'pid.require'   => '请选择上级菜单',
        'title.require' => '请输入菜单名称',
        'name.require'  => '请输入控制器方法',
//        'sort.require'  => '请输入排序',
//        'sort.number'   => '排序只能填写数字'
    ];
}
