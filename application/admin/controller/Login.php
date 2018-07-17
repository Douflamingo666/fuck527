<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Login as Log;

class Login extends Controller{
	
	public function index(){
		
		if(request() -> isPost()){
			$login = new Log;
			$res = $login -> login(input('username'),input('password'));
			if($res == 1){
				return $this -> success('登录成功，正在跳转','Index/index');
			} elseif($res == 2){
				return $this -> error('账号或密码错误');
			} else {
				return $this -> error('账号存都不存在，你搞锤子');
			}
		}
		return $this -> fetch();
	}
	
	public function logout(){
		session(null);
		return $this -> success('成功退出','index');
	}
}