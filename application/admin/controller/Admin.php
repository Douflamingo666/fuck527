<?php
namespace app\admin\controller;
// use think\Controller;

class Admin extends Base{
	
	public function lst(){
		$adminres = db('admin') -> select();
		$this -> assign('adminres',$adminres);
		return $this -> fetch();
	}
	
	public function add(){
		
		if(request() -> isPost()){
			$data = [
				'username' => input('username'),
				'password' => md5(input('password')),
			];
			
			$validate = \think\Loader::validate('admin');
			if($validate -> check($data)){
				$db = db('admin') -> insert($data);
				if($db){
					return $this -> success('管理员添加成功','lst');
				} else {
					return $this -> error('管理员添加失败！');
				}
			} else {
				return $this -> error($validate -> getError());
			}
		}
		return $this -> fetch();
	}
	
	public function edit(){
		$id = input('id');
		
		if(request() -> isPost()){
			$oldpasswd = md5(input('password1'));
			$info = db('admin') -> where('id',$id) -> find();
			if($oldpasswd == $info['password']){
				$data = [
					'id' => $id,
					'username' => input('username'),
					'password' => md5(input('password')),
				];
				
				$validate = \think\Loader::validate('admin');
				if($validate -> scene('edit') -> check($data)){
					$db = \think\Db::name('admin') -> update($data);
					if($db){
						return $this -> success('管理员修改成功！！！','lst');
					} else {
						return $this -> error('管理员修改失败！！！');
					}
				} else {
					return $this -> error($validate -> getError());
				}
			} else{
				return $this -> error('原密码错误！！！');
			}
		}
		
		$adminres = db('admin') -> where('id',$id) -> find();
		$this -> assign('adminres',$adminres);
		return $this -> fetch();
	}
	
	public function delete(){
		$id = input('id');
		if(db('admin') -> delete($id)){
			return $this -> success("管理员删除成功！！！");
		} else {
			return $this -> error('管理员删除失败！！！');
		}
	}
}