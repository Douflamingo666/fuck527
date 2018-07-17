<?php
namespace app\admin\controller;

class Story extends Base{
	
	public function lst(){
		$storyres = \think\Db::name('story') -> select();
		$this -> assign('storyres',$storyres);
		return $this -> fetch();
	}
	
	public function add(){
		
		if(request() -> isPost()){
			$data = [
				'title' => input('title'),
				'column' => input('column'),
			];
			$validate = \think\Loader::validate('story');
			if($validate -> check($data)){
				if($_FILES){
					$file = request() -> file('story');
					//用id来当做文件名
					$sid = \think\Db::table('fk_story') -> order('sid desc') -> column('sid');
					if(empty($sid)){
						$name = 1;
					} else {
						$name = $sid[0] + 1;
					}
					$dir = iconv('UTF-8','GBK',ROOT_PATH.'public/static/up-story/');
					$info = $file -> rule('uniqid') -> move($dir,$name.".txt");
					
					if($info){
						$data['path'] = 'static/up-story/'.$name.".txt";
						
						$db = \think\Db::name('story') -> insert($data);
						if($db){
							return $this -> success('上传成功！！！','lst');
						} else {
							return $this -> error('上传失败！！！');
						}
					} else {
						return $this -> error('文件上传失败！！！');
					}
				} else {
					return $this -> error('未接收到文件');
				}
			} else {
				return $this -> error($validate -> getError());
			}
		}
		
		$storyres = \think\Db::name('column') -> where('type','story') -> select();
		$this -> assign('storyres',$storyres);
		return $this -> fetch();
	}
	
	public function edit(){
		$sid = input('id');
		
		if(request() -> isPost()){
			$data = [
				'sid' => $sid,
				'title' => input('title'),
				'column' => input('column'),
			];
			
			$validate = \think\Loader::validate('image');
			if($validate -> scene('edit') -> check($data)){
				$db = db('story') -> update($data);
				if($db){
					return $this -> success("小说修改成功！！！",'lst');
				} else {
					return $this -> error('小说修改失败！！！');
				}
			}
		}
		
		$storyres = \think\Db::name('story') -> where('sid',$sid) -> find();
		$this -> assign('storyres',$storyres);
		$column = \think\Db::name('column') -> where('type','story') -> select();
		$this -> assign('column',$column);
		return $this -> fetch();
	}
	
	public function delete(){
		$sid = input('id');
		
		if(db('story') -> delete($sid)){
			$dir = iconv('UTF-8','GBK',ROOT_PATH.'public/static/up-story/'.$sid.'.txt');
			unlink($dir);
			return $this -> success('小说删除成功','lst');
		} else {
			return $this -> error('小说删除失败');
		}
		
	}
	
	public function water(){
		$sid = \think\Db::table('fk_image') -> order('iid desc') -> column('iid');
		var_dump($sid);
// 		return $name;
	}
}