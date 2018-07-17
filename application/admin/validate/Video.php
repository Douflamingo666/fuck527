<?php

namespace app\admin\validate;
use think\Validate;

class Video extends Validate{
	
	protected $rule = [
		"title" => "require|max:50|unique:video",
	];
	
	protected $message = [
		"title.require" => "标题不能为空",
		"title.max" => "标题不能大于50个字",
		"title.unique" => "标题不能重复",
	];
	
	protected $scene = [
		'edit' => 'title',
	];
}