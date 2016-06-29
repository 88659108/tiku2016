<?php
namespace Api\Controller;
use Think\Controller;
class UserController extends Controller {
    public function index(){
       
    }
	
	
	
	/**
	 * 用户登录
	 *
	 */
	public function login(){
		
		
		$username	= I('username', '');
		$password	= I('password', '');
		
		
		if(!$username){
			$data   = get_ajax_array(null, '用户名为空');
			$this->ajaxReturn($data);	
		}
		
		if(!$password){
			$data   = get_ajax_array(null, '密码为空');
			$this->ajaxReturn($data);	
		}
		
		
		$token  = md5(time() . time());
		$data   = get_ajax_array(array('token'=>$token), 'ok', 1);
		
		$this->ajaxReturn($data);		
    }
	
	
	/**
	 * 用户注册
	 *
	 */
	public function register(){
		
		$username	= I('username', '');
		$password	= I('password', '');
		
		
		if(!$username){
			$data   = get_ajax_array(null, '用户名为空');
			$this->ajaxReturn($data);	
		}
		
		if(!$password){
			$data   = get_ajax_array(null, '密码为空');
			$this->ajaxReturn($data);	
		}
		
		
		
		
		
		$token  = md5(time() . time());
		$data   = get_ajax_array(array('token'=>$token), 'ok', 1);
		
		
		$this->ajaxReturn($data);		
    }
	
	
	
}