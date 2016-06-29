<?php
namespace Api\Controller;
use Think\Controller;
class AppController extends Controller {
    public function index(){
        
		
    }
	
	
	
	
	
	public function loading(){
		
		$ico	= array();
		$ico[]	= array('url'=>'');	
		$ico[]	= array('url'=>'');	
		$ico[]	= array('url'=>'');	
		
		
		$tips	= array();
		$tips[] = array('id'=>1, 'title'=>'title', 'content'=>'content', 'created'=>time(), 'read'=>0);
		$tips[] = array('id'=>2, 'title'=>'title', 'content'=>'content', 'created'=>time(), 'read'=>0);
		
		
		$data   = get_ajax_array(array('ico'=>$ico, 'tips'=>$tips), 'ok', 1);
		
		
		$this->ajaxReturn($data);		
		
	}
}