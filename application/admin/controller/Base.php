<?php
namespace app\admin\controller;
use think\Controller;

class Base extends Controller{
	public function _initialize(){
		if(!session('id')){
			return $this -> error('日你温骚，给劳资去登录','Login/index');
		}
	}
}