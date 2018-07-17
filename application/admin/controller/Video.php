<?php
namespace app\admin\controller;

class Video extends Base{
	
	//视频列表
	public function lst(){
		$videores = db('video') -> select();
		$this -> assign('videores',$videores);
		return $this -> fetch();
	}
	
	//添加视频
	public function add(){
		
		//判断是否有数据上传
		if(request() -> isPost()){
			//接收数据，并存入$data
			$data = [
				'title' => input('title'),
				'column' => input('column'),
			];
			//判断是否有视频上传
			if($_FILES['video']['tmp_name']){
				$file = request() -> file('video');
				$info = $file -> move(ROOT_PATH."public/static".DS."up_video");
				if($info){
					//将视频保存路径存入到$data中
					$data['path'] = "static".DS."up_video/".$info -> getSaveName();						
				} else{
					return $this -> error($file -> getError());
				}
			} else {
				return $this -> error("请添加视频文件！");
			}
			
			//插入数据到库中
			$validate = \think\Loader::validate('Video');
			if($validate -> check($data)){
				$db = \think\Db::name('video') -> insert($data);
				if($db){
					return $this -> success("视频添加成功！！！");
				} else {
					return $this -> error("视频添加失败！！！");
				}
			} else {
				return $this -> error($validate -> getError());
			}
		}
		
		//查询栏目表
		$colres = \think\Db::name('column') -> where("type","EQ","video") -> select();
		$this -> assign('colres',$colres);
		
		return $this -> fetch();
	}
	
	//修改视频
	public function edit(){
		$vid = input('id');
		if(request() -> isPost()){
			$data = [
				'vid' => $vid,
				'title' => input('title'),
				'column' => input('column'),
			];
			
			//场景验证数据
			$validate = \think\Loader::validate('video');
			if($validate -> scene('edit') -> check($data)){
				//如果$data里没有加vid，而是在下面用where限制，会报错。应该是要让场景验证里去掉unique
				$db = db('video') -> update($data);
				if($db){
					return $this -> success('修改成功','lst');
				} else {
					return $this -> error('修改失败');
				}
			} else {
				return $this -> error($validate -> getError());
			}
		}
		//将已有信息传入模板
		$videores = db('video') -> where('vid',$vid) -> find();
		$this -> assign('videores',$videores);
		$colres = db('column') -> where('type','video') -> select();
		$this -> assign('colres',$colres);
		return $this -> fetch();
	}
	
	//删除视频
	public function delete(){
		$vid = input('id');
		
		//删除相应文件
		$info = db('video') -> where('vid',$vid) -> find();
		//下面的两种写法都可以
		//unlink(iconv("UTF-8","GBK",ROOT_PATH."public/".$info['path']));
		unlink(iconv("UTF-8","GBK","./".$info['path']));

		
		//删除视频后，要检查目录是否为空，如果是空，就得把目录删掉
		//$path = iconv("UTF-8","GBK","./".$info['path']);
		$revdir = strrev($info['path']);
		$newdir = strrev(strstr($revdir,DS));
		//$path = iconv("UTF-8","GBK",ROOT_PATH."public/".$newdir);
		$path = iconv("UTF-8","GBK","./".$newdir);
		if($dp = opendir($path)){
			$exists = 0;
			while(($file = iconv("GBK","UTF-8",readdir($dp))) != false){
				if($file != "." && $file != ".."){
					$exists = 1;
					break;
				}
			}
			//$exists等于0就代表目录为空了
			if($exists == 0){
				if(rmdir($path)){
					$index = 1;
				} else {
					$index = 0;
				}
			}
		} else {
			return $this -> error("我日你妈，文件都打不开，搞鸭儿啊");
		}

		//删除表中记录
		if(db('video') -> delete($vid)){
			if($index == 0){
				return $this -> success("卧槽，目录没删掉");
			}
			return $this -> success("删除成功！！！");
		} else {
			return $this -> error("删除失败！！！");
		}
	}
}
