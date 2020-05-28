<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta http-equiv="X-UA-Compatible" content="IE=10" />
<link rel="stylesheet" type="text/css" href="/hddevice/mz/Public/zizhu/css/base.css" />
<link rel="stylesheet" type="text/css" href="/hddevice/mz/Public/zizhu/css/index.css" />
<script language="javascript" src="/hddevice/mz/Public/zizhu/js/jquery-1.9.1.js" ></script> 
<script language="javascript" src="/hddevice/mz/Public/zizhu/js/ServerClock.js" ></script>  
<script language="javascript" type="text/javascript" src="/hddevice/mz/Public/layer/layer.js"></script>
<script language="javascript" type="text/javascript" src="/hddevice/mz/Public/zizhu/js/my.js"></script>
<SCRIPT language=javascript> 
//+---------------------------------------------------  
//| 日期输出字符串，重载了系统的toString方法  
//+---------------------------------------------------  
Date.prototype.toString = function(showWeek)  
{   
    var myDate= this;  
    var str = myDate.toLocaleDateString();  
    if (showWeek)  
    {   
        var Week = ['日','一','二','三','四','五','六'];  
        str += ' 星期' + Week[myDate.getDay()];  
    }  
    return str;  
}  
<!-- 
//window.onerror=function(){return true;} 
// --> 
</SCRIPT> 
<style>
#dialog{
	position:fixed;
	border: solid;
	background:#abd3e8;
	width: 270px;
	height: 330px;
}
#dialog table{
    margin:10px auto;
    background: #eee;
    width:240px;
    height:280px;
}
#dialog table tr td input{
    width: 70px;
    height: 40px;
    cursor: pointer;
}
#dialog .pwd_yz_tk{
	width: 220px;
    height: 40px;
}
.send-msg{
    background-color: #F0F1F1;
    display: block;
    width:50%;
    height: 50%;
    z-index: 99999;
    position :fixed;
    top:25%;
    left:25%;
    border:solid 1px #12B5E5;
}
.send-msg caption{
    padding-top:10px;
    padding-bottom: 10px;
    background:#90C9DA;
    font-size: 16px;
    color: #fff;
}
.send-msg tr:nth-child(1){
    margin-top: 100px;
    margin-left: 40px;
}
.send-msg button{
    padding-top:20px;
    padding-bottom: 10px;
    background:#90C9DA;
    font-size: 16px;
    margin:25px;
    margin-top: 40px;
    padding: 30px;
    border:solid 1px #12B5E5;
}
.send-msg button:first-child{
    margin-left: 220px;
}
#cover {
    position: fixed;
    z-index:9999;
    top:0px;
    left: 0px;
    display: none;
    width:100%;
    height: 100%;
    opacity: 0;
    background: #000 none repeat scroll 0% 0%;
}

</style>
<script language="javascript">
 window.onload = function () {
        stime();
        window.external.send(1,4);
    }
    var c = 0;
	<?php
 $weekary = array("日","一","二","三","四","五","六"); $W = $weekary[date("w")]; ?>
    var Y =<?php echo date('Y')?>, M =<?php echo date('n')?>, D =<?php echo date('j')?>;
    function stime() {
        c++
        sec = <?php echo time() - strtotime(date("Y-m-d"))?>+c;
        H = Math.floor(sec / 3600) % 24
        I = Math.floor(sec / 60) % 60
        S = sec % 60
        if (S < 10) S = '0' + S;
        if (I < 10) I = '0' + I;
        if (H < 10) H = '0' + H;
        if (H == '00' & I == '00' & S == '00') D = D + 1; //日进位
        if (M == 2) { //判断是否为二月份******
            if (Y % 4 == 0 && !Y % 100 == 0 || Y % 400 == 0) { //是闰年(二月有28天)
                if (D == 30) {
                    M += 1;
                    D = 1;
                } //月份进位
            }
            else { //非闰年(二月有29天)
                if (D == 29) {
                    M += 1;
                    D = 1;
                } //月份进位
            }
        }
        else { //不是二月份的月份******
            if (M == 4 || M == 6 || M == 9 || M == 11) { //小月(30天)
                if (D == 31) {
                    M += 1;
                    D = 1;
                } //月份进位
            }
            else { //大月(31天)
                if (D == 32) {
                    M += 1;
                    D = 1;
                } //月份进位
            }
        }
       $.ajax({
	 		url:"/hddevice/mz/index.php/ZiZhu/Index/getdangqian_timme", 
	 		type:'post',
	 		dataType:'json',
	 		success:function(data){
	 			$("#show_times").html(data['rq']+"<span style='margin-right:34px;'>"+data['xq']+"</span>"+data['sj'])
	 		}
	 	})
		/********************改为一秒执行一次***********************/
        setTimeout("stime()", 30000); 
    }
</script> 
<script  language="javascript">


function Refresh(){  
     /* var year = new Date().getFullYear();
		var month = new Date().getMonth()+1;
		if(month<10){
			month = "0"+month;
		}
		var day = new Date().getDate();
		var hour = new Date().getHours();
		var minutes = new Date().getMinutes();
		if(minutes<10){
			minutes = "0"+minutes;
		}
		var seconds = new Date().getSeconds();
		if(seconds<10){
			seconds = "0"+seconds;
		}
		var noon = "";
		if(hour < 6){noon="凌晨"}
		else if(hour<9){noon="早上"}
		else if(hour<12){noon="上午"}
		else if(hour<14){noon="中午"}
		else if(hour<17){noon="下午"}
		else if(hour<19){noon="傍晚"}
	
        $("#times").html(year+"年"+month+"月"+day+"号 "+hour+":"+minutes+":"+seconds+' 星期'+'日一二三四五六'.charAt(new Date().getDay()));
	  */
	 
	   //setTimeout("Refresh()",1000);  
		// $("#times").html(srvClock);
    }  
var timer=setTimeout("Refresh()",1000); 
function setCalls(pat_code,noon_flag){ 
	var params = {"pat_code":pat_code};
	$.ajax({
		url:"/hddevice/mz/index.php/ZiZhu/Index/setCalls", 
		type:'post',
		dataType:'json',
		data:params,
		success:function(data){
			
		}
	})
}

</script> 

<script language="javascript">
var data,datarow;
var interval = "";
var interval2 = new Array();
var interval3 = new Array();
var countdown = 60;
var jzk_flag = 0;
var page = 1;
var pagedata = null;
var ocx;
var cardNum;
var now_time = null;
var moring_time = null;
var moon_time = null;
var type_times=1;
function settime(val) {

	if (countdown == 1) { 
		$("#tuichu").trigger("click");
		countdown = 0; 
		clearTimeout(interval2);
		return;
	} else { 
		countdown--; 
		$("#downcount").show().text(countdown);
	} 
	
	var in2 = setTimeout(function() { 
		settime(val) 
	},1000);
	interval2.push(in2);
	
} 
/***
***倒计时函数
********/
function daojishi(etime){
	for(var key in interval2){
		clearTimeout(interval2[key]);
	}
	countdown = etime;
	settime(etime);
	
	
}
function backspace(){ //退格
	var vals = $("#jz_card_no").val();
	var vals2 = vals.substring(0,vals.length-1)
	key_value2 = vals2;
	$("#jz_card_no").val(key_value2);
}//取长度为原长度减一的串，实现退格效
/*******金属密码键盘明文调用开始***************/
var key_value2 = "";
function secr_c(str){
	switch(str){
	case "ENTER":
	$("#confirm").trigger("click");
	break;
	case "CLEAR":
	backspace()
	break;
	case "1":
	case "2":
	case "3":
	case "4":
	case "5":
	case "6":
	case "7":
	case "8":
	case "9":
	case "0":
	case "00":
	key_value2+=str;
	break;
	}
	
	$("#jz_card_no").val(key_value2); 
}
function init(){
	jzk_flag = window.external.InitIC();
	//window.external.nTextInput(); 
}
//获取打印纸的状态
function print_status(zzj_id){
	
	var params = {"zzj_id":zzj_id};
	$.ajax({
			url:"/hddevice/mz/index.php/ZiZhu/Index/print_status", 
			type:'post',
			dataType:'json',
			data:params,
			success:function(data){
				//alert(data);
				if(data['Paper_End']=="有纸" && data['Paper_Jam']=="打印机没卡纸"){
				   $(".mtnum2").hide();
                   $(".main_left_1").show();
				}else if(data['Paper_End']=="缺纸"){
                   $(".main_left_1").hide();
	               $(".main_right_1").hide();
	               $(".main_left_2").show();
                   $(".mtnum2").hide();                  
                   $(".print_notice").show();                   
                   print_status(zzj_id);
				}else if(data['Paper_Jam']=="打印机卡纸"){
 				   $(".main_left_1").hide();
	               $(".main_right_1").hide();
	               $(".main_left_2").show();
                   $(".mtnum2").hide();                  
                   $(".print_notice1").show();                   
                   print_status(zzj_id);
				}
			}
	})
}
/*******金属密码键盘明文调用结束***********************/
$(function(){
	/*******模拟开关机状态开始*******************************************************************************/

	setInterval(function(){
		var params = {"zzj_id":$("#zzj_id").val()};
		 $.ajax({
        	url:"/hddevice/mz/index.php/ZiZhu/Index/setKGJ", 
			type:'post',
			dataType:'json',
			data:params,
			success:function(data){

			}
        })
	},10000);
/*******模拟开关机状态结束*******************************************************************************/
	//alert(77);
/**向后退卡部分**开始**************************************/
	$('#dialog').hide();//隐藏
    $('#dialog').offset({
		left:Math.ceil(($(document).width()-$('#dialog').width())/2),
		top:$(window).scrollTop() + 100
	});
	$('.block_top2').click(function(){
		$('#dialog').show();//显示密码键盘
		
	})
	//向后退卡的键盘输入
	var tk_yz_val = "";//定义密码框中的变量
	//输入密码
	$(".tk_yz").on("mousedown",function(){
		tk_yz_val +=$(this).val();
		$('.pwd_yz_tk').val(tk_yz_val);
	})
	//点击退出时,隐藏;并清空变量
	$('.close').click(function(){
		$('#tips').remove();
		$('#dialog').hide();
		tk_yz_val = "";
		$('.pwd_yz_tk').val('');
	})
	//点击清除时,清空变量
	$('.tk_yz_qc').click(function(){		
		tk_yz_val = "";
		$('.pwd_yz_tk').val('');
	})
	//校验退卡密码
	$(".tk_yz_click").click(function(){
		// alert($('.pwd_yz_tk').val());
		var params = {"pwd_yz_tk":$('.pwd_yz_tk').val()};
		$.ajax({
	 	    url:"/hddevice/mz/index.php/ZiZhu/Index/pwd_yz_tk", 
	 	    type:'post',
	 	    dataType:'json',
		    data:params,
            success:function(data)
			{
				if(data == 1){					
					//密码正确
					// alert(333);
					//关闭密码键盘,清除记录
					$('#tips').remove();
					$('#dialog').hide();
					tk_yz_val = "";
					$('.pwd_yz_tk').val('');
					//退卡
					window.external.FreeYBIntfDll();
					window.external.MoveBackCard();
					window.external.DisAllowCardIn();
					//window.external.KeyBoardComClose();
					//window.external.keybord_close();
					window.external.Out_UMS_CardClose();


					window.external.MoveBackCard2();
				}else{
					$(".tk_yz_text").html("密码错误");//密码错误
					$('.pwd_yz_tk').val('');
					tk_yz_val = "";
				}
			}
	    });
	})

/**向后退卡部分**结束**************************************/
print_status($("#zzj_id").val());
/*******数字键盘区开始*******************************************************************************/
var key_value = "";
$(".j_keybord_area .key li.num").on("mousedown",function(){
	//$(".j_keybord_area .key li").removeClass("active");
	
	$(this).addClass("active");
	key_value+=$(this).attr("param");
	$("#jz_card_no").val(key_value);

})
$(".j_keybord_area .key li.del").on("click",function(){
	var jz_card_no=$("#jz_card_no").val();
     var a="";
     if(jz_card_no.length>0){
     var a=jz_card_no.substr(0,jz_card_no.length-1);
    }
	$("#jz_card_no").val(a);	
	key_value=a;
	key_value2="";
})
$(".j_keybord_area .key li").on("mouseup",function(){
	$(".j_keybord_area .key li").removeClass("active");
	//$(this).addClass("active");
})
/*******数字键盘区结束********************************************************************************/
/*******挂号数字键盘开始********************************************************************************/
$(".j_keybord_area_guahao .key li.num").on("mousedown",function(){
	//$(".j_keybord_area .key li").removeClass("active");
	
	$(this).addClass("active");
	key_value+=$(this).attr("param");
	$("#jz_card_no_guahao").val(key_value);

})
$(".j_keybord_area_guahao .key li.del").on("click",function(){
	var jz_card_no_guahao=$("#jz_card_no_guahao").val();
     var a="";
     if(jz_card_no_guahao.length>0){
         var a=jz_card_no_guahao.substr(0,jz_card_no_guahao.length-1);
    }
	$("#jz_card_no_guahao").val(a);	
	key_value=a;
	key_value2="";
})
$(".j_keybord_area_guahao .key li").on("mouseup",function(){
	$(".j_keybord_area_guahao .key li").removeClass("active");
	//$(this).addClass("active");
})
/*******身份证数字键盘开始********************************************************************************/
$(".j_keybord_area_sfz .key li.num").on("mousedown",function(){
	//$(".j_keybord_area .key li").removeClass("active");
	
	$(this).addClass("active");
	key_value+=$(this).attr("param");
	$("#jz_card_no_sfz").val(key_value);

})
$(".j_keybord_area_sfz .key li.del").on("click",function(){
	var jz_card_no_sfz=$("#jz_card_no_sfz").val();
     var a="";
     if(jz_card_no_sfz.length>0){
     var a=jz_card_no_sfz.substr(0,jz_card_no_sfz.length-1);
    }
	$("#jz_card_no_sfz").val(a);	
	key_value=a;
	key_value2="";
})
$(".j_keybord_area_sfz .key li").on("mouseup",function(){
	$(".j_keybord_area_sfz .key li").removeClass("active");
	//$(this).addClass("active");
})
/*******挂号身份证数字键盘开始********************************************************************************/
$(".j_keybord_area_guahao_sfz .key li.num").on("mousedown",function(){
	//$(".j_keybord_area .key li").removeClass("active");
	
	$(this).addClass("active");
	key_value+=$(this).attr("param");
	$("#jz_card_no_guahao_sfz").val(key_value);

})

$(".j_keybord_area_guahao_sfz .key li.del").on("click",function(){
	var jz_card_no_guahao_sfz=$("#jz_card_no_guahao_sfz").val();
     var a="";
     if(jz_card_no_guahao_sfz.length>0){
         var a=jz_card_no_guahao_sfz.substr(0,jz_card_no_guahao_sfz.length-1);
    }
	$("#jz_card_no_guahao_sfz").val(a);	
	key_value=a;
	key_value2="";
})
$(".j_keybord_area_guahao_sfz .key li").on("mouseup",function(){
	$(".j_keybord_area_guahao_sfz .key li").removeClass("active");
	//$(this).addClass("active");
})

$(".block_top").click(function(){
	//alert(44);
	//弹出卡
	window.external.MoveOutCard();
	//禁止进卡
	window.external.DisAllowCardIn();
})
/**************************处方列表JS开始*********************************************************/
$(document).on("click",".chufang_list ul li.five",function(){
	if($(this).next(".detail").is(':hidden')){
		$(".chufang_list ul li.five").removeClass("z2");
		$(this).addClass("z2");
		$(".detail").hide();
		$(this).next(".detail").show();
	}else{
		$(this).removeClass("z2");
		$(this).next(".detail").hide();
	}
	
})
$(document).on("click",".chufang_list ul li.one",function(){
	//首先获取本列的checkbox是否已经被选中
	if($(this).prev(".chk").find("input[type='checkbox']").is(':checked')){
		$(this).prev(".chk").find("input[type='checkbox']").prop("checked",false);
		$(this).addClass("nocheck");
	}else{
		$(this).prev(".chk").find("input[type='checkbox']").prop("checked",true);
		$(this).removeClass("nocheck");
	}
})
/**************************科室选项卡切换*********************************************************/
$(document).on("click","#attr_list li",function()
{	
	$("#attr_list li").eq($(this).index()).addClass("current").siblings().removeClass("current");
	$("#sub_list div").hide().eq($(this).index()).show();
												
												})
/**************************科室点击事件*********************************************************/
$(document).on("click",".chose_unit",function(){
	 var params = {"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"patient_id":$("#pat_code").val()};
	 $.ajax({
	 	url:"/hddevice/mz/index.php/ZiZhu/Index/gh_unlock", 
	 	type:'post',
	 	dataType:'json',
		data:params,
        success:function(data)
			{
				
			 }
	 });
	//首先获取到被选中科室的科室编号
	 var unit_sn=$(this).attr("roomId");
	 $("#unit_sn").val(unit_sn);
	  if(unit_sn=="0203010"){	 	
	 	if($("#pat_age").val()<16){
	 		alert("对不起，年龄小于16岁不能挂骨科！");
	 		return;
	 	}
	 }
	  if(unit_sn=="0301010" || unit_sn=="0302010"){
	 	if($("#pat_sex").val()=="男"){
	 		alert("对不起，男同志不能挂女人号！");
	 		return;
	 	}
	 }
	 if(unit_sn=="0401000" || unit_sn=="0402000"){
	 	if($("#pat_age").val()>14){
	 		alert("对不起，年龄超过14对不能挂儿科号！");
	 		return;
	 	}
	 }
	 if(unit_sn == "1300000" || unit_sn == "0402000" || unit_sn == "0401002"){
		if($("#card_code").val() == 20){
	 		/*alert("对不起，本科室只允许自费挂号！请使用就诊卡或身份证进行挂号缴费！");*/
	 		sendMsg();
	 		return;
	 	}

	}
	 var params = {"unit_sn":unit_sn,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"patient_id":$("#pat_code").val(),"card_no":$("#card_no").val(),};
	 var index_load = "";
		$.ajax({
			url:"/hddevice/mz/index.php/ZiZhu/Index/getSchedInfo", 
			type:'post',
			dataType:'json',
			data:params,
			beforeSend:function(){
				index_load = layer.msg('医生排班信息数据查询中,请稍候...', {icon: 16,time:20000});
				$("#fanhui").css({"visibility":"hidden"});
				$("#confirm").css({"visibility":"hidden"});
				$("#tuichu").css({"visibility":"hidden"});
			},
			success:function(data)
			{
				layer.close(index_load);
				$(".mtnum2").hide();
				$(".chose_doctor").show();
				$("#op_now").val("chose_doctor");
				$("#fanhui").css({"visibility":"visible"});
				$("#tuichu").css({"visibility":"visible"});
				 var html="";
				 var sub_list= data["datarow"];
				 //var page=1;
					   if(data['result']['execute_flag']==0)
					   {
						   $("#keshi_name").text($("#pat_name").val());
						   pagedata = data.datarow;
						   now_time = data.now_time;
						   moring_time = data.moring_time;
						   moon_time = data.moon_time;
						   //alert(now_time);
						   //alert(moring_time);
						   //alert(moon_time);
						   showdata(page=1);
					   }
			 }
			 })
})
//分页展示方法
function showdata(page) {
                    var html = '';
                    var pagesize = 4;
					var totalpage = Math.ceil(pagedata.length/pagesize);
                    var end = (page * pagesize) - 1;
                    var start = end - pagesize + 1;
					if(page==totalpage){
						$(".next").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");
					}else{
						$(".next").css("background","url(/hddevice/mz/Public/zizhu/img/ft.png)");
						$(".next").css("color","#FFF");
					}
					if(page==1){
						$(".prev").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");
					}else{
						$(".prev").css("background","url(/hddevice/mz/Public/zizhu/img/ft.png)");
						$(".prev").css("color","#FFF");
						}
					if(pagedata.length<=4){
						$(".prev").hide();
						$(".next").hide();
						}else{
							$(".prev").show();
							$(".next").show();
							}
                    $.each(pagedata, function (key, val) {
                        if (key >= start && key <= end) {
                        	//var now_time = new Date();
							if(val.ampm == 'p'){
							   val.ampm = '下午';
							   if(now_time>moon_time){
							   	val['record_left']=0
							   }
							}
							if(val.ampm == 'a'){
							   val.ampm = '上午';
							   if(now_time>moring_time)
							   {
							      val['record_left']=0
							   }
							}
							if(val.ampm == "%"){
                                val.ampm = "全天";
							}
							if(val['record_left']==0 ){
								//alert(val['unit_sn']);
								        if(val["doctor_name"]!=""){
											html+="<div><h4>姓名:"+val["doctor_name"]+"</h4><ul><li>科室:"+val["unit_name"]+"</li><li style='color:red'>号别:"+val['clinic_name']+"</li><li>医事服务费:"+val['sum_fee']+"</li><li style='color:red'>午别:"+val["ampm"]+"</li><li>剩余号:"+val["record_left"]+"</li></ul><span class='disabled' record_sn="+val["record_sn"]+" req_type="+val["req_type"]+" unit_name="+val["unit_name"]+" doctor_name="+val["doctor_name"]+" >已挂完</span></div>";
											}else{
												html+="<div><h4>&nbsp;&nbsp;&nbsp;"+val["doctor_name"]+"</h4><ul><li>科室:"+val["unit_name"]+"</li><li style='color:red'>号别:"+val['clinic_name']+"</li><li>医事服务费:"+val['sum_fee']+"</li><li style='color:red'>午别:"+val["ampm"]+"</li><li>剩余号:"+val["record_left"]+"</li></ul><span class='disabled' record_sn="+val["record_sn"]+" req_type="+val["req_type"]+" unit_name="+val["unit_name"]+" doctor_name="+val["doctor_name"]+" >已挂完</span></div>";
											}
										}
											else{
                                              if(val["doctor_name"]!=""){
                                              	html+="<div><h4>姓名:"+val["doctor_name"]+"</h4><ul><li>科室:"+val["unit_name"]+"</li><li style='color:red'>号别:"+val['clinic_name']+"</li><li>医事服务费:"+val['sum_fee']+"</li><li style='color:red'>午别:"+val["ampm"]+"</li><li>剩余号:"+val["record_left"]+"</li></ul><span class='sure_ghao' record_sn="+val["record_sn"]+" req_type="+val["req_type"]+" unit_name="+val["unit_name"]+" doctor_name="+val["doctor_name"]+" ><a class='ysh'  doctor_sn="+val['doctor_sn']+" ></a>挂号</span></div>";
										    }else{
										    html+="<div><h4>&nbsp;&nbsp;&nbsp;"+val["doctor_name"]+"</h4><ul><li>科室:"+val["unit_name"]+"</li><li style='color:red'>号别:"+val['clinic_name']+"</li><li>医事服务费:"+val['sum_fee']+"</li><li style='color:red'>午别:"+val["ampm"]+"</li><li>剩余号:"+val["record_left"]+"</li></ul><span class='sure_ghao' record_sn="+val["record_sn"]+" req_type="+val["req_type"]+" unit_name="+val["unit_name"]+" doctor_name="+val["doctor_name"]+" ><a class='ysh'  doctor_sn="+val['doctor_sn']+" ></a>挂号</span></div>";	
										    }
										   
											}
							
                        }
                    });
                    if (html) {
                        $(".doctor_list").html(html);
						$(".total_page").html("共"+totalpage+"页/第"+page+"页")
                    } else {
                        return false;
                    }
                }
           //下一页
                $(".next").click(function () {
                    page++;
                    if(showdata(page)===false){
                        page--;
						$(".next").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");
                    }
                });

                //上一页
                $(".prev").click(function () {
                    if (page == 1) {
                        return false;
                    }
                    page--;
                    showdata(page);
                });
				
				
/**************************科室点击事件结束*********************************************************/

$("#yuyue_guohao").click(function(){
	var zzj_id = $("#zzj_id").val();
		var zzj_id = $("#zzj_id").val();
		//急诊的，不让挂号
	/*if($("#zzj_id").val()=="ZZ016"||$("#zzj_id").val()=="ZZ025"){
		 $(".main_left_1").hide();
	     $(".main_right_1").hide();
	     $(".main_left_2").show();
         $(".mtnum2").hide();
         $(".hint").show();
         $(".btn_area").show();
         $("#confirm").css({"visibility":"hidden"});
		 $("#fanhui").css({"visibility":"hidden"});
         $("#tuichu").css({"visibility":"visible"});
         daojishi(6);
		 return;
	}*/
	var abc = new Date();
	if(abc.Format("yyyy-MM-dd")>="2019-10-01" && abc.Format("yyyy-MM-dd")<="2019-10-07" || abc.Format("yyyy-MM-dd") =="2018-12-31" || abc.Format("yyyy-MM-dd") =="2019-01-01"){
		if(abc.Format("hh:mm")>="11:50"){
			$(".main_left_1").hide();
			$(".main_right_1").hide();
			$(".main_left_2").show();
			$(".mtnum2").hide();
			$(".show").show();
			$(".btn_area").show();
			$("#confirm").css({"visibility":"hidden"});
			$("#fanhui").css({"visibility":"hidden"});
			$("#tuichu").css({"visibility":"visible"});
			daojishi(6);
			return;
		    }
	}
   // var abc = new Date();

	//美容科
	     
		if(abc.Format("hh:mm")=="17:20"||abc.Format("hh:mm")>"17:20"){
			$(".main_left_1").hide();
			$(".main_right_1").hide();
			$(".main_left_2").show();
			$(".mtnum2").hide();
			$(".show").show();
			$(".btn_area").show();
			$("#confirm").css({"visibility":"hidden"});
			$("#fanhui").css({"visibility":"hidden"});
			$("#tuichu").css({"visibility":"visible"});
			daojishi(6);
			return;
	    }		
	
	window.location.href="/hddevice/mz/index.php/ZiZhu/YuYue/index/zzj_id/"+zzj_id;
})

$("#buhao").click(function(){
	var zzj_id = $("#zzj_id").val();
		var zzj_id = $("#zzj_id").val();
		//急诊的，不让挂号
	/*if($("#zzj_id").val()=="ZZ016"||$("#zzj_id").val()=="ZZ025"){
		 $(".main_left_1").hide();
	     $(".main_right_1").hide();
	     $(".main_left_2").show();
         $(".mtnum2").hide();
         $(".hint").show();
         $(".btn_area").show();
         $("#confirm").css({"visibility":"hidden"});
		 $("#fanhui").css({"visibility":"hidden"});
         $("#tuichu").css({"visibility":"visible"});
         daojishi(6);
		 return;
	}*/
	var abc = new Date();
	if(abc.Format("yyyy-MM-dd")>="2019-10-01" && abc.Format("yyyy-MM-dd")<="2019-10-07"){
		if(abc.Format("hh:mm")>="11:50"){
			$(".main_left_1").hide();
			$(".main_right_1").hide();
			$(".main_left_2").show();
			$(".mtnum2").hide();
			$(".show").show();
			$(".btn_area").show();
			$("#confirm").css({"visibility":"hidden"});
			$("#fanhui").css({"visibility":"hidden"});
			$("#tuichu").css({"visibility":"visible"});
			daojishi(6);
			return;
		    }
	}
    var abc = new Date();


	//美容科
	     
		if(abc.Format("hh:mm")=="17:20"||abc.Format("hh:mm")>"17:20"){
			$(".main_left_1").hide();
			$(".main_right_1").hide();
			$(".main_left_2").show();
			$(".mtnum2").hide();
			$(".show").show();
			$(".btn_area").show();
			$("#confirm").css({"visibility":"hidden"});
			$("#fanhui").css({"visibility":"hidden"});
			$("#tuichu").css({"visibility":"visible"});
			daojishi(6);
			return;
	    }		
	
	window.location.href="/hddevice/mz/index.php/ZiZhu/Buhao/buhao/zzj_id/"+zzj_id;
})

$("#yuyue_quhao").click(function(){
		var zzj_id = $("#zzj_id").val();
		//急诊的，不让挂号
	/*if($("#zzj_id").val()=="ZZ016"||$("#zzj_id").val()=="ZZ025"){
		 $(".main_left_1").hide();
	     $(".main_right_1").hide();
	     $(".main_left_2").show();
         $(".mtnum2").hide();
         $(".hint").show();
         $(".btn_area").show();
         $("#confirm").css({"visibility":"hidden"});
		 $("#fanhui").css({"visibility":"hidden"});
         $("#tuichu").css({"visibility":"visible"});
         daojishi(6);
		 return;
	}*/
	var abc = new Date();
	if(abc.Format("yyyy-MM-dd")>="2019-10-01" && abc.Format("yyyy-MM-dd")<="2019-10-07"){
		if(abc.Format("hh:mm")>="11:50"){
			$(".main_left_1").hide();
			$(".main_right_1").hide();
			$(".main_left_2").show();
			$(".mtnum2").hide();
			$(".show").show();
			$(".btn_area").show();
			$("#confirm").css({"visibility":"hidden"});
			$("#fanhui").css({"visibility":"hidden"});
			$("#tuichu").css({"visibility":"visible"});
			daojishi(6);
			return;
		    }
	}
   // var abc = new Date();


	//美容科
      
		if(abc.Format("hh:mm")=="17:20"||abc.Format("hh:mm")>"17:20"){
			$(".main_left_1").hide();
			$(".main_right_1").hide();
			$(".main_left_2").show();
			$(".mtnum2").hide();
			$(".show").show();
			$(".btn_area").show();
			$("#confirm").css({"visibility":"hidden"});
			$("#fanhui").css({"visibility":"hidden"});
			$("#tuichu").css({"visibility":"visible"});
			daojishi(6);
			return;
	    }		
	
	window.location.href="/hddevice/mz/index.php/ZiZhu/QuHao/index/zzj_id/"+zzj_id;
})

$("#chaxun").click(function(){
	var zzj_id = $("#zzj_id").val();
	window.location.href="/hddevice/mz/index.php/ZiZhu/ChaXun/index/zzj_id/"+zzj_id;
})
/**************************挂号点击事件开始*********************************************************/





$(document).on("click",".sure_ghao",function(){
	// alert("###");
	switch($("#card_code").val()){
		case "20":
		var record_sn=$(this).attr("record_sn");
		var unit_name=$(this).attr("unit_name");
		var req_type=$(this).attr("req_type");	
		var doctor_name=$(this).attr("doctor_name");
		$(".mtnum2").hide();
       $(".suohao_guahao").show();
       $("#confirm").css({"visibility":"hidden"});
		$("#tuichu").css({"visibility":"hidden"});
		$("#fanhui").css({"visibility":"hidden"});
		daojishi(100);
		var index_load="";
		var params = {"record_sn":record_sn,"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"req_type":req_type};
		//医保划价先自费划价获取划价流水号
				$.ajax({
					url:"/hddevice/mz/index.php/ZiZhu/Index/reg_huajia", 
					type:'post',
					dataType:'json',
					data:params,
					/*beforeSend:function(){
						index_load = layer.msg('号源占位中,请稍后...', {icon: 16,time:20000});
						$("#confirm").css({"visibility":"hidden"});
						$("#tuichu").css({"visibility":"hidden"});
						$("#fanhui").css({"visibility":"hidden"});
					},*/
					success:function(data){
						layer.close(index_load);
						$("#op_now").val("ic_pay_info_show_guahao");
						if(data['result']['execute_flag']==0)
						{
							$("#record_sn").val(record_sn);
							//获取到划价流水号
							$("#pay_seq").val(data['datarow']['pay_seq']);
							$("#patient_id").val(data['datarow']['patient_id']);
							// var yb_input_param = $("#pay_seq").val()+"&"+$("#patient_id").val()+"&"+$("#card_code").val()+"&"+$("#card_no").val()+"&"+$("#response_type").val()+"&"+$("#zzj_id").val();
							var gh_flag="1";
							var yb_input_param = $("#pay_seq").val()+"&"+$("#patient_id").val()+"&"+$("#card_code").val()+"&"+$("#card_no").val()+"&"+$("#response_type").val()+"&"+$("#zzj_id").val()+"&"+gh_flag;
							//index_load2 = layer.msg('医保划价分解中,请稍后...', {icon: 16,time:20000});
							//alert(888);
							setTimeout(function(){
									//调用本地医保dll划价
								    var patinfo = window.external.YYT_YB_GH_CALC(yb_input_param);
									var params = {"input_xml":patinfo,"op_code":$("#op_code").val()}; 
									//讲划价信息传递到后台解析
									$.ajax({
										url:"/hddevice/mz/index.php/ZiZhu/Index/YbHalcParseGhao", 
										type:'post',
										dataType:'json',
										data:params,
										success:function(data){
											//layer.close(index_load2);
											if(data['result']['execute_flag']==0){
											   $("#record_sn").val(record_sn);
								//alert($("#record_sn").val());
												$("#patient_id").val(data['datarow']['patient_id']);
												$("#cash").val(data['datarow']['cash']);
												$("#charge_total").val(data['datarow']['charge_total']);
												$("#zhzf").val(data['datarow']['zhzf']);
												$("#tczf").val(data['datarow']['tczf']);
												$("#pay_seq").val(data['datarow']['pay_seq']);
												$("#confirm").css({"visibility":"visible"});
												$("#tuichu").css({"visibility":"visible"});
												$("#fanhui").css({"visibility":"visible"});
												$(".mtnum2").hide();
												$(".fenjie_result_guahao").show();
												$(".yb_txt").html("医保个人账户支付：");
												/******************修改就诊卡号为患者id*********************/
												$(".fenjie_result_guahao .p1 .s2").text($("#patient_id").val());
												/*********************************/
												$(".fenjie_result_guahao .p2 .uname").text($("#pat_name").val());
												$(".fenjie_result_guahao .p2 .sex").text($("#pat_sex").val());
												$(".fenjie_result_guahao .p3 .p_chare_totle").text(data['datarow']['charge_total']);
												$(".fenjie_result_guahao .p4 .p_cash").text(data['datarow']['cash']);
												$(".fenjie_result_guahao .p5 .p_zhzf").text(data['datarow']['zhzf']);
												$(".fenjie_result_guahao .p5 .p_tczf").text(data['datarow']['tczf']);
												$(".fenjie_result_guahao .p6 .p_cz_room").text(unit_name);
												$(".fenjie_result_guahao .p6 .p_cz_doctor").text(doctor_name);
												$(".fenjie_result_guahao .p7 .yb_zh").text($("#personcount").val());
												
												//$("#pay_confirm").show().html("就诊卡号："+$("#card_no").val()+"<br>姓名："+$("#pat_name").val()+" 性别："+$("#pat_sex").val()+"<br>总金额："+data['datarow']['charge_total']+"<br>现金支付："+data['datarow']['cash']+"<br>医保个人账户支付："+data['datarow']['zhzf']+" 统筹支付："+data['datarow']['tczf']+"<br><input type='button' value='确认' id='pay_btn_confirm' onclick='getPayTypeArea()' />"); 
											}else{
												//layer.msg(data['result']['execute_message'], {icon: 14,time:2000});
												$(".suohao_guahao h3").text(data['result']['execute_message'])
												$(".btn_area").show();
												$("#confirm").css({"visibility":"hidden"});
												$("#fanhui").css({"visibility":"hidden"});
												$("#tuichu").css({"visibility":"visible"});
											}
										
										}
									})
							},1000)
													
						}else{
							$(".suohao_guahao h3").text(data['result']['execute_message'])
												$(".btn_area").show();
												$("#confirm").css({"visibility":"hidden"});
												$("#fanhui").css({"visibility":"hidden"});
												$("#tuichu").css({"visibility":"visible"});
						}
					}
				})
		break;
		case "21":
		var record_sn=$(this).attr("record_sn");
		var unit_name=$(this).attr("unit_name");
		var req_type=$(this).attr("req_type");	
		var doctor_name=$(this).attr("doctor_name");
		/*var doctor_sn=$(this).('.ysh').attr('doctor_sn');*/
		var doctor_sn=$(this).find('.ysh').attr('doctor_sn');
		//alert(doctor_sn);
		/*=$(this).attr("doctor_sn");*/
        $("#doctor_sn").val(doctor_sn);
       $(".mtnum2").hide();
       $(".suohao_guahao").show();
       $("#confirm").css({"visibility":"hidden"});
		$("#tuichu").css({"visibility":"hidden"});
		$("#fanhui").css({"visibility":"hidden"});
        //alert($("#unit_sn").val());
		daojishi(100);
		var index_load="";
		var params = {"record_sn":record_sn,"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"req_type":req_type};
				$.ajax({
					url:"/hddevice/mz/index.php/ZiZhu/Index/reg_huajia", 
					type:'post',
					dataType:'json',
					data:params,
					/*beforeSend:function(){
						index_load = layer.msg('数据请求中,请稍后...', {icon: 16,time:20000});
						$("#confirm").css({"visibility":"hidden"});
						$("#tuichu").css({"visibility":"hidden"});
						$("#fanhui").css({"visibility":"hidden"});
					},*/
					success:function(data){
						//layer.close(index_load);
						$("#op_now").val("ic_pay_info_show_guahao");
						if(data['result']['execute_flag']==0)
						{
							$("#record_sn").val(record_sn);
							//alert($("#record_sn").val());
							$("#patient_id").val(data['datarow']['patient_id']);
							$("#cash").val(data['datarow']['cash']);
							$("#charge_total").val(data['datarow']['charge_total']);
							$("#zhzf").val(data['datarow']['zhzf']);
							$("#tczf").val(data['datarow']['tczf']);
							$("#pay_seq").val(data['datarow']['pay_seq']);
							$("#confirm").css({"visibility":"visible"});
							$("#tuichu").css({"visibility":"visible"});
							$("#fanhui").css({"visibility":"visible"});
							$(".mtnum2").hide();
							$(".fenjie_result_guahao").show();
							$(".yb_txt").html("医院垫付：");
							/******************修改就诊卡号为患者id*********************/
							$(".fenjie_result_guahao .p1 .s2").text($("#patient_id").val());
							/*********************************/
							$(".fenjie_result_guahao .p2 .uname").text($("#pat_name").val());
							$(".fenjie_result_guahao .p2 .sex").text($("#pat_sex").val());
							$(".fenjie_result_guahao .p3 .p_chare_totle").text(data['datarow']['charge_total']);
							$(".fenjie_result_guahao .p4 .p_cash").text(data['datarow']['cash']);
							$(".fenjie_result_guahao .p5 .p_zhzf").text(data['datarow']['zhzf']);
							$(".fenjie_result_guahao .p5 .p_tczf").text(data['datarow']['tczf']);
							$(".fenjie_result_guahao .p6 .p_cz_room").text(unit_name);
							$(".fenjie_result_guahao .p6 .p_cz_doctor").text(doctor_name);
						}else{
							$(".suohao_guahao h3").text(data['result']['execute_message'])
												$(".btn_area").show();
												$("#confirm").css({"visibility":"hidden"});
												$("#fanhui").css({"visibility":"hidden"});
												$("#tuichu").css({"visibility":"visible"});
						}
					}
				})
			break;
		}
})
/**************************挂号点击事件开始*********************************************************/
/**************************处方列表JS结束*********************************************************/
//首页自助缴费按钮点击事件
$("#index_zizhu_btn").click(function(){
	var abc = new Date();
	if(abc.Format("yyyy-MM-dd")>="2019-10-01" && abc.Format("yyyy-MM-dd")<="2019-10-07"){
		if(abc.Format("hh:mm")>="11:50"){
			$(".main_left_1").hide();
			$(".main_right_1").hide();
			$(".main_left_2").show();
			$(".mtnum2").hide();
			$(".show").show();
			$(".btn_area").show();
			$("#confirm").css({"visibility":"hidden"});
			$("#fanhui").css({"visibility":"hidden"});
			$("#tuichu").css({"visibility":"visible"});
			daojishi(6);
			return;
		    }
	}

       
		if(abc.Format("hh:mm")=="17:20"||abc.Format("hh:mm")>"17:20"){
			$(".main_left_1").hide();
			$(".main_right_1").hide();
			$(".main_left_2").show();
			$(".mtnum2").hide();
			$(".show").show();
			$(".btn_area").show();
			$("#confirm").css({"visibility":"hidden"});
			$("#fanhui").css({"visibility":"hidden"});
			$("#tuichu").css({"visibility":"visible"});
			daojishi(6);
			return;
	    		
	}

	var timestamp =(new Date()).getTime();
	//var time2 = (new Date(new Date().toLocaleDateString()+' 11:30:00')).valueOf();
	//var time3 = time2.getTime();
    //alert(timestamp);
	
	//alert(cash(197.97));
	$(".main_left_1").hide();
	$(".main_right_1").hide();
	$(".main_left_2").show();
	$(".chose_card_type_area").show();
	$(".btn_area").show();
	$("#confirm").css({"visibility":"hidden"});
	$("#fanhui").css({"visibility":"visible"});
	$("#tuichu").css({"visibility":"visible"});
	$("#op_now").val("choose_card");
	$("#business_type").val("自助缴费");
	
	$("#op_code").val(new Date().Format("yyyyMMddhhmmssS")/*GetRandomNum(10000,99999)*/);
	writeLog("选择自助缴费");
	daojishi(30);	
	return;
})
$('#jz_card_no').keydown(function(e){
if(e.keyCode==13){
  $("#confirm").trigger("click");
}
});
$('#jz_card_no_guahao').keydown(function(e){
if(e.keyCode==13){
  $("#confirm").trigger("click");
}
});
$('#jz_card_no_guahao_sfz').keydown(function(e){
if(e.keyCode==13){
  $("#confirm").trigger("click");
}
});
$('#jz_card_no_sfz').keydown(function(e){
if(e.keyCode==13){
  $("#confirm").trigger("click");
}
});
	if($("#zzj_id").val()=="ZZ016"||$("#zzj_id").val()=="ZZ025" ){
		$("#guahao").css({"background":"url(../../../../../Public/zizhu/img/button/dangri.png) no-repeat;"});

	}
/************首页自助挂号点击事件***************/
$("#guahao").on("click",function(){

	
	if($("#zzj_id").val()=="ZZ016"||$("#zzj_id").val()=="ZZ025"){
		 $(".main_left_1").hide();
	     $(".main_right_1").hide();
	     $(".main_left_2").show();
         $(".mtnum2").hide();
         $(".hint").show();
         $(".btn_area").show();
         $("#confirm").css({"visibility":"hidden"});
		 $("#fanhui").css({"visibility":"hidden"});
         $("#tuichu").css({"visibility":"visible"});
         daojishi(6);
		 return;
	}
    var abc = new Date();
	if(abc.Format("yyyy-MM-dd")>="2019-10-01" && abc.Format("yyyy-MM-dd")<="2019-10-07"){
		if(abc.Format("hh:mm")>="11:50"){
			$(".main_left_1").hide();
			$(".main_right_1").hide();
			$(".main_left_2").show();
			$(".mtnum2").hide();
			$(".show").show();
			$(".btn_area").show();
			$("#confirm").css({"visibility":"hidden"});
			$("#fanhui").css({"visibility":"hidden"});
			$("#tuichu").css({"visibility":"visible"});
			daojishi(6);
			return;
		    }
	}


	     
		// if(abc.Format("hh:mm")=="17:20"||abc.Format("hh:mm")>"17:20"){
		// 	$(".main_left_1").hide();
		// 	$(".main_right_1").hide();
		// 	$(".main_left_2").show();
		// 	$(".mtnum2").hide();
		// 	$(".show").show();
		// 	$(".btn_area").show();
		// 	$("#confirm").css({"visibility":"hidden"});
		// 	$("#fanhui").css({"visibility":"hidden"});
		// 	$("#tuichu").css({"visibility":"visible"});
		// 	daojishi(6);
		// 	return;
	 //    }		
	
	$(".main_left_1").hide();
	$(".main_left_2").hide();
	$(".chose_card_type_area_guahao").show();
	$(".btn_area").show();
	$("#confirm").css({"visibility":"hidden"});
	$("#fanhui").css({"visibility":"visible"});
	$("#tuichu").css({"visibility":"visible"});
	$("#op_now").val("choose_card_guahao");
	$("#business_type").val("自助挂号");
	$("#op_code").val(new Date().Format("yyyyMMddhhmmssS")/*GetRandomNum(10000,99999)*/);
	writeLog("选择自助挂号");
	daojishi(30);	
	return;
})
/************选择了就诊卡***************/
$("#xz_ic").on("click",function(){
	writeLog("选择了就诊卡");
	//window.external.send(6,2);
	window.external.send(2,2);
	$(".chose_card_type_area").hide();
	$(".jiuzhen_op_area").show();
	$("#jz_card_no").focus();
	$("#card_code").val("21");
	$("#op_now").val("ic_get_pat_info");
	$(".btn_area").show();
	$("#confirm").css({"visibility":"visible"});
	//var flag = window.external("InitIC","");
	jzk_flag = window.external.InitIC();
	//window.external.nTextInput();
	
	if(jzk_flag>0){
		$(".jiuzhen_op_area .tips").text("初始化成功");
		
		interval = setInterval(getCardNo, "1000");
		interval3.push(interval);
		
	}else{
		$(".jiuzhen_op_area .tips").text("初始化失败");
	}
	//window.external.nTextInput();
	daojishi(60);
	
})
/****************选择了身份证*******************/
$("#xz_sfz").on("click",function(){
	writeLog("选择了身份证");
	window.external.send(6,2);
	$(".chose_card_type_area").hide();
	$(".jiuzhen_op_area_sfz").show();
	$("#jz_card_no_sfz").focus();
	$("#card_code").val("21");
	$("#op_now").val("ic_get_pat_info");
	$(".btn_area").show();
	$("#confirm").css({"visibility":"visible"});
	//var flag = window.external("InitIC","");
	jzk_flag = window.external.InitIC();
	//window.external.nTextInput();
	if(jzk_flag>0){
		$(".jiuzhen_op_area .tips").text("初始化成功");
		
		interval = setInterval(getCardNoS, "1000");
		interval3.push(interval);
		
	}else{
		$(".jiuzhen_op_area .tips").text("初始化失败");
	}
	//window.external.nTextInput();
	daojishi(60);
	
})
/****************自助挂号选择了就诊卡*******************/
$("#xz_ic_guahao").on("click",function(){
	writeLog("选择了就诊卡");
	window.external.send(2,2);
	$(".chose_card_type_area_guahao").hide();
	$(".jiuzhen_op_area_guahao").show();
	$("#jz_card_no_guahao").focus();
	$("#card_code").val("21");
	$("#op_now").val("ic_get_pat_info_guahao");
	$(".btn_area").show();
	$("#confirm").css({"visibility":"visible"});

	//var flag = window.external("InitIC","");
	jzk_flag = window.external.InitIC();
	//window.external.nTextInput();
	
	if(jzk_flag>0){
		$(".jiuzhen_op_area_guahao .tips_guahao").text("初始化成功");
		
		interval = setInterval(getCardNo, "1000");
		interval3.push(interval);
		
	}else{
		$(".jiuzhen_op_area_guahao .tips_guahao").text("初始化失败");
	}
	//window.external.nTextInput();
	daojishi(60);
	
})
/****************自助挂号选择了身份证*******************/
$("#xz_sfz_guahao").on("click",function(){
	window.external.send(6,2);
	writeLog("选择了身份证");
	$(".chose_card_type_area_guahao").hide();
	$(".jiuzhen_op_area_guahao_sfz").show();
	$("#jz_card_no_guahao_sfz").focus();
	$("#card_code").val("21");
	$("#op_now").val("ic_get_pat_info_guahao");
	$(".btn_area").show();
	$("#confirm").css({"visibility":"visible"});

	//var flag = window.external("InitIC","");
	jzk_flag = window.external.InitIC();
	//window.external.nTextInput();
	
	if(jzk_flag>0){
		$(".jiuzhen_op_area_guahao .tips_guahao").text("初始化成功");
		
		interval = setInterval(getCardNoS, "1000");
		interval3.push(interval);
		
	}else{
		$(".jiuzhen_op_area_guahao .tips_guahao").text("初始化失败");
	}
	//window.external.nTextInput();
	daojishi(60);
	
})
/****************选择了医保卡*******************/
$("#xz_yibao").on("click",function(){
	window.external.send(1,2);
	$(".mtnum2").hide();
	$(".yb_op_area").show();
	$("#op_now").val("chose_yb_card");
	$(".btn_area").show();
	$(".btn_area ul li").css({"visibility":"visible"});
	$("#confirm").css({"visibility":"hidden"});
	window.external.AllowCardIn();
	var flag=false;
	interval = setInterval(function(){
		flag = window.external.ReadStatus();	
		if(flag){
			$("#confirm").trigger("click");
			clearInterval(interval);
		}
	}, "1000");	
	
	interval3.push(interval);
	writeLog("选择了医保卡");
	daojishi(60);
	
})

//补挂号费选择了银行卡
$("#pay_bank_quhao").on("click",function(){
    $(".mtnum2").hide();
    $(".bank_quhao").show();
    $(".btn_area").hide();
    //$("#confirm").css({"visibility":"hidden"});
    //$("#fanhui").css({"visibility":"hidden"})
    writeLog("选择了银行卡");
    window.external.send(4,2);
    $("#op_now").val("zf_pay_bank_quhao");
    $("#pay_type").val("zf_bank");
    daojishi(120);
    var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
    $("#stream_no").val(out_trade_no);
    var total_amount = cash($("#cash").val());
    
    //初始化
    var flag = window.external.Out_UMS_Init();
    //alert(total_amount);
    //alert(flag);
    if(flag==0){
        //允许进卡
        //alert("00"+$("#zzj_id").val()+","+"00"+$("#zzj_id").val()+","+$("#tansType").val()+","+total_amount+","+"666666"+","+"88888888"+","+"111111111111"+","+"222222"+","+"777777"+","+""+","+"999");
        var req_flag =window.external.Out_UMS_SetReq("00000001","00000002","00",total_amount,"666666","88888888","111111111111","222222","777777","20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020","999");
        if(req_flag==0){
        window.external.Out_UMS_EnterCard(); 
        //检查卡
        var check_flag=false;
        interval = setInterval(function(){
            check_flag =window.external.Out_UMS_CheckCard();
            if(check_flag==0){
                clearInterval(interval);
                $(".bank_quhao .tips").html("系统处理中，请稍候....");  
                setTimeout(function(){
                    var read_flag = window.external.Out_UMS_ReadCard();
                    if(read_flag==0){
                        $("#confirm").trigger("click");
                    }else{
                        setTimeout(function(){
                        window.external.Out_UMS_EjectCard();
                        window.external.Out_UMS_CardClose();
                        $("#tuichu").trigger("click");
                                            },3000)
                        }
                },1000)
                
            }
        }, "1000"); 
        }
        }else{
            $(".bank_quhao tips").html("初始化读卡器失败!");
            }
    //UMS_CheckCard();
    //UMS_ReadCard();
})



//补挂号费选择了银行卡
$("#pay_bank_quhao1").on("click",function(){
    $(".mtnum2").hide();
    $(".bank_quhao1").show();
    $(".btn_area").hide();
    //$("#confirm").css({"visibility":"hidden"});
    //$("#fanhui").css({"visibility":"hidden"})
    writeLog("选择了银行卡");
    window.external.send(4,2);
    $("#op_now").val("zf_pay_bank_quhao1");
    $("#pay_type").val("zf_bank");
    daojishi(120);
    var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
    $("#stream_no").val(out_trade_no);
    var total_amount = cash($("#cash").val());
    
    //初始化
    var flag = window.external.Out_UMS_Init();
    //alert(total_amount);
    //alert(flag);
    if(flag==0){
        //允许进卡
        //alert("00"+$("#zzj_id").val()+","+"00"+$("#zzj_id").val()+","+$("#tansType").val()+","+total_amount+","+"666666"+","+"88888888"+","+"111111111111"+","+"222222"+","+"777777"+","+""+","+"999");
        var req_flag =window.external.Out_UMS_SetReq("00000001","00000002","00",total_amount,"666666","88888888","111111111111","222222","777777","20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020","999");
        if(req_flag==0){
        window.external.Out_UMS_EnterCard(); 
        //检查卡
        var check_flag=false;
        interval = setInterval(function(){
            check_flag =window.external.Out_UMS_CheckCard();
            if(check_flag==0){
                clearInterval(interval);
                $(".bank_quhao1 .tips").html("系统处理中，请稍候....");  
                setTimeout(function(){
                    var read_flag = window.external.Out_UMS_ReadCard();
                    if(read_flag==0){
                        $("#confirm").trigger("click");
                    }else{
                        setTimeout(function(){
                        window.external.Out_UMS_EjectCard();
                        window.external.Out_UMS_CardClose();
                        $("#tuichu").trigger("click");
                                            },3000)
                        }
                },1000)
                
            }
        }, "1000"); 
        }
        }else{
            $(".bank_quhao1 tips").html("初始化读卡器失败!");
            }
    //UMS_CheckCard();
    //UMS_ReadCard();
})




//补挂号费选择了支付宝
$("#pay_zhifubao_quhao").on("click",function(){
	//alert($("#business_type").val());
    $("#op_now").val("zf_pay_zhifubao_guahao");
      writeLog("选择了支付宝");
    $("#pay_type").val("alipay");
    daojishi(300);
    var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
    $("#stream_no").val(out_trade_no);
    var total_amount = $("#cash").val();
    var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#pat_name").val()+"预约取号"}; 
    var index_load = "";
    $.ajax({
        url:"/hddevice/mz/index.php/ZiZhu/YuYue/getAliPayCode", 
        type:'post',
        dataType:'json',
        data:params,
        beforeSend:function(){
            index_load = layer.msg('支付二维码请求中,请稍候...', {icon: 16,time:20000});
        },
        success:function(data){
           

            layer.close(index_load);
            $(".mtnum2").hide();
            $(".alipay_ma_show_quhao").show();
            $(".btn_area").show();
            $("#confirm").css({"visibility":"hidden"})
            $("#tuichu").css({"visibility":"hidden"})
            $("#fanhui").css({"visibility":"hidden"})
            
            
            $("#stream_no").val(out_trade_no);
            $(".alipay_ma_show_quhao .pay_val").text("￥"+$("#cash").val());
            $(".alipay_ma_show_quhao .erweima").html("<img src='http://172.168.0.89"+data['message']['imgurl']+"' width='240' />");
            s1 = setInterval(function(){
                getResult(out_trade_no);                             
            },5000); 
            interval3.push(s1);
            //$("#dingshi").val(s1);
            
        }
    })
    
})

$("#pay_zhifubao_quhao1").on("click",function(){
	/*alert($("#card_code").val()+","+$("#card_no").val()+","+$("#pat_code").val()+","+$("#record_sn").val()+","+$("#response_type").val()+","+$("#charge_total").val()+","+$("#zhzf").val()+","+$("#tczf").val()+","+$("#cash").val()+","+$("#pay_seq").val()+","+$("#trade_no").val()+","+$("#stream_no").val()+","+$("#op_code").val()+","+$("#zzj_id").val()+","+$("#pay_type").val());*/
	//alert($("#business_type").val());
    $("#op_now").val("zf_pay_zhifubao_guahao");
      writeLog("选择了支付宝");
    $("#pay_type").val("alipay");
    daojishi(300);
    var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
    $("#stream_no").val(out_trade_no);
    var total_amount = $("#cash").val();
    var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#pat_name").val()+"预约取号"}; 
    var index_load = "";
    $.ajax({
        url:"/hddevice/mz/index.php/ZiZhu/YuYue/getAliPayCode", 
        type:'post',
        dataType:'json',
        data:params,
        beforeSend:function(){
            index_load = layer.msg('支付二维码请求中,请稍候...', {icon: 16,time:20000});
        },
        success:function(data){
           

            layer.close(index_load);
            $(".mtnum2").hide();
            $(".alipay_ma_show_quhao1").show();
            $(".btn_area").show();
            $("#confirm").css({"visibility":"hidden"})
            $("#tuichu").css({"visibility":"hidden"})
            $("#fanhui").css({"visibility":"hidden"})
            
            
            $("#stream_no").val(out_trade_no);
            $(".alipay_ma_show_quhao1 .pay_val").text("￥"+$("#cash").val());
            $(".alipay_ma_show_quhao1 .erweima").html("<img src='http://172.168.0.89"+data['message']['imgurl']+"' width='240' />");
            s1 = setInterval(function(){
                getResult(out_trade_no);                             
            },5000); 
            interval3.push(s1);
            //$("#dingshi").val(s1);
            
        }
    })
    
})




/****************自助挂号选择了医保卡*******************/
$("#xz_yibao_guahao").on("click",function(){
	window.external.send(1,2);
	$(".mtnum2").hide();
	$(".yb_op_area_guahao").show();
	$("#op_now").val("chose_yb_card_guahao");
	$(".btn_area").show();
	$(".btn_area ul li").css({"visibility":"visible"});
	$("#confirm").css({"visibility":"hidden"});
	//允许卡进
	window.external.AllowCardIn();
	var flag=false;
	interval = setInterval(function(){
		//读取医保卡状态
		flag = window.external.ReadStatus();	
		if(flag){
			$("#confirm").trigger("click");
			clearInterval(interval);
		}
	}, "1000");	
	
	interval3.push(interval);
	writeLog("选择了医保卡");
	daojishi(60);
	
})
/***退出按钮事件***/
$("#tuichu").on("click",function(){
	print_status($("#zzj_id").val());
	window.external.send(1,4);
	writeLog("点击退出退到主界面");
	$(".main_left_1").show();
	$(".main_right_1").show();
	$(".mtnum2").hide();
	$("#jz_card_no").val("");
	$("#jz_card_no_guahao").val("");
	$("#jz_card_no_guahao_sfz").val("");
	$("#jz_card_no_sfz").val("");
	$(".btn_area").hide();
	$(".op_now").val("");
	  <!--------添加了用户的姓名-------->
	$("#jz_name").html("");
	$("#guahao_name").html("");
	$("#keshi_name").html("");
	$("#attr_list").html("");
	$("#sub_list").html("");
	$(".chose_room h4").html("");
	$(".chose_room h3").html("");
	$(".tips").html("");
	$(".alipay_ma_show .tips").html("请用手机支付宝扫描付款");
	$(".alipay_ma_show_guahao .tips").html("请用手机支付宝扫描付款");
	$(".alipay_ma_show_guahao .erweima").html("");	
	$(".alipay_ma_show .erweima").html("");	
	$(".alipay_ma_show .pay_val").html("");
	$(".alipay_ma_show_guahao .pay_val").html("");
	$(".chufang_list h4").html("");
	$(".chufang_list h3").html("");
	$(".yb_op_area .tips").html('');
	$(".yb_op_area_guahao .tips_guahao").html('');
	key_value="";
	key_value2="";
	window.external.FreeYBIntfDll();
	window.external.MoveOutCard();
	window.external.DisAllowCardIn();
	//window.external.KeyBoardComClose();
	//window.external.keybord_close();
	window.external.Out_UMS_CardClose();
	window.external.Out_UMS_EjectCard();
	//UMS_EjectCard();
	//UMS_CardClose();
	$(".inhide").val("");
	$("#downcount").hide();
	for(var key in interval2){
		clearTimeout(interval2[key]);
	}
	for(var key in interval3){
		clearInterval(interval3[key]);
	}
	location.reload();
})
/*****确定按钮点击事件**********/
$("#confirm").on("click",function(){
	var op_now = $("#op_now").val();
	//alert(op_now);
	for(var key in interval3){
		clearInterval(interval3[key]);
	}
	
	/**获取自费患者就诊记录**/
	switch(op_now){
		case "ic_get_pat_info":
		//window.external.keybord_close();
		window.external.send(1,4);
		if($("#jz_card_no").val()!=""){
		var kaid = $("#jz_card_no").val();
		}else{
	    var kaid = $("#jz_card_no_sfz").val();
	    //alert(kaid);
		}
		$("#op_now").val("ic_pay_chufang_show");
		$(".mtnum2").hide();
		$(".chufang_area").show();
		$("#confirm").css({"visibility":"visible"});
		clearInterval(interval);
		var business_type = $("#business_type").val();

		var params = {"kaid":kaid,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type}; 
		var index_load = "";
		$.ajax({
			url:"/hddevice/mz/index.php/ZiZhu/Index/getPatInfo", 
			type:'post',
			dataType:'json',
			data:params,
			beforeSend:function(){
				index_load = layer.msg('患者就诊数据查询中,请稍候...', {icon: 16,time:20000});
				$("#fanhui").css({"visibility":"hidden"});
				$("#confirm").css({"visibility":"hidden"});
				$("#tuichu").css({"visibility":"hidden"});
			},
			success:function(data){
				//alert(77);exit;
				layer.close(index_load);
				$("#fanhui").css({"visibility":"visible"});
				$("#tuichu").css({"visibility":"visible"});
				//添加了医保患者的姓名
				if(data['patInfo']['result']['execute_flag']==0){
					$("#jz_name").text(data['patInfo']['datarow']['name']);
					//$("#patinfo_area").show().html("卡号："+data['datarow']['p_bar_code']+" 姓名："+data['datarow']['name']+" 性别："+data['datarow']['sex_chn']);
					$("#pat_code").val(data['patInfo']['datarow']['patient_id']); 
					$("#card_no").val(data['patInfo']['datarow']['card_no']);
					$("#pat_name").val(data['patInfo']['datarow']['name']);
					$("#pat_sex").val(data['patInfo']['datarow']['sex_chn']);
					$("#times").val(data['chufang']['datarow'][0]['attr']['times']);
					$("#response_type").val(data['patInfo']['datarow']['response_type']);
					$("#reponse_name").val(data['patInfo']['datarow']['response_chn']);
					if(data['chufang']['result']['execute_flag']==0){
						var html="";
						$("#confirm").css({"visibility":"visible"});
						var cflist = data['chufang']['datarow'];
						for(var i=0;i<cflist.length;i++){
							var sub_html = "";
							var sub_item = data['chufang']['datarow'][i]['sub'];
							for(var m=0;m<sub_item.length;m++){
								sub_html+="<tr><td align='center'>"+(m+1)+"</td><td>"+sub_item[m]['detail_name']+"</td><td>"+sub_item[m]['amount']+"</td><td>"+sub_item[m]['prices']+"</td><td>"+sub_item[m]['units']+"</td><td>"+sub_item[m]['costs']+"</td></tr>";
								$("#doctor_code").val(sub_item[m]['doctor_code']);
								$("#doctor_name").val(sub_item[m]['ordered_doctor']);
								$("#dept_code").val(sub_item[m]['apply_unit']);
								$("#dept_name").val(sub_item[m]['ordered_by']);
							}
							html+="<ul><li class='chk'><input type='checkbox' name='times_order_no[]' value='"+cflist[i].attr.times+"|"+cflist[i].attr.order_no+"' checked='checked' /></li><li class='one'>"+cflist[i].attr.order_no+"</li><li class='two'>"+cflist[i].attr.ordered_by+"</li><li class='thr'>"+cflist[i].attr.order_charge+"</li><li class='four'>"+cflist[i].sub[0].order_date+"</li><li class='five z1'>展开</li><table class='detail' style='display:none;'><tr><th>序号</th><th>名称</th><th>数量</th><th>单价</th><th>单位</th><th>费用</th></tr>"+sub_html+"</table></ul>";
						}
					
						$(".chufang_list").html(html);
						if($("#response_type").val()>=90){
                            layer.confirm('医保身份患者请使用医保卡挂号,否则本次所有费用将按照自费处理', {
                            btn: ['确定'] //按钮
                            });
    

                              
                            }
					}else{
						//alert(data['chufang']['result']['execute_message']);
						$(".chufang_list h4").html(data['chufang']['result']['execute_message']);


					}
				}else{
					/*$(".chufang_list h4").html(data['patInfo']['result']['execute_message']);
					*/
					if(data['patInfo']['result']['execute_message']=="有就诊未缴挂号费记录,请先补缴挂号费!"){
                                    	var ts="预约挂号-->选择卡类型-->补挂号费";
                                   	 	$(".chufang_list h4").html(data['patInfo']['result']['execute_message']);
                                    	$(".btn_bu").show();
										$("#bugua").css({"visibility":"visible"});

										$("#jz_name").text(data['TwopatInfo']['datarow']['name']);
										$("#pat_code").val(data['TwopatInfo']['datarow']['patient_id']); 
										$("#card_no").val(data['TwopatInfo']['datarow']['card_no']);
										$("#pat_name").val(data['TwopatInfo']['datarow']['name']);
										$("#pat_sex").val(data['TwopatInfo']['datarow']['sex_chn']);
										$("#response_type").val(data['TwopatInfo']['datarow']['response_type']);
										$("#reponse_name").val(data['TwopatInfo']['datarow']['response_chn']);
										$("#buhao_rukou").val("自助缴费");
										if($("#response_type").val()>=90){
					                            layer.confirm('医保身份患者请使用医保卡挂号,否则本次所有费用将按照自费处理', {
					                            btn: ['确定'] //按钮
					                            });
					    

					                              
					                            }




                               	 		}else{
                                     	$(".chufang_list h4").html(data['patInfo']['result']['execute_message']);
                               	 		}


					

				}
			}
		})
		daojishi(120);
	break;
	/********************自助挂号病人信息查询*************************/
	case "ic_get_pat_info_guahao":
	window.external.send(1,4);
	if($("#jz_card_no_guahao").val()!="")
	   {
			var kaid = $("#jz_card_no_guahao").val();
	   }else{
	   	    var kaid = $("#jz_card_no_guahao_sfz").val();
	   }
			$("#op_now").val("chose_room");
			$(".mtnum2").hide();
			$(".chose_room").show();
			$("#confirm").css({"visibility":"visible"});
			var business_type =$("#business_type").val();

			var params = {"kaid":kaid,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type}; 
			var index_load = "";
			$.ajax({
				   	url:"/hddevice/mz/index.php/ZiZhu/Index/getGhPatInfo", 
					type:'post',
					dataType:'json',
					data:params,
					beforeSend:function(){
						index_load = layer.msg('出诊科室数据查询中,请稍候...', {icon: 16,time:20000});
						$("#fanhui").css({"visibility":"hidden"});
						$("#confirm").css({"visibility":"hidden"});
						$("#tuichu").css({"visibility":"hidden"});
					},
					success:function(data)
					{
						//
						layer.close(index_load);
						$("#fanhui").css({"visibility":"visible"});
						$("#tuichu").css({"visibility":"visible"});
						if(data['patGhInfo']['result']['execute_flag']==0)
						{	
							var age1=(data['patGhInfo']['datarow']['birthday'].substring(-1,10));
							$("#guahao_name").text(data['patGhInfo']['datarow']['name']);
							$("#pat_code").val(data['patGhInfo']['datarow']['patient_id']); 
							$("#card_no").val(data['patGhInfo']['datarow']['card_no']);
							//$("#card_code").val(data['patGhInfo']['datarow']['card_code']);
							$("#pat_name").val(data['patGhInfo']['datarow']['name']);
							$("#pat_sex").val(data['patGhInfo']['datarow']['sex_chn']);
							//alert(data['patGhInfo']['datarow']['response_type']);
							$("#response_type").val(data['patGhInfo']['datarow']['response_type']);
							//$("#pat_age").val(data['patGhInfo']['datarow']['age']);
							$("#pat_age").val(ages(age1));
							if($("#response_type").val()>=90){
                            layer.confirm('医保身份患者请使用医保卡挂号,否则本次所有费用将按照自费处理', {
                            btn: ['确定'] //按钮
                            });
    

                              
                            }
							//alert($("#response_type").val());
							$("#reponse_name").val(data['patGhInfo']['datarow']['response_chn']);
							if(data['room']['result']['execute_flag']==0)
							{
								var attr_html = "";
								var sub_html = "";
								var roomlist = data['room']['datarow'];
								//alert(roomlist[0]['attr']['class_name']);
								for(var i=0;i<roomlist.length;i++)
								{
									if(i==0)
									{
										attr_html+="<li class='current'>"+roomlist[i]['attr']['class_name']+"</li>";
									}else
									{
										if(roomlist[i]['attr']['class_name']=="中医五官"){
                                            roomlist[i]['attr']['class_name']="中医五官康复";
                                            attr_html+="<li style='font-size:15px'>"+roomlist[i]['attr']['class_name']+"</li>";
                                        }else{
                                            attr_html+="<li>"+roomlist[i]['attr']['class_name']+"</li>";
                                        }
									}
									//alert(roomlist[i]['attr']['class_name']);
									
									var sublist = roomlist[i]['sub'];
									var sub1_html = "";
									for(var m=0;m<sublist.length;m++)
									{
										//alert(sublist[m]["unit_name"]);
										sub1_html+="<span class='chose_unit' roomId="+sublist[m]["unit_sn"]+">"+sublist[m]["unit_name"]+"</span>";
									}
									if(i==0)
									{
										sub_html+="<div>"+sub1_html+"</div>";
									}else
										{
											sub_html+="<div style='display:none'>"+sub1_html+"</div>";
										}
									
								}
							   $("#attr_list").html(attr_html);
							   $("#sub_list").html(sub_html);
							}
						}else
							{
								if(data['patGhInfo']['result']['execute_message']=="有就诊未缴挂号费记录,请先补缴挂号费!"){
                                    var ts="预约挂号-->选择卡类型-->补挂号费";
                                    $(".chose_room h4").html(data['patGhInfo']['result']['execute_message']);

	                                $(".btn_bu").show();
									$("#bugua").css({"visibility":"visible"});

									var age1=(data['TwopatGhInfo']['datarow']['birthday'].substring(-1,10));
									$("#guahao_name").text(data['TwopatGhInfo']['datarow']['name']);
									$("#pat_code").val(data['TwopatGhInfo']['datarow']['patient_id']); 
									$("#card_no").val(data['TwopatGhInfo']['datarow']['card_no']);
									
									$("#pat_name").val(data['TwopatGhInfo']['datarow']['name']);
									$("#pat_sex").val(data['TwopatGhInfo']['datarow']['sex_chn']);
									
									$("#response_type").val(data['TwopatGhInfo']['datarow']['response_type']);
									
									$("#pat_age").val(ages(age1));
									if($("#response_type").val()>=90){
		                            layer.confirm('医保身份患者请使用医保卡挂号,否则本次所有费用将按照自费处理', {
		                            btn: ['确定'] //按钮
		                            });
		    

		                              
		                            }
									
									$("#reponse_name").val(data['TwopatGhInfo']['datarow']['response_chn']);
									$("#buhao_rukou").val("自助挂号");





									
                                }else{
                                    $(".chose_room h4").html(data['patGhInfo']['result']['execute_message']);
                                }

                            


                                
							}
					}
				  })
	break;
	/********************自费缴费信息展示确认 划价*************************/
	case "ic_pay_chufang_show":
	daojishi(100);
	var times_order_no = "";
	var index_load2="";
	$("input[name='times_order_no[]']:checked").each(function(i, n){	
			times_order_no += n.value+";";
	})		
	if(times_order_no==""){
		 layer.msg('请至少选择一项', {icon: 15,time:2000});
		 return;
	}
	$("#op_now").val("ic_pay_info_show");
	switch($("#card_code").val()){
			
		case '21'://就诊卡划价
			var index_load="";
			var params = {"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"times":$("#times").val(),"times_order_no":times_order_no,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val()};
			$.ajax({
				url:"/hddevice/mz/index.php/ZiZhu/Index/ajax_huajia", 
				type:'post',
				dataType:'json',
				data:params,
				beforeSend:function(){
					index_load = layer.msg('数据请求中,请稍后...', {icon: 16,time:20000});
					$("#confirm").css({"visibility":"hidden"});
				},
				success:function(data){
					layer.close(index_load);
					if($("#response_type").val()>=90){
						    alert("请使用医保卡进行缴费，否则本次费用将按照自费处理");
					        }
					if(data['result']['execute_flag']==0){
						/******************小张**修改的隐藏域赋值**********************/
						$("#patient_id").val(data['datarow']['patient_id']);
						/*********************************/
						$("#cash").val(data['datarow']['cash']);
						$("#charge_total").val(data['datarow']['charge_total']);
						$("#zhzf").val(data['datarow']['zhzf']);
						$("#tczf").val(data['datarow']['tczf']);
						$("#pay_seq").val(data['datarow']['pay_seq']);
						$("#confirm").css({"visibility":"visible"});
						$(".mtnum2").hide();
						$(".fenjie_result").show();
						$(".yb_txt").html("医院垫付：");
						/******************修改就诊卡号为患者id*********************/
						$(".fenjie_result .p1 .s2").text($("#patient_id").val());
						/*********************************/
						$(".fenjie_result .p2 .uname").text($("#pat_name").val());
						$(".fenjie_result .p2 .sex").text($("#pat_sex").val());
						$(".fenjie_result .p3 .p_chare_totle").text(data['datarow']['charge_total']);
						$(".fenjie_result .p4 .p_cash").text(data['datarow']['cash']);
						$(".fenjie_result .p5 .p_zhzf").text(data['datarow']['zhzf']);
						$(".fenjie_result .p6 .p_tczf").text(data['datarow']['tczf']);
						
						//$("#pay_confirm").show().html("就诊卡号："+$("#card_no").val()+"<br>姓名："+$("#pat_name").val()+" 性别："+$("#pat_sex").val()+"<br>总金额："+data['datarow']['charge_total']+"<br>现金支付："+data['datarow']['cash']+"<br>医保个人账户支付："+data['datarow']['zhzf']+" 统筹支付："+data['datarow']['tczf']+"<br><input type='button' value='确认' id='pay_btn_confirm' onclick='getPayTypeArea()' />"); 
					}else{
						layer.msg(data['result']['execute_message'], {icon: 14,time:2000});
					}
				}
			})
		break;
		case '20'://医保划价
		break;
		default:
		layer.msg("未知错误", {icon: 14,time:2000});
		break;
	}
	/*$(".mtnum2").hide();
	$(".fenjie_result").show();
	$(".fenjie_result .p1 .s2").text($("#card_no").val());
	$(".fenjie_result .p2 .uname").text($("#pat_name").val());
	$(".fenjie_result .p2 .sex").text($("#pat_sex").val());
	$(".fenjie_result .p3 .p_chare_totle").text($("#charge_total").val());
	$(".fenjie_result .p4 .p_cash").text($("#cash").val());
	$(".fenjie_result .p5 .p_zhzf").text($("#zhzf").val());
	$(".fenjie_result .p5 .p_tczf").text($("#tczf").val());
	*/
	break;
	/*********自费患者费用确认保存**************/
	case "ic_pay_info_show":
	if($("#cash").val()==0){
		var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
		$("#stream_no").val(out_trade_no);
		$(".mtnum2").hide();
		$(".pay_success").show();
		$(".pay_success h3").html("费用确认中,请稍候...");
		paySuccessSendToHis($("#card_code").val());
	}else{
		$("#op_now").val("pay_chose");
		$(".mtnum2").hide();
		$(".chose_pay_type_area").show();
		$(".btn_area").show();
		$("#confirm").css({"visibility":"hidden"});
	}
	
	break;
	/*********自费患者挂号费用确认保存**************/
	case "ic_pay_info_show_guahao":
	if($("#cash").val()==0){
		var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
		$("#stream_no").val(out_trade_no);
		$(".mtnum2").hide();
		$(".pay_success").show();
		$(".pay_success h3").html("费用确认中,请稍候...");
		paySuccessSendToHis($("#card_code").val());
	}else{
		$("#op_now").val("pay_chose_guahao");
		$(".mtnum2").hide();
		$(".chose_pay_type_area_guahao").show();
		$(".btn_area").show();
		$("#confirm").css({"visibility":"hidden"});
		}
	break;
	/*********自费患者补挂号费用确认保存**************/
	case "ic_pay_info_show_quhao":
		if($("#cash").val()==0){
        var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
        $("#stream_no").val(out_trade_no);
        $(".mtnum2").hide();
        $(".pay_success").show();
        $(".pay_success h3").html("费用确认中,请稍候...");
        paySuccessSendToHis($("#card_code").val());
    }else{
    	switch($("#buhao_rukou").val()){
			case "自助挂号":
			$("#op_now").val("pay_type_quhao");
	        $(".mtnum2").hide();
	        $(".chose_pay_type_area_quhao1").show();
	        $(".btn_area").show();
	        $("#confirm").css({"visibility":"hidden"});
			break;
			case "自助缴费":
			$("#op_now").val("pay_type_quhao");
	        $(".mtnum2").hide();
	        $(".chose_pay_type_area_quhao").show();
	        $(".btn_area").show();
	        $("#confirm").css({"visibility":"hidden"});
			break;
        
        	}
		}

	break;
	/********获取医保患者就诊信息开始**********************************************************************************/
	case "chose_yb_card":
	    writeLog("读取医保卡患者信息");
	   	window.external.send(1,4);
		daojishi(100);
		$(".yb_op_area .tips").html('医保患者数据读取中,请稍后...');
		var flag;  
		var patinfo;
		$("#op_now").val("yb_get_pat_chufang");
		//$("#confirm").css({"visibility":"hidden"});
		$(".btn_area").hide();
		setTimeout(function(){
			flag = window.external.InitYbIntfDll();		
			//alert(flag);
			if(flag==0){
				writeLog("开始读取医保读卡器，医保读卡器初始化成功","INFO"); 
				
				patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
				//alert(patinfo);
				setTimeout(function(){
					if(patinfo!=""){
						var params = {"input_xml":patinfo,"op_code":$("#op_code").val()}; 
						$.ajax({
							url:"/hddevice/mz/index.php/ZiZhu/Index/YbXmlParse", 
							type:'post',
							dataType:'json',
						
							data:params,
							beforeSend:function(){
								$(".yb_op_area .tips").html('就诊信息查询中,请稍后...');
							},
							success:function(data){
								writeLog("患者处方信息读取成功");
								if(data['result']['execute_flag']==0){
									//走到这步说明医保卡已经成功读取，给隐藏域赋值
									//添加了患者的姓名
									//alert(data['datarow'][0]['attr']['times']);
									$("#times").val(data['datarow'][0]['attr']['times']);
									//alert($("#times").val());
									$("#jz_name").text(data['yb_input_data']['name']);
									$("#pat_code").val(data['yb_input_data']['patient_id']); 
									$("#card_no").val(data['yb_input_data']['card_no']);
									$("#card_code").val(data['yb_input_data']['card_code']);
									$("#pat_name").val(data['yb_input_data']['name']);
									$("#pay_seq").val(data['data']['pay_seq']);
									$("#yb_flag").val(data['yb_input_data']['yb_flag']);
									$("#personcount").val(data['yb_input_data']['personcount']);
									if(data['yb_input_data']['yb_flag']=="0"){
										$("#op_now").val("ic_pay_chufang_show");
										$("#card_code").val("21");
									}
									$("#pat_sex").val(data['yb_input_data']['sex_chn']);
								   $("#responce_type").val(data['yb_input_data']['response_type']);
								   
								   $("#healthcare_card_no").val(data['yb_input_data']['yb_card_no']);
									
									if(data['result']['execute_flag']==0){
										$(".mtnum2").hide();
										$(".chufang_area").show();
										var html="";
										var cflist = data['datarow'];
										for(var i=0;i<cflist.length;i++){
											var sub_html = "";
											var sub_item = data['datarow'][i]['sub'];
											for(var m=0;m<sub_item.length;m++){
												sub_html+="<tr><td align='center'>"+(m+1)+"</td><td>"+sub_item[m]['detail_name']+"</td><td>"+sub_item[m]['amount']+"</td><td>"+sub_item[m]['prices']+"</td><td>"+sub_item[m]['units']+"</td><td>"+sub_item[m]['costs']+"</td></tr>";
												$("#doctor_code").val(sub_item[m]['doctor_code']);
												$("#doctor_name").val(sub_item[m]['ordered_doctor']);
												$("#dept_code").val(sub_item[m]['apply_unit']);
												$("#dept_name").val(sub_item[m]['ordered_by']);
											}
											
											html+="<ul><li class='chk'><input type='checkbox' name='times_order_no[]' value='"+cflist[i].attr.times+"|"+cflist[i].attr.order_no+"' checked='checked' /></li><li class='one'>"+cflist[i].attr.order_no+"</li><li class='two'>"+cflist[i].attr.ordered_by+"</li><li class='thr'>"+cflist[i].attr.order_charge+"</li><li class='four'>"+cflist[i].sub[0].order_date+"</li><li class='five z1'>展开</li><table class='detail' style='display:none;'><tr><th>序号</th><th>名称</th><th>数量</th><th>单价</th><th>单位</th><th>费用</th></tr>"+sub_html+"</table></ul>";
										}
									
										$(".chufang_list").html(html);
										//$("#info").html("您一共有"+data.datarow.length+"个处方需要处理");
										$(".btn_area").show();
										$("#confirm").css({"visibility":"visible"})
									}else{
										//$(".yb_op_area .tips").html(data['result']['execute_message']);
										$(".mtnum2").hide();
										$(".chufang_area").show();
										$(".chufang_list h4").text(data['result']['execute_message']);
										/*if(data['result']['execute_message']=="有就诊未缴挂号费记录,请先补缴挂号费!"){
                                    	var ts="预约挂号-->选择卡类型-->补挂号费";
                                   	 	$(".chufang_list h4").html(data['result']['execute_message']);
                                    	$(".chufang_list h3").html(ts);
                               	 		}else{
                                     	$(".chufang_list h4").text(data['result']['execute_message']);
                               	 		}*/
										$(".btn_area").show();
										$("#confirm").css({"visibility":"hidden"})
									}
								}else{
									//$(".yb_op_area .tips").html(data['result']['execute_message']);
									$(".mtnum2").hide();
									$(".chufang_area").show();
									if(data['result']['execute_message']=="有就诊未缴挂号费记录,请先补缴挂号费!"){
                                    $(".btn_bu").show();
									$("#bugua").css({"visibility":"visible"});
                                    $(".chufang_list h4").html(data['result']['execute_message']);
                                   
                               		 }else{
                                     $(".chufang_list h4").text(data['result']['execute_message']);
                               		 }
                               		
									//$(".chufang_list h4").text(data['result']['execute_message']);
									$("#buhao_rukou").val("自助缴费");
									$(".btn_area").show();
									$("#confirm").css({"visibility":"hidden"});
									
									$("#response_type").val(data['yb_input_data']['response_type']);
									$("#jz_name").text(data['yb_input_data']['name']);
									$("#pat_code").val(data['yb_input_data']['patient_id']); 
									$("#card_no").val(data['yb_input_data']['card_no']);
									$("#card_code").val(data['yb_input_data']['card_code']);
									$("#pat_name").val(data['yb_input_data']['name']);
									/*$("#pay_seq").val(data['data']['pay_seq']);*/
									$("#yb_flag").val(data['yb_input_data']['yb_flag']);
									$("#personcount").val(data['yb_input_data']['personcount']);
								}
								
								
							}
						})		
					
					}
					else{
						$(".yb_op_area .tips").html("未知错误");
					}
				},2000);
				
				
			}else{
				
				$(".yb_op_area .tips").html('医保读卡器初始化失败！'); 
				window.external.MoveOutCard();
				$(".btn_area").show();
				$("#op_now").val("chose_yb_card");
				/*$("#confirm").css({"visibility":"hidden"});
				$("#fanhui").css({"visibility":"visible"});
				$("#tuichu").css({"visibility":"visible"});*/
			}
		}, 500);
		
		
		
		
		
		break;
	/*********获取医保患者就诊信息结束************************************************************************************************/
	/*********自助挂号获取医保患者就诊信息开始************************************************************************************************/
	case "chose_yb_card_guahao":
	    window.external.send(1,4);
		daojishi(100);
		$(".yb_op_area_guahao .tips_guahao").html('医保患者数据读取中,请稍后...');
		var flag;  
		var patinfo;
		$("#op_now").val("chose_room");
		//$("#confirm").css({"visibility":"hidden"});
		$(".btn_area").hide();
		setTimeout(function(){
			flag = window.external.InitYbIntfDll();		
			if(flag==0){
				writeLog("开始读取医保读卡器，医保读卡器初始化成功","INFO"); 
				//调用医保dll获取患者医保卡信息
				patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
				//alert(patinfo);
				setTimeout(function(){
					if(patinfo!=""){
						var params = {"input_xml":patinfo,"op_code":$("#op_code").val()}; 
						$.ajax({
							url:"/hddevice/mz/index.php/ZiZhu/Index/YbXmlParseGhao", 
							type:'post',
							dataType:'json',
							data:params,
							beforeSend:function(){
								$(".yb_op_area_guahao .tips_guahao").html('出诊科室查询中,请稍后...');
							},
							success:function(data){
								//医保读卡成功，给隐藏域赋值
								$(".mtnum2").hide();
								$(".chose_room").show();
								$(".btn_area").show();
								$("#confirm").css({"visibility":"hidden"});
								$("#fanhui").css({"visibility":"visible"});
								$("#tuichu").css({"visibility":"visible"});
								if(data['result']['execute_flag']==0){
									$("#guahao_name").text(data['yb_input_data']['name']);
									$("#pat_code").val(data['yb_input_data']['patient_id']); 
									$("#response_type").val(data['yb_input_data']['response_type']);
									//alert($("#response_type").val());
									$("#card_no").val(data['yb_input_data']['card_no']);
									$("#card_code").val(data['yb_input_data']['card_code']);
									$("#pat_name").val(data['yb_input_data']['name']);
									$("#yb_flag").val(data['yb_input_data']['yb_flag']);
									$("#personcount").val(data['yb_input_data']['personcount']);
									$("#pat_sex").val(data['yb_input_data']['sex_chn']);
									$("#pat_age").val(data['yb_input_data']['age']);
										if(data['room']['result']['execute_flag']==0)
										{
											var attr_html = "";
											var sub_html = "";
											var roomlist = data['room']['datarow'];
											//alert(roomlist[0]['attr']['class_name']);
											for(var i=0;i<roomlist.length;i++)
											{
												if(i==0)
												{
													attr_html+="<li class='current'>"+roomlist[i]['attr']['class_name']+"</li>";
												}else
												{
													 if(roomlist[i]['attr']['class_name']=="中医五官"){
                                           				 roomlist[i]['attr']['class_name']="中医五官康复";
                                           				attr_html+="<li style='font-size:15px'>"+roomlist[i]['attr']['class_name']+"</li>";
                                        				}else{
                                          				  attr_html+="<li>"+roomlist[i]['attr']['class_name']+"</li>";
                                        				}
												}
												//alert(roomlist[i]['attr']['class_name']);
												
												var sublist = roomlist[i]['sub'];
												var sub1_html = "";
												for(var m=0;m<sublist.length;m++)
												{
													//alert(sublist[m]["unit_name"]);
													sub1_html+="<span class='chose_unit' roomId="+sublist[m]["unit_sn"]+">"+sublist[m]["unit_name"]+"</span>";
												}
												if(i==0)
												{
													sub_html+="<div>"+sub1_html+"</div>";
												}else
													{
														sub_html+="<div style='display:none'>"+sub1_html+"</div>";
													}
												
											}
										   $("#attr_list").html(attr_html);
										   $("#sub_list").html(sub_html);
										}
								}else{

/*									$("#guahao_name").text(data['yb_input_data']['name']);
									$("#pat_code").val(data['yb_input_data']['patient_id']); 
									$("#response_type").val(data['yb_input_data']['response_type']);
									//alert($("#response_type").val());
									$("#card_no").val(data['yb_input_data']['card_no']);
									$("#card_code").val(data['yb_input_data']['card_code']);
									$("#pat_name").val(data['yb_input_data']['name']);
									$("#yb_flag").val(data['yb_input_data']['yb_flag']);
									$("#personcount").val(data['yb_input_data']['personcount']);
									$("#pat_sex").val(data['yb_input_data']['sex_chn']);
									$("#pat_age").val(data['yb_input_data']['age']);*/


									//$(".yb_op_area .tips").html(data['result']['execute_message']);
									$(".mtnum2").hide();
									$(".chose_room").show();
									
									if(data['result']['execute_message']=="有就诊未缴挂号费记录,请先补缴挂号费!"){
                                    
                                    $(".chose_room h4").html(data['result']['execute_message']);
                                    $(".btn_bu").show();
									$("#bugua").css({"visibility":"visible"});

									$("#guahao_name").text(data['yb_input_data']['name']);
									$("#pat_code").val(data['yb_input_data']['patient_id']); 
									$("#response_type").val(data['yb_input_data']['response_type']);
									//alert($("#response_type").val());
									$("#card_no").val(data['yb_input_data']['card_no']);
									$("#card_code").val(data['yb_input_data']['card_code']);
									$("#pat_name").val(data['yb_input_data']['name']);
									$("#yb_flag").val(data['yb_input_data']['yb_flag']);
									$("#personcount").val(data['yb_input_data']['personcount']);
									$("#pat_sex").val(data['yb_input_data']['sex_chn']);
									$("#pat_age").val(data['yb_input_data']['age']);
                                	}else{
                                    $(".chose_room h4").text(data['result']['execute_message']);
                               		 }
                               		$("#buhao_rukou").val("自助挂号");
									$("#card_code").val('20');
									$(".btn_area").show();
									$("#confirm").css({"visibility":"hidden"});
									
								    }
							}
						})		
					
					}
					else{
						$(".yb_op_area_guahao .tips").html("未知错误");
					}
				},2000);
				
				
			}else{
				
				$(".yb_op_area .tips").html('医保读卡器初始化失败！'); 
				window.external.MoveOutCard();
				$(".btn_area").show();
				$("#op_now").val("chose_yb_card");
				/*$("#confirm").css({"visibility":"hidden"});
				$("#fanhui").css({"visibility":"visible"});
				$("#tuichu").css({"visibility":"visible"});*/
			}
		}, 500);
		
		
		
		
		
		break;
	/*********自助挂号获取医保患者信息结束************************************************************************************************/
	case "yb_get_pat_chufang":
	//alert(11);
	/********医保缴费信息展示开始 医保划价******************************************************************/
	//window.external.KeyBoardComOpen();
	//window.external.KeyBoardStartInput();
		daojishi(120);
		var times_order_no = "";
		
		var index_load2="";
		$("input[name='times_order_no[]']:checked").each(function(i, n){	
				times_order_no += n.value+";";
		})		
		if(times_order_no==""){
			 layer.msg('请至少选择一项', {icon: 15,time:2000});
			 return;
		}
		$(".btn_area").hide();
		$("#times_order_no").val(times_order_no);
		var yb_input_param = $("#pat_code").val()+"&"+$("#card_code").val()+"&"+$("#card_no").val()+"&"+$("#times").val()+"&"+$("#pay_seq").val()+"&"+times_order_no+"&"+$("#zzj_id").val();
		//医保划价分解延迟执行1秒,解决页面卡顿问题 
		//$("#pay_confirm").show().text("医保划价分解中,请稍后...");
		//alert(yb_input_param);
		index_load2 = layer.msg('医保划价分解中,请稍后...', {icon: 16,time:20000});
		setTimeout(function(){
			var patinfo = window.external.YYT_YB_SF_CALC(yb_input_param);
			//alert(patinfo);
	        writeLog("划价成功");
			var params = {"input_xml":patinfo,"op_code":$("#op_code").val()}; 
				$.ajax({
					url:"/hddevice/mz/index.php/ZiZhu/Index/YbHalcParse", 
					type:'post',
					dataType:'json',
					data:params,
					success:function(data){
						writeLog("划价数据返回前台成功");
						layer.close(index_load2);
						if(data['result']['execute_flag']==0){
							$("#op_now").val("yb_pay_info_show");
							$("#cash").val(data['datarow']['cash']);
							$("#charge_total").val(data['datarow']['charge_total']);
							$("#zhzf").val(data['datarow']['zhzf']);
							$("#tczf").val(data['datarow']['tczf']);
							$("#pay_seq").val(data['datarow']['pay_seq']);
							$(".mtnum2").hide();
							$(".fenjie_result").show();
							$(".yb_txt").html("医保账户个人支付：");
							$(".btn_area").show();
							$(".fenjie_result .p1 .s2").text($("#card_no").val());
							$(".fenjie_result .p2 .uname").text($("#pat_name").val());
							$(".fenjie_result .p2 .sex").text($("#pat_sex").val());
							$(".fenjie_result .p3 .p_chare_totle").text(data['datarow']['charge_total']);
							$(".fenjie_result .p4 .p_cash").text(data['datarow']['cash']);
							$(".fenjie_result .p5 .p_zhzf").text(data['datarow']['zhzf']);
							$(".fenjie_result .p6 .p_tczf").text(data['datarow']['tczf']);
							$(".fenjie_result .p7 .yb_zh").text($("#personcount").val());
							
							//$("#pay_confirm").show().html("就诊卡号："+$("#card_no").val()+"<br>姓名："+$("#pat_name").val()+" 性别："+$("#pat_sex").val()+"<br>总金额："+data['datarow']['charge_total']+"<br>现金支付："+data['datarow']['cash']+"<br>医保个人账户支付："+data['datarow']['zhzf']+" 统筹支付："+data['datarow']['tczf']+"<br><input type='button' value='确认' id='pay_btn_confirm' onclick='getPayTypeArea()' />"); 
						}else{
							//layer.msg(data['result']['execute_message'], {icon: 14,time:2000});
							$(".chufang_list").html("<h4>"+data['result']['execute_message']+"</h4>");
							
							$(".btn_area").show();
							$("#confirm").css({"visibility":"hidden"});
							$("#fanhui").css({"visibility":"visible"});
							$("#tuichu").css({"visibility":"visible"});
						}
					
					}
				})
		},3000)
		//$(".yb_info").text(patinfo);
	/********医保缴费信息展示结束**************************************************************************/
	break;
	/*********医保缴费确认开始 进入支付方式界面*********************************************/
	case "yb_pay_info_show":
	if($("#cash").val()==0){
		writeLog("进入0元环节");
		//window.external.KeyBoardComClose();
		var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
		$("#stream_no").val(out_trade_no);
		$(".mtnum2").hide();
		$(".pay_success").show();
		$(".pay_success h3").html("费用确认中,请稍候...");
		paySuccessSendToHis($("#card_code").val());
	}else{
		writeLog("进入支付环节");
		//window.external.KeyBoardComClose();
		$("#op_now").val("pay_chose");
		$(".mtnum2").hide();
		$(".chose_pay_type_area").show();
		$(".btn_area").show();
		$("#confirm").css({"visibility":"hidden"});
		daojishi(100);
	}
	break;
	/*********银行卡输入密码开始*********************************************/
	case "zf_pay_bank":
	daojishi(120);
	$(".btn_area").hide();
	$("#op_now").val("write_bank_password");
	$(".mtnum2").hide();
	$(".bank_password").show();
	$("#confirm").css({"visibility":"hidden"});
	$(".PinField").focus();
	pin_flag=window.external.UMS_StartPin();
	if(pin_flag==0){
		PinProcess();
		}
	break;
	case "write_bank_password":
	$(".mtnum2").hide();
	$(".pay_success").show();
	$(".pay_success h3").html("系统处理中,请稍候...");
	
	setTimeout(function(){
		bank_str=window.external.Out_UMS_TransCard();
		//alert(bank_str);
		var arr = bank_str.split(",");
		$("#RespCode").val(arr[0]);
		//alert($("#RespCode").val());
		$("#RespInfo").val(arr[1]);
		$("#idCard").val(arr[2]);
		$("#Amount").val(arr[3]);
		$("#trade_no").val(arr[8]);
		$("#Batch").val(arr[5]);
		$("#TransDate").val(arr[6]);
		$("#TransTime").val[arr[7]];
		$("#Ref").val(arr[8]);
		$("#Auth").val(arr[9]);
		$("#Memo").val(arr[10]);
		$("#Lrc").val(arr[11]);
		//alert($("#RespCode").val());
		if(	$("#RespCode").val()==00)
		{
            pay_record_bank();
            writeLog("银联支付成功");
            $("#PayStatus").val("银联支付成功");
			paySuccessSendToHis($("#card_code").val());

			//pay_record_bank();
			//writeLog("银联支付成功");
			$("#PayStatus").val("银联支付成功");
		}else
		{
			if($("#RespCode").val()==55)
			{
				//pay_record_bank();
				$("#op_now").val("zf_pay_bank");
				$(".pay_success h3").html($("#RespCode").val()+$("#RespInfo").val());
				setTimeout(function()
							{
								var read_flag = window.external.Out_UMS_ReadCard();
								if(read_flag==0){
								$("#confirm").trigger("click");
								}
							},2000)
			}else
			{
				$(".pay_success h3").html($("#RespCode").val()+$("#RespInfo").val());
				setTimeout(function()
							{
									window.external.Out_UMS_EjectCard();
									$("#tuichu").trigger("click");
							},2000)
			}
			        		
		}
   },1000)
	break;
	case "zf_pay_bank_guahao":
	daojishi(120);
	$(".mtnum2").hide();
	$(".btn_area").hide();
	$("#op_now").val("write_bank_password_guahao");
	$(".mtnum2").hide();
	$(".bank_password_guahao").show();
	$("#confirm").css({"visibility":"hidden"});
	$(".PinField").focus();
	pin_flag=window.external.UMS_StartPin();
	if(pin_flag==0){
		PinProcessGh();
		}
	break;
	case "write_bank_password_guahao":
	$(".mtnum2").hide();
	$(".pay_success").show();
	$(".pay_success h3").html("系统处理中,请稍候...");
	//$(".btn_area").hide();
	setTimeout(function(){
		bank_str=window.external.Out_UMS_TransCard();
		//alert(bank_str);
		var arr = bank_str.split(",");
		$("#RespCode").val(arr[0]);
		//alert($("#RespCode").val());
		$("#RespInfo").val(arr[1]);
		$("#idCard").val(arr[2]);
		$("#Amount").val(arr[3]);
		$("#trade_no").val(arr[8]);
		$("#Batch").val(arr[5]);
		$("#TransDate").val(arr[6]);
		$("#TransTime").val[arr[7]];
		$("#Ref").val(arr[8]);
		$("#Auth").val(arr[9]);
		$("#Memo").val(arr[10]);
		$("#Lrc").val(arr[11]);
		if(	$("#RespCode").val()==00)
		{
			pay_record_bank();
            writeLog("银联支付成功");
            $("#PayStatus").val("银联支付成功");
			paySuccessSendToHis($("#card_code").val());
		}else
		{
			if($("#RespCode").val()==55)
			{
				//pay_record_bank();
				$("#op_now").val("zf_pay_bank_guahao");
				$(".pay_success h3").html($("#RespCode").val()+$("#RespInfo").val());
				setTimeout(function()
							{
								var read_flag = window.external.Out_UMS_ReadCard();
								if(read_flag==0){
								$("#confirm").trigger("click");
								}
							},2000)
			}else
			{
				$(".pay_success h3").html($("#RespCode").val()+$("#RespInfo").val());
				setTimeout(function()
							{
									window.external.Out_UMS_EjectCard();
									$("#tuichu").trigger("click");
							},2000)
			}
			        		
		}
   },1000)
	break;
	case "zf_pay_bank_quhao":
    daojishi(120);
    $("#op_now").val("write_bank_password_quhao");
    $(".mtnum2").hide();
    $(".bank_password_quhao").show();
    $("#confirm").css({"visibility":"hidden"});
    $("#PinFieldBu").focus();
    pin_flag=window.external.UMS_StartPin();
    if(pin_flag==0){
        PinProcessBu();
        }
    break;
    case "write_bank_password_quhao":
    $(".mtnum2").hide();
    $(".pay_success").show();
    $(".pay_success h3").html("系统处理中,请稍候...");
    setTimeout(function(){
        bank_str=window.external.Out_UMS_TransCard();
        //alert(bank_str);
        var arr = bank_str.split(",");
        $("#RespCode").val(arr[0]);
        //alert($("#RespCode").val());
        $("#RespInfo").val(arr[1]);
        $("#idCard").val(arr[2]);
        $("#Amount").val(arr[3]);
        $("#trade_no").val(arr[8]);
        $("#Batch").val(arr[5]);
        $("#TransDate").val(arr[6]);
        $("#TransTime").val[arr[7]];
        $("#Ref").val(arr[8]);
        $("#Auth").val(arr[9]);
        $("#Memo").val(arr[10]);
        $("#Lrc").val(arr[11]);
        if( $("#RespCode").val()==00)
        {
            pay_record_bank();
            writeLog("银联支付成功");
            $("#PayStatus").val("银联支付成功");
            paySuccessSendToHis($("#card_code").val());
        }else
        {
            if($("#RespCode").val()==55)
            {
                //pay_record_bank();
                $("#op_now").val("zf_pay_bank_quhao");
                $(".pay_success h3").html($("#RespCode").val()+$("#RespInfo").val());
                setTimeout(function()
                            {
                                var read_flag = window.external.Out_UMS_ReadCard();
                                if(read_flag==0){
                                $("#confirm").trigger("click");
                                }
                            },2000)
            }else
            {
                $(".pay_success h3").html($("#RespCode").val()+$("#RespInfo").val());
                setTimeout(function()
                            {
                                    window.external.Out_UMS_EjectCard();
                                    $("#tuichu").trigger("click");
                            },2000)
            }
                            
        }
   },1000)
    break;
    case "zf_pay_bank_quhao1":
    daojishi(120);
    $("#op_now").val("write_bank_password_quhao1");
    $(".mtnum2").hide();
    $(".bank_password_quhao1").show();
    $("#confirm").css({"visibility":"hidden"});
    $("#PinFieldBu1").focus();
    pin_flag=window.external.UMS_StartPin();
    if(pin_flag==0){
        PinProcessBu1();
        }
    break;
    case "write_bank_password_quhao1":
    $(".mtnum2").hide();
    $(".pay_success").show();
    $(".pay_success h3").html("系统处理中,请稍候...");
    setTimeout(function(){
        bank_str=window.external.Out_UMS_TransCard();
        //alert(bank_str);
        var arr = bank_str.split(",");
        $("#RespCode").val(arr[0]);
        //alert($("#RespCode").val());
        $("#RespInfo").val(arr[1]);
        $("#idCard").val(arr[2]);
        $("#Amount").val(arr[3]);
        $("#trade_no").val(arr[8]);
        $("#Batch").val(arr[5]);
        $("#TransDate").val(arr[6]);
        $("#TransTime").val[arr[7]];
        $("#Ref").val(arr[8]);
        $("#Auth").val(arr[9]);
        $("#Memo").val(arr[10]);
        $("#Lrc").val(arr[11]);
        if( $("#RespCode").val()==00)
        {
            pay_record_bank();
            writeLog("银联支付成功");
            $("#PayStatus").val("银联支付成功");
            paySuccessSendToHis($("#card_code").val());
        }else
        {
            if($("#RespCode").val()==55)
            {
                //pay_record_bank();
                $("#op_now").val("zf_pay_bank_quhao1");
                $(".pay_success h3").html($("#RespCode").val()+$("#RespInfo").val());
                setTimeout(function()
                            {
                                var read_flag = window.external.Out_UMS_ReadCard();
                                if(read_flag==0){
                                $("#confirm").trigger("click");
                                }
                            },2000)
            }else
            {
                $(".pay_success h3").html($("#RespCode").val()+$("#RespInfo").val());
                setTimeout(function()
                            {
                                    window.external.Out_UMS_EjectCard();
                                    $("#tuichu").trigger("click");
                            },2000)
            }
                            
        }
   },1000)
    break;
	default:
	return false;
	break;
	}
	return false;
})
//选择支付宝支付 
$("#pay_zhifubao").on("click",function(){
	/*alert("网络问题，支付宝暂停使用付宝暂停使用");
	return;*/
	type_times=1;
	$("#op_now").val("zf_pay_zhifubao");
	$("#pay_type").val("alipay");
	$(".mtnum2").hide();
	$(".alipay_ma_show").show();
	$(".btn_area").hide();
	//$("#confirm").css({"visibility":"hidden"})
	daojishi(300);
	var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
	$("#stream_no").val(out_trade_no);
	var total_amount = $("#cash").val();
		if($("#business_type").val()=="补挂号费"){
    var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#pat_name").val()+"补挂号费"}; 
	}else{
	var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#pat_name").val()+"自助缴费"};
	}
	 	 
 	//var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#pat_name").val()+"自助缴费"}; 
	var index_load = "";
	$.ajax({
		url:"/hddevice/mz/index.php/ZiZhu/Index/getAliPayCode", 
		type:'post',
		dataType:'json',
		data:params,
		beforeSend:function(){
			index_load = layer.msg('支付二维码请求中,请稍候...', {icon: 16,time:20000});
		},
		success:function(data){
			layer.close(index_load);
			$("#stream_no").val(out_trade_no);
			$(".alipay_ma_show .pay_val").text("￥"+$("#cash").val());
			$(".alipay_ma_show .erweima").html("<img src='http://172.168.0.89"+data['message']['imgurl']+"' width='240' />");
			
			s1 = setInterval(function(){
				getResult(out_trade_no);							 
			},5000); 
			interval3.push(s1);
			//$("#dingshi").val(s1);
			
		}
	})
	
})
//自助缴费选择银行卡支付
$("#pay_bank").on("click",function(){

	$(".mtnum2").hide();
	$(".bank").show();
	$(".btn_area").hide();
	//$("#confirm").css({"visibility":"hidden"});
	//$("#fanhui").css({"visibility":"hidden"})
	window.external.send(4,2)
	$("#op_now").val("zf_pay_bank");
	$("#pay_type").val("zf_bank");
	daojishi(120);
	var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
	$("#stream_no").val(out_trade_no);
	var total_amount = cash($("#cash").val());
	
	//初始化
	//alert(total_amount);
	var flag = window.external.Out_UMS_Init();
	//alert(flag);
	if(flag==0){
		
		//alert("00"+$("#zzj_id").val()+","+"00"+$("#zzj_id").val()+","+$("#tansType").val()+","+total_amount+","+"666666"+","+"88888888"+","+"111111111111"+","+"222222"+","+"777777"+","+""+","+"999");
		var req_flag =window.external.Out_UMS_SetReq("00000001","00000002","00",total_amount,"666666","88888888","111111111111","222222","777777","20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020","999");
		if(req_flag==0){
		window.external.Out_UMS_EnterCard(); 
		//检查卡
		var check_flag=false;
		interval = setInterval(function(){
			check_flag =window.external.Out_UMS_CheckCard();
			if(check_flag==0){
				window.external.send(1,4);
				clearInterval(interval);
				$(".bank .tips").html("系统处理中，请稍候....");	
				setTimeout(function(){
					var read_flag = window.external.Out_UMS_ReadCard();
					if(read_flag==0){
						$("#confirm").trigger("click");
					}else{
						setTimeout(function(){
						window.external.Out_UMS_EjectCard();
						window.external.Out_UMS_CardClose();
						$("#tuichu").trigger("click");
											},3000)
						}
				},1000)
				
			}
		}, "1000");	
		}
		}else{
			$(".bank tips").html("初始化读卡器失败!");
			}
	//UMS_CheckCard();
    //UMS_ReadCard();
})
//自助挂号选择银行卡支付
$("#pay_bank_guahao").on("click",function(){
	//alert($("#pat_name").val());
	$(".mtnum2").hide();
	$(".bank_guahao").show();
	$(".btn_area").hide();
	//$("#confirm").css({"visibility":"hidden"});
	//$("#fanhui").css({"visibility":"hidden"})
	writeLog("选择了银行卡");
	window.external.send(4,2);
	$("#op_now").val("zf_pay_bank_guahao");
	$("#pay_type").val("zf_bank");
	daojishi(120);
	var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
	$("#stream_no").val(out_trade_no);
	var total_amount = cash($("#cash").val());
	
	//初始化
	var flag = window.external.Out_UMS_Init();
	//alert(total_amount);
	//alert(flag);
	if(flag==0){
		//允许进卡
		//alert("00"+$("#zzj_id").val()+","+"00"+$("#zzj_id").val()+","+$("#tansType").val()+","+total_amount+","+"666666"+","+"88888888"+","+"111111111111"+","+"222222"+","+"777777"+","+""+","+"999");
		var req_flag =window.external.Out_UMS_SetReq("00000001","00000002","00",total_amount,"666666","88888888","111111111111","222222","777777","20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020","999");
		if(req_flag==0){
		window.external.Out_UMS_EnterCard(); 
		//检查卡
		var check_flag=false;
		interval = setInterval(function(){
			check_flag =window.external.Out_UMS_CheckCard();
			if(check_flag==0){
				clearInterval(interval);
				$(".bank_guahao .tips").html("系统处理中，请稍候....");	
				setTimeout(function(){
					var read_flag = window.external.Out_UMS_ReadCard();
					if(read_flag==0){
						$("#confirm").trigger("click");
					}else{
						setTimeout(function(){
						window.external.Out_UMS_EjectCard();
						window.external.Out_UMS_CardClose();
						$("#tuichu").trigger("click");
											},3000)
						}
				},1000)
				
			}
		}, "1000");	
		}
		}else{
			$(".bank tips").html("初始化读卡器失败!");
			}
	//UMS_CheckCard();
    //UMS_ReadCard();
})
//自助挂号选择支付宝支付
$("#pay_zhifubao_guahao").on("click",function(){
	/*alert("网络问题，支付宝暂停使用付宝暂停使用");
	return;*/
	type_times=1;
	$(".mtnum2").hide();
	$(".alipay_ma_show_guahao").show();
	$(".btn_area").hide();
	//$("#confirm").css({"visibility":"hidden"})
	$(".alipay_ma_show_guahao .erweima").html("");								  
	$("#op_now").val("zf_pay_zhifubao_guahao");
	$("#pay_type").val("alipay");
	daojishi(300);
	var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
	$("#stream_no").val(out_trade_no);
	var total_amount = $("#cash").val();
	if($("#business_type").val()=="补挂号费"){
    var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#pat_name").val()+"补挂号费"}; 
	}else{
	var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#pat_name").val()+"自助挂号"};
	}
	 	 
	var index_load = "";
	$.ajax({
		url:"/hddevice/mz/index.php/ZiZhu/Index/getAliPayCode", 
		type:'post',
		dataType:'json',
		data:params,
		beforeSend:function(){
			index_load = layer.msg('支付二维码请求中,请稍候...', {icon: 16,time:20000});
		},
		success:function(data){
			//alert($("#stream_no").val());
			//alert(data['message']['imgurl']);
			layer.close(index_load);
			$("#stream_no").val(out_trade_no);
			$(".alipay_ma_show_guahao .pay_val").text("￥"+$("#cash").val());
			$(".alipay_ma_show_guahao .erweima").html("<img src='http://172.168.0.89"+data['message']['imgurl']+"' width='240' />");
			s1 = setInterval(function(){
				getResult(out_trade_no);							 
			},5000); 
			interval3.push(s1);
			//$("#dingshi").val(s1);
			
		}
	})
	
})




$("#bugua").on("click",function(){
	 writeLog("选择了补挂号费");
    $("#business_type").val("补挂号费");
    $(".btn_bu").hide();
	$("#bugua").css({"visibility":"hidden"});
/*  alert($("#card_no").val());
  alert($("#pat_code").val());
  alert($("#op_code").val());
  alert($("#zzj_id").val());
  alert($("#card_code").val());*/
   switch($("#card_code").val()){
    case "21":
    //alert($("#buhao_rukou").val());
    var params = {"patient_id":$("#pat_code").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"card_no":$("#card_no").val()};
     var index_load = "";
     $.ajax({
        url:"/hddevice/mz/index.php/ZiZhu/YuYue/getPatReservationRecord", 
            type:'post',
            dataType:'json',
            data:params,
            beforSend:function(){
                index_load = layer.msg('预约记录信息查询中,请稍候...', {icon: 16,time:20000});
                $("#fanhui").css({"visibility":"hidden"});
                $("#confirm").css({"visibility":"hidden"});
                $("#tuichu").css({"visibility":"hidden"});
            },
            success:function(data){
             /*  alert(data['result']['execute_flag']);
               alert($("#patient_id").val());*/
              //alert(data['datarow']['req_type']);
                layer.close(index_load);
                $(".mtnum2").hide();
                $(".yuyue_record2").show();
                $("#op_now").val("chose_yuyue_record");
                $("#fanhui").css({"visibility":"visible"});
                $("#tuichu").css({"visibility":"visible"});
                $("#confirm").css({"visibility":"hidden"});
                var a="";
                 if(data['result']['execute_flag']==0){
                    $("#jz_name1").html($("#pat_name").val());
                    $("#jz_name2").html($("#pat_name").val());
                     /*alert($("#pat_name").val());
                    alert($("#jz_name1").html());*/
                    var html="";
                    var yuyue_list= data["datarow"];
                    for(var i=0;i<yuyue_list.length;i++){
                     var m=i+1;
                 html+="<ul><li class='two'>"+m+"</li><li class='thr'>"+yuyue_list[i]["unit_name"]+"</li><li class='four'>"+yuyue_list[i]["charge"]+"</li><li class='six'>"+yuyue_list[i]["request_date"].replace("T"," ").substring(0,10) +"</li><li class='seven sure_quhao' record_sn="+yuyue_list[i]["register_sn"]+" req_type="+yuyue_list[i]["req_type"]+" unit_name="+yuyue_list[i]["unit_name"]+" doctor_name="+yuyue_list[i]["doctor_name"]+">补挂号费</li></ul>";
                 }
                 //alert(html);
                    $(".chufang_list1").html(html);
                   
                    $("#pat_code").val(data['datarow'][0]['patient_id']); 
                    //$("#card_no").val(data['datarow'][0]['card_no']);
                   // $("#pat_name").val(data['datarow'][0]['name']);
                    //$("#pat_sex").val(data['datarow'][0]['sex']);
                     // alert(data['datarow'][0]['doctor_name']);
                    $("#response_type").val(data['datarow'][0]['response_type']);
                    $("#reponse_name").val(data['datarow'][0]['response_chn']);
                    $("#social_no").val(data['datarow'][0]['social_no']);
                    $("#mobile").val(data['datarow'][0]['mobile']);
                    $("#doctor_name").val(data['datarow'][0]['doctor_name']);
                 }else{
                    //alert($("#message").val());
                    a+=$("#message").val()+"<br/>";
                    a+=data['result']['execute_message'];
                     $(".yuyue_record2 .tips").html(a);
                 }
            }
     })
     break;
     case "20":
   //  alert(111);
     var params = {"patient_id":$("#pat_code").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"card_no":$("#card_no").val()};
     var index_load = "";
     $.ajax({
            url:"/hddevice/mz/index.php/ZiZhu/YuYue/getPatReservationRecord", 
            type:'post',
            dataType:'json',
            data:params,
            beforSend:function(){
                index_load = layer.msg('预约记录信息查询中,请稍候...', {icon: 16,time:20000});
                $("#fanhui").css({"visibility":"hidden"});
                $("#confirm").css({"visibility":"hidden"});
                $("#tuichu").css({"visibility":"hidden"});
            },
            success:function(data){
              //  alert(111);
               // alert(data['result']['execute_flag']);
                layer.close(index_load);
                $(".mtnum2").hide();
                $(".yuyue_record2").show();
                $("#op_now").val("chose_yuyue_record");
                $("#fanhui").css({"visibility":"visible"});
                $("#tuichu").css({"visibility":"visible"});
                $("#confirm").css({"visibility":"hidden"});
                 if(data['result']['execute_flag']==0){
                    $("#jz_name1").html($("#pat_name").val());
                    $("#jz_name2").html($("#pat_name").val());
                    $("#pat_code").val(data['datarow'][0]['patient_id']); 
                    //$("#card_no").val(data['datarow'][0]['card_no']);
                    //$("#pat_name").val(data['datarow'][0]['name']);
                    //$("#pat_sex").val(data['datarow'][0]['sex']);
                    $("#response_type").val(data['datarow'][0]['response_type']);
                    $("#reponse_name").val(data['datarow'][0]['response_chn']);
                    $("#social_no").val(data['datarow'][0]['social_no']);
                    $("#mobile").val(data['datarow'][0]['mobile']);

                    $("#doctor_name").val(data['datarow'][0]['doctor_name']);
                 var html="";
                 var yuyue_list= data["datarow"];
                 //alert(yuyue_list[0]["gh_sequence"]);
                 for(var i=0;i<yuyue_list.length;i++){
                    var m=i+1;
                 html+="<ul><li class='two'>"+m+"</li><li class='thr'>"+yuyue_list[i]["unit_name"]+"</li><li class='four'>"+yuyue_list[i]["charge"]+"</li><li class='six'>"+yuyue_list[i]["request_date"].replace("T"," ").substring(0,10) +"</li><li class='seven sure_quhao' record_sn="+yuyue_list[i]["register_sn"]+" req_type="+yuyue_list[i]["req_type"]+" unit_name="+yuyue_list[i]["unit_name"]+" gh_sequence="+yuyue_list[i]["gh_sequence"]+" unit_sn="+yuyue_list[i]["unit_sn"]+">补挂号费</li></ul>";
                 }
                $(".chufang_list1").html(html);
                 }else{
                    var a;
                   
                     a+=$("#message").val()+"<br/>";
                     a+=data['result']['execute_message'];

                     // alert($("#message").val());
                    // alert(a);
                     $(".yuyue_record2 .tips").html(a);
                    // $(".yuyue_record2 .tips").html($("#message").val());
                 }
            }
     })
     break;
 }
})



$(document).on("click",".sure_quhao",function(){
    

  $("#business_type").val("补挂号费");
     writeLog("选择了补挂号费");
    //alert($("#card_code").val());
     switch($("#card_code").val()){
        case "21":
        var record_sn=$(this).attr("record_sn");
        var req_type=$(this).attr("req_type");
        var unit_name=$(this).attr("unit_name");

        
        daojishi(100);
        var index_load="";
        var params = {"record_sn":record_sn,"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"req_type":req_type};
        $.ajax({
                    url:"/hddevice/mz/index.php/ZiZhu/YuYue/quhao_huajia", 
                    type:'post',
                    dataType:'json',
                    data:params,
       
            beforeSend:function(){
                        index_load = layer.msg('数据请求中,请稍后...', {icon: 16,time:20000});
                        $("#confirm").css({"visibility":"hidden"});
                        $("#tuichu").css({"visibility":"hidden"});
                        $("#fanhui").css({"visibility":"hidden"});
                    },
            success:function(data){
                //alert(333);
               layer.close(index_load);
               if(data['result']['execute_flag']==0){
               
                           
                            $("#confirm").css({"visibility":"visible"});
                            $("#tuichu").css({"visibility":"visible"});
                            $("#fanhui").css({"visibility":"visible"});
                            $("#record_sn").val(record_sn);
                            //alert($("#record_sn").val());
                            $("#patient_id").val(data['datarow']['patient_id']);
                            $("#cash").val(data['datarow']['cash']);
                            $("#charge_total").val(data['datarow']['charge_total']);
                            $("#zhzf").val(data['datarow']['zhzf']);
                            $("#tczf").val(data['datarow']['tczf']);
                            $("#pay_seq").val(data['datarow']['pay_seq']);
                            $(".mtnum2").hide();
                            $(".result_quhao").show();
                            $("#op_now").val("ic_pay_info_show_quhao")
                            $(".yb_txt").html("医院垫付：");
                            /******************修改就诊卡号为患者id*********************/
                            $(".result_quhao .p1 .s2").text($("#patient_id").val());
                            /*********************************/
                            $(".result_quhao .p2 .uname").text($("#pat_name").val());
                            $(".result_quhao .p2 .sex").text($("#pat_sex").val());
                            $(".result_quhao .p3 .p_chare_totle").text(data['datarow']['charge_total']);
                            $(".result_quhao .p4 .p_cash").text(data['datarow']['cash']);
                            $(".result_quhao .p5 .p_zhzf").text(data['datarow']['zhzf']);
                            $(".result_quhao .p5 .p_tczf").text(data['datarow']['tczf']);
                            $(".result_quhao .p6 .p_cz_room").text(unit_name);
                            $(".result_quhao .p6 .p_cz_doctor").text($("#doctor_name").val());
                        }else{
                            layer.msg(data['result']['execute_message'], {icon: 14,time:2000});
                        }
           }
      })
      break;
      case '20':
        var record_sn=$(this).attr("record_sn");
        var gh_sequence=$(this).attr("gh_sequence");
        //salert(gh_sequence);
        var record_sn_se = record_sn+"_"+gh_sequence;
        var req_type=$(this).attr("req_type");
        var unit_name=$(this).attr("unit_name");

        var unit_sn=$(this).attr("unit_sn");
        //alert(unit_sn);
         if(unit_sn == "1300000" || unit_sn == "0402000" || unit_sn == "0401002"){
        if($("#card_code").val() == 20){
           /* alert("对不起，本科室只允许自费挂号！请使用就诊卡或身份证进行挂号缴费！");*/
           sendMsg();
            return;
        }

    }


        
        var gh_flag="3";
       // $("#gh_flag").val("3");
        daojishi(100);
        var index_load="";
/*        alert(record_sn_se);
        alert($("#pat_code").val());
        alert($("#card_code").val());
        alert($("#card_no").val());
        alert($("#response_type").val());
        alert($("#zzj_id").val());
        alert(gh_flag);*/

        var yb_input_param = record_sn_se+"&"+$("#pat_code").val()+"&"+$("#card_code").val()+"&"+$("#card_no").val()+"&"+$("#response_type").val()+"&"+$("#zzj_id").val()+"&"+gh_flag;
        //alert(yb_input_param);
        index_load2 = layer.msg('医保划价分解中,请稍后...', {icon: 16,time:20000});
        setTimeout(function(){
                                    //调用本地医保dll划价     
                                var patinfo = window.external.YYT_YB_GH_CALC(yb_input_param);
                                //alert(patinfo);
                                var params = {"input_xml":patinfo,"op_code":$("#op_code").val()}; 
                                    //讲划价信息传递到后台解析
                                    $.ajax({
                                        url:"/hddevice/mz/index.php/ZiZhu/YuYue/YbHalcParseGhao", 
                                        type:'post',
                                        dataType:'json',
                                        data:params,
                                        success:function(data){
                                            layer.close(index_load2);
                                            if(data['result']['execute_flag']==0){
                                               $("#record_sn").val(record_sn);
                                                $("#patient_id").val(data['datarow']['patient_id']);
                                                $("#cash").val(data['datarow']['cash']);
                                                $("#charge_total").val(data['datarow']['charge_total']);
                                                $("#zhzf").val(data['datarow']['zhzf']);
                                                $("#tczf").val(data['datarow']['tczf']);
                                                $("#pay_seq").val(data['datarow']['pay_seq']);
                                                $("#btn_area").show();
                                                $("#confirm").css({"visibility":"visible"});
                                                $(".mtnum2").hide();
                                                $(".result_quhao").show();
                                                 $("#op_now").val("ic_pay_info_show_quhao")
                                                $(".yb_txt").html("医保个人账户支付：");
                                                /******************修改就诊卡号为患者id*********************/
                                                $(".result_quhao .p1 .s2").text($("#patient_id").val());
                                                /*********************************/
                                                $(".result_quhao .p2 .uname").text($("#pat_name").val());
                                                $(".result_quhao .p2 .sex").text($("#pat_sex").val());
                                                $(".result_quhao .p3 .p_chare_totle").text(data['datarow']['charge_total']);
                                                $(".result_quhao .p4 .p_cash").text(data['datarow']['cash']);
                                                $(".result_quhao .p5 .p_zhzf").text(data['datarow']['zhzf']);
                                                $(".result_quhao .p5 .p_tczf").text(data['datarow']['tczf']);
                                                $(".result_quhao .p6 .p_cz_room").text(unit_name);
                                                $(".result_quhao .p6 .p_cz_doctor").text($("#doctor_name").val());
                                                $(".result_quhao .p7 .yb_zh").text($("#personcount").val());
                                            }else{
                                                //layer.msg(data['result']['execute_message'], {icon: 14,time:2000});
                                                $(".chufang_list").html("<h4>"+data['result']['execute_message']+"</h4>")
                                                $(".btn_area").show();
                                                $("#confirm").css({"visibility":"visible"});
                                                $("#fanhui").css({"visibility":"visible"});
                                                $("#tuichu").css({"visibility":"visible"});
                                            }
                                        
                                        }
                                    })
                            },1000);
      break;
    }
    
    
})



/******
**返回按钮功能处理
****/
$("#fanhui").on("click",function(){
//	alert($("#op_now").val());
	switch($("#op_now").val()){
	case "choose_card":
	$("#op_now").val("");
	$("#jz_card_no").val("");
	$(".mtnum2").hide();
	$(".main_left_1").show();
	$(".main_right_1").show();
	$(".btn_area").hide();
	//window.external.FreeYBIntfDll();
	for(var key in interval3){
		clearInterval(interval3[key]);
	}
	for(var key in interval2){
		clearTimeout(interval2[key]);
	}
	$("#downcount").html("");
	break;
	/***从就诊卡读卡界面回到卡种类选择界面*************************/
	case "ic_get_pat_info":
	window.external.send(1,4);
	$("#jz_card_no_sfz").val("");
	$("#jz_card_no").val("");
	$("#op_now").val("choose_card");
	$(".mtnum2").hide();
	$("#confirm").css({"visibility":"hidden"});
	$(".chose_card_type_area").show();
	key_value="";
	key_value2="";
	//window.external.keybord_close();
	daojishi(30);
	
	break;
	/***从自费处方列表页回到就诊卡读卡界面***************************/
	case "ic_pay_chufang_show":
	//添加了患者的姓名
	$("#jz_card_no").val("");
	$("#jz_card_no_sfz").val("");
	$("#jz_name").html("");
	$("#confirm").css({"visibility":"visible"});
	$("#op_now").val("choose_card");
	//$(".chufang_list").html("<h4></h4>");
	//$(".chufang_list").html("<h3></h3>");
	$(".mtnum2").hide();
	$(".chose_card_type_area").show();
	$(".btn_bu").hide();
	$("#bugua").css({"visibility":"hidden"});
	$(".chufang_list h4").html("");
	$(".chufang_list h3").html("");
	/*interval = setInterval(getCardNo, "1000");
	interval3.push(interval);
	$("#jz_card_no").val("");
	$("#jz_card_no").focus();
	key_value="";
	key_value2="";
	
	jzk_flag=window.external.InitIC();
	//window.external.nTextInput(); 
	
	if(jzk_flag>0){
		$(".jiuzhen_op_area .tips").text("初始化成功");
		
		interval = setInterval(getCardNo, "1000");
		interval3.push(interval);
		
	}else{
		$(".jiuzhen_op_area .tips").text("初始化失败");
	}*/
	daojishi(60);
	break;
	/***从自费缴费确认展示回到自费处方列表****************/
	case "ic_pay_info_show":
	$(".mtnum2").hide();
	$("#op_now").val("ic_pay_chufang_show");
	$(".chufang_area").show();
	daojishi(240);
	
	break;
	/*******从医保读卡界面退回到卡种类选择界面****************/
	case "chose_yb_card":
	window.external.send(1,4);
	$("#op_now").val("choose_card");
	$(".mtnum2").hide();
	$("#confirm").css({"visibility":"hidden"});
	$(".chose_card_type_area").show();
	window.external.MoveOutCard();
	window.external.DisAllowCardIn();
	window.external.FreeYBIntfDll();
	daojishi(30);
	
	break;
	/*******自助挂号从医保读卡界面退回到卡种类选择界面****************/
	case "chose_yb_card_guahao":
	window.external.send(1,4);
	$("#op_now").val("choose_card_guahao");
	$(".mtnum2").hide();
	$("#confirm").css({"visibility":"hidden"});
	$(".chose_card_type_area_guahao").show();
	window.external.MoveOutCard();
	window.external.DisAllowCardIn();
	window.external.FreeYBIntfDll();
	daojishi(30)
	break;
	/***从医保患者处方界面回到医保读卡界面***********************************************/
	case "yb_get_pat_chufang":
	$(".mtnum2").hide();
	$(".yb_op_area").show();
	$("#op_now").val("chose_yb_card");
	$("#confirm").css({"visibility":"hidden"});
	$(".btn_bu").hide();
	$("#bugua").css({"visibility":"hidden"});
	$(".yb_op_area .tips").html('');
	daojishi(60);
	break;
	/***从医保费用明示界面回到医保处方界面************************/
	case "yb_pay_info_show":
	$(".mtnum2").hide();
	$(".chufang_area").show();
	$("#op_now").val("yb_get_pat_chufang");
	daojishi(240);
	break;
	/****从选择支付方式界面回到费用明示界面***********************/
	case "pay_chose":
		$(".mtnum2").hide();
		$("#confirm").css({"visibility":"visible"});
		switch($("#card_code").val()){
		case "20":
		$("#op_now").val("yb_pay_info_show");
		break;
		case "21":
		$("#op_now").val("ic_pay_info_show");
		break;
		}
		$(".fenjie_result").show();
		for(var key in interval3){
			clearInterval(interval3[key]);
		}
		daojishi(60);
	break;
	/****从选择支付方式界面回到费用明示界面***********************/
	case "pay_chose_guahao":
		$(".mtnum2").hide();
		$("#confirm").css({"visibility":"visible"});
		$("#op_now").val("ic_pay_info_show_guahao");
		$(".fenjie_result_guahao").show();
		for(var key in interval3){
			clearInterval(interval3[key]);
		}
		
		daojishi(60);
	break;
	/*********补挂号费支付选择返回到信息确认界面**************/
	case "pay_type_quhao":
			$(".mtnum2").hide();
         	$("#confirm").css({"visibility":"visible"});
            switch($("#card_code").val()){
            case "20":
            $("#op_now").val("ic_pay_info_show_quhao");
            break;
            case "21":
            $("#op_now").val("ic_pay_info_show_quhao");
             break;
            }
            $(".result_quhao").show();

         daojishi(60);

		break;

	/**********自助挂号选择业务类型返回主界面************/
	case "choose_card_guahao":
	$("#op_now").val();
	$(".main_left_1").show();
	$(".mtnum2").hide();
	$(".btn_area").hide();
	for(var key in interval3){
		clearInterval(interval3[key]);
	}
	for(var key in interval2){
		clearTimeout(interval2[key]);
	}
	$("#downcount").html("");
	break;
	/***自助挂号从就诊卡读卡界面回到卡种类选择界面*************************/
	case "ic_get_pat_info_guahao":
	window.external.send(1,4);
	$("#jz_card_no_guahao").val("");
	$("#jz_card_no_guahao_sfz").val("");
	$("#op_now").val("choose_card_guahao");
	$(".mtnum2").hide();
	$("#confirm").css({"visibility":"hidden"});
	$(".chose_card_type_area_guahao").show();
	key_value="";
	key_value2="";
	//window.external.keybord_close();
	daojishi(30);
	break;
	/***自助挂号从选择科室页面返回到就诊卡读卡页面****************/
	case "chose_room":
	$(".chose_room h4").html("");
	$(".chose_room h3").html("");
	switch($("#card_code").val()){
		case "20":
		window.external.send(1,4);
		$("#guahao_name").html("");
		$(".mtnum2").hide();
		$("#attr_list").html("");
		$("#sub_list").html("");
		$(".yb_op_area_guahao").show();
		$(".yb_op_area_guahao .tips_guahao").html('');
		$("#op_now").val("chose_yb_card_guahao");
		$(".btn_area").show();
		$(".btn_area ul li").css({"visibility":"visible"});
		$("#confirm").css({"visibility":"hidden"});
		$(".btn_bu").hide();
		$("#bugua").css({"visibility":"hidden"});
		//允许卡进
		window.external.AllowCardIn();
		var flag=false;
		interval = setInterval(function(){
			//读取医保卡状态
			flag = window.external.ReadStatus();	
			if(flag){
				$("#confirm").trigger("click");
				clearInterval(interval);
			}
		}, "1000");	
		
		interval3.push(interval);
		break;
		case "21":
		window.external.send(1,4);
		$("#guahao_name").html("");
		$("#confirm").css({"visibility":"visible"});
		$("#op_now").val("choose_card_guahao");
		$(".mtnum2").hide();
		$(".chose_card_type_area_guahao").show();
		$("#attr_list").html("");
		$("#sub_list").html("");
		$("#jz_card_no_guahao").val("");
		$("#jz_card_no_guahao_sfz").val("");
		$(".btn_bu").hide();
		$("#bugua").css({"visibility":"hidden"});
		key_value="";
		key_value2="";
		/*interval = setInterval(getCardNo, "1000");
		interval3.push(interval);
		$("#jz_card_no_guahao").val("");
		$("#jz_card_no_guahao").focus();
		key_value="";
		key_value2="";
		jzk_flag=window.external.InitIC();
		//window.external.nTextInput(); 
		if(jzk_flag>0){
			$(".jiuzhen_op_area_gahao .tips_guahao").text("初始化成功");
			
			interval = setInterval(getCardNo, "1000");
			interval3.push(interval);
			
		}else{
			$(".jiuzhen_op_area_guahao .tips_guahao").text("初始化失败");
		}*/
		break;
		}
	daojishi(60);
	break;
	/***自助挂号选择医生列返回选择科室表****************/
	case "chose_doctor":
	$(".mtnum2").hide();
	$("#keshi_name").html("");
	$("#op_now").val("chose_room");
	$(".chose_room").show();
	daojishi(120);
	break;
	/***从自助挂号缴费确认展示回到选择医生列表****************/
	case "ic_pay_info_show_guahao":
	$(".mtnum2").hide();
	$("#op_now").val("chose_doctor");
	$(".chose_doctor").show();
	daojishi(240);
	break;
	/****从挂号支付宝二维码界面回到支付选择界面************/
	case "zf_pay_zhifubao_guahao":
		$(".mtnum2").hide();
		$("#op_now").val("pay_chose_guahao");
		$(".chose_pay_type_area_guahao").show();
		daojishi(60);
		break;
	/****从支付宝二维码界面回到支付选择界面************/
	case "zf_pay_zhifubao":
		$(".mtnum2").hide();
		$("#op_now").val("pay_chose");
		$(".chose_pay_type_area").show();
		daojishi(60);
		break;

	case "zf_pay_bank_guahao":
		$(".mtnum2").hide();
		$("#op_now").val("pay_chose_guahao");
		$(".chose_pay_type_area_guahao").show();
		daojishi(60);
		break;
		/****从补挂号费列表界面返回补号提示界面************/	
	case "chose_yuyue_record":
	//alert($("#buhao_rukou").val());
		switch($("#buhao_rukou").val()){
			case "自助挂号":
				$(".mtnum2").hide();
				$(".yuyue_record2").hide();
				$("#keshi_name").html("");
				$("#op_now").val("chose_room");
				$(".chose_room").show();
		        $("#confirm").css({"visibility":"hidden"});
		        $(".chufang_list").html("");
		        $(".yuyue_record .tips").html("");
		       // $("#pat_code").val("");
		        daojishi(120);
			break;
			case "自助缴费":
				$(".mtnum2").hide();
				$("#op_now").val("ic_pay_chufang_show");
				$(".chufang_area").show();
				$(".chufang_list1").html("");
		        $(".yuyue_record .tips").html("");
				daojishi(240);
			break;
		
		}
		$(".btn_bu").show();
		$("#bugua").css({"visibility":"visible"});
	break;
	/****从费用信息界面返回补挂号费列表界面************/	
	case "ic_pay_info_show_quhao":
			$("#op_now").val("chose_yuyue_record");
	        $(".mtnum2").hide();
	        $(".yuyue_record2").show();
	        $("#confirm").css({"visibility":"hidden"});
	        daojishi(30);
	    

		break;

	}	

})
})




//算年纪
function   ages(str){  
        var   r   =   str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);    
        if(r==null)return   false;    
        var   d=   new   Date(r[1],   r[3]-1,   r[4]);    
        if   (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4])  
        {  
              var   Y   =   new   Date().getFullYear();  
              return(Y-r[1]);  
        }  
        return("输入的日期格式错误！");  
  }  
  

/**
*轮询调用查询接口是否支付成功
**/
function getResult(out_trade_no,s1){
	var params = {"out_trade_no":out_trade_no,"op_code":$("#op_code").val()};  
	$.ajax({
		url:"/hddevice/mz/index.php/ZiZhu/Index/ajaxGetPayStatus", 
		type:'post',
		dataType:'json',
		data:params,
		success:function(data){
			if(data.message.PayStatus=="WAIT_BUYER_PAY"){ 
			 	$(".tips").html("等待患者付款中...");
			 	$(".btn_area").hide();
			}
			if(data.message.PayStatus=="TRADE_SUCCESS" && data.message.OutTradeNo == $("#stream_no").val()){
				writeLog("支付宝支付成功");
				setTimeout(function(){
				$("#fanhui").css({"visibility":"hidden"});
				$("#tuichu").css({"visibility":"hidden"});
				$("#idCard").val(data['message']['BuyerLogonId']);
				//clearInterval($("#dingshi").val());
				for(var key in interval3){
					clearInterval(interval3[key]);
				}
				$("#trade_no").val(data.message.TradeNo);
				$("#PayStatus").val("支付宝支付成功");
				/****开始调用缴费确认接口***/
				$(".mtnum2").hide();
				$(".pay_success").show();
				$(".pay_success h3").html("费用确认中,请稍候...");
				if(type_times==1){
					daojishi(120);
					paySuccessSendToHis($("#card_code").val());
					type_times++;
				}
				
				//$("#p_four .success").show().html("支付成功,5秒后返回");
				/*setTimeout(function(){
					$("#fanhui").trigger("click");					
				},5000)
				*/
				},1000);
				
			}else{
				
			}
		}
	})		
}
/**
*支付成功回写HIS库  
***/
function paySuccessSendToHis(card_code){
	writeLog("进去到HIS保存费用环节");
	//window.external("ShowMessage",card_code);
	//window.external("ShowMessage",card_code);
function Tcash(cash){
	var money = new Number(cash).toFixed(2);
	var length =parseInt((money*100)).toString().length;
	var n = 12-length;
	var yl_cash="";
	for(i=0;i<n;i++){
		yl_cash+="0";
		}
		return yl_cash+parseInt((money*100));
	}




if($("#business_type").val()=="补挂号费"){
    //alert(111);
       switch(card_code){
            case '21'://就诊卡
            var index_load="";
            var params = {"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"record_sn":$("#record_sn").val(),"response_type":$("#response_type").val(),"charge_total":$("#charge_total").val(),"zhzf":$("#zhzf").val(),"tczf":$("#tczf").val(),"pay_charge_total":$("#cash").val(),"pay_seq":$("#pay_seq").val(),"trade_no":$("#trade_no").val(),"stream_no":$("#stream_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"pay_type":$("#pay_type").val(),"bk_card_no":$("#idCard").val()};
            $.ajax({
                url:"/hddevice/mz/index.php/ZiZhu/YuYue/yyt_qh_save", 
                type:'post',
                dataType:'json',
                data:params,
                success:function(data){
                    var now = new Date().Format("yyyy-MM-dd");//挂号时间
                    var tk_status="";//退款状态
                    var mx="";//明细
                    var pt="";
                    //alert(data['result']['execute_flag']);
                   if(data['result']['execute_flag']==0){
                        writeLog("his数据接收成功");
                        $(".pay_success h3").html(data['result']['execute_message']+"<br>请收好您的挂号号条");
                        window.external.send(5,2);
                        mx = data['datarow'];
                        var date=mx['enter_date'];
                        var date1=date.replace("T"," "); 
/*window.external.PrtJzDateCreatGuaHao(mx['location_info'],mx['ampm'],mx['sequence_no'],mx['outpatient_no'],mx['patient_id'],mx['unit_name'],mx['clinic_name'],mx['patient_name'],mx['sex'],mx['age'],mx['response_type'],mx['total_fee'],mx['fee_zf'],mx['fee_yb'],"垫付："+mx['fee_df'],mx['fee_zf'],$("#zzj_id").val(),mx['receipt_sn'],date1,mx['suggest_time'],$("#idCard").val(),$("#trade_no").val(),$("#stream_no").val(),$("#PayStatus").val(),mx['group_name'],mx['emp_name']);*/



                 var s1="";
                    var pos = 10;
                    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                    s1 += "<tr font='黑体' size='12' x='35' y='" + (pos +=10 ) + "' >就诊</tr>";
                    s1 += "<tr font='黑体' size='12' x='35' y='" + (pos += 20) + "' >位置</tr>";
                    
                    s1 += "<tr font='黑体' size='12' x='190' y='" + (pos -= 10) + "' >"+mx['ampm']+""+mx['sequence_no']+"</tr>";
                    s1 += "<tr font='黑体' size='12' x='80' y='" + (pos -= 10) + "' >补挂号费条</tr>";                  
                    
                    s1 += "<tr font='黑体' size='11' x='70' y='" +(pos+=30) + "' >北京市海淀医院(自助)</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 40) + "' >病案号:</tr>";

                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >"+mx['unit_name']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >号别:"+mx['clinic_name']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >姓名:"+mx['patient_name']+"  性别："+mx['sex']+"  "+mx['age']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >身份:"+mx['response_type']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" +(pos += 20) + "' >医事服务费："+mx['total_fee']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >个人支付："+mx['fee_zf']+" 基金支付:"+mx['fee_yb']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >垫付："+mx['fee_df']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >实收："+mx['fee_zf']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >机器编号："+$("#zzj_id").val()+" 流水号:"+mx['receipt_sn']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >补挂时间："+date1+"</tr>";
                    
                    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                    "' >---------------------------------------------</tr>";
                if($("#pay_type").val()=="alipay"){
                    
                    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付金额："+mx['fee_zf']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝流水号："+$("#trade_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#stream_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝状态："+$("#PayStatus").val()+"</tr>";
                }else if($("#pay_type").val()=="zf_bank"){
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡卡号："+$("#idCard").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡金额："+mx['fee_zf']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡流水号："+$("#trade_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >流水号："+$("#stream_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡状态："+$("#PayStatus").val()+"</tr>";

                }
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >补挂号费成功</tr>";
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：挂号单作为退号凭证,请勿遗失</tr>";

                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >如需挂号收据,缴费时请提前告知收费员</tr>";
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >请患者到相应分诊台分诊报到机报到就诊</tr>";    

                    s1 += "</print_info>";
                    window.external.paint(s1,$("#pat_code").val(),200,40,120,80);


                        
                    }else{
                        if($("#pay_type").val()=="alipay")
                        {
                            if(data['result']['execute_flag']==-1)
                            {  
                                window.external.send(5,2);
                                writeLog("进入支付宝退款");
                                $(".pay_success h3").html(data['pay_result']['SubMsg']);
                                 if(data.pay_result.Code==10000)
                                  {
                                    //tk_status = "交款失败,支付宝退款成功,请重新交易！";
                                    $(".pay_success h3").html("交款失败,支付宝退款成功,请重新交易！");
                                  }else
                                  {
                                    //tk_status = "交款失败,支付宝退款失败,请到二楼收费处进行人工退款！";
                                        $(".pay_success h3").html("交款失败,支付宝退款失败,请到二楼收费处进行人工退款！");
                                    
                                  }
                    var s1="";
                    var pos = 0;
                    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                    s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
                    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
                    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
                                                            "</tr>";
                    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                                "' >---------------------------------------------</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
                                if(data.pay_result.Code==10000){
                                    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款成功,请重新交易！</tr>";

                                  }else{
                                 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款失败,请到二楼收</tr>";
                                 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处进行人工退款！</tr>";
                                  }


                                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
                                s1 += "</print_info>";
                                //alert(s1);
                                window.external.paint(s1,'',200,40,50,30);
                                 setTimeout(function(){
                                        $("#tuichu").trigger("click"); 
                                    },2000)
                            }   
                          if(data['result']['execute_flag']==-2 ){
                            $("#tuichu").trigger("click");

                          }
                           if(data['result']['execute_flag']==-3){
                            window.external.send(5,2);
                            $(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
                           var s1="";
                           var pos = 0;
                                s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
                                                            "</tr>";
                                s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                                "' >---------------------------------------------</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >退款状态："+tk_status+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >his状态：-3,his回滚失败</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >请去二楼收费窗口处理</tr>";
                                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
                                s1 += "</print_info>";
                                window.external.paint(s1,'',200,40,50,30);

                          
                          setTimeout(function(){
                            $("#tuichu").trigger("click"); 
                        },2000)


                          }

                        }else if($("#pay_type").val()=="zf_bank")
                        {
                            if(data['result']['execute_flag']==-1){
                                  if(data['bank']['RespCode']=="00"){
                                    writeLog("进入银行卡退款");
                                    window.external.send(5,2); 
                            //$(".pay_success h3").html(data['pay_result']['SubMsg']);
                            var total_amount = Tcash($("#cash").val());
                            //alert(total_amount);
                            var tui_flag = window.external.Out_UMS_Init();
                            //alert(tui_flag);
                            //alert(tui_flag);
                            if(tui_flag==0)
                            {
                                writeLog("发起冲正请求");
                                //入参开始
                                var tui_req_flag =window.external.Out_UMS_SetReq("00000001","00000002","06",total_amount,"666666","88888888","111111111111","222222","777777","20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020","999");
                                //alert(tui_req_flag);
                                if(tui_req_flag==0)
                                {
                                    //alert(tui_req_flag);
                                    tui_bank_str=window.external.Out_UMS_TransCard();
          //alert(bank_str);
                                   var arr = tui_bank_str.split(",");

                                    $("#T_RespCode").val(arr[0]);
                                    //alert($("#T_RespCode").val());
                                    if( $("#T_RespCode").val()==00)
                                    {
                                        writeLog("冲正成功");
                                        tk_status = "退款成功";                                     
                                        $("#refund_bank").val(tk_status);  
                                         bank_refund();                             
                                        $(".pay_success h3").html("交款失败,银联退款成功,请重新交易!");
                                    }else
                                    {
                                        writeLog("冲正失败");
                                        tk_status = "退款失败";
                                        $("#refund_bank").val(tk_status); 
                                         bank_refund();
                                        $(".pay_success h3").html("交款失败,银联退款失败,请到二楼收费处进行人工退款!");
                                    }
                                }
                            }
                        }
                                var s1="";
                                var pos = 0;
                                s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
                                                            "</tr>";
                                s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                                "' >---------------------------------------------</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
                               
                             if( $("#T_RespCode").val()==00){
                                   s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款成功,请重新交易!</tr>";
                                    }else{
                                    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款失败,请到二</tr>";
                                    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >楼收费处8号窗口进行人工退款！</tr>";
                                    }
                            
                                s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
                                s1 += "</print_info>";
                                window.external.paint(s1,'',200,40,50,30);
                                 setTimeout(function(){
                            $("#tuichu").trigger("click"); 
                                    },2000)
                        }else{
                            if(data['result']['execute_flag']==-2 )
                            {
                                $("#tuichu").trigger("click");

                             }
                            if(data['result']['execute_flag']==-3 )
                            {
                                 $(".pay_success h3").html(data['pay_result']['SubMsg']);
                                window.external.send(5,2);
                                var s1="";
                                var pos = 0;
                                s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
                                                            "</tr>";
                                s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                                "' >---------------------------------------------</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >his状态：his交款失败,"+data['result']['execute_flag']+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >请去二楼收费窗口处理</tr>";
                                s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
                                s1 += "</print_info>";
                                window.external.paint(s1,'',200,40,50,30);
                                  setTimeout(function(){
                                        $("#tuichu").trigger("click"); 
                                    },2000)
                                /*setTimeout(function()
                                {
                                    $("#tuichu").trigger("click"); 
                                },2000)*/

                             }


                          // writeLog("冲正失败");
                            //tk_status = "退款失败";
                          
                        }
                    }   
                    }
                        //开始记录交易信息
                        var dept_code = $("#dept_code").val();
                        var dept_name = $("#dept_name").val();
                        var doctor_code = $("#doctor_code").val();
                        var doctor_name = $("#doctor_name").val();
                        var card_type = $("#card_code").val();
                        var business_type = $("#business_type").val();
                        var pat_card_no = $("#card_no").val();
                        var healthcare_card_no = $("#healthcare_card_no").val();
                        var id_card_no = $("#idCard").val();
                        var pat_id = $("#pat_code").val();
                        var pat_name = $("#pat_name").val();
                        var pat_sex = $("#pat_sex").val();
                        var charge_total = $("#charge_total").val();
                        var cash = $("#cash").val();
                        var zhzf = $("#zhzf").val();
                        var tczf = $("#tczf").val();
                        var trading_state = $("#PayStatus").val();
                        var healthcare_card_trade_state = "";
                        var his_state = data['result']['execute_message'];
                        var bank_card_id="";
                        var reg_info = "";
                        var trade_no = $("#trade_no").val();
                        var zzj_id =$("#zzj_id").val();
                        var stream_no = $("#stream_no").val();
                        var pay_type = $("#pay_type").val();
                        pay_record(dept_code,dept_name,doctor_code,doctor_name,card_type,business_type,pat_card_no,healthcare_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,healthcare_card_trade_state,his_state,bank_card_id,reg_info,trade_no,zzj_id,stream_no,pay_type,tk_status);
                        setTimeout(function(){
                            $("#tuichu").trigger("click"); 
                        },2000);
                }
            })
            break;
            var cheque_type="";
            var bk_card_no=" ";
            case '20'://医保卡
           // alert($("#cash").val());
            if($("#cash").val()==0)
            {
              cheque_type="1";
              bk_card_no="1";
            }else{
                    if($("#pay_type").val()=="alipay")
                    {
                        cheque_type="c";
                        bk_card_no=$("#idCard").val();
                    }else
                    {
                        cheque_type="9";
                        bk_card_no= $("#idCard").val();
                    }
                }
            var record_id="";
            var gh_flag="3";
            var int_xml_param = $("#record_sn").val()+"&"+$("#pay_seq").val()+"&"+$("#response_type").val()+"&"+$("#pat_code").val()+"&"+$("#card_code").val()+"&"+$("#card_no").val()+"&"+$("#charge_total").val()+"&"+$("#cash").val()+"&"+$("#zhzf").val()+"&"+$("#tczf").val()+"&"+record_id+"&"+$("#trade_no").val()+"&"+$("#stream_no").val()+"&"+$("#zzj_id").val()+"&"+cheque_type+"&"+bk_card_no+"&"+gh_flag;
         
                $(".yb_info").text(int_xml_param);  
                //医保划价分解延迟执行1秒,解决页面卡顿问题 
                $("#pay_confirm").show().text("医保费用确认中,请稍后...");
                setTimeout(function(){
                    var patinfo = window.external.YYT_YB_GH_SAVE(int_xml_param);
                   // alert(patinfo);
                        //alert("开始解析");
                        var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"pay_type":$("#pay_type").val()}; 
                        var pt="";
                        var mx="";
                        $.ajax({
                            url:"/hddevice/mz/index.php/ZiZhu/YuYue/YbSfConfirmXmlParseBhao", 
                            type:'post',
                            dataType:'json',
                            data:params,
                            success:function(data)
                            { 
                                var now = new Date().Format("yyyy-MM-dd");//挂号时间
                                var tk_status="";//退款状态
                                var yb_zh= $("#personcount").val()-$("#zhzf").val();
                               if(data['result']['execute_flag']==0)
                                {
                                    writeLog("his数据接收成功");
                                    $(".pay_success h3").html(data['result']['execute_message']+"<br>请收好您的挂号凭条"+"<br>医保个人账户余额"+yb_zh+"元");
                                    //window.external.send(5,2);
                                    /***********开始打印*********/  
                                    mx = data['datarow'];   
                                    var date=mx['enter_date'];
                                    var date1=date.replace("T"," ");
                                    /*window.external.PrtJzDateCreatGuaHao(mx['location_info'],mx['ampm'],mx['sequence_no'],mx['outpatient_no'],mx['patient_id'],mx['unit_name'],mx['clinic_name'],mx['patient_name'],mx['sex'],mx['age'],mx['response_type'],mx['total_fee'],mx['fee_zf'],mx['fee_yb'],"医保个人账户支付:"+mx['fee_zhzf'],mx['fee_zf'],$("#zzj_id").val(),mx['receipt_sn'],date1,mx['suggest_time'],$("#idCard").val(),$("#trade_no").val(),$("#stream_no").val(),$("#PayStatus").val(),mx['group_name'],mx['emp_name']);*/

                    var s1="";
                    var pos = 10;
                    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                    s1 += "<tr font='黑体' size='12' x='35' y='" + (pos +=10 ) + "' >就诊</tr>";
                    s1 += "<tr font='黑体' size='12' x='35' y='" + (pos += 20) + "' >位置</tr>";
                    
                    s1 += "<tr font='黑体' size='12' x='190' y='" + (pos -= 10) + "' >"+mx['ampm']+""+mx['sequence_no']+"</tr>";
                    s1 += "<tr font='黑体' size='12' x='80' y='" + (pos -= 10) + "' >补挂号费条</tr>";                  
                    
                    s1 += "<tr font='黑体' size='11' x='70' y='" +(pos+=30) + "' >北京市海淀医院(自助)</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 40) + "' >病案号:</tr>";

                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >"+mx['unit_name']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >号别:"+mx['clinic_name']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >姓名:"+mx['patient_name']+"  性别："+mx['sex']+"  "+mx['age']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >身份:"+mx['response_type']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" +(pos += 20) + "' >医事服务费："+mx['total_fee']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >个人支付："+mx['fee_zf']+" 基金支付:"+mx['fee_yb']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >医保个人账户支付："+mx['fee_zhzf']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >垫付："+mx['fee_df']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >实收："+mx['fee_zf']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >机器编号："+$("#zzj_id").val()+" 流水号:"+mx['receipt_sn']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >补挂时间："+date1+"</tr>";

                    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                    "' >---------------------------------------------</tr>";
                if($("#pay_type").val()=="alipay"){
                    
                    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付金额："+mx['fee_zf']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝流水号："+$("#trade_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#stream_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝状态："+$("#PayStatus").val()+"</tr>";
                }else if($("#pay_type").val()=="zf_bank"){
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡卡号："+$("#idCard").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡金额："+mx['fee_zf']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡流水号："+$("#trade_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >流水号："+$("#stream_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡状态："+$("#PayStatus").val()+"</tr>";

                }
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >补挂号费成功</tr>";
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：挂号单作为退号凭证,请勿遗失</tr>";
                    
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >如需挂号收据,缴费时请提前告知收费员</tr>";
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >请患者到相应分诊台分诊报到机报到就诊</tr>";    

                    s1 += "</print_info>";
                    window.external.paint(s1,$("#pat_code").val(),200,40,120,80);





                                    
                                    writeLog("弹卡");
                                    setTimeout(function(){
                                        $("#tuichu").trigger("click"); 
                                    },2000)

                                }else{
                                    if($("#pay_type").val()=="alipay"){
                                    if(data['result']['execute_flag']==-1)
                                    {
                                        //alert(data.pay_result.Code);
                                        writeLog("进入支付宝退款");
                                        $(".pay_success h3").html(data['pay_result']['SubMsg']);
                                         //window.external.send(5,2);
                                        
                                                if(data.pay_result.Code==10000)
                                            {
                                                //tk_status = "交款失败,支付宝退款成功,请重新交易!";
                                                
                                                $(".pay_success h3").html(data['pay_result']['SubMsg']);
                                            }else{
                                                //tk_status = "交款失败,支付宝退款失败,请到二楼收费处进行人工退款！";
                                                
                                                $(".pay_success h3").html(data['pay_result']['SubMsg']+"<br/>"+data['result']['execute_message']);
                                            }
                                    
                                    
                                var s1="";
                                var pos = 0;
                                s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
                                                            "</tr>";
                                s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                                "' >---------------------------------------------</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";

                             if( $("#T_RespCode").val()==00){
                                   s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,支付宝退款成功,请重新交易!</tr>";
                                    }else{
                                    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,支付宝退款失败</tr>";
                                    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >请到二楼收费处进行人工退款！</tr>";
                                    }
                                /* s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,支付宝退款失败</tr>";
                                    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >请到二楼收费处进行人工退款！</tr>";*/
                                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
                                s1 += "</print_info>";
                                window.external.paint(s1,'',200,40,50,30);
                                 setTimeout(function(){
                                        $("#tuichu").trigger("click"); 
                                    },2000)

                                }
                                    if(data['result']['execute_flag']==-2 ){
                                        $("#tuichu").trigger("click");

                                      }

                                       if(data['result']['execute_flag']==-3){
                                         //window.external.send(5,2);
                                        $(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
                                        var s1="";
                                var pos = 0;
                                s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
                                                            "</tr>";
                                s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                                "' >---------------------------------------------</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态："+tk_status+"</tr>";
                               
                                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
                                s1 += "</print_info>";
                                window.external.paint(s1,'',200,40,50,30);
                                      setTimeout(function(){
                                        $("#tuichu").trigger("click"); 
                                    },2000)
                                      }

                                    }else if($("#pay_type").val()=="zf_bank"){
                                    if(data['result']['execute_flag']==-1){
                                          if(data['bank']['RespCode']=="00"){
                                    writeLog("进入银行卡退款");
                                   // window.external.send(5,2); 
                            //$(".pay_success h3").html(data['pay_result']['SubMsg']);
                            var total_amount = Tcash($("#cash").val());
                            //alert(total_amount);
                            var tui_flag = window.external.Out_UMS_Init();
                            //alert(tui_flag);
                            //alert(tui_flag);
                            if(tui_flag==0)
                            {
                                writeLog("发起冲正请求");
                                //入参开始
                                var tui_req_flag =window.external.Out_UMS_SetReq("00000001","00000002","06",total_amount,"666666","88888888","111111111111","222222","777777","20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020","999");
                                //alert(tui_req_flag);
                                if(tui_req_flag==0)
                                {
                                    //alert(tui_req_flag);
                                    tui_bank_str=window.external.Out_UMS_TransCard();
          //alert(bank_str);
                                   var arr = tui_bank_str.split(",");

                                    $("#T_RespCode").val(arr[0]);
                                    //alert($("#T_RespCode").val());
                                    if( $("#T_RespCode").val()==00)
                                    {
                                        writeLog("冲正成功");
                                        tk_status = "退款成功";                                     
                                        $("#refund_bank").val(tk_status);  
                                         bank_refund();                             
                                        $(".pay_success h3").html("交款失败,银联退款成功,请重新交易!");
                                    }else
                                    {
                                        writeLog("冲正失败");
                                        tk_status = "退款失败";
                                        $("#refund_bank").val(tk_status); 
                                         bank_refund();
                                        $(".pay_success h3").html("交款失败,银联退款失败,请到二楼收费处进行人工退款!");
                                    }
                                }
                            }
                        }
                                var s1="";
                                var pos = 0;
                                s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
                                                            "</tr>";
                                s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                                "' >---------------------------------------------</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
                               
                             if( $("#T_RespCode").val()==00){
                                   s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款成功,请重新交易!</tr>";
                                    }else{
                                    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款失败,请到二</tr>";
                                    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >楼收费处8号窗口进行人工退款！</tr>";
                                    }
                            
                                s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
                                s1 += "</print_info>";
                                window.external.paint(s1,'',200,40,50,30);
                                /* setTimeout(function(){
                            $("#tuichu").trigger("click"); 
                                    },2000)*/
                        }else{
                            if(data['result']['execute_flag']==-2 ){
                                $("#tuichu").trigger("click");

                              }
                            if(data['result']['execute_flag']==-3 ){
                            $(".pay_success h3").html(data['pay_result']['SubMsg']);
                           // window.external.send(5,2);
                            $(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
                            var s1="";
                                var pos = 0;
                                s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
                                                            "</tr>";
                                s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                                "' >---------------------------------------------</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >his状态：his交款失败,"+data['result']['execute_flag']+"</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >请去二楼收费窗口处理</tr>";
                                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
                                s1 += "</print_info>";
                                window.external.paint(s1,'',200,40,50,30);

                          
                          setTimeout(function(){
                            $("#tuichu").trigger("click"); 
                        },2000)

                              }
                            
                        }
                    }           
                                var dept_code = $("#dept_code").val();
                                var dept_name = $("#dept_name").val();
                                var doctor_code = $("#doctor_code").val();
                                var doctor_name = $("#doctor_name").val();
                                var card_type = $("#card_code").val();
                                var business_type = $("#business_type").val();
                                var pat_card_no = $("#card_no").val();
                                var healthcare_card_no = $("#healthcare_card_no").val();
                                var id_card_no = $("#idCard").val();
                                var pat_id = $("#pat_code").val();
                                var pat_name = $("#pat_name").val();
                                var pat_sex = $("#pat_sex").val();
                                var charge_total = $("#charge_total").val();
                                var cash = $("#cash").val();
                                var zhzf = $("#zhzf").val();
                                var tczf = $("#tczf").val();
                                var trading_state = $("#PayStatus").val();
                                var healthcare_card_trade_state = data['result']['execute_message'];
                                var his_state = "";
                                var bank_card_id="";
                                var reg_info = "";
                                var trade_no = $("#trade_no").val();
                                var zzj_id =$("#zzj_id").val();
                                var stream_no = $("#stream_no").val();
                                var pay_type = $("#pay_type").val();
                                pay_record(dept_code,dept_name,doctor_code,doctor_name,card_type,business_type,pat_card_no,healthcare_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,healthcare_card_trade_state,his_state,bank_card_id,reg_info,trade_no,zzj_id,stream_no,pay_type,tk_status);
                                setTimeout(function(){
                                    $("#tuichu").trigger("click");
                                },2000);
                                
                                
                                }
                                
                            }
                        })
                },1000)
            break;
            default:
            layer.msg("未知错误", {icon: 14,time:2000});
            break;
            
     }
     }


















if($("#business_type").val()=="自助挂号"){
	//alert(card_code);
	switch(card_code){
			case '21'://就诊卡
			var index_load="";
			var params = {"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"record_sn":$("#record_sn").val(),"response_type":$("#response_type").val(),"charge_total":$("#charge_total").val(),"zhzf":$("#zhzf").val(),"tczf":$("#tczf").val(),"pay_charge_total":$("#cash").val(),"pay_seq":$("#pay_seq").val(),"trade_no":$("#trade_no").val(),"stream_no":$("#stream_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"pay_type":$("#pay_type").val(),"bk_card_no":$("#idCard").val()};
		//alert($("#card_code").val()+","+$("#card_no").val()+","+$("#pat_code").val()+","+$("#record_sn").val()+","+$("#response_type").val()+","+$("#charge_total").val()+","+$("#zhzf").val()+","+$("#tczf").val()+","+$("#cash").val()+","+$("#pay_seq").val()+","+$("#trade_no").val()+","+$("#stream_no").val()+","+$("#op_code").val()+","+$("#zzj_id").val()+","+$("#pay_type").val());
			$.ajax({
				url:"/hddevice/mz/index.php/ZiZhu/Index/yyt_gh_save", 
				type:'post',
				dataType:'json',
				data:params,
				success:function(data){
					var now = new Date();//挂号时间
					var tk_status="";//退款状态
					var mx="";//明细
					var pt="";
					//alert(data['result']['execute_flag']);
					if(data['result']['execute_flag']==0){
						writeLog("his数据接收成功");
						//这里用来存储数据
						Transaction(data['datarow']['total_fee'],data['datarow']['fee_zf']);
						//$(".pay_success h3").html("挂号成功"+"<br>准备显示挂号信息");
						$(".pay_success h3").html(data['result']['execute_message']+"<br>请收好您的挂号号条");
						window.external.send(5,2);
						mx = data['datarow'];
						var date=mx['enter_date'];
						var date1=date.replace("T"," "); 
						var sequence_no= data['datarow']['sequence_no'];
//alert("打条");						
/*window.external.PrtJzDateCreatGuaHao(mx['location_info'],mx['ampm'],mx['sequence_no'],mx['outpatient_no'],mx['patient_id'],mx['unit_name'],mx['clinic_name'],mx['patient_name'],mx['sex'],mx['age'],mx['response_type'],mx['total_fee'],mx['fee_zf'],mx['fee_yb'],"垫付："+mx['fee_df'],mx['fee_zf'],$("#zzj_id").val(),mx['receipt_sn'],date1,mx['suggest_time'],$("#idCard").val(),$("#trade_no").val(),$("#stream_no").val(),$("#PayStatus").val(),mx['group_name'],mx['emp_name']);*/




					var  params = {"sequence_no":sequence_no,"doctor_sn":$("#doctor_sn").val(),"unit_sn":$("#unit_sn").val()};
                    $.ajax({
                        url:"/hddevice/mz/index.php/ZiZhu/Index/ic_check", 
                        type:'post',
                        dataType:'json',
                        data:params,
                        success:function(data){
                        	//alert(111);
                            /*$(".mtnum2").hide();
                            $(".pay_success2 h3").show();
*/                         // $("#RegInfo").val(data['RegInfo']);
                            if(data['Message']=="1"){
                            	//alert(222);
                                //alert(11);$(".pay_success h3").html("挂号成功"+"<br>请收好您的挂号号条");
                                /*setTimeout(function(){*/
                                $(".pay_success h3").attr("style","padding-top:-100;width:98%; font-size:50px");
                                $(".pay_success h3").html("您所在科室目前已叫到"+data['RegInfo']+"号 <br>您前面还有"+data['WaitNum']+"人在等候<br/>请尽快报到,科室位置：三层东南侧");
                                  /*  },2000)*/

                                

                            }else{
                                //alert(22);
                                $(".pay_success h3").html("您所在科室目前已叫到"+data['RegInfo']+"<br>您前面还有"+data['WaitNum']+"人在等候,请尽快到科室报到");

                            }
                        }

                    });








					
					var s1="";
					var pos = 10;
					s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
					s1 += "<tr font='黑体' size='12' x='35' y='" + (pos +=10 ) + "' >就诊</tr>";
					s1 += "<tr font='黑体' size='12' x='35' y='" + (pos += 20) + "' >位置</tr>";
					
					s1 += "<tr font='黑体' size='12' x='190' y='" + (pos -= 10) + "' >"+mx['ampm']+""+mx['sequence_no']+"</tr>";
					if(mx['location_info'] !==""){
					s1 += "<tr font='黑体' size='12' x='80' y='" + (pos -= 10) + "' >"+mx['location_info']+"</tr>";		
					}		
					s1 += "<tr font='黑体' size='11' x='70' y='" +(pos+=30) + "' >北京市海淀医院(自助)</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 40) + "' >病案号:</tr>";

					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >"+mx['unit_name']+"</tr>";
					if(mx['group_name'] !== ""){

					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >"+mx['group_name']+"</tr>";

					}
					if(mx['emp_name'] !== ""){

					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >号别:"+mx['clinic_name']+"    "+mx['emp_name']+"</tr>";
					}else{
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >号别:"+mx['clinic_name']+"</tr>";
					}

					
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >姓名:"+mx['patient_name']+"  性别："+mx['sex']+"  "+mx['age']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >身份:"+mx['response_type']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" +(pos += 20) + "' >医事服务费："+mx['total_fee']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >个人支付："+mx['fee_zf']+" 基金支付:"+mx['fee_yb']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >垫付："+mx['fee_df']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >实收："+mx['fee_zf']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >机器编号："+$("#zzj_id").val()+" 流水号:"+mx['receipt_sn']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >挂号时间："+date1+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >"+mx['suggest_time']+"</tr>";
					s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
				                                    "' >---------------------------------------------</tr>";
			    if($("#pay_type").val()=="alipay"){
			    	
			    	s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付金额："+mx['fee_zf']+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝流水号："+$("#trade_no").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#stream_no").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝状态："+$("#PayStatus").val()+"</tr>";
			    }else if($("#pay_type").val()=="zf_bank"){
			    	s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >银行卡卡号："+$("#idCard").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >银行卡金额："+mx['fee_zf']+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >银行卡流水号："+$("#trade_no").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >流水号："+$("#stream_no").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >银行卡状态："+$("#PayStatus").val()+"</tr>";

			    }
				 	
				    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：挂号单作为退号凭证,请勿遗失</tr>";
				    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >本号条当日有效，过期作废</tr>";
				    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >如需挂号收据,缴费时请提前告知收费员</tr>";
				    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >请患者到相应分诊台分诊报到机报到就诊</tr>";	

				    s1 += "</print_info>";
					window.external.paint(s1,$("#pat_code").val(),200,40,120,80);


					







					//alert("打完了");
					 setTimeout(function(){
							$("#tuichu").trigger("click"); 
						},2000)	

					}else{
						if($("#pay_type").val()=="alipay")
						{
							if(data['result']['execute_flag']==-1)
							{  //-1：回滚成功，退款失败     -2 重复交易      -3需要回滚
								window.external.send(5,2);
								writeLog("进入支付宝退款");
								$(".pay_success h3").html(data['pay_result']['SubMsg']);
								 if(data.pay_result.Code==10000)
								  {
								  	writeLog("支付宝退款成功");
									//tk_status = "支付宝退款成功";
									$(".pay_success h3").html(data['pay_result']['SubMsg']);
								  }else{
								  	writeLog("支付宝退款失败");
								  	//tk_status = "支付宝退款失败";
									$(".pay_success h3").html(data['pay_result']['SubMsg']+"<br/>"+data['result']['execute_message']);
								  	
								  }
					var s1="";
					var pos = 0;
					s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
					s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
					s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
					s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
					s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
					s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
					s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    if(data.pay_result.Code==10000){
									s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款成功,请重新交易！</tr>";

								  }else{
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款失败,请到二楼收</tr>";
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处8号窗口进行人工退款！</tr>";
								  }
								/* s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,请到二楼收费处人工处理！</tr>";*/
								 
							    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
								 setTimeout(function(){
										$("#tuichu").trigger("click"); 
									},2000)
							}	
						  if(data['result']['execute_flag']==-2 ){
						  	$("#tuichu").trigger("click");

						  }
						   if(data['result']['execute_flag']==-3){
						   	window.external.send(5,2);
						  	$(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
						   var s1="";
						   var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >退款状态："+tk_status+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >his状态：需要回滚</tr>";
							     s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款失败,请到二楼收</tr>";
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处8号窗口进行人工退款！</tr>";
							    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);

						  
						  setTimeout(function(){
							$("#tuichu").trigger("click"); 
						},2000)


						  }

						}else if($("#pay_type").val()=="zf_bank"){
                            if(data['result']['execute_flag']==-1){
                            	if(data['bank']['RespCode']=="00"){
                           writeLog("进入银行卡退款");
                            		window.external.send(5,2);
		                           // $(".pay_success h3").html(data['pay_result']['SubMsg']);
									var total_amount = Tcash($("#cash").val());									
									var tui_flag = window.external.Out_UMS_Init();									
									
									if(tui_flag==0)
									{
										writeLog("发起冲正请求");
										//入参开始
										var tui_req_flag =window.external.Out_UMS_SetReq("00000001","00000002","06",total_amount,"666666","88888888","111111111111","222222","777777","20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020","999");
										
										if(tui_req_flag==0)
										{
											//alert(tui_req_flag);
											tui_bank_str=window.external.Out_UMS_TransCard();
				  //alert(bank_str);
				                           var arr = tui_bank_str.split(",");

				                            $("#T_RespCode").val(arr[0]);
				                            
				                            if( $("#T_RespCode").val()==00){
				                            	writeLog("冲正成功");
				                            	tk_status = "退款成功";
				                            	$("#refund_bank").val(tk_status);
				                            	bank_refund();
				                            	$(".pay_success h3").html("收款失败,银联退款成功,请重新交易!");
				                            }else{
				                            	writeLog("冲正失败");
				                            	tk_status = "退款失败";
				                                $("#refund_bank").val(tk_status);
				                                bank_refund();
				                            	$(".pay_success h3").html("收款失败,银联退款失败,请到二楼收费处进行人工退款！");
				                            }
										}
									}
                            	}
                            
                                var s1="";
						        var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    if( $("#T_RespCode").val()==00){
		                           s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款成功,请重新交易!</tr>";
		                            }else{
		                            s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款失败,请到二楼</tr>";
		                            s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >收费处8号窗口进行人工退款！</tr>";
		                            }
		                       /* s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,请到二楼收费处人工处理！</tr>";*/
							    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
							    window.external.paint(s1,"",200,40,50,30);
							    setTimeout(function(){
										$("#tuichu").trigger("click"); 
									},2000)
						}else 
						{
							if(data['result']['execute_flag']==-2 )
							{
							  	$("#tuichu").trigger("click");

							 }
							if(data['result']['execute_flag']==-3 )
							{
								 $(".pay_success h3").html(data['pay_result']['SubMsg']);
								window.external.send(5,2);
		   						var s1="";
						        var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='12' x='20' y='" + (pos += 20) + "' >his状态：需要回滚</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,银行卡退款失败,请到二楼收</tr>";
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处8号窗口进行人工退款！</tr>";
							    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
								  setTimeout(function(){
										$("#tuichu").trigger("click"); 
									},2000)
								/*setTimeout(function()
								{
									$("#tuichu").trigger("click"); 
								},2000)*/

							 }


                          // writeLog("冲正失败");
		                    //tk_status = "退款失败";
		                  
						}
					}	
					}
						//开始记录交易信息
						var dept_code = $("#dept_code").val();
						var dept_name = $("#dept_name").val();
						var doctor_code = $("#doctor_code").val();
						var doctor_name = $("#doctor_name").val();
						var card_type = $("#card_code").val();
						var business_type = $("#business_type").val();
						var pat_card_no = $("#card_no").val();
						var healthcare_card_no = $("#healthcare_card_no").val();
						var id_card_no = $("#idCard").val();
						var pat_id = $("#pat_code").val();
						var pat_name = $("#pat_name").val();
						var pat_sex = $("#pat_sex").val();
						var charge_total = $("#charge_total").val();
						var cash = $("#cash").val();
						var zhzf = $("#zhzf").val();
						var tczf = $("#tczf").val();
						var trading_state = $("#PayStatus").val();
						var healthcare_card_trade_state = "";
						var his_state = data['result']['execute_message'];
						var bank_card_id="";
						var reg_info = "";
						var trade_no = $("#trade_no").val();
						var zzj_id =$("#zzj_id").val();
						var stream_no = $("#stream_no").val();
						var pay_type = $("#pay_type").val();
						pay_record(dept_code,dept_name,doctor_code,doctor_name,card_type,business_type,pat_card_no,healthcare_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,healthcare_card_trade_state,his_state,bank_card_id,reg_info,trade_no,zzj_id,stream_no,pay_type,tk_status);
						setTimeout(function(){
							$("#tuichu").trigger("click"); 
						},2000);
				}
			})
			break;
			var cheque_type="";
			var	bk_card_no="";
			case '20'://医保卡
			if($("#cash").val()==0)
			{
			  writeLog("0元收费开始");
			  cheque_type="1";
			  bk_card_no="1";
			}else{
					if($("#pay_type").val()=="alipay")
					{
						cheque_type="c";
						bk_card_no=$("#idCard").val();
					}else
					{
						cheque_type="9";
						bk_card_no= $("#idCard").val();
					}
			}
			var record_id="";
			var gh_flag="1";
			// var int_xml_param = $("#record_sn").val()+"&"+$("#pay_seq").val()+"&"+$("#response_type").val()+"&"+$("#pat_code").val()+"&"+$("#card_code").val()+"&"+$("#card_no").val()+"&"+$("#charge_total").val()+"&"+$("#cash").val()+"&"+$("#zhzf").val()+"&"+$("#tczf").val()+"&"+record_id+"&"+$("#trade_no").val()+"&"+$("#stream_no").val()+"&"+$("#zzj_id").val()+"&"+cheque_type+"&"+bk_card_no;
			var int_xml_param = $("#record_sn").val()+"&"+$("#pay_seq").val()+"&"+$("#response_type").val()+"&"+$("#pat_code").val()+"&"+$("#card_code").val()+"&"+$("#card_no").val()+"&"+$("#charge_total").val()+"&"+$("#cash").val()+"&"+$("#zhzf").val()+"&"+$("#tczf").val()+"&"+record_id+"&"+$("#trade_no").val()+"&"+$("#stream_no").val()+"&"+$("#zzj_id").val()+"&"+cheque_type+"&"+bk_card_no+"&"+gh_flag;
			
//$("#record_sn").val()+"&"+$("#pat_code").val()+"&"+$("#card_no").val()+"&"+$("#response_type").val()+"&"+$("#charge_total").val()+"&"+$("#cash").val()+"&"+$("#zhzf").val()+"&"+$("#tczf").val()+"&c&"+$("#stream_no").val()+"&"+$("#trade_no").val()+"&"+$("#zzj_id").val();
				$(".yb_info").text(int_xml_param);  
				//医保划价分解延迟执行1秒,解决页面卡顿问题 
				$("#pay_confirm").show().text("医保费用确认中,请稍后...");
				setTimeout(function(){
					var patinfo = window.external.YYT_YB_GH_SAVE(int_xml_param);
						//alert("开始解析");
						var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"pay_type":$("#pay_type").val(),"cash":$("#cash").val(),"trade_no":$("#trade_no").val(),"stream_no":$("#stream_no").val()};
						var pt="";
						var mx="";
						$.ajax({
							url:"/hddevice/mz/index.php/ZiZhu/Index/YbSfConfirmXmlParseGhao", 
							type:'post',
							dataType:'json',
							data:params,
							success:function(data)
							{
								// alert(data['result']['execute_flag']);//..??
								var now = new Date();//挂号时间
								var tk_status="";//退款状态
								var yb_zh= $("#personcount").val()-$("#zhzf").val();
								if(data['result']['execute_flag']==0)
								{
									writeLog("his数据接收成功");
									Transaction(data['datarow']['total_fee'],data['datarow']['fee_zf']);
									$(".pay_success h3").html(data['result']['execute_message']+"<br>请收好您的挂号凭条"+"<br>医保个人账户余额"+yb_zh+"元");
									window.external.send(5,2);
									/***********开始打印*********/	
									mx = data['datarow'];	
									var date=mx['enter_date'];
									var date1=date.replace("T"," ");
									/*window.external.PrtJzDateCreatGuaHao(mx['location_info'],mx['ampm'],mx['sequence_no'],mx['outpatient_no'],mx['patient_id'],mx['unit_name'],mx['clinic_name'],mx['patient_name'],mx['sex'],mx['age'],mx['response_type'],mx['total_fee'],mx['fee_zf'],mx['fee_yb'],"医保个人账户支付:"+mx['fee_zhzf'],mx['fee_zf'],$("#zzj_id").val(),mx['receipt_sn'],date1,mx['suggest_time'],$("#idCard").val(),$("#trade_no").val(),$("#stream_no").val(),$("#PayStatus").val(),mx['group_name'],mx['emp_name']);*/





					var s1="";
					var pos = 10;
					s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
					s1 += "<tr font='黑体' size='12' x='35' y='" + (pos +=10 ) + "' >就诊</tr>";
					s1 += "<tr font='黑体' size='12' x='35' y='" + (pos += 20) + "' >位置</tr>";
					
					s1 += "<tr font='黑体' size='12' x='190' y='" + (pos -= 10) + "' >"+mx['ampm']+""+mx['sequence_no']+"</tr>";
					if(mx['location_info'] !==""){
					s1 += "<tr font='黑体' size='12' x='80' y='" + (pos -= 10) + "' >"+mx['location_info']+"</tr>";		
					}					
					
					s1 += "<tr font='黑体' size='11' x='70' y='" +(pos+=30) + "' >北京市海淀医院(自助)</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 40) + "' >病案号:</tr>";

					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >"+mx['unit_name']+"</tr>";
					if(mx['group_name'] !== ""){

					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >"+mx['group_name']+"</tr>";

					}
					if(mx['emp_name'] !== ""){

					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >号别:"+mx['clinic_name']+"    "+mx['emp_name']+"</tr>";
					}else{
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >号别:"+mx['clinic_name']+"</tr>";
					}
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >姓名:"+mx['patient_name']+"  性别："+mx['sex']+"  "+mx['age']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >身份:"+mx['response_type']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" +(pos += 20) + "' >医事服务费："+mx['total_fee']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >个人支付："+mx['fee_zf']+" 基金支付:"+mx['fee_yb']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >垫付："+mx['fee_df']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >实收："+mx['fee_zf']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >机器编号："+$("#zzj_id").val()+" 流水号:"+mx['receipt_sn']+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >挂号时间："+date1+"</tr>";
					s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >"+mx['suggest_time']+"</tr>";
					s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
				                                    "' >---------------------------------------------</tr>";
			    if($("#pay_type").val()=="alipay"){
			    	
			    	s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付金额："+mx['fee_zf']+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝流水号："+$("#trade_no").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#stream_no").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >支付宝状态："+$("#PayStatus").val()+"</tr>";
			    }else if($("#pay_type").val()=="zf_bank"){
			    	s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >银行卡卡号："+$("#idCard").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >银行卡金额："+mx['fee_zf']+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >银行卡流水号："+$("#trade_no").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >流水号："+$("#stream_no").val()+"</tr>";
			    	s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >银行卡状态："+$("#PayStatus").val()+"</tr>";

			    }
				 	
				    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：挂号单作为退号凭证,请勿遗失</tr>";
				    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >本号条当日有效，过期作废</tr>";
				    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >如需挂号收据,缴费时请提前告知收费员</tr>";
				    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >请患者到相应分诊台分诊报到机报到就诊</tr>";	

				    s1 += "</print_info>";
					window.external.paint(s1,$("#pat_code").val(),200,40,120,80);







									
									writeLog("弹卡");
									setTimeout(function(){
										$("#tuichu").trigger("click"); 
									},2000)

								}else{
									if($("#pay_type").val()=="alipay"){
									if(data['result']['execute_flag']==-1)
									{
									    window.external.send(5,2);
										writeLog("进入支付宝退款");
										$(".pay_success h3").html(data['pay_result']['SubMsg']);
										
										if(data.pay_result.Code==10000){
												//tk_status = "支付宝退款成功";
												writeLog("支付宝退款成功");
												$(".pay_success h3").html(data['pay_result']['SubMsg']);
											}else{
												//tk_status = "支付宝退款失败"+data['pay_result']['SubMsg'];
												writeLog("支付宝退款失败");
												$(".pay_success h3").html(data['pay_result']['SubMsg']+"<br/>"+data['result']['execute_message']);
											}
									
									
								var s1="";
							    var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							   if(data.pay_result.Code==10000){
									s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款成功,请重新交易！</tr>";

								  }else{
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款失败,请到二楼收</tr>";
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处8号窗口进行人工退款！</tr>";
								  }
								/*s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,请到二楼收费处人工处理！</tr>"; */
							    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
								 setTimeout(function(){
										$("#tuichu").trigger("click"); 
									},2000)

								}
									if(data['result']['execute_flag']==-2 ){
									  	$("#tuichu").trigger("click");

									  }

									   if(data['result']['execute_flag']==-3){
									   	 window.external.send(5,2);
									  	$(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
									  	var s1="";
							    var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >his状态：需要回滚</tr>";
							     s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款失败,请到二楼收</tr>";
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处8号窗口进行人工退款！</tr>";
							    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
									  setTimeout(function(){
										$("#tuichu").trigger("click"); 
									},2000)
									  }

									}else if($("#pay_type").val()=="zf_bank"){
                                    if(data['result']['execute_flag']==-1){
                                    	if(data['bank']['RespCode']=="00"){
                                    		        window.external.send(5,2);
                                   					writeLog("进入银行卡退款");
								   var back_total_amount = Tcash($("#cash").val());
							        //银行卡强冲开始
							        //初始化
							        var tui_flag = window.external.Out_UMS_Init();
							//alert(tui_flag);
							        if(tui_flag==0){
								    writeLog("发起冲正请求");
								//入参开始
								    var tui_req_flag =window.external.Out_UMS_SetReq("00000001","00000002","06",back_total_amount,"666666","88888888","111111111111","222222","777777","20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020","999");
								//alert(tui_req_flag);
								    if(tui_req_flag==0){
									 tui_bank_str=window.external.Out_UMS_TransCard();
		//alert(bank_str);
		                            var arr = tui_bank_str.split(",");

		                             $("#T_RespCode").val(arr[0]);
		                            //alert($("#T_RespCode").val());
		                             if( $("#T_RespCode").val()==00){
		                            	writeLog("冲正成功");
		                            	tk_status = "退款成功";		                            
		                            	$("#refund_bank").val(tk_status);
		                            	bank_refund();
		                            	$(".pay_success h3").html("交款失败,退款成功,请重新交易!");
		                            }else{
		                            	writeLog("冲正失败");
		                            	tk_status = "退款失败";
		                                $("#refund_bank").val(tk_status);
		                                bank_refund();
		                            	$(".pay_success h3").html( "交款失败,退款失败,请去二楼收费窗口人工退费!");
		                            }
								}

							}
							window.external.send(5,2);
							//$(".pay_success h3").html(data['pay_result']['SubMsg']);

                         }

									
							
								var s1="";
						        var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							   if( $("#T_RespCode").val()==00){
		                           s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款成功,请重新交易!</tr>";
		                            }else{
		                            s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款失败,请到二楼</tr>";
		                            s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >收费处8号窗口进行人工退款！</tr>";
		                            }
		                       /*s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,请到二楼收费处人工处理！</tr>";*/
							    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
								 setTimeout(function(){
							$("#tuichu").trigger("click"); 
									},2000)
						}else{

							if(data['result']['execute_flag']==-2 ){
							  	$("#tuichu").trigger("click");

							  }
							if(data['result']['execute_flag']==-3 ){
							$(".pay_success h3").html(data['pay_result']['SubMsg']);
						    window.external.send(5,2);
   						    $(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
							var s1="";
						        var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >his状态：需要回滚</tr>";
							     s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,银行卡退款失败,请到二楼收</tr>";
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处8号窗口进行人工退款！</tr>";
							    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);

						  
						  setTimeout(function(){
							$("#tuichu").trigger("click"); 
						},2000)

							  }
							
						}
					}			
								var dept_code = $("#dept_code").val();
								var dept_name = $("#dept_name").val();
								var doctor_code = $("#doctor_code").val();
								var doctor_name = $("#doctor_name").val();
								var card_type = $("#card_code").val();
								var business_type = $("#business_type").val();
								var pat_card_no = $("#card_no").val();
								var healthcare_card_no = $("#healthcare_card_no").val();
								var id_card_no = $("#idCard").val();
								var pat_id = $("#pat_code").val();
								var pat_name = $("#pat_name").val();
								var pat_sex = $("#pat_sex").val();
								var charge_total = $("#charge_total").val();
								var cash = $("#cash").val();
								var zhzf = $("#zhzf").val();
								var tczf = $("#tczf").val();
								var trading_state = $("#PayStatus").val();
								var healthcare_card_trade_state = data['result']['execute_message'];
								var his_state = "";
								var bank_card_id="";
								var reg_info = "";
								var trade_no = $("#trade_no").val();
								var zzj_id =$("#zzj_id").val();
								var stream_no = $("#stream_no").val();
								var pay_type = $("#pay_type").val();
								pay_record(dept_code,dept_name,doctor_code,doctor_name,card_type,business_type,pat_card_no,healthcare_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,healthcare_card_trade_state,his_state,bank_card_id,reg_info,trade_no,zzj_id,stream_no,pay_type,tk_status);
								setTimeout(function(){
									$("#tuichu").trigger("click");
								},2000);
								
								}
								
								//window.external.PrtJzDateCreat($("#card_code").val(),$("#pat_name").val(),$("#pat_sex").val(),data['datarow']['attr']['reponcename'],$("#pat_code").val(),pt,data['data']['zhzf'],data['data']['tczf'],data['data']['charge_total'],data['data']['cash'],$("#idCard").val(),data['data']['cash'],$("#trade_no").val(),$("#stream_no").val(),$("#PayStatus").val(),"",data['result']['execute_message'],tk_status,"ZZJ01");		
								//writeLog($("#card_code").val(),$("#pat_name").val(),$("#pat_sex").val(),$("#pat_code").val(),pt,data['data']['zhzf'],data['data']['charge_total'],data['data']['cash'],$("#idCard").val(),data['data']['cash'],$("#trade_no").val(),$("#stream_no").val(),$("#PayStatus").val(),"",data['result']['execute_message'],"成功","ZZJ01");
							}
						})
				},1000)
			break;
			default:
			layer.msg("未知错误", {icon: 14,time:2000});
			break;
			
	 }
	 
	}


if($("#business_type").val()=="自助缴费"){
	switch(card_code){
			case '21'://就诊卡
			var index_load="";
			var params = {"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"times":$("#times").val(),"responce_type":$("#response_type").val(),"charge_total":$("#charge_total").val(),"zhzf":$("#zhzf").val(),"tczf":$("#tczf").val(),"pay_charge_total":$("#cash").val(),"pay_seq":$("#pay_seq").val(),"trade_no":$("#trade_no").val(),"stream_no":$("#stream_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"pay_type":$("#pay_type").val(),"bk_card_no":$("#idCard").val()};
			$.ajax({
				url:"/hddevice/mz/index.php/ZiZhu/Index/yyt_sf_save", 
				type:'post',
				dataType:'json',
				data:params,
				success:function(data){
					var now = new Date();//挂号时间
					var tk_status="";//退款状态
					var mx="";//明细
					var pt="";
					if(data['result']['execute_flag']==0){
						//alert(8);
						$(".pay_success h3").html(data['result']['execute_message']+"<br>请收好您的缴费凭条");
						Transaction(data['data']['charge_total'],data['data']['cash']);
						window.external.send(5,2);
						mx = data['datarow']['mingxi'];
						var pt = "";
						var m=0;
						
						for(var i=0;i<mx.length;i++){
							if(m==0){
								pt+=mx[i]['charge_name']+"&"+mx[i]['specification']+"&"+mx[i]['charge_price']+"&"+mx[i]['charge_amount'];
							}else{
								pt+="|"+mx[i]['charge_name']+"&"+mx[i]['specification']+"&"+mx[i]['charge_price']+"&"+mx[i]['charge_amount'];
							}
							
							m++;
						}
						//开始打印
						if(data['data']['cash']==0){
							window.external.send(5,2);
							window.external.PrtJzDateCreat($("#card_code").val(),$("#pat_name").val(),$("#pat_sex").val(),data['datarow']['attr']['reponcename'],$("#pat_code").val(),pt,data['data']['zhzf'],data['data']['tczf'],data['data']['charge_total'],data['data']['cash'],$("#idCard").val(),data['data']['cash'],"",$("#stream_no").val(),"减免全部",tk_status,data['result']['execute_message'],"",$("#zzj_id").val());
						}else{
							//alert($("#card_code").val()+","+$("#pat_name").val()+","+$("#pat_sex").val()+","+data['datarow']['attr']['reponcename']+","+$("#pat_code").val()+","+pt+","+data['data']['zhzf']+","+data['data']['tczf']+","+data['data']['charge_total']+","+data['data']['cash']+","+$("#idCard").val()+","+data['data']['cash']+","+$("#trade_no").val()+","+$("#stream_no").val()+","+$("#PayStatus").val()+","+tk_status+","+data['result']['execute_message']+","+""+","+$("#zzj_id").val());
							window.external.send(5,2);
							window.external.PrtJzDateCreat($("#card_code").val(),$("#pat_name").val(),$("#pat_sex").val(),data['datarow']['attr']['reponcename'],$("#pat_code").val(),pt,data['data']['zhzf'],data['data']['tczf'],data['data']['charge_total'],data['data']['cash'],$("#idCard").val(),data['data']['cash'],$("#trade_no").val(),$("#stream_no").val(),$("#PayStatus").val(),tk_status,data['result']['execute_message'],"",$("#zzj_id").val());
						}
						setTimeout(function(){
							$("#tuichu").trigger("click"); 
						},2000); 
						  
						
					}else{
						
						
						if($("#pay_type").val()=="alipay"){
							if(data['result']['execute_flag']=="-1")
							{ 
								window.external.send(5,2);
								writeLog("进入支付宝退款");
								$(".pay_success h3").html(data['pay_result']['SubMsg']);
								if(data.pay_result.Code==10000){
									//tk_status = "退款成功";
									writeLog("支付宝退款成功");
									$(".pay_success h3").html(data['pay_result']['SubMsg']);
								}else{
									//tk_status = "退款失败"+data['pay_result']['SubMsg'];
									writeLog("支付宝退款失败");
									$(".pay_success h3").html(data['pay_result']['SubMsg']+"<br/>"+data['result']['execute_message']);
								}

						   var s1="";
						   var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    if(data.pay_result.Code==10000){
									s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款成功,请重新交易！</tr>";

								  }else{
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款失败,请到二楼收</tr>";
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处8号窗口进行人工退款！</tr>";
								  }
							/*	s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,请到二楼收费处人工处理！</tr>";*/
							    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
								setTimeout(function(){
									$("#tuichu").trigger("click"); 
										},2000)

						  
							}
							if(data['result']['execute_flag']==-2 )
							{
								$("#tuichu").trigger("click");

							}

						    if(data['result']['execute_flag']==-3)
						    {
							window.external.send(5,2);    
						     $(".pay_success h3").html(data['pay_result']['SubMsg']);
							// $(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
						     var s1="";
						     var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >退款状态："+tk_status+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >his状态：需要回滚</tr>";
							     s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款失败,请到二楼</tr>";
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >收费处8号窗口进行人工退款！</tr>";
							    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
								setTimeout(function(){
									$("#tuichu").trigger("click"); 
										},2000)

						  	    
							}
						
						}else if($("#pay_type").val()=="zf_bank"){
							if(data['result']['execute_flag']=="-1"){
								if(data['bank']['RespCode']=="00"){
									window.external.send(5,2); 
							writeLog("进入银行卡退款");
							var total_amount = Tcash($("#cash").val());
							//alert(total_amount);
							var tui_flag = window.external.Out_UMS_Init();
							//alert(tui_flag);
							//alert(tui_flag);
							if(tui_flag==0)
							{
								writeLog("发起冲正请求");
								//入参开始
								var tui_req_flag =window.external.Out_UMS_SetReq("00000001","00000002","06",total_amount,"666666","88888888","111111111111","222222","777777","20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020","999");
								//alert(tui_req_flag);
								if(tui_req_flag==0)
								{
									//alert(tui_req_flag);
									tui_bank_str=window.external.Out_UMS_TransCard();
		  //alert(bank_str);
		                           var arr = tui_bank_str.split(",");

		                            $("#T_RespCode").val(arr[0]);
		                            //alert($("#T_RespCode").val());
		                            if( $("#T_RespCode").val()==00)
		                            {
		                            	writeLog("冲正成功");
		                            	tk_status = "退款成功";		                            	
		                            	$("#refund_bank").val(tk_status);
		                            	 bank_refund();
		                            	$(".pay_success h3").html("交款失败,银联退款成功,请重新交易!");
		                            }else
		                            {
		                            	writeLog("冲正失败");
		                            	tk_status = "退款失败";
		                            	$("#refund_bank").val(tk_status);
		                            	 bank_refund();
		                            	$(".pay_success h3").html("交款失败,银联退款失败,请到二楼收费处进行人工退款!");
		                            }
								}
							}
						}
							
								var s1="";
						        var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							   if( $("#T_RespCode").val()==00){
		                           s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款成功,请重新交易!</tr>";
		                            }else{
		                            s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款失败,请到二楼</tr>";
		                            s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >收费处8号窗口进行人工退款！</tr>";
		                            }
		                      /*  s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >收款失败,请到二楼收费处人工退款！</tr>";*/
							    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
								setTimeout(function(){
									$("#tuichu").trigger("click"); 
										},2000)

							
						}else{
							if(data['result']['execute_flag']==-2 ){
							  	$("#tuichu").trigger("click");

							  }
							if(data['result']['execute_flag']==-3 ){
								window.external.send(5,2); 
							 	$(".pay_success h3").html("-3,his回滚失败,请去窗口做回滚处理");
								var s1="";
						        var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='12' x='20' y='" + (pos += 20) + "' >his状态：需要回滚</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款失败,请到二楼</tr>";
		                        s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >收费处8号窗口进行人工退款！</tr>";
							    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
								setTimeout(function(){
									$("#tuichu").trigger("click"); 
										},2000)

						/*$(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
						window.external.send(5,2);
   						$(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
						window.external.PrtJzDateCreat($("#card_code").val(),$("#pat_name").val(),$("#pat_sex").val(),$("#reponse_name").val(),$("#pat_code").val(),pt,$("#zhzf").val(),$("#tczf").val(),$("#charge_total").val(),$("#cash").val(),$("#idCard").val(),$("#cash").val(),$("#trade_no").val(),$("#stream_no").val(),"",tk_status,data['result']['execute_message'],"",$("#zzj_id").val());

						  
						  setTimeout(function(){
							$("#tuichu").trigger("click"); 
						},2000)
*/
							  }


							//writeLog("冲正失败");
		                    //tk_status = "银联卡退款失败";
		                   // $(".pay_success h3").html("退款失败<br>"+data['result']['execute_message']);
						}
					}
						
						//开始打印 
						//window.external.send(5,2); 
						//window.external.PrtJzDateCreat($("#card_code").val(),$("#pat_name").val(),$("#pat_sex").val(),$("#reponse_name").val(),$("#pat_code").val(),pt,$("#zhzf").val(),$("#tczf").val(),$("#charge_total").val(),$("#cash").val(),$("#idCard").val(),$("#cash").val(),$("#trade_no").val(),$("#stream_no").val(),"",tk_status,data['result']['execute_message'],"",$("#zzj_id").val()); 
						
					}
					
						//开始记录交易信息
						var dept_code = $("#dept_code").val();
						var dept_name = $("#dept_name").val();
						var doctor_code = $("#doctor_code").val();
						var doctor_name = $("#doctor_name").val();
						var card_type = $("#card_code").val();
						var business_type = $("#business_type").val();
						var pat_card_no = $("#card_no").val();
						var healthcare_card_no = $("#healthcare_card_no").val();
						var id_card_no = $("#idCard").val();
						var pat_id = $("#pat_code").val();
						var pat_name = $("#pat_name").val();
						var pat_sex = $("#pat_sex").val();
						var charge_total = $("#charge_total").val();
						var cash = $("#cash").val();
						var zhzf = $("#zhzf").val();
						var tczf = $("#tczf").val();
		                var T_RespCode = $("#T_RespCode").val();
						var trading_state = $("#PayStatus").val();
						var healthcare_card_trade_state = "";
						var his_state = data['result']['execute_message'];
						var bank_card_id="";
						var reg_info = "";
						var trade_no = $("#trade_no").val();
						var zzj_id =$("#zzj_id").val();
						var stream_no = $("#stream_no").val();
						var pay_type = $("#pay_type").val();
						pay_record(dept_code,dept_name,doctor_code,doctor_name,card_type,business_type,pat_card_no,healthcare_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,healthcare_card_trade_state,his_state,bank_card_id,reg_info,trade_no,zzj_id,stream_no,pay_type,tk_status);
						setTimeout(function(){
							$("#tuichu").trigger("click"); 
						},2000);
					
					
				}
			})
			break;
			case '20'://医保卡$("#response_type").val
			var	bk_card_no="";
			var chque_type="";
			if($("#cash").val()==0)
			{
			  cheque_type="1";
			  bk_card_no="1";
			}else{
					if($("#pay_type").val()=="alipay")
					{
						cheque_type="c";
						bk_card_no=$("#idCard").val();
					}else
					{
						cheque_type="9";
						bk_card_no= $("#idCard").val();
					}
			}
			var int_xml_param = $("#pay_seq").val()+"&"+$("#pat_code").val()+"&"+$("#card_no").val()+"&"+$("#response_type").val()+"&"+$("#times").val()+"&"+$("#charge_total").val()+"&"+$("#cash").val()+"&"+$("#zhzf").val()+"&"+$("#tczf").val()+"&"+cheque_type+"&"+$("#stream_no").val()+"&"+$("#trade_no").val()+"&"+$("#times_order_no").val()+"&"+$("#zzj_id").val()+"&"+bk_card_no;
				$(".yb_info").text(int_xml_param);  
				//医保划价分解延迟执行1秒,解决页面卡顿问题 
				$("#pay_confirm").show().text("医保费用确认中,请稍后...");
				setTimeout(function(){
					var patinfo = window.external.YYT_YB_SF_SAVE(int_xml_param);
						//alert("开始解析");
						var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"pay_type":$("#pay_type").val(),"cash":$("#cash").val(),"trade_no":$("#trade_no").val(),"stream_no":$("#stream_no").val()};
						var pt="";
						var mx="";
						$.ajax({
							url:"/hddevice/mz/index.php/ZiZhu/Index/YbSfConfirmXmlParse", 
							type:'post',
							dataType:'json',
							data:params,
							success:function(data)
							{
								var now = new Date();//挂号时间
								writeLog("his数据接收成功");
								var yb_zh= $("#personcount").val()-$("#zhzf").val();
								var tk_status="";//退款状态
								if(data['result']['execute_flag']==0){
									//$(".pay_success h3").html(data['result']['execute_message']+"<br>请收好您的缴费凭条");
									$(".pay_success h3").html(data['result']['execute_message']+"<br>请收好您的缴费凭条"+"<br>医保个人账户余额"+yb_zh+"元");
									/***********开始打印*********/
								Transaction(data['data']['charge_total'],data['data']['cash']);
									mx = data['datarow']['mingxi'];
									var m=0;
									for(var i=0;i<mx.length;i++){
										if(m==0){
											pt+=mx[i]['charge_name']+"&"+mx[i]['specification']+"&"+mx[i]['charge_price']+"&"+mx[i]['charge_amount'];
										}else{
											pt+="|"+mx[i]['charge_name']+"&"+mx[i]['specification']+"&"+mx[i]['charge_price']+"&"+mx[i]['charge_amount'];
										}
										
										m++;
									}
									//开始打印
									
									if(data['data']['cash']==0)
									{
										window.external.send(5,2);
										window.external.PrtJzDateCreat($("#card_code").val(),$("#pat_name").val(),$("#pat_sex").val(),data['datarow']['attr']['reponcename'],$("#pat_code").val(),pt,data['data']['zhzf'],data['data']['tczf'],data['data']['charge_total'],data['data']['cash'],$("#idCard").val(),data['data']['cash'],"",$("#stream_no").val(),"",tk_status,data['result']['execute_message'],tk_status,$("#zzj_id").val());	
									}else
									{
										window.external.send(5,2);
										window.external.PrtJzDateCreat($("#card_code").val(),$("#pat_name").val(),$("#pat_sex").val(),data['datarow']['attr']['reponcename'],$("#pat_code").val(),pt,data['data']['zhzf'],data['data']['tczf'],data['data']['charge_total'],data['data']['cash'],$("#idCard").val(),data['data']['cash'],$("#trade_no").val(),$("#stream_no").val(),$("#PayStatus").val(),"",data['result']['execute_message'],tk_status,$("#zzj_id").val());	
									}
									writeLog("弹卡");
									
									setTimeout(function(){
										$("#tuichu").trigger("click"); 
									},2000)
									
								}else
								{
									//$(".pay_success h3").html(data['result']['execute_message']);
									if($("#pay_type").val()=="alipay"){
										if(data['result']['execute_flag']==-1){
											window.external.send(5,2); 
											$(".pay_success h3").html(data['pay_result']['SubMsg']);
											writeLog("进入支付宝退款");

											if(data.pay_result.Code==10000){
												//tk_status = "退款成功";
												writeLog("支付宝退款成功");
												$(".pay_success h3").html("交款失败,退款成功,请重新交易!");
											//$(".pay_success h3").html("退款成功"+data['result']['execute_message']);
											}else{
												//tk_status = "退款失败"+data['pay_result']['SubMsg'];
												writeLog("支付宝退款失败");
												$(".pay_success h3").html( "交款失败,退款失败,请到二楼收费处进行人工退款!");
												//$(".pay_success h3").html(data['pay_result']['SubMsg']+"<br/>"+data['result']['execute_message']);
											}
						   var s1="";
						   var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    if(data.pay_result.Code==10000){
									s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款成功,请重新交易！</tr>";

								  }else{
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款失败,请到二楼收</tr>";
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处8号窗口进行人工退款！</tr>";
								  }
								/* s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,请二楼收费处人工退款！</tr>";*/
							    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
												
											 setTimeout(function(){
										$("#tuichu").trigger("click"); 
									},2000)
											 	

										}
										


									if(data['result']['execute_flag']==-2 ){
									  	$("#tuichu").trigger("click");

									  }

									   if(data['result']['execute_flag']==-3){
									   	window.external.send(5,2); 
									   	$(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
						   var s1="";
						   var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >支付宝支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >支付流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >退款状态："+tk_status+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >his状态：需要回滚</tr>";
							     s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款失败,请到二楼收</tr>";
								 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处8号窗口进行人工退款！</tr>";
							    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
									  	/*$(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
									window.external.PrtJzDateCreat($("#card_code").val(),$("#pat_name").val(),$("#pat_sex").val(),$("#reponse_name").val(),$("#pat_code").val(),pt,$("#zhzf").val(),$("#tczf").val(),$("#charge_total").val(),$("#cash").val(),$("#idCard").val(),$("#cash").val(),$("#trade_no").val(),$("#stream_no").val(),"",tk_status,data['result']['execute_message'],"",$("#zzj_id").val());

									  */
									  setTimeout(function(){
										$("#tuichu").trigger("click"); 
									},2000)
									  }


								    }else if($("#pay_type").val()=="zf_bank"){
							    if(data['result']['execute_flag']==-1){

							    	if(data['bank']['RespCode']=="00"){
							    	writeLog("进入银行卡退款");
								 	window.external.send(5,2); 
							//$(".pay_success h3").html(data['pay_result']['SubMsg']);
							var total_amount = Tcash($("#cash").val());
							//alert(total_amount);
							var tui_flag = window.external.Out_UMS_Init();
							//alert(tui_flag);
							//alert(tui_flag);
							if(tui_flag==0)
							{
								writeLog("发起冲正请求");
								//入参开始
								var tui_req_flag =window.external.Out_UMS_SetReq("00000001","00000002","06",total_amount,"666666","88888888","111111111111","222222","777777","20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020","999");
								//alert(tui_req_flag);
								if(tui_req_flag==0)
								{
									//alert(tui_req_flag);
									tui_bank_str=window.external.Out_UMS_TransCard();
		  //alert(bank_str);
		                           var arr = tui_bank_str.split(",");

		                            $("#T_RespCode").val(arr[0]);
		                            //alert($("#T_RespCode").val());
		                            if( $("#T_RespCode").val()==00)
		                            {
		                            	writeLog("冲正成功");
		                            	tk_status = "退款成功";		                            	
		                            	$("#refund_bank").val(tk_status);  
		                            	 bank_refund();                         	
		                            	$(".pay_success h3").html("交款失败,银联退款成功,请重新交易!");
		                            }else
		                            {
		                            	writeLog("冲正失败");
		                            	tk_status = "退款失败";
		                            	$("#refund_bank").val(tk_status); 
		                            	 bank_refund();
		                            	$(".pay_success h3").html("交款失败,银联退款失败,请到二楼收费处进行人工退款!");
		                            }
								}
							}
						}
							
								var s1="";
						        var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							  
							 if( $("#T_RespCode").val()==00)
		                            {
		                           s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款成功,请重新交易!</tr>";
		                            }else{
		                            s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款失败,请到二</tr>";
		                            s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >楼收费处8号窗口进行人工退款！</tr>";
		                            }
							   
							    s1 += "<tr font='黑体' size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
								setTimeout(function(){
									$("#tuichu").trigger("click"); 
										},2000)

							
						}else{

								if(data['result']['execute_flag']==-2 ){
							  	$("#tuichu").trigger("click");

							  }
							   if(data['result']['execute_flag']==-3 ){
							   	window.external.send(5,2); 
							   	$(".pay_success h3").html("-3,his回滚失败,请去窗口做回滚处理");
								var s1="";
						        var pos = 0;
							    s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
								s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
								s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+"  身份："+$("#reponse_name").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >就诊号："+$("#pat_code").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >缴费日期："+now+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >" + "获取缴费明细失败" +
							                                "</tr>";
							    s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
							                                    "' >---------------------------------------------</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >银联支付金额："+$("#cash").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >银联流水号："+$("#trade_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='8' x='20' y='" + (pos += 20) + "' >本地流水号："+$("#stream_no").val()+"</tr>";
							    s1 += "<tr font='黑体' size='12' x='20' y='" + (pos += 20) + "' >his状态：需要回滚</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,银联退款失败,请到二</tr>";
		                        s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >楼收费处8号窗口进行人工退款！</tr>";
							    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
							    s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
							    s1 += "</print_info>";
								window.external.paint(s1,"",200,40,50,30);
						/*$(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
						window.external.send(5,2);
   						$(".pay_success h3").html("交易失败，请收好您的号条到窗口人工处理");
						window.external.PrtJzDateCreat($("#card_code").val(),$("#pat_name").val(),$("#pat_sex").val(),$("#reponse_name").val(),$("#pat_code").val(),pt,$("#zhzf").val(),$("#tczf").val(),$("#charge_total").val(),$("#cash").val(),$("#idCard").val(),$("#cash").val(),$("#trade_no").val(),$("#stream_no").val(),"",tk_status,data['result']['execute_message'],"",$("#zzj_id").val());

						  
						  setTimeout(function(){
							$("#tuichu").trigger("click"); 
						},2000)

							  }
							
								$(".pay_success h3").html(data['result']['execute_message']);*/
								 setTimeout(function(){
							 		$("#tuichu").trigger("click"); 
									},2000)
									}
								}
							}

						//开始记录交易信息
								var dept_code = $("#dept_code").val();
								var dept_name = $("#dept_name").val();
								var doctor_code = $("#doctor_code").val();
								var doctor_name = $("#doctor_name").val();
								var card_type = $("#card_code").val();
								var business_type = $("#business_type").val();
								var pat_card_no = $("#card_no").val();
								var healthcare_card_no = $("#healthcare_card_no").val();
								var id_card_no = $("#idCard").val();
								var pat_id = $("#pat_code").val();
								var pat_name = $("#pat_name").val();
								var pat_sex = $("#pat_sex").val();
								var charge_total = $("#charge_total").val();
								var cash = $("#cash").val();
								var zhzf = $("#zhzf").val();
								var tczf = $("#tczf").val();
								var trading_state = $("#PayStatus").val();
								var healthcare_card_trade_state = data['result']['execute_message'];
								var his_state = "";
								var bank_card_id="";
								var reg_info = "";
								var trade_no = $("#trade_no").val();
								var zzj_id =$("#zzj_id").val();
								var stream_no = $("#stream_no").val();
								var pay_type = $("#pay_type").val();
								pay_record(dept_code,dept_name,doctor_code,doctor_name,card_type,business_type,pat_card_no,healthcare_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,healthcare_card_trade_state,his_state,bank_card_id,reg_info,trade_no,zzj_id,stream_no,pay_type,tk_status);
								setTimeout(function(){
									$("#tuichu").trigger("click");
								},2000);
								
								
								}
								
							}
						})
				},1000)
			break;
			default:
			layer.msg("未知错误", {icon: 14,time:2000});
			break;
			
	 }
	 }
}
/********交易记录*******/
function pay_record(dept_code,dept_name,doctor_code,doctor_name,card_type,business_type,pat_card_no,healthcare_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,healthcare_card_trade_state,his_state,bank_card_id,reg_info,trade_no,zzj_id,stream_no,tk_status){
		//alert(dept_code+","+dept_name+","+doctor_code+","+doctor_name+","+card_type+","+business_type+","+pat_card_no+","+healthcare_card_no+","+id_card_no+","+pat_id+","+pat_name+","+pat_sex+","+charge_total+","+cash+","+zhzf+","+tczf+","+trading_state+","+healthcare_card_trade_state+","+his_state+","+bank_card_id+","+reg_info+","+trade_no+","+zzj_id+","+stream_no);
		var params = {"dept_code":dept_code,"dept_name":dept_name,"doctor_code":doctor_code,"doctor_name":doctor_name,"card_type":card_type,"business_type":business_type,"pat_card_no":pat_card_no,"healthcare_card_no":healthcare_card_no,"id_card_no":id_card_no,"pat_id":pat_id,"pat_name":pat_name,"pat_sex":pat_sex,"charge_total":charge_total,"cash":cash,"zhzf":zhzf,"tczf":tczf,"trading_state":trading_state,"healthcare_card_trade_state":healthcare_card_trade_state,"his_state":his_state,"bank_card_id":bank_card_id,"reg_info":tk_status,"trade_no":trade_no,"zzj_id":zzj_id,"stream_no":$("#stream_no").val(),"tk_status":tk_status}; 
		//alert("开始记录交易数据");
		$.ajax({
			url:"/hddevice/mz/index.php/ZiZhu/Index/witeJyRecordToDataBase", 
			type:'post',
			dataType:'json',
			data:params,
			success:function(data){
				
			}
		})
}
function pay_record_bank(){
	//alert(111);
		//alert(dept_code+","+dept_name+","+doctor_code+","+doctor_name+","+card_type+","+business_type+","+pat_card_no+","+healthcare_card_no+","+id_card_no+","+pat_id+","+pat_name+","+pat_sex+","+charge_total+","+cash+","+zhzf+","+tczf+","+trading_state+","+healthcare_card_trade_state+","+his_state+","+bank_card_id+","+reg_info+","+trade_no+","+zzj_id+","+stream_no);
		
		//var params = {"RespCode":$("#RespCode").val(),"RespInfo":$("#RespInfo").val(),"idCard":$("#idCard").val(),"Amount":$("#Amount").val(),"trade_no":$("#trade_no").val(),"Batch":$("#Batch").val(),"TransDate":$("#TransDate").val(),"pat_id":$("#pat_code").val(),"business_type":$("#business_type").val()}; 
		//alert(22);
		//alert("开始记录交易数据");alert($("#pat_name").val());
		/*var params = {"RespCode":$("#RespCode").val(),"RespInfo":$("#RespInfo").val(),"idCard":$("#idCard").val(),"Amount":$("#Amount").val(),"trade_no":$("#trade_no").val(),"Batch":$("#Batch").val(),"TransDate":$("#TransDate").val(),"Ref":$("#Ref").val(),"pat_id":$("#pat_code").val(),"business_type":$("#business_type").val(),"out_trade_no":$("#stream_no").val(),"pat_name":$("#pat_name").val()}; */
		var params = {"RespCode":$("#RespCode").val(),"RespInfo":$("#RespInfo").val(),"idCard":$("#idCard").val(),"Amount":$("#Amount").val(),"trade_no":$("#trade_no").val(),"Batch":$("#Batch").val(),"TransDate":$("#TransDate").val(),"Ref":$("#Ref").val(),"pat_id":$("#pat_code").val(),"business_type":$("#business_type").val(),"out_trade_no":$("#stream_no").val(),"pat_name":$("#pat_name").val()};
		$.ajax({
			url:"/hddevice/mz/index.php/ZiZhu/Index/witeJyRecordToDataBaseBank", 
			type:'post',
			dataType:'json',
			data:params,
			success:function(data){
				//alert(22)
			}
		})
}
function bank_refund(){
	    var params = {"out_trade_no":$("#stream_no").val(),"refund_status":$("#refund_bank").val()}; 
		//alert(22);
		//alert("开始记录交易数据");
		$.ajax({
			url:"/hddevice/mz/index.php/ZiZhu/Index/witeJyRecordToDataBaseRefundBank", 
			type:'post',
			dataType:'json',
			data:params,
			success:function(data){
				// alert(data);
			}
		})
}
/**
*日志记录函数
**/
function writeLog(logtxt,logtype){
	
	var params = {"log_txt":logtxt,"log_type":logtype,"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"direction":"操作步骤","op_code":$("#op_code").val()};
	$.ajax({
			url:"/hddevice/mz/index.php/ZiZhu/Index/writeLogs", 
			type:'post',
			dataType:'json',
			data:params,
			success:function(data){}
	})
}
//存储成功的记录
function Transaction(original_cost,current_price){
	// patient_id    患者id
	// user_name     患者姓名
	// out_trade_no  订单号
	// transaction_type  交易类型
	// transaction_mode  支付方式
	// transaction_conente  交易内容
	// original_cost 原价
	// current_price 现价
	// zzj_id        自助机id
	var params = {"patient_id":$("#pat_code").val(),"user_name":$("#pat_name").val(),"out_trade_no":$("#stream_no").val(),"transaction_type":$("#card_code").val(),"transaction_mode":$("#pay_type").val(),"transaction_conente":$("#business_type").val(),"original_cost":original_cost,"current_price":current_price,'zzj_id':$("#zzj_id").val()};	
	$.ajax({
			url:"/hddevice/mz/index.php/ZiZhu/Index/transaction_logs", 
			type:'post',
			dataType:'json',
			data:params,
			success:function(data){}
	})

}
//读取就诊卡号方法
function getCardNo(){
	var card_no =window.external.GetCardNo();
	// alert(card_no);
	//var card_no2 = window.external.sfz_card_read();
	//自助挂号业务逻辑
	if($("#business_type").val()=="自助挂号")
	{
		if(card_no!="")
		{
			window.external.send(1,4);
			window.external.Beep();
			$("#jz_card_no_guahao").val(card_no);
			$("#confirm").trigger("click");
		}
	}
	//自助缴费业务逻辑
	if($("#business_type").val()=="自助缴费")
	{

		if(card_no!="")
		{
			window.external.send(1,4);
			window.external.Beep();
			$("#jz_card_no").val(card_no);
			$("#confirm").trigger("click");
		}
	}
}
//读取身份证方法
function getCardNoS(){
	//var card_no =window.external.GetCardNo();
	var card_no2 = window.external.sfz_card_read();
	//自助挂号业务逻辑
	if($("#business_type").val()=="自助挂号")
	{
		if(card_no2!="")
		{
			if(card_no2!="请重放身份证"&&card_no2!="开usb失败"&&card_no2!="读卡失败")
			{
				var s = card_no2.split(",");
				$("#jz_card_no_guahao_sfz").val(s[5]);
				for(var key in interval3)
				{
					clearInterval(interval3[key]);
				}
				window.external.send(1,4);
				$("#confirm").trigger("click");
			}
		}
	}
	//自助缴费业务逻辑
	if($("#business_type").val()=="自助缴费")
	{
        if(card_no2!="")
		{
			if(card_no2!="请重放身份证"&&card_no2!="开usb失败"&&card_no2!="读卡失败")
			{
				var s = card_no2.split(",");
				$("#jz_card_no_sfz").val(s[5]);
				for(var key in interval3){
					clearInterval(interval3[key]);
				}
				window.external.send(1,4);
				$("#confirm").trigger("click");
			}
		}
	}
}


















//ocx控件方法
//初始化控件

// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符， 
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字) 
// 例子： 
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423 
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18 
Date.prototype.Format = function (fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds(), //毫秒
        "w": this.getDay()//星期 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}	

</script>
<style type="text/css">
</style>
<title>自助程序</title>
</head>

<body>
<div style="display:block;" id="cover"></div>
<!-------------------------添加了病人隐藏域----------------------------->
<input type="hidden" class="inhide" id="patient_id" />
<!-------------------------添加了病人隐藏域----------------------------->
<input type="hidden" class="inhide" id="op_code" value=""/>
<input type="hidden" class="inhide" id="dingshi" value=""/>
<input type="hidden" class="inhide" id="pat_code" value=""/>
<input type="hidden" class="inhide" id="card_no" value=""/>
<input type="hidden" class="inhide" id="card_code" value=""/>
<input type="hidden" class="inhide" id="response_type" value=""/>
<input type="hidden" class="inhide" id="times" value="" />
<input type="hidden" class="inhide" id="pat_name" value="" />
<input type="hidden" class="inhide" id="pat_sex" value="" />
<input type="hidden" class="inhide" id="pat_age" value="" />
<input type="hidden" class="inhide" id="cash" value=""/>
<input type="hidden" class="inhide" id="charge_total" value=""/>
<input type="hidden" class="inhide" id="zhzf" value=""/>
<input type="hidden" class="inhide" id="tczf" value=""/>
<input type="hidden" class="inhide" id="pay_seq" value=""/>
<input type="hidden" class="inhide" id="trade_no" value=""/>
<input type="hidden" class="inhide" id="idCard" value=""/><!----支付宝支付帐号--->
<input type="hidden" class="inhide" id="PayStatus" value=""/>
<input type="hidden" class="inhide" id="times_order_no"  value=""/>
<input type="hidden" class="inhide" id="dept_code" value=""/>
<input type="hidden" class="inhide" id="doctor_code" value=""/>
<input type="hidden"  class="inhide" id="dept_name" value=""/>
<input type="hidden" class="inhide" id="doctor_name" value=""/>
<input type="hidden" class="inhide" id="doctor_sn" value=""/>
<input type="hidden" class="inhide" id="unit_sn" value=""/>
<input type="hidden" class="inhide" id="healthcare_card_no" value=""/>
<input type="hidden" id="zzj_id"  value="<?php echo ($id); ?>" />
<input type="hidden"  class="inhide" id="tansType" value="00"  />
<input type="hidden" class="inhide" id="reponse_name" value="" />
<input type="hidden" id="daojishi" class="inhide" value=""/>
<input type="hidden" id="business_type" class="inhide" value=""/>
<input type="hidden" id="pay_type" class="inhide" value=""/>
<input type="hidden" id="yb_flag" class="inhide" value=""/>
<input type="hidden" id="record_sn" class="inhide" value=""/>
<input type="hidden" id="bank_card_num" class="inhide" value=""/>
<input type="hidden" id="personcount" class="inhide" value=""/>
<!---银联隐藏域--->
<input type="hidden" id="refund_bank" class="inhide" value=""/>
<input type="hidden" id="T_RespCode" class="inhide" value=""/>
<input type="hidden" id="RespCode" class="inhide" value=""/>
<input type="hidden" id="RespInfo" class="inhide" value=""/>
<input type="hidden" id="CardNo" class="inhide" value=""/>
<input type="hidden" id="Amount" class="inhide" value=""/>
<input type="hidden" id="Trace" class="inhide" value=""/>
<input type="hidden" id="Batch" class="inhide" value=""/>
<input type="hidden" id="TransDate" class="inhide" value=""/>
<input type="hidden" id="TransTime" class="inhide" value=""/>
<input type="hidden" id="Ref" class="inhide" value=""/>
<input type="hidden" id="Auth" class="inhide" value=""/>
<input type="hidden" id="Memo" class="inhide" value=""/>
<input type="hidden" id="Lrc" class="inhide" value=""/>
<input type="hidden" id="buhao_rukou" class="inhide" value=""/>
<!---本地交易流水号--->
<input type="hidden" id="stream_no" value=""/> 
<input type="hidden" id="bank_success" value=""/> 
<!--------
\*记录当前操作到哪步了

/*获取自费就诊记录 ic_get_pat_info
/*自费费用确认展示 ic_sf_show
/*自费缴费确认提交 ic_sf_save
/*选择支付方式    pay_chose
/*自费选择支付宝支付  zf_pay_zhifubao
/*在卡类型界面选择了医保卡 chose_yb_card
----------> 
<input type="hidden" id="op_now" />
<div class="main_body">
	<div id="dialog">
	    <div>
	        <span style="margin-left: 10px;">&nbsp;&nbsp;密码验证</span><span style="margin-right: 10px;cursor:pointer;float: right;" class="close">X</span>
	    </div>	
		<table>
			
			<tr>
				<td colspan="3"><input class="pwd_yz_tk" type="password" name="" id="" /></td>
			</tr>
			<tr>
				<td colspan="2"><span class="tk_yz_text">请输入密码</span></td>				
				<td><input class="tk_yz_click" type="button" value="验证密码" /></td>
			</tr>
			<tr>
				<td><input class="tk_yz" type="button" value="1" /></td>
				<td><input class="tk_yz" type="button" value="2" /></td>
				<td><input class="tk_yz" type="button" value="3" /></td>
			</tr>
			<tr>
				<td><input class="tk_yz" type="button" value="4" /></td>
				<td><input class="tk_yz" type="button" value="5" /></td>
				<td><input class="tk_yz" type="button" value="6" /></td>
			</tr>
			<tr>
				<td><input class="tk_yz" type="button" value="7" /></td>
				<td><input class="tk_yz" type="button" value="8" /></td>
				<td><input class="tk_yz" type="button" value="9" /></td>
			</tr>
			<tr>
				<td><input class="tk_yz" type="button" value="0" /></td>
				<td><input class="tk_yz_qc" type="button" value="清除" /></td>
				<td class="close"><input type="button" value="退出" /></td>
			</tr>			
		</table>	
	</div>
	<div id="downcount"></div>
	<div id="show_times">2016年10月12日 星期三 &nbsp;16:51</div>
	<!--<div class="main_left_1">
    	 <div class="intro_area">
        	<img src="/hddevice/mz/Public/zizhu/img/hos.jpg" width="450" style="float:left; margin:20px; margin-top:10px; margin-bottom:0px;	 "/>
            <p>北京市海淀医院(北京大学第三医院海淀院区)位于海淀区中关村国家科技自主创新核心区，紧邻地铁四号线、十号线，是一所集医疗、科研、教学、预防保健于一体的大型现代化三级综合性医院，是北京市医保定点医院，2008年北京奥运会、残奥会定点医院。目前承担着北京中医药大学、南 昌大学研究生培养任务;海淀卫校临床教学及实习任务，现为南昌大学教学医院;北京市全科住院医师规范化培训基地。</p><p>海淀医院不仅担负着海淀区300多万人口的医疗服务任务，还同时担负着周边高等学府、科研院所及企业高管人员的健康保健和医疗救护工作，是海淀区公共卫生突发事件应急救治和重大传染性疾病防控的中坚力量。</p>
        </div>
    </div>
    <div class="main_right_1">
    	<ul>
        	<li class="disabled">自助挂号</li>
            <li class="disabled">预约挂号</li>
            <li id="index_zizhu_btn">自助缴费</li>
            
        </ul> 
    </div>-->
    <div class="main_left_1">
    	<!-- <h3></h3> -->
    	<div class="shang">
        <ul>
             <li id="index_zizhu_btn"></li>
            <li id="guahao"></li>
        </ul>
   		 </div>
   		 <div class="zhong">
   		 	<li id="buhao"></li>
            <li id="jianka"></li>
            <li id="chaxun"></li>
   		 </div> 
   		 <div class="xia">
    	<ul>
        	<li id="yuyue_quhao"></li>
			<li id="yuyue_guohao"></li>
        </ul>
        </div>
        <div class="block_top"></div>
        <div class="block_top2" style="text-indent: -9999"> </div>
    </div>
   <div class="main_left_2">
   		<div class="hint mtnum2"style="display:none;margin-top:180px;font-size:62px;margin-left:160px;">
        	<h3>本机器不支持挂号</h3>
        	<h3>请去窗口进行挂号</h3>
        </div>

         <div class="show mtnum2" style="display:none;margin-top:180px;font-size:62px;">
        	<h3>账单结算时间</h3>
        	<h3>禁止使用</h3>
        </div>
        <div class="print_notice mtnum2"  style="display:none;margin-top:180px;font-size:62px;margin-left:70px;">
        	<h3><b>打印机缺纸<br/>暂停使用<br/>请联系二楼门诊服务台</b></h3>
        </div>
        <div class="print_notice1 mtnum2" style="display:none;margin-top:180px;font-size:62px;margin-left:120px;">
        	<h3>打印机卡纸 暂停使用<br/>请联系二楼门诊服务台</h3>
        </div>
    	<!---选择卡类型--->
        <div class="chose_card_type_area mtnum2" style="display:none;">
        	<h3>请您选择卡类型</h3>
            <ul>
            	<li class="ic" id="xz_ic">就诊卡</li>
            	<li class="sfz" id="xz_sfz">身份证</li>
                <li class="yibao" id="xz_yibao">医保卡</li>
            </ul>
        </div>
        <!----就诊卡操作界面---->
        <div class="jiuzhen_op_area mtnum2" style="display:none;">
        	<h3>请扫描就诊卡或输入就诊卡号</h3>
            <input type="text" maxlength="12" class="iptx" value="" id="jz_card_no" />
            <div class="j_keybord_area">
            	<ul class="img_view"></ul>

                <ul class="key">
                	<li class="num" param="1">1</li>
                    <li class="num" param="2">2</li>
                    <li class="num" param="3">3</li>
                    <li class="num" param="4">4</li>
                    <li class="num" param="5">5</li>
                    <li class="num" param="6">6</li>
                    <li class="num" param="7">7</li>
                    <li class="num" param="8">8</li>
                    <li class="num" param="9">9</li>
                    <li class="num" param="0">0</li>
                    <li class="del">删除</li>
                </ul>
                <div class="tips"></div>
            </div>
        </div>
        <!----S身份证操作界面---->
        <div class="jiuzhen_op_area_sfz mtnum2" style="display:none;">
        	<h3>请刷身份证或输入身份证号</h3>
            <input type="text" class="iptx" value="" id="jz_card_no_sfz" />
            <div class="j_keybord_area_sfz">
            	<ul class="img_view" id="img_view_sfz">
            	</ul>

                <ul class="key">
                	<li class="num" param="1">1</li>
                    <li class="num" param="2">2</li>
                    <li class="num" param="3">3</li>
                    <li class="num" param="4">4</li>
                    <li class="num" param="5">5</li>
                    <li class="num" param="6">6</li>
                    <li class="num" param="7">7</li>
                    <li class="num" param="8">8</li>
                    <li class="num" param="9">9</li>
                    <li class="num" param="0">0</li>
                    <li class="num" param="X">X</li>
                    <li class="del">删除</li>
                </ul>
                <div class="tips"></div>
            </div>
        </div>
        <!---------------医保卡插入操作界面-------------------->
        <div class="yb_op_area mtnum2" style="display:none;">
        	<h3>请插入您的医保卡</h3>
            <div class="tips"></div>
        </div>
        <!--------处方列表-------->
        <div class="chufang_area mtnum2" style="display:none;">
          <!--------添加了患者的姓名-------->
          <span style="color:#009FD6;font-size:35px;position:absolute;left:135px;" id="jz_name" ></span>
        	<h3 style="font-size:35px">请确认缴费信息</h3>
            <div class="bar_tit">
            	<ul>
                	<li class="one">处方号</li>
                    <li class="two">就诊科室</li>
                    <li class="thr">金额</li>
                    <li class="four">日期</li>
                </ul>
            </div>
            <div class="chufang_list">
            <h4></h4>
            <h3></h3>
            </div>
        </div>

        <div class="yuyue_record2 mtnum2" style="display:none;">
          <!--------添加了患者的姓名-------->
          <span style="color:#009FD6;font-size:35px;position:absolute;left:135px;" id="jz_name1" ></span>
            <h3 style="font-size:35px">请确认诊间挂号信息</h3>
            <div class="bar_tit">
                <ul>
                    <li class="one">序号</li>
                    <li class="two">就诊科室</li>
                    <li class="thr">金额</li>
                    <li class="four">日期</li>
                </ul>
            </div>
            <div class="chufang_list1"></div>
            <div class="tips"></div>
        </div>
		
		<div class="result_quhao mtnum2" style="display:none">
           <h3>请确认补挂号费信息</h3>
           	<ul>
               <li class="p1"><span class="s1">患者ID：</span><span class="s2"></span></li>
               <li class="p2">
                           <span class="txt txt_1">姓名：</span><span class="uname"></span> <span class="txt">性别：</span><span class="sex"></span>                
               </li>
               <li class="p6">
                   <span class="fj_cz_room">科室：</span><span class="p_cz_room"></span><span class="fj_cz_doctor">医生：</span><span class="p_cz_doctor"></span>
               </li>
               <li class="p3">
                   <span class="txt">总金额：</span><span class="p_chare_totle"></span>
               </li>
               <li class="p4"><span class="txt">现金支付：</span><span class="p_cash"></span></li>
               <li class="p5"><span class="txt yb_txt">医保账户支付：</span><span class="p_zhzf"></span><span class="txt">统筹支付：</span><span class="p_tczf"></span></li>
               </li>
                  <li class="p7"><span class="s1">医保个人账户总额：</span><span class="yb_zh"></span></li>
           	</ul>
       	</div>

        <div class="fenjie_result mtnum2" style="display:none">
        	<h3>请确认缴费信息</h3>
            <ul>
            	<li class="p1"><span class="s1">患者ID：</span><span class="s2"></span></li>
                <li class="p2">
					<span class="txt txt_1">姓名：</span><span class="uname"></span> <span class="txt">性别：</span><span class="sex"></span>                
                </li>
                <li class="p3">
                	<span class="txt">总金额：</span><span class="p_chare_totle"></span>
                </li>
                <li class="p4"><span class="txt">现金支付：</span><span class="p_cash"></span></li>
                 <li class="p6"><span class="txt">统筹支付：</span><span class="p_tczf"></span></li>
                <li class="p5"><span class="txt yb_txt">医保个人账户支付：</span><span class="p_zhzf"></span></li>
                 <li class="p7"><span class="txt">医保个人账户总额：</span><span class="yb_zh"></span></li>
            </ul>
        </div>
        <!---------选择支付方式--------->
        <div class="chose_pay_type_area mtnum2" style="display:none">
        	<h3>请您选择支付方式</h3>
            <ul>
            	<li class="bank_pay" id="pay_bank">银行卡</li>
                <li class="zhifubao" id="pay_zhifubao">支付宝</li>
            </ul>
        </div>
        <!------------支付宝二维码显示区----------->
        <div class="alipay_ma_show mtnum2" style="display:none">
        	<h3>扫一扫付款</h3>
            <div class="pay_val"></div>
            <div class="erweima">
            	<img src="" width="240" />
            </div>
            <div class="tips">请用手机支付宝扫描付款</div>
        </div>
        <!------------银行卡----------->
        <div class="bank mtnum2" style="display:none">
        	<h3>请插入您的银行卡</h3>
            <div class="tips" id="bank_tips"></div>
            <p>请将<strong>银联</strong>/VISA<strong>标识在上朝外</strong>插入银行卡</p>
        </div>
        <div class="bank_password mtnum2" style="display:none;">
        	<h3>请输入您的银行卡密码</h3>
            <input type="password" class="PinField1" id="PinField" />
            <div class="bank_notice_area">
            	<ul class="bank_img_view"></ul>
                <ul class="bank_lang">
                	<li class="lang">请不要透漏您的密码！</li>
                    <li class="lang">输入密码时请注意遮挡！</li>
                    <li class="lang">密码不足六位请按确认键！</li>
                </ul>
            </div>
            <div class="tips"></div>
        </div>

       	<!--预约取号选择支付页面-->
        <div class="chose_pay_type_area_quhao mtnum2" style="display:none">
        	<h3>请您选择支付方式</h3>
            <ul>
            	<li class="bank_pay" id="pay_bank_quhao">银行卡</li>
                <li class="zhifubao" id="pay_zhifubao_quhao">支付宝</li>
            </ul>
        </div>

         <!--银行卡-->
        <div class="bank_quhao mtnum2" style="display:none">
            <h3>请插入您的银行卡(如图)</h3>
            <div class="tips" id="bank_tips"></div>
            <p>请将<strong>银联</strong>/VISA<strong>标识在上朝外</strong>插入银行卡</p>
        </div>
        <div class="bank_password_quhao mtnum2" style="display:none;">
            <h3>请输入您的银行卡密码</h3>
            <input type="text" class="PinField1" id="PinFieldBu" />
            <div class="bank_notice_area_quhao">
                <ul class="bank_img_view"></ul>
                <ul class="bank_lang">
                    <li class="lang">请不要透漏您的密码！</li>
                    <li class="lang">输入密码时请注意遮挡！</li>
                    <li class="lang">密码不足六位请按确认键！</li>
                </ul>
            </div>
            <div class="tips"></div>
        </div>

        <!--付款成功-->
         <!--预约取号支付宝二维码页面-->
        <div class="alipay_ma_show_quhao mtnum2" style="display:none">
            <h3>扫一扫付款</h3>
            <div class="pay_val"></div>
            <div class="erweima">
            <img src="" width="240" />
            </div>
            <div class="tips">请用手机支付宝扫描付款</div>
        </div>
        <!--------------付款成功-------------->
        <div class="pay_success mtnum2" style="display:none">
        	<h3>操作完毕 !</h3>
        </div>
   </div>
    <div class="main_left_guahao">
   		  <!--------------挂号选择卡类型-------------->
   		 <div class="chose_card_type_area_guahao mtnum2" style="display:none;">
        	<h3>请您选择卡类型</h3>
            <ul>
            	<li class="ic" id="xz_ic_guahao">就诊卡</li>
            	<li class="sfz" id="xz_sfz_guahao">身份证</li>
                <li class="yibao" id="xz_yibao_guahao">医保卡</li>
            </ul>
        </div>
         <!--------------挂号就诊卡操作界面-------------->
        <div class="jiuzhen_op_area_guahao mtnum2" style="display:none;">
        	<h3>请扫描就诊卡或输入就诊卡号</h3>
            <input type="text" maxlength="12" class="iptx" value="" id="jz_card_no_guahao" />
            <div class="j_keybord_area_guahao">
            	<ul class="img_view"></ul>

                <ul class="key">
                	<li class="num" param="1">1</li>
                    <li class="num" param="2">2</li>
                    <li class="num" param="3">3</li>
                    <li class="num" param="4">4</li>
                    <li class="num" param="5">5</li>
                    <li class="num" param="6">6</li>
                    <li class="num" param="7">7</li>
                    <li class="num" param="8">8</li>
                    <li class="num" param="9">9</li>
                    <li class="num" param="0">0</li>
                    <li class="del">删除</li>
                </ul>
                <div class="tips_guahao"></div>
            </div>
        </div>
         <!--------------挂号身份证操作界面-------------->
        <div class="jiuzhen_op_area_guahao_sfz mtnum2" style="display:none;">
        	<h3>请刷身份证或输入身份证号</h3>
            <input type="text" class="iptx" value="" id="jz_card_no_guahao_sfz" />
            <div class="j_keybord_area_guahao_sfz">
            	<ul class="img_view" id="img_view_sfz_g"></ul>

                <ul class="key">
                	<li class="num" param="1">1</li>
                    <li class="num" param="2">2</li>
                    <li class="num" param="3">3</li>
                    <li class="num" param="4">4</li>
                    <li class="num" param="5">5</li>
                    <li class="num" param="6">6</li>
                    <li class="num" param="7">7</li>
                    <li class="num" param="8">8</li>
                    <li class="num" param="9">9</li>
                    <li class="num" param="0">0</li>
                    <li class="num" param="X">X</li>
                    <li class="del">删除</li>
                </ul>
                <div class="tips_guahao"></div>
            </div>
        </div>
        <div class="yb_op_area_guahao mtnum2" style="display:none;">
        	<h3>请插入您的医保卡</h3>
            <div class="tips_guahao"></div>
        </div>
         <!--------自助挂号选择科室-------->
        <div class="chose_room mtnum2" style="display:none;">
         <span style="color:#009FD6;font-size:35px;position:absolute;left:135px;top:8px" id="guahao_name" ></span>
      		<h5 style="margin-top:-20px;">请选择科室</h5>
            <div class="room_list" style="margin-top:20px">
            	<ul id='attr_list' style='height:55px'></ul>
                <div id='sub_list'></div>
             	<h4></h4>
             	<h3></h3>
            </div>
        </div>
          <!--------自助挂号选择医生-------->
         <div class="chose_doctor mtnum2" style="display:none;">
            <!------<span style="color:#009FD6;font-size:35px;position:absolute;left:105px;" id="keshi_name" ></span>-------->
      		<h3>请选择挂号医生<h3/>
            <div class="doctor_list">
            	
           </div>
           <div class="page">
           		<span class="total_page"></span>
           		<span class="prev">上一页</span>
                <span class="next">下一页</span>
           </div>
        </div>

        <div class="yuyue_record2 mtnum2" style="display:none;">
          <!--------添加了患者的姓名-------->
          <span style="color:#009FD6;font-size:35px;position:absolute;left:135px;" id="jz_name2" ></span>
            <h3 style="font-size:35px">请确认诊间挂号信息</h3>
            <div class="bar_tit">
                <ul>
                    <li class="one">序号</li>
                    <li class="two">就诊科室</li>
                    <li class="thr">金额</li>
                    <li class="four">日期</li>
                </ul>
            </div>
            <div class="chufang_list1"></div>
            <div class="tips"></div>
        </div>
		
		<div class="result_quhao mtnum2" style="display:none">
           <h3>请确认补挂号费信息</h3>
           	<ul>
               <li class="p1"><span class="s1">患者ID：</span><span class="s2"></span></li>
               <li class="p2">
                           <span class="txt txt_1">姓名：</span><span class="uname"></span> <span class="txt">性别：</span><span class="sex"></span>                
               </li>
               <li class="p6">
                   <span class="fj_cz_room">科室：</span><span class="p_cz_room"></span><span class="fj_cz_doctor">医生：</span><span class="p_cz_doctor"></span>
               </li>
               <li class="p3">
                   <span class="txt">总金额：</span><span class="p_chare_totle"></span>
               </li>
               <li class="p4"><span class="txt">现金支付：</span><span class="p_cash"></span></li>
               <li class="p5"><span class="txt yb_txt">医保账户支付：</span><span class="p_zhzf"></span><span class="txt">统筹支付：</span><span class="p_tczf"></span></li>
               </li>
                  <li class="p7"><span class="s1">医保个人账户总额：</span><span class="yb_zh"></span></li>
           	</ul>
       	</div>
       	<div class='suohao_guahao mtnum2' style="display:none">
       		<h3>锁定号源中，请稍后...</h3>
       	</div>
         <!--------自助挂号划价分解结果-------->
        <div class="fenjie_result_guahao mtnum2" style="display:none">
        	<h3>请确认挂号信息</h3>
            <ul>
            	<li class="p1"><span class="s1">患者ID：</span><span class="s2"></span></li>
                <li class="p2">
					<span class="txt txt_1">姓名：</span><span class="uname"></span> <span class="txt">性别：</span><span class="sex"></span>                
                </li>
                <li class="p6">
                	<span class="fj_cz_room">科室：</span><span class="p_cz_room"></span><span class="fj_cz_doctor">医生：</span><span class="p_cz_doctor"></span>
                </li>
                <li class="p3">
                	<span class="txt">总金额：</span><span class="p_chare_totle"></span>
                </li>
                <li class="p4"><span class="txt">现金支付：</span><span class="p_cash"></span></li>
                <li class="p5"><span class="txt yb_txt">医保账户支付：</span><span class="p_zhzf"></span><span class="txt">统筹支付：</span><span class="p_tczf"></span></li>
                </li>
               	<li class="p7"><span class="s1">医保个人账户总额：</span><span class="yb_zh"></span></li>
            </ul>
        </div>
        <!---------挂号选择支付方式--------->
        <div class="chose_pay_type_area_guahao mtnum2" style="display:none">
        	<h3>请您选择支付方式</h3>
            <ul>
            	<li class="bank_pay" id="pay_bank_guahao">银行卡</li>
                <li class="zhifubao" id="pay_zhifubao_guahao">支付宝</li>
            </ul>
        </div>
        <!------------自费挂号支付宝二维码显示区----------->
        <div class="alipay_ma_show_guahao mtnum2" style="display:none">
        	<h3>扫一扫付款</h3>
            <div class="pay_val"></div>
            <div class="erweima">
            	<img src="" width="240" />
            </div>
            <div class="tips">请用手机支付宝扫描付款</div>
        </div>
        <!------------银行卡----------->
        <div class="bank_guahao mtnum2" style="display:none">
        	<h3>请插入您的银行卡(如图)</h3>
            <div class="tips" id="bank_tips"></div>
            <p>请将<strong>银联</strong>/VISA<strong>标识在上朝外</strong>插入银行卡</p>
        </div>
        <div class="bank_password_guahao mtnum2" style="display:none;">
        	<h3>请输入您的银行卡密码</h3>
            <input type="password" class="PinField1" id="PinFieldGh" />
            <div class="bank_notice_area_guahao">
            	<ul class="bank_img_view"></ul>
                <ul class="bank_lang">
                	<li class="lang">请不要透漏您的密码！</li>
                    <li class="lang">输入密码时请注意遮挡！</li>
                    <li class="lang">密码不足六位请按确认键！</li>
                </ul>
            </div>
            <div class="tips"></div>
        </div>

       	<!--预约取号选择支付页面-->
        <div class="chose_pay_type_area_quhao1 mtnum2" style="display:none">
        	<h3>请您选择支付方式</h3>
            <ul>
            	<li class="bank_pay" id="pay_bank_quhao1">银行卡</li>
                <li class="zhifubao" id="pay_zhifubao_quhao1">支付宝</li>
            </ul>
        </div>

         <!--银行卡-->
        <div class="bank_quhao1 mtnum2" style="display:none">
            <h3>请插入您的银行卡</h3>
            <div class="tips" id="bank_tips"></div>
            <p>请将<strong>银联</strong>/VISA<strong>标识在上朝外</strong>插入银行卡</p>
        </div>
        <div class="bank_password_quhao1 mtnum2" style="display:none;">
            <h3>请输入您的银行卡密码</h3>
            <input type="text" class="PinField1" id="PinFieldBu1" />
            <div class="bank_notice_area_quhao1">
                <ul class="bank_img_view"></ul>
                <ul class="bank_lang">
                    <li class="lang">请不要透漏您的密码！</li>
                    <li class="lang">输入密码时请注意遮挡！</li>
                    <li class="lang">密码不足六位请按确认键！</li>
                </ul>
            </div>
            <div class="tips"></div>
        </div>

        <!--付款成功-->
         <!--预约取号支付宝二维码页面-->
        <div class="alipay_ma_show_quhao1 mtnum2" style="display:none">
            <h3>扫一扫付款</h3>
            <div class="pay_val"></div>
            <div class="erweima">
            <img src="" width="240" />
            </div>
            <div class="tips">请用手机支付宝扫描付款</div>
        </div>
        <!--------------付款成功-------------->
        <div class="pay_success mtnum2" style="display:none">
        	<h3>操作完毕 !</h3>
        </div>
   </div>
   <div class="btn_bu" style="display:none;">
   	<ul>
   		<b><li id="bugua">补挂号费入口</li></b>
    </ul>
   </div>
   <div class="btn_area" style="display:none;">
   	<ul>
    	<li id="confirm">确定</li>
        <li id="fanhui">返回</li>
        <li id="tuichu">退出</li>
    </ul>
   </div>
   <div id="send-msg" class="send-msg">
            <table width="100%" border="0">
                <caption>提示</caption>
                <tr>
                    <td style="font-size:30px;"><strong>对不起，本科室只允许自费挂号！请使用就诊卡或身份证进行挂号缴费！是否使用就诊卡或身份证进行挂号缴费？</strong></td>
                </tr>
                <tr>
                    <td class="xz">
                        <button onclick="send();" class="msgbtn">确定</button>
                        <button onclick="reset();" class="msgbtn">取消</button>
                    </td>
                </tr>
            </table>
        </div>
</div>
</div>
<script> 
//ocx控件初始化
/*function OcxInit(){
	ocx = document.getElementById("pos");
}

function UMS_Init()
{
	var iret;
	ocx.MySetApptype(1);
	iret = ocx.UMSInit();
	return iret;
}
//设置参数
function UMS_SetReq()
{
	var iret;
	ocx.Request="00000001"+"00000002"+"00"+cash($("#cash").val())+"666666"+"20161215"+"121212121212"+"777777"+"888888"+"20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020"+"999";
	iret = ocx.UMSsetreq();
	return iret;
}
//进卡
function UMS_EnterCard()
{
	var iret;
	iret=ocx.UmsEnterCard();
	return iret;
}
//检查卡
function UMS_CheckCard()
{
	var iret;
	iret=ocx.UMSCheckCard();
	return iret;
}
//读卡
function UMS_ReadCard()
{
	var iret;
	iret=ocx.UmsReadCard();
	cardNum = ocx.CardNum;
	if(iret==0 || iret ==1)
	{
		cardNum=ocx.CardNum;
	}
	else if(iret == 2){
		cardNum=ocx.CardNum;
		}
	else if(iret == 3){
		cardNum=ocx.CardNum;
		}
	else 		
		alert("读卡失败");
	return cardNum;
}

//启用键盘加密
function UMS_StartPin()
{
		var iret
		iret=ocx.UMSStartPin();
		return iret;
}
//弹出卡
function UMS_EjectCard()
{
	var iret
	iret=ocx.UMSEjectCard();
}
//关闭读卡器
function UMS_CardClose()
{
	var iret
	iret=ocx.UMSCardClose();
}
//交易函数
function UMS_TransCard()
{
	ocx.UMSTransCard();
	document.getElementById("RespCode").value=ocx.RespCode;
	document.getElementById("RespInfo").value=ocx.RespInfo;
	document.getElementById("idCard").value=ocx.RespCardNo;
	document.getElementById("Amount").value=ocx.RespAmount;
	document.getElementById("trade_no").value=ocx.RespTrace;
	document.getElementById("Batch").value=ocx.RespBatch;
	document.getElementById("TransDate").value=ocx.RespTransDate;
	document.getElementById("TransTime").value=ocx.RespTransTime;
	document.getElementById("Ref").value=ocx.RespRef;
	document.getElementById("Auth").value=ocx.RespAuth;
	document.getElementById("Memo").value=ocx.RespMemo;
	document.getElementById("Lrc").value=ocx.RespLrc;
}
*/
//获取键值函数
var time;
function UMS_GetOnePass()
{
	var i;
	var iret=window.external.Out_UMS_GetOnePass();
	if(iret==42)//"*"
	  {PinCounter=PinCounter+1;
	  }
	else if(iret==8)//退格
	{
		if(PinCounter>0)
			PinCounter=PinCounter-1;			
	}
	else if(iret==27)//取消
	{
		clearInterval(time);
		PinCounter=0;
		window.external.Out_UMS_EjectCard();
		$("#tuichu").trigger("click");
	}
	else if(iret==13)//确认
	{
		clearInterval(time);
		setTimeout(function(){
			var get_pin_flag =window.external.Out_UMS_GetPin();
			if(get_pin_flag==0){
			$("#confirm").trigger("click");
			}
		},1000)
	}
	else if(iret==2)//超时
	{
		clearInterval(time);
		$(".bank_password .tips").html("用户输入超时");
		setTimeout(function(){
						window.external.Out_UMS_EjectCard();
						$("#tuichu").trigger("click");
					},1000)
	}
	else if(iret==0)
	{
		PinCounter=PinCounter;
	}
	else
	{
	}
	document.getElementById("PinField").value="";
	for(i=0;i<PinCounter;i++){
	document.getElementById("PinField").value=document.getElementById("PinField").value+"*";
	}
}
function UMS_GetOnePassGh()
{
	var i;
	var iret=window.external.Out_UMS_GetOnePass();
	if(iret==42)//"*"
	  {PinCounter=PinCounter+1;
	  }
	else if(iret==8)//退格
	{
		if(PinCounter>0)
			PinCounter=PinCounter-1;			
	}
	else if(iret==27)//取消
	{
		clearInterval(time);
		PinCounter=0;
		window.external.Out_UMS_EjectCard();
		$("#tuichu").trigger("click");
	}
	else if(iret==13)//确认
	{
		clearInterval(time);
		setTimeout(function(){
			var get_pin_flag =window.external.Out_UMS_GetPin();
			if(get_pin_flag==0){
			$("#confirm").trigger("click");
			}
		},1000)
	}
	else if(iret==2)//超时
	{
		clearInterval(time);
		$(".bank_password .tips").html("用户输入超时");
		setTimeout(function(){
						window.external.Out_UMS_EjectCard();
						$("#tuichu").trigger("click");
					},1000)
	}
	else if(iret==0)
	{
		PinCounter=PinCounter;
	}
	else
	{
	}
	document.getElementById("PinFieldGh").value="";
	for(i=0;i<PinCounter;i++){
	document.getElementById("PinFieldGh").value=document.getElementById("PinFieldGh").value+"*";
	}
}
function PinProcess()
{
	document.getElementById("PinField").value= "";
	document.getElementById("PinField").style.display = "block";
	PinCounter=0;
	time=setInterval(UMS_GetOnePass,100);        
}
function PinProcessGh()
{
	document.getElementById("PinFieldGh").value= "";
	document.getElementById("PinFieldGh").style.display = "block";
	PinCounter=0;
	time=setInterval(UMS_GetOnePassGh,100);        
}





function UMS_GetOnePassBu()
{
	var i;
	var iret=window.external.Out_UMS_GetOnePass();
	if(iret==42)//"*"
	  {PinCounter=PinCounter+1;
	  }
	else if(iret==8)//退格
	{
		if(PinCounter>0)
			PinCounter=PinCounter-1;			
	}
	else if(iret==27)//取消
	{
		clearInterval(time);
		PinCounter=0;
		window.external.Out_UMS_EjectCard();
		$("#tuichu").trigger("click");
	}
	else if(iret==13)//确认
	{
		clearInterval(time);
		setTimeout(function(){
			var get_pin_flag =window.external.Out_UMS_GetPin();
			if(get_pin_flag==0){
			$("#confirm").trigger("click");
			}
		},1000)
	}
	else if(iret==2)//超时
	{
		clearInterval(time);
		$(".bank_password .tips").html("用户输入超时");
		setTimeout(function(){
						window.external.Out_UMS_EjectCard();
						$("#tuichu").trigger("click");
					},1000)
	}
	else if(iret==0)
	{
		PinCounter=PinCounter;
	}
	else
	{
	}
	document.getElementById("PinFieldBu").value="";
	for(i=0;i<PinCounter;i++){
	document.getElementById("PinFieldBu").value=document.getElementById("PinFieldBu").value+"*";
	}
}
function PinProcessBu()
{
	document.getElementById("PinFieldBu").value= "";
	document.getElementById("PinFieldBu").style.display = "block";
	PinCounter=0;
	time=setInterval(UMS_GetOnePassBu,100);        
}

function UMS_GetOnePassBu1()
{
	var i;
	var iret=window.external.Out_UMS_GetOnePass();
	if(iret==42)//"*"
	  {PinCounter=PinCounter+1;
	  }
	else if(iret==8)//退格
	{
		if(PinCounter>0)
			PinCounter=PinCounter-1;			
	}
	else if(iret==27)//取消
	{
		clearInterval(time);
		PinCounter=0;
		window.external.Out_UMS_EjectCard();
		$("#tuichu").trigger("click");
	}
	else if(iret==13)//确认
	{
		clearInterval(time);
		setTimeout(function(){
			var get_pin_flag =window.external.Out_UMS_GetPin();
			if(get_pin_flag==0){
			$("#confirm").trigger("click");
			}
		},1000)
	}
	else if(iret==2)//超时
	{
		clearInterval(time);
		$(".bank_password .tips").html("用户输入超时");
		setTimeout(function(){
						window.external.Out_UMS_EjectCard();
						$("#tuichu").trigger("click");
					},1000)
	}
	else if(iret==0)
	{
		PinCounter=PinCounter;
	}
	else
	{
	}
	document.getElementById("PinFieldBu1").value="";
	for(i=0;i<PinCounter;i++){
	document.getElementById("PinFieldBu1").value=document.getElementById("PinFieldBu1").value+"*";
	}
}
function PinProcessBu1()
{
	document.getElementById("PinFieldBu1").value= "";
	document.getElementById("PinFieldBu1").style.display = "block";
	PinCounter=0;
	time=setInterval(UMS_GetOnePassBu1,100);        
}
/*
function UMS_GetPin()
{
	var iret=ocx.UMSGetPin();
	return iret;
}*/
//格式化金额字符串
function cash(cash){
	var money = new Number(cash).toFixed(2);
	var length =parseInt((money*100)).toString().length;
	var n = 12-length;
	var yl_cash="";
	for(i=0;i<n;i++){
		yl_cash+="0";
		}
		return yl_cash+parseInt((money*100).toFixed(2)).toString();
	}
//交易成功返回字符串处理
///////////////右键
if(document.all){
    document.onselectstart= function(){return false;}; //for ie
}else{
    document.onmousedown= function(){return false;};
    document.onmouseup= function(){return true;};
}
document.onselectstart = new Function('event.returnValue=false;');
   $(document).bind("contextmenu",function(){return false;});  
    $(document).bind("keydown",function(e){ 
e=window.event||e; 
if(e.keyCode==116){ 
e.keyCode = 0; 
return false; 
} 
});
    ////////////////////////////
function send(){

/*alert($("#business_type").val());*/
		window.external.send(1,4);
	    $("#jz_card_no_guahao").val("");
	    $("#jz_card_no_guahao_sfz").val("");
	    $("#op_now").val("choose_card_guahao");
	    $(".mtnum2").hide();
	    $(".chose_card_type_area_guahao").show();
	    $(".chose_room h4").html("");
	    $(".chose_room h3").html("");
        $("#guahao_name").html("");
        $("#attr_list").html("");
        $("#sub_list").html("");
        $(".yb_op_area_guahao .tips_guahao").html('');
        $(".btn_area").show();

        $(".btn_bu").hide();
        $("#jz_card_no_guahao").val("");
        $("#jz_card_no_guahao_sfz").val("");
        $(".btn_bu").hide();
        $("#bugua").css({"visibility":"hidden"});
        $("#op_now").val("");
		$("#jz_card_no").val("");
		$(".mtnum2").hide();
		$(".main_left_1").show();
		$(".main_right_1").show();
		$(".btn_area").hide();
	//window.external.FreeYBIntfDll();
	for(var key in interval3){
		clearInterval(interval3[key]);
	}
	for(var key in interval2){
		clearTimeout(interval2[key]);
	}
	$("#downcount").html("");
        sendMsghide();
        window.external.MoveOutCard();
		window.external.DisAllowCardIn();
		window.external.FreeYBIntfDll();
        key_value="";
        key_value2="";
        location.reload();




}
function reset(){
	sendMsghide();
}


function sendMsg(){
    $("#send-msg").show();
    $("#cover").show();
}

function sendMsghide(){
    $(".send-msg").hide();
    $("#cover").hide();
}
/*$("#send-msg").click(function(){
    sendMsghide();
})*/

$(document).ready(function(){
    $(".send-msg").hide();
    $("#cover").hide();
/*    $("#cover").click(function(){
        $("send-msg").hide();
        $(this).hide();
    })*/
})
</script> 
</body>
</html><SCRIPT Language=VBScript><!--

//--></SCRIPT>