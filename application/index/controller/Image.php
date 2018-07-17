<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Column;
use app\index\model\Image as MImage;

class Image extends Controller{

	public function title(){
		$Mcolumn = new Column;
		$videores = $Mcolumn -> where('type','video') -> select();
		$this -> assign('videores',$videores);
		$imageres = $Mcolumn -> where('type','image') -> select();
		$this -> assign('imageres',$imageres);
		$storyres = $Mcolumn -> where('type','story') -> select();
		$this -> assign('storyres',$storyres);
	}

	//图片主页
	public function index(){
		$this -> title();

		$Mimage = model('Image');
		
		$numIndex = array();
		$numArr = array();
		$iidnum = $Mimage -> column('iid'); //将所有iid的值存入到数组中
		$resend = count($iidnum);//数组$iidnum的长度值
		if($resend <= 21){
			for($i = 0;$i < $resend;$i++){
				$imgres[$i] = $Mimage -> where('iid',$iidnum[$i]) -> find();
			}
			$this -> assign('resend',$resend);
			$this -> assign('imgres',$imgres);
		} else {
			$index = 0;
			while($index < 21){
				$num = rand(0,$resend - 1);
				if(!in_array($num,$numIndex)){
					$numIndex[] = $num;
					$numArr[] = $iidnum[$num];
					$index++;
				}
			}
			for($k = 0;$k < 21;$k++){
				$imgres[$k] = $Mimage -> where('iid',$numArr[$k]) -> find();
			}
			$this -> assign('resend',21);
			$this -> assign('imgres',$imgres);
		}

		return $this -> fetch();
	}

	//图片分类
	public function classfiy(){
		$this -> title();
		$type = input('type');
		$this -> assign('type',$type);

		$Mimage = model('Image');
		$newimage = $Mimage -> where('column',$type) -> paginate(22);
		$this -> assign('newimage',$newimage);
		
		return $this -> fetch();
	}

	public function play(){
		$this -> title();
		$Mimage = model('Image');

		$iid = input('iid');
		$fileArr = array();
		$imageplay = $Mimage -> where('iid',$iid) -> find();
		$this -> assign('imageplay',$imageplay);
		$path = iconv("UTF-8","GBK","./".$imageplay['path']);//对中文名的目录进行处理

		if($dh = opendir($path)){
			while(($file = readdir($dh)) != false){
				if($file != "." && $file != ".."){
					//$fileArr[] = iconv("GBK","UTF-8",$imageplay['path']."/".$file);//这个干不起，就算把gbk和utf-8换过来也不行
					$newfile = iconv("GBK","UTF-8",$file);
					$fileArr[] = $imageplay['path']."/".$newfile;
				}
			}
			$filenum = count($fileArr);
			$this -> assign('filenum',$filenum);
			$this -> assign('fileArr',$fileArr);
		} else {
			return $this -> error("日你妈，打不开，走开");
		}

		return $this -> fetch();
	}

	public function water(){
		$Mimage = model('Image');

		$iid = 3;
		$fileArr = array();
		$imageplay = $Mimage -> where('iid',$iid) -> find();
		$path = iconv("UTF-8","GBK","./" . $imageplay['path']);//目录是：./static/up_images/火影

		if(is_dir($path)){
			echo "this is dir"."<br />";
		} else {
			echo "sorry"."<br />";
		}
		//$file = scandir($path);
		if($dh = opendir($path)){
			while(($file = readdir($dh)) != false){
				if($file != "." && $file != ".."){
					$fileArr[] = $imageplay['path']."/".$file;
				}
			}
			var_dump($fileArr);
			closedir($dh);
		} else {
			echo "打不开";
		}
	}
	public function water2(){
		$Mimage = new MImage;

		$iid = 1;
		$fileArr = array();
		$imageplay = $Mimage -> where('iid',$iid) -> find();
		$path = './'.$imageplay['path'];

		$dp = dir($path);
		while($file = $dp -> read()){
			if($file != "." && $file != ".."){
				$fileArr[] = $file;
			}
		}
		var_dump($fileArr);
	}
}
