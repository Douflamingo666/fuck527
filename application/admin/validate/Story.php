<?php

namespace app\admin\validate;
use think\Validate;

class Story extends Validate{
	
	protected $rule = [
		'title' => 'require|max:50|unique:image',
	];
	
	protected $message = [
		'title.require' => '标题不能为空',
		'title.max' => '标题不能超过50个字符',
		'title.unique' => '标题不能重复',
	];
	
	protected $scene = [
		'edit' => 'title',
	];
}