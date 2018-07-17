<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Column;
use app\index\model\Story as MStory;

class Story extends Controller{
	
	//加载分类标题栏
	public function title(){
		$Mcolumn = new Column;
		$videores = $Mcolumn -> where('type','video') -> select();
		$this -> assign('videores',$videores);
		$storyres = $Mcolumn -> where('type','story') -> select();
		$this -> assign('storyres',$storyres);
		$imageres = $Mcolumn -> where('type','image') -> select();
		$this -> assign('imageres',$imageres);
	}

	//小说主页
	public function index(){

		//加载分类标题栏
		$this -> title();

		$Mstory = new MStory;
		//随机抽取22条推荐数据
		$numArr = array();//定义空数组，存放取出来的sid的值
		$numIndex = array();//记录已得到的下标
		//将所有的sid值存入到数组中去
		$sidnum = $Mstory -> column('sid');
		if(($resend = $Mstory -> count()) <= 21){//记录条数
			for($k = 0;$k < $resend;$k++){
				$stores[$k] = $Mstory -> where('sid',$sidnum[$k]) -> find();
			}
			$this -> assign('resend',$resend);
			$this -> assign('stores',$stores);
		} else {
			$index = 0;
			while($index < 21){
				$num = rand(0,$resend - 1);
				if(!in_array($num,$numIndex)){
					$numIndex[] = $num;
					$numArr[] = $sidnum[$num];
					$index++;
				}
			}
			//将读取的数据存入到二维数组$stores中
			for($j = 0;$j < 21;$j++){
				$stores[$j] = $Mstory -> where('sid',$numArr[$j]) -> find();
			}
			$this -> assign('resend',21);
			$this -> assign('stores',$stores);
		}
	
		return $this -> fetch();
	}

	//小说分类
	public function classfiy(){
		$this -> title();
		
		$Mstory = new MStory;
		$type = input('type');
		$this -> assign('type',$type);
		$newstory = $Mstory -> where('column',$type) -> paginate(22);
		//var_dump($storyres);
		$this -> assign('newstory',$newstory);

		return $this -> fetch();
	}

	//观赏页面
	public function play(){
		$this -> title();
		$sid = input('sid');

		$storyplay = model('Story') -> where('sid',$sid) -> find();
		$path = "./".$storyplay['path'];//要访问public/stati下的内容，__PUBLIC__是使用不了的，应该用./static就可以了
		$file = file($path);
		$storynum = count($file);
		//因为我保存的文件时直接用windows的记事本，所以编码并不是utf-8，所以这里要进行转换。如果，用的是utf-8保存的话，这里可能要修改一下。
		for($i = 0;$i < $storynum;$i++){
			$file[$i] = iconv("GBK","UTF-8",$file[$i]);
		}
		$this -> assign('storynum',$storynum);
		$this -> assign('file',$file);
		$this -> assign('storyplay',$storyplay);

		return $this -> fetch();
	}

	//water实验
	public function water(){
		//$Mstory = \think\Loader::model('Story');
		//$Mstory = model('Story');
		//$data = $Mstory::get(2);
		//echo $data -> title;

		//$column = model('Image');
		//$Mstory = model('Story');
		//$res = $Mstory -> save(['column' => '校园春色'],function($query){
								//$query -> where('sid',1);
						//});
		//$info = MStory::get(['sid' => 2]);
		//$info = MStory::getBySid(1);
		//echo $info -> column;
		//var_dump($info -> toArray());
		//echo $info -> toJson();
		$Mstory = new MStory;
		//$num = array();
		//$num = $Mstory -> where('sid',">",0) -> column('sid');
		//var_dump($num);
		$storyplay = model('Story') -> where('sid',1) -> find();
		return $this -> fetch();
	}
}
