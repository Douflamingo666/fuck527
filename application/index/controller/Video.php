<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Column;
use app\index\model\Video as MVideo;
use app\index\model\Comment;

class Video extends Controller{

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

	//随机升序或降序，用于评论的排行
	public function comment_order(){
		$num = rand(1,2);
		if($num == 1){
			return "desc";
		} else {
			return "asc";
		}
	}
	
	//视频主页面
	public function index(){
		
		//加载分类标题栏
		$this -> title();

		//加载推荐视频
		$Mvideo = new MVideo;
		$recom1 = $Mvideo -> where('column','网红明星') -> limit(6) -> select();
		$this -> assign('recom1',$recom1);
		$recom2 = $Mvideo -> where("column","欧美影视") -> limit(6) -> select();
		$this -> assign('recom2',$recom2);
		$recom3 = $Mvideo -> where("column","卡通动漫") -> limit(6) -> select();
		$this -> assign('recom3',$recom3);

		//加载排行榜
		return $this -> fetch();
	}
	
	//视频分类
	public function classfiy(){

		//加载分类标题栏
		$this -> title();

		//读取video中的相应分类数据
		$Mvideo = new MVideo;
		$type = input('type');
		$this -> assign('type',$type);
		$newvideo = $Mvideo -> where('column',$type) -> paginate(24);//这里的排序要再做处理
		$this -> assign('newvideo',$newvideo);

		return $this -> fetch();
	}

	//播放页面
	public function play(){

		//加载分类标题栏
		$this -> title();

		//视频播放
		$vid = input('id');
		$this -> assign('vid',$vid);
		$Mvideo = new MVideo;
		$videoplay = $Mvideo -> where('vid',$vid) -> find();
		$this -> assign('videoplay',$videoplay);

		//导入评论
		$Mcomment = new Comment;
		$order = $this -> comment_order();
		$comres = $Mcomment -> where('vid',$vid) -> order("com_order $order") -> select();
		$this -> assign('comres',$comres);

		return $this -> fetch();
	}

	//录入评论
	public function write_comment(){

		$Mcomment = new Comment;
		$Mcomment -> data([
			'vid' => input('vid'),
			'content' => input('textarea'),
			'com_order' => rand(1,1000),
		]);
		if($Mcomment -> save()){
			return $this -> success('发表成功！');
		} else {
			return $this -> error("发表失败！");
		}
	}

}
