<?php
namespace Api\Controller;
use Think\Controller;
class PaperController extends Controller {
    public function index(){}
	
	
	
	
    public function plist(){
		
		$paper		= D('Paper/Paper');
		$nums	    = I('get.nums', 5, 'int');
		
		
		$where		= 'status = 1 AND isupdate=1';
		if($year) $where .= ' AND year = ' . $year;
		if($city) $where .= ' AND cityid = '. $city;
		
		$count      = $paper->field('id AS paperid, title, applys, diff, qnums, topscore, avescore')->where($where)->count();
		
		$list		= $paper->field('id AS paperid, title, applys, diff, qnums, topscore, avescore')->where($where)->limit($nums)->select();
		
		
		$data       = get_ajax_array(array('title'=>'真题试卷', 'list'=>$list, 'count'=>$count), 'ok', 1);
			
		//成功
		$this->ajaxReturn($data);   
    }
	
	
	
	public function apply(){
		
		$_SESSION['userid'] 	= 1073;
		$_SESSION['username'] 	= 'pp1073';	
		
		/**
		 * 整理参数
		 * 母卷主键,用户主键,挑战卷主键
		 */
		
		//接收参数
		$paperid	= I('get.paperid', 0, 'int');
		$userid		= intval(get_sessions('userid'));
		
		$gameuid	= I('get.gameuid', 0, 'int');
		$gamepid	= I('get.gamepid', 0, 'int');
		$gamemsg	= I('get.gamemsg', '');
		
		
		if($userid == 0){
			$data   = get_ajax_array(null, '请先登录');
			$this->ajaxReturn($data);	
		}
		
		if($paperid == 0){	
			$data   = get_ajax_array(null, '没有指定试卷');
			$this->ajaxReturn($data);	
		}
		
		//母卷
		$paper		= D('Paper/Paper');
		$pinfo		= $paper->info($paperid, 'id, title, dataview, status, qnums, topscore, loglist, applys');
		if(!$pinfo || $pinfo['status'] == 0){
			$data   = get_ajax_array(null, '申请的试卷不存在');
			$this->ajaxReturn($data);		
		}
		
		$upaper		= D('Paper/UserPaper', 'Logic');
		$params		= array('gameinfo' => array('gameuid'=>$gameuid, 'gamepid'=>$gamepid, 'gamemsg'=>$gamemsg));
		
		//开启事务
		$paper->startTrans();
		
		//生成答卷
		$results	= $upaper->apply($pinfo['id'], $userid, $pinfo['dataview'], $pinfo['title'], $params);
		
		//给母卷记录日志[最近5条]
		$topnums	= 5;
		$loglist	= ($pinfo['loglist']) ? json_decode($pinfo['loglist'], true) : array();
		if(count($loglist) >= $topnums){ array_splice($loglist, 0, 1); }
		
		
		$loginfo	= array('userid'=>$userid, 'upaperid'=>$results['paperid'], 'time'=>time(), 'typeid'=>1);
		if($gameuid > 0 && $gamepid > 0){ $loginfo['gameinfo']	= $params['gameinfo']; }
		$loglist[]	= $loginfo;
			
			
		//更新母卷
		$pdata		= array('applys'=>$pinfo['applys'] + 1, 'loglist' => json_encode($loglist));
		$result1	= $paper->where('id=' . $pinfo['id'])->save($pdata);
		
		if($results['paperid'] > 0 && $result1){
			$paper->commit();
			
			$json	= array();
			$json['sourceid']	= $pinfo['id'];
			$json['paperid']	= $results['paperid'];
			$json['created']	= time();
			$json['title']		= $pinfo['title'];
			$json['qnums']		= $pinfo['qnums'];
			$json['dataview']	= $pinfo['dataview'];
			
			$qlist		= $this->qlists(0, $results['paperid'], $userid);
			
			$json   = get_ajax_array(array('upinfo'=>$json, 'qlist'=>$qlist), '恭喜，试卷申请成功..', 1);
			
			//成功
			$this->ajaxReturn($json);
		}
		
		$paper->rollback();
		
		
		$data   = get_ajax_array(null, '网络错误, 试卷申请失败');
		$this->ajaxReturn($data);
    }
	
	
	public function speed(){
        
    }
	
	public function classtype(){
        
    }
	
	public function error(){
        
    }
	
	public function collect(){
        
    }
	
	
	
	
	
	//格式化试题输出字段
	private function questionFields($qinfo, $question){
		
		//客观题字段[查询]
		$info	=  array(
			
			'qid'			=> $qinfo['sourceid'],//$qinfo['id'],
			'body'			=> $qinfo['body'],
			'options'		=> $qinfo['options'],
			'description'	=> $qinfo['description'],
			'answer'		=> $qinfo['answer'],
			'wrongans'		=> $qinfo['wrongans'],
			'optiontype'	=> $qinfo['optiontype'],
			'views'			=> $qinfo['views'],
			'errnums'		=> $qinfo['errnums'],
			'collects'		=> $qinfo['collects'],
			'values'		=> $qinfo['values'],
			'loglist'		=> $qinfo['loglist'],
			'pointname'		=> $qinfo['pointname']
		);
		
		$question['info']	= $info;
		
		//返回结果
		return $question;
	}
	
	public function qlist(){
		
		$_SESSION['userid'] 	= 1073;
		$_SESSION['username'] 	= 'pp1073';	
		
		
		$paperid	= I('get.paperid', 0, 'int');
		$model		= I('model', 0, 'int');
		$userid		= intval(get_sessions('userid'));
		
		//母卷
		$paper		= D('Paper/Paper');
		$pinfo		= $paper->info($paperid, 'id, dataview, status');
		if(!$pinfo || $pinfo['status'] == 0){
			$data   = get_ajax_array(null, '申请的试卷不存在');
			$this->ajaxReturn($data);		
		}
		
		
		$qlist		= $this->qlists($model, $paperid, $userid);
		
		
		$data   = get_ajax_array(array('qlist'=>$qlist), 1, 'ok');
		$this->ajaxReturn($data);
		
	
	}
	
	
	
	public function qlists($model, $paperid, $userid){
		
		
		$upaper			= D('Paper/UserPaper')->table($userid);
		$chart_field	= ',ranking,addtime,subtime,score,analysis';
		$click_field	= ',marks,collects,notes,bugs';
		
		$upinfo			= $upaper->info($paperid, $userid, 'id,title,paperid,userid,status,totals,dataview,qnums,speed,totaltime,usetime'.$chart_field.$click_field);
		
		
		//试卷数据格式化
		$dataview		= json_decode($upinfo['dataview'], true);
		if(!$dataview){
			$this->error('答卷存在异常,请重新申请..', '/index.php/Paper/Index/', 3);		
		}
		
		
		//$data			= array();
		
		//查找当前nav
		/*$data['nav']	= array();
		foreach($dataview['navs'] as $key=>$val){
			if($val['key'] == $model){
				$data['nav']	= $val;
				break;
			}
		}*/
		
		$question		= D('Teaching/Question');
		$list			= $dataview['item'][$model];
		foreach($list as $key=>$val){
			
			//客观题
			if($val['son'] == 0){
				$qinfo	= $question->selectQuestion($val['qid']);
				
				//字段过滤[格式化]
				$val		= $this->questionFields($qinfo, $val);
			
				
			//资料题	
			}else{
				
				//{"qid":"1603", "son":"5", "sonlist":{"0":{"qid":"28693", "answer":"C", "uanswer":"D"}, "3":{"qid":"28696", "answer":"B", "uanswer":"B"}}
				$qinfo				= $question->selectMaterial($val['qid']);
				$val['material']	= $qinfo['material'];
				
				//处理子题列表
				$sonlist			= $qinfo['sonlist'];
				$sonlist1			= $val['sonlist'];
				foreach($sonlist as $k=>$v){
					
					//字段过滤[格式化]
					$v				= $this->questionFields($v, $sonlist1[$k]);
					$sonlist[$k]	= $v;
				}
				$val['sonlist']		= $sonlist;
			}
			
			$list[$key]				= $val;
		}
		
		
		//$data['dataview']			= $list;
		
		return $list;
    }
	
	public function handover(){
        
    }	
}