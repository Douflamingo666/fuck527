<?php

namespace app\admin\validate;
use think\Validate;

class Admin extends Validate{
	
	protected $rule = [
		'username' => 'require|max:10|unique:admin',
	];
	
	protected $message = [
		'username.require' => '姓名不能为空',
		'username.max' => '姓名不能超过10位',
		'username.unique' => '姓名不能重复',
	];
	
	protected $scene = [
		'edit' => 'username',
	];
}