<?php


function get_ajax_array($data, $msg = '', $status = 0){
	
	$info			= array();
	$info['status'] = $status;
	$info['access'] = time();
	$info['info']   = $msg;
	$info['data'] 	= $data;
	
	return $info;
}