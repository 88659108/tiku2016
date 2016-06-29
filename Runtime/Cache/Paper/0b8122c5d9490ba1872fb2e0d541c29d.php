<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>试卷列表页</title>
<link rel="stylesheet" type="text/css" href="/Public/css/public.css" />
<link rel="stylesheet" type="text/css" href="/Public/css/ui-dialog.css" />
<link rel="stylesheet" type="text/css" href="/Public/css/answer.css" />

<link href="http://cdn.bootcss.com/twitter-bootstrap/2.2.2/css/bootstrap.min.css" rel="stylesheet">
<link href="http://cdn.bootcss.com/twitter-bootstrap/2.2.2/css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="assets/css/google-bootstrap.css" rel="stylesheet">

<style type="text/css">
.code-input { height:25px; padding-left:5px; padding-bottom:2px; width:160px; font-size:14px; font-weight:bold; color:#396;}
.js-code-box p.text { padding:5px; 0px;}
.js-text-text { color:#F00; }

ul{ list-style:none; clear:both; padding:5px; overflow:hidden; height:30px;}
ul li{ float:left; padding:5px; display:block; }
ul li a{ display:block; color:#F00; }

table { clear:both; margin-top:30px; }
</style>
</head>

<body>


<ul>
<li><a href=" ?year=1999">1999</a></li>
<li><a href=" ?year=2000">2000</a></li>
<li><a href=" ?year=2001">2001</a></li>
<li><a href=" ?year=2002">2002</a></li>
<li><a href=" ?year=2003">2003</a></li>
<li><a href=" ?year=2004">2004</a></li>
<li><a href=" ?year=2005">2005</a></li>
<li><a href=" ?year=2006">2006</a></li>
<li><a href=" ?year=2007">2007</a></li>
<li><a href=" ?year=2008">2008</a></li>
<li><a href=" ?year=2009">2009</a></li>
<li><a href=" ?year=2010">2010</a></li>
<li><a href=" ?year=2011">2011</a></li>
<li><a href=" ?year=2012">2012</a></li>
<li><a href=" ?year=2013">2013</a></li>
<li><a href=" ?year=2014">2014</a></li>
<li><a href=" ?year=2015">2015</a></li>
</ul>


<ul>
<li><a href=" ?city=">全部</a></li>
<?php if(is_array($citylist)): $i = 0; $__LIST__ = $citylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pp): $mod = ($i % 2 );++$i;?><li><a href=" ?city=<?php echo ($pp["id"]); ?>"><?php echo ($pp["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>




<table width="630" class="table table-striped table-bordered table-hover" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td>编号</td>
    <td>标题</td>
    <td>题量</td>
    <td>年份</td>
    <td>省份</td>
    <td>操作</td>
  </tr>
  
  <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pp): $mod = ($i % 2 );++$i;?><tr>
    <td><?php echo ($i); ?></td>
    <td><?php echo ($pp["title"]); ?></td>
    <td><?php echo ($pp["qnums"]); ?></td>
    <td><?php echo ($pp["year"]); ?></td>
    <td><?php echo ($pp["city"]); ?></td>
    <td>
    <a href="javascript:void(0);" class="js-paperlist-apply" paperid="<?php echo ($pp["id"]); ?>">立即申请</a>
    <a href="/index.php/Paper/Index/special?paperid=<?php echo ($pp["id"]); ?>" target="_blank">下载试卷</a>
    </td>
    
  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>

<div class="public-box submit-box js-code-box" style="display:none; margin-top:120px;">
  	<p class="text" style="text-align:left; font-weight:bold;">手机号：<input placeholder="1508485...." type="text" class="code-input js-text-tel" onkeyup="value=value.replace(/[^\d]/g,'')" /></p>
  	<p class="text js-text-box-code" style="text-align:left; display:none;font-weight:bold;">邀请码：<input type="text" class="code-input js-text-code" placeholder="abdf3k" style="width:60px;" maxlength="8" /> <span class="js-text-text">*获取过可直接输入使用。 </span></p>
    
  	<div class="submit-btn-box">
    	<a href="#!" class="default-btn green-btn anser-btn js-btn-send-getcode">获得邀请码</a>
        <a href="http://www.zoobao.com/ask/?/account/login/" target="_blank" class="default-btn green-btn anser-btn js-btn-send-getcode">> 去注册帐号</a>
    	<a href="#!" class="default-btn anser-btn js-btn-sending" style="display:none;">57秒后可重发..</a>
    	<a href="#!" class="default-btn green-btn anser-btn js-btn-submit-close" style="display:none;">提交验证，开始做题..</a>
    	<a href="#!" class="default-btn anser-btn js-btn-submiting" style="display:none;">请稍等，正在验证中..</a>
	</div>
</div>

<div class="div-bg js-body-bg" style="display:none;"></div>


<script type="text/javascript" src="/Public/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/Public/js/dialog-min.js"></script>
<script type="text/javascript" src="/Public/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/Public/js/common.js"></script>


<script language="javascript">

var box		= $('.js-code-box');
var txt		= box.find('.js-text-text');
var txtbox	= box.find('.js-text-box-code');

var getcode	= box.find('.js-btn-send-getcode');
var sending	= box.find('.js-btn-sending');
var setcode	= box.find('.js-btn-submit-close');
var subing	= box.find('.js-btn-submiting');
	getcode.click(function(){
		
		var mobj	= box.find('.js-text-tel');
		var mobile	= mobj.val();
		var reg 	= /^0?1[3|4|5|8][0-9]\d{8}$/;
		if(!reg.test(mobile)) {
			var d = dialog({ fixed: true, content: '请填写正确的手机号码..' });
				d.show();
			
			setTimeout(function(){ d.close().remove(); }, 2000); 
			return;
		}
		
		var btn	= $(this);
			btn.hide();
			sending.show();
			
			//倒计时...
				
		ajaxSend('/Paper/Index/getcode/', {mobile:mobile}, function(json){
			
			if(json.status == 1 || json.status == -2){
				
				var str		= '* 您已获取过，请直接输入。';
				var str1 	= '* 请查看短信，输入邀请码。';
				var text	= (json.status == 1) ? str1 : str;
					txt.html(text);
				
					sending.hide();
					setcode.show();
					txtbox.fadeIn('slow');
					mobj.attr('disabled', 'disabled');
				
				return;	
			}
			
			btn.show();
			sending.hide();
		
			var d = dialog({ fixed: true, content: json.info });
				d.show();
			
			setTimeout(function(){ d.close().remove(); }, 2000);
			
		}, function(){
			
			btn.show();
			sending.hide();
			
		});
	});

	
	setcode.click(function(){
	
		var mobj	= box.find('.js-text-tel');
		var mobile	= mobj.val();
		var reg 	= /^0?1[3|4|5|8][0-9]\d{8}$/;
		if(!reg.test(mobile)) {
			var d = dialog({ fixed: true, content: '请填写正确的手机号码..' });
				d.show();
			
			setTimeout(function(){ d.close().remove(); }, 2000); 
			return;
		}
		
		var cobj	= box.find('.js-text-code');
		var code	= cobj.val();
		if(!code){
			
			var d = dialog({ fixed: true, content: '请输入邀请码..' });
				d.show();
			
			setTimeout(function(){ d.close().remove(); }, 2000); 
			return;
		}
		
		var btn	= $(this);
			btn.hide();
			subing.show();
				
		ajaxSend('/Paper/Index/setcode/', {mobile:mobile, code:code}, function(json){
			
			if(json.status > 0){
				
				subing.html('恭喜，成功验证。');
				
				$('.js-paperlist-apply').removeAttr('isajax');
				
				
				var d = dialog({ fixed: true, content: '欢迎您加入, 请继续申请试卷。' });
				d.show();
				setTimeout(function(){ d.close().remove(); }, 3000);
				
				
				ajaxSend('/ask/system/api.php?do=login_user', {session_id:json.session_id}, function(){
					
					location.href = '/index.php/Paper/';
						
				}, null, {ajaxurl:true});
				
				
				box.hide();
				$('.js-body-bg').hide();
				
				return;	
			}
			
			btn.show();
		
			var d = dialog({ fixed: true, content: json.info });
				d.show();
			
			setTimeout(function(){ d.close().remove(); }, 2000);
			
			btn.show();
			subing.hide();
			
		}, function(){
			
			btn.show();
			subing.hide();
		});
		
	});
	
	
	

var apply	= $('.js-paperlist-apply');
	apply.click(function(){
		
		var isajax	= $(this).attr('isajax');
		if(isajax) return;
		
		$(this).attr('isajax', 1).html('稍等，正在申请中..');
		
		var paperid	= $(this).attr('paperid');
		applyPaper(paperid, $(this));
	});
		
function applyPaper(paperid, obj){
	
	var gamepid	= 0;
	var gameuid	= 0;
	var gamemsg	= 'chm';
	
	var _cookie	= 'paper-apply-' + paperid;
	var _pid 	= $.cookie(_cookie);
	if(_pid){
		location.href = '/index.php/Paper/Answer/index?paperid='+_pid;
		return ;	
	}
	
	var data	= 'paperid=' + paperid + '&gameuid='+gameuid+'&gamepid='+gamepid+'&gamemsg='+gamemsg;
	ajaxSend('/Paper/Index/applyPaper', data, function(json){
		
		if(json.status > 0){
			obj.html('恭喜，成功申请，正在跳转..');	
			$.cookie(_cookie, json.paperid);
			location.href = '/index.php/Paper/Answer/index?paperid='+json.paperid;
			return;
		}
		
		var d = dialog({ fixed: true, content: json.info });
				d.show();
			
			setTimeout(function(){ d.close().remove(); }, 2000);
		
		obj.html('立即申请');	
		box.fadeIn('slow');
		$('.js-body-bg').show();
		
	},function(){
		box.fadeIn('slow');
		$('.js-body-bg').show();
	});	
}	
	
	
</script>



<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?497db2a22b54b711ea138cc0ed1d3c0a";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

</body>
</html>