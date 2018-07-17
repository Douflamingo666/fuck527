<?php
namespace app\admin\controller;

class Image extends Base{
	
	public function lst(){
		
		$imageres = \think\Db::name('image') -> select();
		$this -> assign('imageres',$imageres);
		return $this -> fetch();
	}
	
	public function add(){
		
		if(request() -> isPost()){
			$data = [
				'title' => input('title'),
				'column' => input('column'),
			];
			//验证数据
			$validate = \think\Loader::validate('image');
			if($validate -> check($data)){
				//处理图片
				if($_FILES){
					//判断文件格式是否正确
				
					$files = request() -> file('image');
					//创建以title为名的目录
					$dir = iconv("UTF-8","GBK",ROOT_PATH.'public/static/up_images/'.input('title'));
					if(mkdir($dir,false)){
						foreach($files as $file){
							$info = $file -> rule('uniqid') -> move($dir,'');
							if(!$info){
								return $this -> error($file -> getError());
							}
							$filename = $info->getSaveName();
							$exclePath = iconv("GB2312","UTF-8",  $filename);
						}
						//图片保存路径
						$data['path'] = 'static/up_images/'.input('title').DS;
					} else {
						return $this -> error("图片上传失败！");
					}
				}
				//写入记录
				$db = \think\Db::name('image') -> insert($data);
				if($db){
					return $this -> success('图片上传成功，感谢狼友的支持','lst');
				} else {
					return $this -> error('哦豁，不得行哦');
				}
			} else {
				return $this -> error($validate -> getError());
			}
		}
		
		$imageres = \think\Db::name('column') -> where('type','image') ->select();
		$this -> assign('imageres',$imageres);
		return $this -> fetch();
	}
	
	public function delete(){
		$iid = input('id');
		
		$info = db('image') -> where('iid',$iid) -> find();
		$dir = iconv('UTF-8','GBK',ROOT_PATH.'public/'.$info['path']);
		
		if($handle = opendir($dir)){
			while(($file = iconv("GBK","UTF-8",readdir($handle))) != false){
				if($file == "." || $file == "..")
					continue;
				$path = iconv("UTF-8","GBK",ROOT_PATH."public/".$info['path']."/".$file);
				unlink($path);
			}
			closedir($handle);
			
 			$res = rmdir($dir);
 			if($res){
 				$index = 1;
 			} else {
 				$index = 0;
 			}
		} else {
			return $this -> error("文件都打不开，你搞鸭儿啊");
		}
		
 		$db = \think\Db::name('image') -> delete($iid);
 		if($db){
			if($index == 0){
 				return $this -> success("删除成功，但是注意哈，目录没删掉！！");
			}
 			return $this -> success("删除成功！！");
 		} else {
			if($index == 0){
 				return $this -> error("删除失败，而且目录也没删掉！！！");
			}
 			return $this -> error("删除失败！！！");
 		}
	}
	
	public function edit(){
		$iid = input('id');
		
		if(request() -> isPost()){
			$data = [
				'iid' => $iid,
				'title' => input('title'),
				'path' => 'static/up_images/'.input('title'),
				'column' => input('column'),
			];
			
			$validate = \think\loader::validate('image');
			if($validate -> scene('edit') -> check($data)){
				$info = \think\Db::name('image') -> where('iid',$iid) -> find();
				$oldname = iconv('UTF-8','GBK',ROOT_PATH.'public/'.$info['path']);
				$db = \think\Db::name('image') -> update($data);
				if($db){
					//修改目录名
					$newname = iconv('UTF-8','GBK',ROOT_PATH.'public/static/up_images/'.input('title'));
					$rename = rename($oldname,$newname);
					return $this -> success('修改成功！！！','lst');
				} else {
					return $this -> error('修改失败！！！');
				}
			}
		}
		
		$imageres = \think\Db::name('image') -> where('iid',$iid) -> find();
		$this -> assign('imageres',$imageres);
		$column = \think\Db::name('column') -> where('type','image') -> select();
		$this -> assign('column',$column);
		return $this -> fetch();
	}
}
