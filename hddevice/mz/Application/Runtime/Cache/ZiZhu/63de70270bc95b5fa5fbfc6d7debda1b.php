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
<script language="javascript" type="text/javascript" src="/hddevice/mz/Public/zizhu/js/jquery.qrcode.min.js"></script>
<script language="javascript">
 window.onload = function () {
    $('#code').qrcode("http://www.baidu.com");
        stime();
        //window.external.send(1,4);
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
<script language="javascript">
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
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}

$(function(){

 $("#op_now").val("chose_card_type_area");
 //$("#confirm").hide();
 $("#confirm").css({"visibility":"hidden"});
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
var key_value="";
var key_value2="";
daojishi(30);
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
/**************************科室选项卡切换*********************************************************/
$(document).on("click","#attr_list li",function()
{   
    $("#attr_list li").eq($(this).index()).addClass("current").siblings().removeClass("current");
    $("#sub_list div").hide().eq($(this).index()).show();
                                                
                                                })

$(document).on("click","#time_list li",function()
{   
    $("#time_list li").eq($(this).index()).addClass("current").siblings().removeClass("current");
    //$("#sub_list div").hide().eq($(this).index()).show();
                                                
                                                })
/*******身份证数字键盘开始********************************************************************************/
$(".j_keybord_area_sfz .key li.num").on("mousedown",function(){
    //$(".j_keybord_area .key li").removeClass("active");
    
    $(this).addClass("active");
    key_value+=$(this).attr("param");
    $("#jz_card_no_sfz").val(key_value);

})
$(".j_keybord_area_sfz .key li.del").on("click",function(){
     var jz_card_no_sfz_val=$("#jz_card_no_sfz").val();
     var a="";
     if(jz_card_no_sfz_val.length>0){
         var a=jz_card_no_sfz_val.substr(0,jz_card_no_sfz_val.length-1);
    }
      $("#jz_card_no_sfz").val(a);   
    key_value = a;
    key_value2="";
})
$(".j_keybord_area_sfz .key li").on("mouseup",function(){
    $(".j_keybord_area_sfz .key li").removeClass("active");
    //$(this).addClass("active");
})
/*******挂号数字键盘开始********************************************************************************/

$(".j_keybord_area_quhao .key li.num").on("mousedown",function(){
    //$(".j_keybord_area .key li").removeClass("active");
    
    $(this).addClass("active");
    key_value+=$(this).attr("param");
    $("#jz_card_no_quhao").val(key_value);

})
$(".j_keybord_area_quhao .key li.del").on("click",function(){
    var jz_card_no_quhao=$("#jz_card_no_quhao").val();
     var a="";
     if(jz_card_no_quhao.length>0){
         var a=jz_card_no_quhao.substr(0,jz_card_no_quhao.length-1);
    }
    $("#jz_card_no_quhao").val(a);    
    key_value = a;
    key_value2="";
})
$(".j_keybord_area_quhao .key li").on("mouseup",function(){
    $(".j_keybord_area_quhao .key li").removeClass("active");
    //$(this).addClass("active");
})




var key_values="";
$(".j_keybord_area_phone .key li.num").on("mousedown",function(){
    key_value+=$(this).attr("param");
    $("#pat_phone").val(key_value);

})
$(".j_keybord_area_phone .key li.del").on("click",function(){
    var phone=$("#pat_phone").val();
    var b="";
     if(phone.length>0){
         var b=phone.substr(0,phone.length-1);
    }
    $("#pat_phone").val(b);    
    key_value = b;
    key_value2="";
})
/*******挂号数字键盘结束********************************************************************************/

$(document).on("click",".sure_quhao",function(){
    

   
    if($("#business_type").val()=="预约取号"){
        
        switch($("#card_code").val()){
        case "21":
        var record_sn=$(this).attr("record_sn");
        $("#reord_sn").val(record_sn);
        var req_type=$(this).attr("req_type");
        $("#req_type").val(req_type);
        //alert(record_sn);
        var unit_name=$(this).attr("unit_name");
        var doctor_name=$(this).attr("doctor_name");

        
        daojishi(100);
        var index_load="";
        var params = {"record_sn":record_sn,"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"req_type":req_type};
        $.ajax({
                    url:"/hddevice/mz/index.php/ZiZhu/YuYue/yuyue_huajia", 
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
                                if(doctor_name == "undefined"){
                                $(".result_quhao .p6 .p_cz_doctor").text("");
                            }else{
                                $(".result_quhao .p6 .p_cz_doctor").text(doctor_name);
                            }
                            
                        }else{
                            layer.msg(data['result']['execute_message'], {icon: 14,time:2000});
                        }
           }
      })
      break;
      case '20':
    
        var record_sn=$(this).attr("record_sn");
        var gh_sequence=$("#gh_sequence").val();
        //alert(gh_sequence);
        var record_sn_se = record_sn+"_"+gh_sequence;
        var req_type=$(this).attr("req_type");
        var unit_name=$(this).attr("unit_name");
        var doctor_name=$(this).attr("doctor_name");
        
        daojishi(100);
        var index_load="";
        var gh_flag="2";
        var yb_input_param = record_sn_se+"&"+$("#pat_code").val()+"&"+$("#card_code").val()+"&"+$("#card_no").val()+"&"+$("#response_type").val()+"&"+$("#zzj_id").val()+"&"+gh_flag;
        //alert(yb_input_param);
        index_load2 = layer.msg('医保划价分解中,请稍后...', {icon: 16,time:20000});
        setTimeout(function(){
                                    //调用本地医保dll划价
                                var patinfo = window.external.YYT_YB_GH_CALC(yb_input_param);
                                var params = {"input_xml":patinfo,"op_code":$("#op_code").val()}; 
                                /*alert(patifo);*/
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
                                             
                                                if(doctor_name == "undefined"){                                                  

                                                    $(".result_quhao .p6 .p_cz_doctor").text("");
                                                }else{
                                                    
                                                     $(".result_quhao .p6 .p_cz_doctor").text(doctor_name);
                                                }
                                               
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
}
if($("#business_type").val()=="补挂号费"){

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
            /*alert("对不起，本科室只允许自费挂号！请使用就诊卡或身份证进行挂号缴费！");*/
            sendMsg();
            return;
        }

    }


        var gh_flag="3";
       // $("#gh_flag").val("3");
        daojishi(100);
        var index_load="";
        var yb_input_param = record_sn_se+"&"+$("#pat_code").val()+"&"+$("#card_code").val()+"&"+$("#card_no").val()+"&"+$("#response_type").val()+"&"+$("#zzj_id").val()+"&"+gh_flag;
        //alert(yb_input_param);
        index_load2 = layer.msg('医保划价分解中,请稍后...', {icon: 16,time:20000});
        setTimeout(function(){
                                    //调用本地医保dll划价     
                                var patinfo = window.external.YYT_YB_GH_CALC(yb_input_param);
                               // alert(patinfo);
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
                                                 $("#op_now").val("ic_pay_info_show_quhao");
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
    
    }
})

//预约挂号选择了就诊卡
$("#xz_ic").on("click",function(){
 
      /*  window.external.PrtJzDateCreatGuaHao("678","12","12","12","12","12","12","12","12","12","12","12","12","12","垫付："+"12","12","12","12","","12","12","12","12","12","12","12");*/
       writeLog("选择了就诊卡");
       window.external.send(2,2);
    $("#op_code").val(new Date().Format("yyyyMMddhhmmssS"));
    $(".chose_card_type_area").hide();
    $(".jiuzhen_op_area_quhao").show();
    $("#jz_card_no_quhao").focus();
    $("#card_code").val("21");
    $("#op_now").val("ic_get_pat_info");
    $("#business_type").val("预约取号")
    $(".btn_area").show();
    $("#confirm").css({"visibility":"visible"});
    //var flag = window.external("InitIC","");
    jzk_flag = window.external.InitIC();
    if(jzk_flag>0){
            $(".jiuzhen_op_area_quhao .tips_quhao").text("初始化成功");
        
        interval = setInterval(getCardNo, "1000");
        interval3.push(interval);
        
    }else{
        $(".jiuzhen_op_area_quhao .tips_quhao").text("初始化失败");
    }
    //window.external.nTextInput();
    daojishi(60);
    })
//预约取号选择了身份证 +new Date().getDate+1
$("#xz_sfz").on("click",function(){
  
   // alert(time);
   writeLog("选择了身份证");
    window.external.send(6,2);
    $("#op_code").val(new Date().Format("yyyyMMddhhmmssS"));
    $(".chose_card_type_area").hide();
    $(".jiuzhen_op_area_sfz").show();
    $("#jz_card_no_sfz").focus();
    $("#card_code").val("21");
    $("#op_now").val("ic_get_pat_info");
    $(".btn_area").show();
    $("#business_type").val("预约取号");
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

//预约取号选择了医保卡
$("#xz_yibao").on("click",function(){
   // alert(123);
    $("#business_type").val("预约取号");
    $("#op_code").val(new Date().Format("yyyyMMddhhmmssS"));
    window.external.send(1,2);
    $(".mtnum2").hide();

    $(".yb_op_area").show();
    $("#op_now").val("chose_yb_card");
    $("#card_code").val("20");
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
$("#tuichu").on("click",function(){
   // alert(444);
    window.external.send(1,4);
    writeLog("点击退出退到主界面");
    $("#jz_card_quhao").val("");
    $("#jz_card_no_guahao_sfz").val("");
    $("#jz_card_no_sfz").val("");
    $(".op_now").val("");
      <!--------添加了用户的姓名-------->
    $("#jz_name").html("");
    $("#guahao_name").html("");
    $("#keshi_name").html("");
    $("#attr_list").html("");
    $("#attr_time").html("");
    $("#sub_list").html("");
    $(".chose_room h4").html("");
    $(".tips").html("");
    $(".alipay_ma_show .tips").html("请用手机支付宝扫描付款");
    $(".alipay_ma_show_quhao .tips").html("请用手机支付宝扫描付款");
    $(".alipay_ma_show_quhao .erweima").html("");  
    $(".alipay_ma_show .erweima").html(""); 
    $(".alipay_ma_show .pay_val").html("");
    $(".alipay_ma_show_quhao .pay_val").html("");
    $(".chufang_list").html("<h4></h4>");
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
    var zzj_id = $("#zzj_id").val();
   // alert(zzj_id);
    //alert("/hddevice/mz/index.php/ZiZhu");

   
})
//选择预约取号
$("#xz_yu_quhao").on("click",function(){
    //alert($("#pat_code").val());
     writeLog("选择了预约取号");
     $("#business_type").val("预约取号");
    switch($("#card_code").val()){
        //就诊卡
        //
    case "21":
        if($("#response_type").val()>=90){
            layer.confirm('医保身份患者请使用医保卡挂号,否则本次所有费用将按照自费处理', {
            btn: ['确定'] //按钮
            });


              
            }
    var params = {"patient_id":$("#pat_code").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),};
     var index_load = "";
     $.ajax({
        url:"/hddevice/mz/index.php/ZiZhu/YuYue/patReservationRecord", 
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
               
                layer.close(index_load);
                $(".mtnum2").hide();
                $(".yuyue_record").show();
                 $(".yuyue_record .tips").html("");
                $("#op_now").val("chose_yuyue_record");
                $("#fanhui").css({"visibility":"visible"});
                $("#tuichu").css({"visibility":"visible"});
                $("#confirm").css({"visibility":"hidden"});
                var a = "";

                 if(data['result']['execute_flag']==0){
                 var html="";
                
                 var yuyue_list= data["datarow"];
                 //alert(yuyue_list.length); 
                 var x=1;
                 for(var i=0;i<yuyue_list.length;i++){
                     var m=i+1;
                    
                     var y= '0'+x;
                         x++;
                    var myDate = new Date();
                    var h =  myDate.getHours();
                    var m = myDate.getMinutes();
                    var num =m.toString().length;
                    if(num == 1){
                        m = '0'+m;
                    }
                    var nowtime=h+''+m;
                   // alert(nowtime);
                    
                    if(nowtime > 1200 && yuyue_list[i]["ampm"] == 'a'){
                         html+="<ul><li class='two'>"+y+"</li><li class='thr'>"+yuyue_list[i]["unit_name"]+"</li><li class='four'>"+yuyue_list[i]["charge"]+"</li><li class='six'>"+yuyue_list[i]["request_date"].replace("T"," ").substring(0,10) +"</li><li style='color:red' class='seven1 sure_guoqi' record_sn="+yuyue_list[i]["register_sn"]+" req_type="+yuyue_list[i]["req_type"]+" unit_name="+yuyue_list[i]["unit_name"]+" doctor_name="+yuyue_list[i]["doctor_name"]+">　　已过期</li></ul>";
                    }else{
                        html+="<ul><li class='two'>"+y+"</li><li class='thr'>"+yuyue_list[i]["unit_name"]+"</li><li class='four'>"+yuyue_list[i]["charge"]+"</li><li class='six'>"+yuyue_list[i]["request_date"].replace("T"," ").substring(0,10) +"</li><li class='seven sure_quhao' record_sn="+yuyue_list[i]["register_sn"]+" req_type="+yuyue_list[i]["req_type"]+" unit_name="+yuyue_list[i]["unit_name"]+" doctor_name="+yuyue_list[i]["doctor_name"]+">取号</li></ul>";
                    }
                   
                 }
                $(".chufang_list").html(html);
                $("#jz_name").text(data['datarow']['0']['name']);
                 }else if(data['result']['execute_flag']==-1){
                    //alert(data['result']['execute_message']);
                    a +=data['result']['execute_message']+"<br>";
                    a +=$("#message").val();
                    //$(".yuyue_record .tips").html(data['result']['execute_message']);
                    $(".yuyue_record .tips").html(a);
                
                 }
            }
     })
     break;
     case "20":
     //alert(111);
     var params = {"patient_id":$("#pat_code").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"card_no":$("#card_no").val()};
     var index_load = "";
     $.ajax({
            url:"/hddevice/mz/index.php/ZiZhu/YuYue/patReservationRecord", 
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
                //alert(111);
                layer.close(index_load);
                $(".mtnum2").hide();
                $(".yuyue_record").show();
                 $(".yuyue_record .tips").html("");
                $("#op_now").val("chose_yuyue_record");
                $("#fanhui").css({"visibility":"visible"});
                $("#tuichu").css({"visibility":"visible"});
                $("#confirm").css({"visibility":"hidden"});
                var a="";
                 if(data['result']['execute_flag']==0){
                 var html="";
                 var yuyue_list= data["datarow"];
                 $("#gh_sequence").val(yuyue_list[0]["gh_sequence"]);
                 //alert(yuyue_list[0]["gh_sequence"]);
                 //alert($("#gh_sequence").val());
                 var x=1
                 for(var i=0;i<yuyue_list.length;i++){
                    var m=i+1;
                    y='0'+x;
                    x++;

                 var myDate = new Date();
                    var h =  myDate.getHours();
                    var m = myDate.getMinutes();
                    var num =m.toString().length;
                    if(num == 1){
                        m = '0'+m;
                    }
                    var nowtime=h+''+m;
                    //alert(nowtime);
                    
                    if(nowtime > 1200 && yuyue_list[i]["ampm"] == 'a'){
                         html+="<ul><li class='two'>"+y+"</li><li class='thr'>"+yuyue_list[i]["unit_name"]+"</li><li class='four'>"+yuyue_list[i]["charge"]+"</li><li class='six'>"+yuyue_list[i]["request_date"].replace("T"," ").substring(0,10) +"</li><li class='seven1 sure_guoqi' record_sn="+yuyue_list[i]["register_sn"]+" req_type="+yuyue_list[i]["req_type"]+" unit_name="+yuyue_list[i]["unit_name"]+" doctor_name="+yuyue_list[i]["doctor_name"]+">　　已过期</li></ul>";
                    }else{
                        html+="<ul><li class='two'>"+y+"</li><li class='thr'>"+yuyue_list[i]["unit_name"]+"</li><li class='four'>"+yuyue_list[i]["charge"]+"</li><li class='six'>"+yuyue_list[i]["request_date"].replace("T"," ").substring(0,10) +"</li><li class='seven sure_quhao' record_sn="+yuyue_list[i]["register_sn"]+" req_type="+yuyue_list[i]["req_type"]+" unit_name="+yuyue_list[i]["unit_name"]+" doctor_name="+yuyue_list[i]["doctor_name"]+">取号</li></ul>";
                    }
                 }
                $(".chufang_list").html(html);
                $("#jz_name").text(data['datarow']['0']['name']);
                 }else  if(data['result']['execute_flag']==-1){
                    a+=$("#message").val()+"<br/>";
                    a+=data['result']['execute_message'];
                     $(".yuyue_record .tips").html(a);

                 }
            }
     })
     break;
 }
})

//选择了补挂号费
$("#xz_bu_guahao").on("click",function(){


    writeLog("选择了补挂号费");
    $("#business_type").val("补挂号费");
  //  alert($("#card_no").val());
   switch($("#card_code").val()){
    case "21":

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
               //alert(data['result']['execute_flag']);
               //alert($("#patient_id").val())
              
                layer.close(index_load);
                $(".mtnum2").hide();
                $(".yuyue_record2").show();
                $("#op_now").val("chose_yuyue_record");
                $("#fanhui").css({"visibility":"visible"});
                $("#tuichu").css({"visibility":"visible"});
                $("#confirm").css({"visibility":"hidden"});
                var a="";
                 if(data['result']['execute_flag']==0){
                    $("#jz_name1").text($("#pat_name").val());
                    // alert($("#pat_name").val());
                    var html="";
                    var yuyue_list= data["datarow"];
                    for(var i=0;i<yuyue_list.length;i++){
                     var m=i+1;
                 html+="<ul><li class='two'>"+m+"</li><li class='thr'>"+yuyue_list[i]["unit_name"]+"</li><li class='four'>"+yuyue_list[i]["charge"]+"</li><li class='six'>"+yuyue_list[i]["request_date"].replace("T"," ").substring(0,10) +"</li><li class='seven sure_quhao' record_sn="+yuyue_list[i]["register_sn"]+" req_type="+yuyue_list[i]["req_type"]+" unit_name="+yuyue_list[i]["unit_name"]+" doctor_name="+yuyue_list[i]["doctor_name"]+">补挂号费</li></ul>";
                 }
                    $(".chufang_list").html(html);
                    $("#jz_name1").text(data['datarow'][0]['name']);
                    $("#jz_name").text(data['datarow'][0]['name']);
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
     //alert(111);
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
                //alert(111);
               // alert(data['result']['execute_flag']);
                layer.close(index_load);
                $(".mtnum2").hide();
                $(".yuyue_record2").show();
                $("#op_now").val("chose_yuyue_record");
                $("#fanhui").css({"visibility":"visible"});
                $("#tuichu").css({"visibility":"visible"});
                $("#confirm").css({"visibility":"hidden"});
                 if(data['result']['execute_flag']==0){
                    $("#jz_name1").text(data['datarow'][0]['name']);
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
                $(".chufang_list").html(html);
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


//预约挂号跳转补挂号费

$("#bugua").on("click",function(){
     writeLog("选择了补挂号费");
    $("#business_type").val("补挂号费");
  /*alert($("#card_no").val());
  alert($("#pat_code").val());
  alert($("#op_code").val());
  alert($("#zzj_id").val());
  alert($("#card_code").val());*/
   switch($("#card_code").val()){
    case "21":

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
               //alert(data['result']['execute_flag']);
               //alert($("#patient_id").val())
              
                layer.close(index_load);
                $(".mtnum2").hide();
                $(".yuyue_record2").show();
                $("#op_now").val("chose_yuyue_record");
                $("#fanhui").css({"visibility":"visible"});
                $("#tuichu").css({"visibility":"visible"});
                $("#confirm").css({"visibility":"hidden"});
                $(".btn_bu").hide();
                $("#bugua").css({"visibility":"hidden"});
                var a="";
                 if(data['result']['execute_flag']==0){
                    $("#jz_name1").text($("#pat_name").val());
                    // alert($("#pat_name").val());
                    var html="";
                    var yuyue_list= data["datarow"];
                    for(var i=0;i<yuyue_list.length;i++){
                     var m=i+1;
                 html+="<ul><li class='two'>"+m+"</li><li class='thr'>"+yuyue_list[i]["unit_name"]+"</li><li class='four'>"+yuyue_list[i]["charge"]+"</li><li class='six'>"+yuyue_list[i]["request_date"].replace("T"," ").substring(0,10) +"</li><li class='seven sure_quhao' record_sn="+yuyue_list[i]["register_sn"]+" req_type="+yuyue_list[i]["req_type"]+" unit_name="+yuyue_list[i]["unit_name"]+" doctor_name="+yuyue_list[i]["doctor_name"]+" unit_sn="+yuyue_list[i]["unit_sn"]+">补挂号费</li></ul>";
                 }
                    $(".chufang_list").html(html);
                    $("#jz_name1").text(data['datarow'][0]['name']);
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
     //alert(111);
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
                //alert(111);
               // alert(data['result']['execute_flag']);
                layer.close(index_load);
                $(".mtnum2").hide();
                $(".yuyue_record2").show();
                $("#op_now").val("chose_yuyue_record");
                $("#fanhui").css({"visibility":"visible"});
                $("#tuichu").css({"visibility":"visible"});
                $("#confirm").css({"visibility":"hidden"});
                $(".btn_bu").hide();
                $("#bugua").css({"visibility":"hidden"});
                 if(data['result']['execute_flag']==0){
                    $("#jz_name1").text(data['datarow'][0]['name']);
                    $("#jz_name").text(data['datarow'][0]['name']);
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
                $(".chufang_list").html(html);
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




//预约挂号
$("#xz_yu_guahao").on("click",function(){
     var params = {"patient_id":$("#pat_code").val(),"type":"check"};
     var index_load = "";
     $.ajax({
        url:"/hddevice/mz/index.php/ZiZhu/YuYue/CheckPatientCall", 
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
              //  alert(data[0]['computed']);
                if(data[0]['computed']=="0"){
                    
                     $(".mtnum2").hide();
                    $(".phone").show();
                    $("#op_now").val("pat_phone");
                    $("#confirm").css({"visibility":"visible"});
                   


                }else if(data[0]['computed']=="1"){
                     writeLog("选择了预约挂号");
                      $(".mtnum2").hide();
                      $(".chose_time").show();
                      $("#business_type").val("预约挂号");
                      $("#op_now").val("xz_yu_guahao");
                      $("#yuyue_guahao_name").text($("#pat_name").val());
                      /*var start = GetDateStr(1);
                      var end = GetDateStr(15);
                      var params = {"start":start,"end":end};*/
                      var index_load = "";
                      $.ajax({
                         url:"/hddevice/mz/index.php/ZiZhu/YuYue/getTwoWeek", 
                                    type:'post',
                                    dataType:'json',
                                    //data:params,
                                    beforeSend:function(){
                                        index_load = layer.msg('预约时间数据查询中,请稍候...', {icon: 16,time:20000});
                                       /* $("#fanhui").css({"visibility":"hidden"});
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"hidden"});*/
                                    },
                                    success:function(data){
                                         $("#fanhui").css({"visibility":"visible"});
                                        $("#tuichu").css({"visibility":"visible"});
                                        $("#confirm").css({"visibility":"hidden"});
                                         layer.close(index_load);
                                        if(data){
                                            var html="";
                                            for(var i=0;i<data.length;i++){

                                             html+="<li class='date' chose_date='"+data[i]+"'>"+data[i]+"</li>";
                                             }
                                        }else{

                                        }
                                        $("#time_list").html(html);
                                        $("#tixing").html("注意：预约挂号只能预约次日及以后日期的号");
                                    }
                      })

                }
               
            }

})



})

/**************************科室点击事件*********************************************************/
$(document).on("click",".chose_unit",function(){
    //alert(121);
     var params = {"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"patient_id":$("#pat_code").val()};
   /*  $.ajax({
        url:"/hddevice/mz/index.php/ZiZhu/YuYue/gh_unlock", 
        type:'post',
        dataType:'json',
        data:params,
        success:function(data)
            {
                
             }
     });*/
    //首先获取到被选中科室的科室编号
     var unit_sn=$(this).attr("roomId");
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
            alert("对不起，本科室只允许自费挂号！请使用就诊卡或身份证进行挂号缴费！");
            return;
        }

    }
     var params = {"unit_sn":unit_sn,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"patient_id":$("#pat_code").val(),"card_no":$("#card_no").val(),"date":$("#date").val()};
     var index_load = "";
        $.ajax({
            url:"/hddevice/mz/index.php/ZiZhu/YuYue/getSchedInfo", 
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
                //$("#total_fee").val(data['datarow'][0]['sum_fee']);
                 var html="";
                 var sub_list= data["datarow"];
                 //var page=1;
                 
                       if(data['result']['execute_flag']==0)
                       {
                           $("#keshi_name").text($("#pat_name").val());
                           pagedata = data.datarow;
                           /*alert(data['datarow']);
                           alert(data.datarow);
                           alert(pagedata);*/
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
                               /*if(now_time>moon_time){
                                val['record_left']=0
                               }*/
                            }
                            if(val.ampm == 'a'){
                               val.ampm = '上午';
                              /* if(now_time>moring_time)
                               {
                                  val['record_left']=0
                               }*/
                            }
                            if(val.ampm == "%"){
                                val.ampm = "全天";
                            }
                            if(val['record_left']==0 ){
                                        if(val["doctor_name"]!=""){
                                            html+="<div><h4>姓名:"+val["doctor_name"]+"</h4><ul><li>科室:"+val["unit_name"]+"</li><li style='color:red'>号别:"+val['clinic_name']+"</li><li>医事服务费:"+val['sum_fee']+"</li><li style='color:red'>午别:"+val["ampm"]+"</li><li>剩余号:"+val["record_left"]+"</li></ul><span class='disabled' record_sn="+val["record_sn"]+" req_type="+val["req_type"]+" unit_name="+val["unit_name"]+" ampm="+val["ampm"]+" clinic_name="+val["clinic_name"]+" doctor_name="+val["doctor_name"]+" >已挂完</span></div>";
                                            }else{
                                                html+="<div><h4>&nbsp;&nbsp;&nbsp;"+val["doctor_name"]+"</h4><ul><li>科室:"+val["unit_name"]+"</li><li style='color:red'>号别:"+val['clinic_name']+"</li><li>医事服务费:"+val['sum_fee']+"</li><li style='color:red'>午别:"+val["ampm"]+"</li><li>剩余号:"+val["record_left"]+"</li></ul><span class='disabled' record_sn="+val["record_sn"]+" req_type="+val["req_type"]+" ampm="+val["ampm"]+" clinic_name="+val["clinic_name"]+" unit_name="+val["unit_name"]+" doctor_name="+val["doctor_name"]+" >已挂完</span></div>";
                                            }
                                        }
                                            else{
                                              if(val["doctor_name"]!=""){
                                        html+="<div><h4>姓名:"+val["doctor_name"]+"</h4><ul><li>科室:"+val["unit_name"]+"</li><li style='color:red'>号别:"+val['clinic_name']+"</li><li>医事服务费:"+val['sum_fee']+"</li><li style='color:red'>午别:"+val["ampm"]+"</li><li>剩余号:"+val["record_left"]+"</li></ul><span class='sure_ghao' record_sn="+val["record_sn"]+" ampm="+val["ampm"]+" clinic_name="+val["clinic_name"]+" req_type="+val["req_type"]+" unit_name="+val["unit_name"]+" doctor_name="+val["doctor_name"]+" >预约</span></div>";
                                            }else{
                                            html+="<div><h4>&nbsp;&nbsp;&nbsp;"+val["doctor_name"]+"</h4><ul><li>科室:"+val["unit_name"]+"</li><li style='color:red'>号别:"+val['clinic_name']+"</li><li>医事服务费:"+val['sum_fee']+"</li><li style='color:red'>午别:"+val["ampm"]+"</li><li>剩余号:"+val["record_left"]+"</li></ul><span class='sure_ghao'record_sn="+val["record_sn"]+" ampm="+val["ampm"]+" clinic_name="+val["clinic_name"]+"  req_type="+val["req_type"]+" unit_name="+val["unit_name"]+" doctor_name="+val["doctor_name"]+" >预约</span></div>"; 
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

//预约时间点击事件
$(document).on("click",".date",function(){
    //alert($("#card_code").val());
    switch($("#card_code").val()){
        case"21":
        var date=$(".current").attr("chose_date");
        $("#date").val(date);
        if($("#jz_card_no_quhao").val()!=""){
        var kaid = $("#jz_card_no_quhao").val();
            if(kaid.length>12){
            kaid=kaid.substr(0,12);
          }
        }else{
        var kaid = $("#jz_card_no_sfz").val();
        }
        
      // alert(kaid);
            $("#op_now").val("chose_room");
            $(".mtnum2").hide();
            $(".chose_room").show();
            $("#confirm").css({"visibility":"visible"});
            //var business_type =$("#business_type").val();
            var business_type ="预约挂号";
            var params = {"kaid":kaid,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type,"date":date}; 

            var index_load = "";
            $.ajax({
                    url:"/hddevice/mz/index.php/ZiZhu/YuYue/getGhPatInfo", 
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
                        //alert(11);
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
                            
                            //alert(data['patGhInfo']['datarow']['response_chn']);
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
                                    {   //alert(roomlist[i]['attr']['class_name']);
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
                                }else{
                                    $(".chose_room h4").html(data['patGhInfo']['result']['execute_message']);
                                    
                                }
                                
                            }
                    }
                  })

        break;

        case"20":
        daojishi(100);
        var date=$(".current").attr("chose_date");
        $("#date").val(date);
        //$(".yb_op_area_guahao .tips_guahao").html('医保患者数据读取中,请稍后...');
        var flag;  
        var patinfo;
        $("#op_now").val("chose_room");
        $(".mtnum2").hide();
        $(".chose_room").show();
                               
        $(".btn_area").hide();
        var index_load="";
        index_load= layer.msg('医保信息读取中,请稍后...', {icon: 16,time:20000});
        setTimeout(function(){
            flag = window.external.InitYbIntfDll();     
            if(flag==0){
                writeLog("开始读取医保读卡器，医保读卡器初始化成功","INFO"); 
                //调用医保dll获取患者医保卡信息
                patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
                //alert(patinfo);
                setTimeout(function(){
                    if(patinfo!=""){
                        var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"date":$("#date").val()}; 
                        $.ajax({
                            url:"/hddevice/mz/index.php/ZiZhu/YuYue/YbXmlParseGhao", 
                            type:'post',
                            dataType:'json',
                            data:params,
                            beforeSend:function(){
                               // $(".yb_op_area_guahao .tips_guahao").html('出诊科室查询中,请稍后...');
                            },
                            success:function(data){
                                //医保读卡成功，给隐藏域赋值
                                layer.close(index_load);
                               $(".btn_area").show();
                                $("#confirm").css({"visibility":"hidden"});
                                $("#fanhui").css({"visibility":"visible"});
                                $("#tuichu").css({"visibility":"visible"});
                                if(data['result']['execute_flag']==0){
                                    //alert(data['yb_input_data']['response_chn'])
                                    $("#guahao_name").text(data['yb_input_data']['name']);
                                    $("#pat_code").val(data['yb_input_data']['patient_id']); 
                                    $("#response_type").val(data['yb_input_data']['response_type']);
                                    //alert($("#response_type").val());
                                    $("#card_no").val(data['yb_input_data']['card_no']);
                                    $("#social_no").val(data['yb_input_data']['social_no']);
                                    $("#card_code").val(data['yb_input_data']['card_code']);
                                    $("#pat_name").val(data['yb_input_data']['name']);
                                    $("#yb_flag").val(data['yb_input_data']['yb_flag']);
                                    $("#personcount").val(data['yb_input_data']['personcount']);
                                    $("#pat_sex").val(data['yb_input_data']['sex_chn']);
                                    $("#pat_age").val(data['yb_input_data']['age']);
                                    $("#reponse_name").val(data['yb_input_data']['response_chn']);
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
                                                {   //alert(roomlist[i]['attr']['class_name']);
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
                                    //$(".yb_op_area .tips").html(data['result']['execute_message']);
                                    $(".mtnum2").hide();
                                    $(".chose_room").show();
                                    
                                   if(data['result']['execute_message']=="有就诊未缴挂号费记录,请先补缴挂号费!"){
                                    var ts="预约挂号-->选择卡类型-->补挂号费";
                                    $(".chose_room h4").html(data['result']['execute_message']);
                                    $(".btn_bu").show();
                                    $("#bugua").css({"visibility":"visible"});
                                }else{
                                     $(".chose_room h4").text(data['result']['execute_message']);
                               }
                                   
                                    $("#card_code").val('20');
                                    $(".btn_area").show();
                                    $("#confirm").css({"visibility":"hidden"})
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
    }
     
})






















/**************************挂号点击事件开始*********************************************************/
$(document).on("click",".sure_ghao",function(){
     var doctor_name=$(this).attr("doctor_name");
     var clinic_name=$(this).attr("clinic_name");
     var ampm=$(this).attr("ampm");
     var record_sn=$(this).attr("record_sn");
     var unit_name=$(this).attr("unit_name");
     var req_type=$(this).attr("req_type");
     
    if(ampm=="a"){
        ampm="上午";
    }else if(ampm=="p"){
        ampm="下午";
    }
  /*  alert(doctor_name);
    alert(clinic_name);
    alert(ampm);*/
     $("#doctor_name").val(doctor_name);
     $("#clinic_name").val(clinic_name);
     $("#ampm").val(ampm);
     $("#record_sn").val(record_sn);
     $("#unit_name").val(unit_name);
     $("#req_type").val(req_type);

    switch($("#card_code").val()){
        case "21":
        $(".mtnum2").hide();
        $(".yy_guahao").show();
        $("#op_now").val("ic_yuyue_guahao");
        $(".yy_guahao .p1 .s2").text($("#pat_code").val());
        $(".yy_guahao .p2 .uname").text($("#pat_name").val());
        $(".yy_guahao .p3 .sex").text($("#pat_sex").val());
        $(".yy_guahao .p6 .p_cz_room").text($("#unit_name").val());
        $(".yy_guahao .p4 .time").text($("#date").val());
        $(".btn_area").show();
        $("#confirm").css({"visibility":"visible"});
        $("#fanhui").css({"visibility":"visible"});
        $("#tuichu").css({"visibility":"visible"});
        break;
        case "20":
        $(".mtnum2").hide();
        $(".yy_guahao").show();
        $("#op_now").val("ic_yuyue_guahao")
        $(".yy_guahao .p1 .s2").text($("#pat_code").val());
        $(".yy_guahao .p2 .uname").text($("#pat_name").val());
        $(".yy_guahao .p3 .sex").text($("#pat_sex").val());
        $(".yy_guahao .p6 .p_cz_room").text($("#unit_name").val());
        $(".yy_guahao .p4 .time").text($("#date").val());
        $(".btn_area").show();
        $("#confirm").css({"visibility":"visible"});
        $("#fanhui").css({"visibility":"visible"});
        $("#tuichu").css({"visibility":"visible"});
            break;
        }
})
/**************************挂号点击事件开始*********************************************************/

//选择了银行卡
$("#pay_bank_guahao").on("click",function(){
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
//预约取号选择了支付宝
$("#pay_zhifubao_quhao").on("click",function(){
   /* alert("网络问题，支付宝暂停使用付宝暂停使用");
    return;*/
    $("#op_now").val("zf_pay_zhifubao_guahao");
      writeLog("选择了支付宝");
    $("#stream_no").val(out_trade_no);
    $("#pay_type").val("alipay");
    daojishi(300);
    var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
    var total_amount = $("#cash").val();
    if($("#business_type").val()=="补挂号费"){
    var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#pat_name").val()+"补挂号费"}; 
    }else{
    var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#pat_name").val()+"预约取号"}; 
    }
    
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


/*****确定按钮点击事件开始**********/
$("#confirm").on("click",function(){
   //alert($("#op_now").val());
    var op_now=$("#op_now").val();
    for(var key in interval3){
        clearInterval(interval3[key]);
    }
    switch(op_now){

        case "pat_phone":

           var params={"pat_phone":$("#pat_phone").val()};
           $.ajax({
                url:"/hddevice/mz/index.php/ZiZhu/YuYue/checkPhone",
                type:'post',
                dataType:'json',
                data:params,
                success:function(data){

                    if(data['code']=="1"){

                        //alert("电话输入有误,请重新输入");
                        $(".tips_phone").text("电话输入有误,请重新输入");
                    }else{

                        var params = {"patient_id":$("#pat_code").val(),"pat_phone":$("#pat_phone").val(),"type":"update"};
                        var index_load = "";
                        $.ajax({
                            url:"/hddevice/mz/index.php/ZiZhu/YuYue/CheckPatientCall", 
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
                                 writeLog("选择了预约挂号");
                                  $(".mtnum2").hide();
                                  $(".chose_time").show();
                                  $("#business_type").val("预约挂号");
                                  $("#op_now").val("xz_yu_guahao");
                                  $("#yuyue_guahao_name").text($("#pat_name").val());
                                  /*var start = GetDateStr(1);
                                  var end = GetDateStr(15);
                                  var params = {"start":start,"end":end};*/
                                  var index_load = "";
                                  $.ajax({
                                     url:"/hddevice/mz/index.php/ZiZhu/YuYue/getTwoWeek", 
                                                type:'post',
                                                dataType:'json',
                                                //data:params,
                                                beforeSend:function(){
                                                    index_load = layer.msg('预约时间数据查询中,请稍候...', {icon: 16,time:20000});
                                                },
                                                success:function(data){
                                                     $("#fanhui").css({"visibility":"visible"});
                                                    $("#tuichu").css({"visibility":"visible"});
                                                    $("#confirm").css({"visibility":"hidden"});
                                                     layer.close(index_load);
                                                    if(data){
                                                        var html="";
                                                        for(var i=0;i<data.length;i++){

                                                         html+="<li class='date' chose_date='"+data[i]+"'>"+data[i]+"</li>";
                                                         }
                                                    }else{

                                                    }
                                                    $("#time_list").html(html);
                                                    $("#tixing").html("注意：预约挂号只能预约次日及以后日期的号");
                                                    }
                                             })
                                         }
                                    })
                    }
                }
           })

          


        break;
        case "ic_get_pat_info":
       // alert($("#jz_card_no_sfz").val());
        window.external.send(1,4);
        if($("#jz_card_no_quhao").val()!=""){
        var kaid = $("#jz_card_no_quhao").val();
        }else{
        var kaid = $("#jz_card_no_sfz").val();
        }

        $("#card_no").val(kaid);
        //alert(kaid);
        clearInterval(interval);
        var business_type = $("#business_type").val();
        var params = {"kaid":kaid,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type}; 
        var index_load = "";
       // alert(22);
        $.ajax({
            url:"/hddevice/mz/index.php/ZiZhu/YuYue/getPatInfo", 
            type:'post',
            dataType:'json',
            data:params,
            beforeSend:function(){
                index_load = layer.msg('数据查询中,请稍候...', {icon: 16,time:20000});
                $("#fanhui").css({"visibility":"hidden"});
                $("#confirm").css({"visibility":"hidden"});
                $("#tuichu").css({"visibility":"hidden"});
            },
            
            success:function(data){
                layer.close(index_load);
                $("#fanhui").css({"visibility":"visible"});
                $("#confirm").css({"visibility":"hidden"});
                $("#tuichu").css({"visibility":"visible"});
                //添加了医保患者的姓名
                if(data['patInfo']['result']['execute_flag']==0){
                    //$("#op_now").val("chose_business_type");
                    $(".mtnum2").hide();
                    //$(".chose_pat_type").show();
                   // $("#confirm").css({"visibility":"visible"});
                    $("#jz_name").text(data['patInfo']['datarow']['name']);
                    //$("#patinfo_area").show().html("卡号："+data['datarow']['p_bar_code']+" 姓名："+data['datarow']['name']+" 性别："+data['datarow']['sex_chn']);
                    $("#pat_code").val(data['patInfo']['datarow']['patient_id']); 
                    $("#card_no").val(data['patInfo']['datarow']['card_no']);
                    $("#pat_name").val(data['patInfo']['datarow']['name']);
                    $("#pat_sex").val(data['patInfo']['datarow']['sex_chn']);
                   // $("#times").val(data['chufang']['datarow'][0]['attr']['times']);
                    $("#response_type").val(data['patInfo']['datarow']['response_type']);
                    $("#reponse_name").val(data['patInfo']['datarow']['response_chn']);
                    $("#social_no").val(data['patInfo']['datarow']['social_no']);
                    $("#mobile").val(data['patInfo']['datarow']['mobile']);

                    setTimeout(function(){
                        $("#xz_yu_guahao").trigger("click");
                                            },1)
/*                   
                    if($("#response_type").val()>=90){
                            layer.confirm('医保身份患者请使用医保卡挂号,否则本次所有费用将按照自费处理', {
                            btn: ['确定'] //按钮
                            });
    

                              
                            }*/
                    //alert($("#pat_name").val());
                    //alert($("#pat_sex").val());
                }else if(data['patInfo']['result']['execute_flag']==-1){
                   /* alert(data['patInfo']['result']['execute_message']);
                     $("#xz_yu_quhao").css({"visibility":"hidden"});*/
                    $("#message").val(data['patInfo']['result']['execute_message']);
                    //$("#op_now").val("chose_business_type");
                    $(".mtnum2").hide();
                    //$(".chose_pat_type").show();
                   setTimeout(function(){
                        $("#xz_yu_guahao").trigger("click");
                                            },1)
                    if($("#response_type").val()>=90){
                            layer.confirm('医保身份患者请使用医保卡挂号,否则本次所有费用将按照自费处理', {
                            btn: ['确定'] //按钮
                            });
    

                              
                            }
                  // $(".chufang_list").html(data['patInfo']['result']['execute_message']);
                }else if(data['patInfo']['result']['execute_flag']==-4){
                    
                }
            }
        })
        daojishi(120);
    break;

    case "ic_pay_info_show_quhao":
      if($("#cash").val()==0){
        var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddhhmmss");
        $("#stream_no").val(out_trade_no);
        $(".mtnum2").hide();
        $(".pay_success").show();
        $(".pay_success h3").html("费用确认中,请稍候...");
        paySuccessSendToHis($("#card_code").val());
    }else{
        $("#op_now").val("pay_zhifubao_quhao");
        $(".mtnum2").hide();
        $(".chose_pay_type_area_quhao").show();
        $(".btn_area").show();
        $("#confirm").css({"visibility":"hidden"});
        }
    break;

   

    case "chose_yb_card":
        window.external.send(1,4);
        daojishi(100);
        $(".yb_op_area .tips").html('医保患者数据读取中,请稍后...');
        var flag;  
        var patinfo;
        $("#op_now").val("chose_business_type");
        $(".btn_area").hide();
        setTimeout(function(){
            flag = window.external.InitYbIntfDll();     
            if(flag==0){
                writeLog("开始读取医保读卡器，医保读卡器初始化成功","INFO"); 
                patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
                //alert(patinfo);
                setTimeout(function(){
                    if(patinfo!=""){
                        var params = {"input_xml":patinfo,"op_code":$("#op_code").val()}; 
                        $.ajax({
                            url:"/hddevice/mz/index.php/ZiZhu/YuYue/YbXmlParse", 
                            type:'post',
                            dataType:'json',
                            data:params,
                            success:function(data){
                               // alert(data['result']['execute_flag']);
                                  if(data['result']['execute_flag']==0){
                                    $("#jz_name").text(data['yb_input_data']['name']);
                                    $("#jz_name1").text(data['yb_input_data']['name']);
                                    $("#guahao_name").text(data['yb_input_data']['name']);
                                    $("#pat_code").val(data['yb_input_data']['patient_id']); 
                                    $("#card_no").val(data['yb_input_data']['card_no']);
                                    $("#pat_name").val(data['yb_input_data']['name']);
                                    $("#yb_flag").val(data['yb_input_data']['yb_flag']);
                                    $("#personcount").val(data['yb_input_data']['personcount']);
                                    $("#pat_sex").val(data['yb_input_data']['sex_chn']);

                                    $("#response_type").val(data['yb_input_data']['response_type']);
                                    //alert($("#response_type").val());
                                    $("#healthcare_card_no").val(data['yb_input_data']['yb_card_no']);
                                    //$("#op_now").val("chose_business_type");
                                    $(".mtnum2").hide();
                                    //$(".chose_pat_type").show();
                                    
                                    $(".btn_area").show();
                                    $("#confirm").css({"visibility":"hidden"});
                                    setTimeout(function(){
                                        $("#xz_yu_guahao").trigger("click");
                                            },1)
                                }else  if(data['result']['execute_flag']==-1){
                                   // alert(111);
                                    //alert(data['yb_input_data']['addition_no1']);
                                     //alert(data['result']['execute_message']);
                                    $("#message").val(data['result']['execute_message']);
                                    //$("#card_no").val(data['yb_input_data']['addition_no1']);
                                    
                                    $(".mtnum2").hide();
                                    //$(".chose_pat_type").show();
                                    $(".btn_area").show();
                                    $("#confirm").css({"visibility":"hidden"});
                                    setTimeout(function(){
                                         $("#xz_yu_guahao").trigger("click");
                                            },1)
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
    case "zf_pay_bank_guahao":
    daojishi(120);
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

    case "ic_yuyue_guahao":
   // alert($("#card_code").val());
    $(".mtnum2").hide();
    $(".pay_success").show();
    $(".pay_success h3").html("预约中,请稍候...");
    paySuccessSendToHis($("#card_code").val());
    $(".btn_area").hide();
    break;

    case "yb_yuyue_guahao":
    $(".mtnum2").hide();
    $(".pay_success").show();
   // $(".pay_success h3").html("预约成功,请收好预约凭条!");
    paySuccessSendToHis($("#card_code").val());
    $(".btn_area").hide();
    break;
    }
})







/*****确定按钮点击事件结束**********/


/*****返回按钮点击事件开始**********/
$("#fanhui").on("click",function(){
  // alert($("#op_now").val());
  
 switch($("#op_now").val()){
       case"chose_card_type_area":
       var zzj_id = $("#zzj_id").val();
      // alert(zzj_id);
      // window.location.href="/hddevice/mz/index.php/ZiZhu/YuYue/index/zzj_id/"+zzj_id;
        window.location.href="/hddevice/mz/index.php/ZiZhu/Index/index/id/"+zzj_id;
       // window.location.href="/hddevice/mz/index.php/ZiZhu/Index/index/id/ZZ001";
        daojishi(30);
        break;
//从输入就诊卡号返回到选择卡类型（就诊卡、医保卡、身份证
        case "ic_get_pat_info":
        window.external.send(1,4);
        $("#op_now").val("chose_card_type_area");
        $(".mtnum2").hide();
        $(".chose_card_type_area").show();
        $("#confirm").css({"visibility":"hidden"});
        $("#jz_card_no_quhao").val("");
        $("#jz_card_no_sfz").val("");
        key_value = "";
        key_value2 = "";
        daojishi(30);
    
        break;
        case "pat_phone":
        $("#op_now").val("chose_card_type_area");
        $(".mtnum2").hide();
        $(".chose_card_type_area").show();
        $("#confirm").css({"visibility":"hidden"});
        $("#jz_card_no_quhao").val("");
        $("#jz_card_no_sfz").val("");
        $("#pat_phone").val("");
        key_value = "";
        key_value2 = "";
        daojishi(30);
        break;
//从选择取号类型返回到输入就诊卡号
        case"chose_business_type":
        //alert($("#card_code").val());
            switch($("#card_code").val()){
                case "21":
                window.external.send(1,4);
                $("#op_now").val("ic_get_pat_info");
                $("#jz_card_no_quhao").val("");
                $("#jz_name").html("");
                $("#jz_name1").html("");
                $("#pat_name").val("");
                $(".mtnum2").hide();
                $(".jiuzhen_op_area_quhao").show();
                $("#confirm").css({"visibility":"visible"});
                daojishi(30);
                break;

                case"20":  
                window.external.send(1,4);
                $("#op_now").val("chose_yb_card");
                $("#jz_name").html("");
                $("#jz_name1").html("");
                $(".yb_op_area .tips").html("");
                $(".mtnum2").hide();
                $(".yb_op_area").show();
                $("#pat_name").val("");
                $("#confirm").css({"visibility":"hidden"});
                daojishi(30);
                break;              

            }
                        
    
        break;
//从确认预约信息返回到选择取号类型
        case"chose_yuyue_record":
        $("#op_now").val("chose_business_type");
        $(".mtnum2").hide();
        $(".chose_pat_type").show();
        $("#confirm").css({"visibility":"hidden"});
        $(".chufang_list").html("");
        $(".yuyue_record .tips").html("");
       // $("#pat_code").val("");
        daojishi(30);
    
        break;
//从确认挂号信息返回到确认预约信息
        case"ic_pay_info_show_quhao":
        $("#op_now").val("chose_yuyue_record");
        $(".mtnum2").hide();
        $(".yuyue_record").show();
        $("#confirm").css({"visibility":"hidden"});
        daojishi(30);
    
        break;
//从选择支付方式返回到确认挂号信息
        case"pay_zhifubao_quhao":
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
//从付款返回到支付方式
        case"zf_pay_zhifubao_guahao":
        $("#op_now").val("pay_zhifubao_quhao");
        $(".mtnum2").hide();
        $(".chose_pay_type_area_quhao").show();
        daojishi(30);
        break;
//从插入医保卡返回到选择卡类型
        case "chose_yb_card":
        window.external.send(1,4);
        $("#op_now").val("chose_card_type_area");
        $(".mtnum2").hide();
        $(".chose_card_type_area").show();
        $("#confirm").css({"visibility":"hidden"});
        daojishi(30);
    
        break;

//从选择预约挂号时间返回取号类型
        case "xz_yu_guahao":
        window.external.send(1,4);
        $("#op_now").val("chose_card_type_area");
        window.external.FreeYBIntfDll();
        window.external.MoveOutCard();
        $(".mtnum2").hide();
        $("#pat_name").val("");
        $("#yuyue_guahao_name").text("");
        $("#guahao_name").text("");
        $(".chose_card_type_area").show();
        $("#confirm").css({"visibility":"hidden"});
        $("#jz_card_no_quhao").val("");
        $("#jz_card_no_sfz").val("");
        key_value = "";
        key_value2 = "";
        daojishi(30);  
        break;

//从选择科室返回到选择预约挂号时间
        case"chose_room":
        $("#op_now").val("xz_yu_guahao");
        $(".mtnum2").hide();
        $(".chose_time").show();
        $(".doctor_list").html("");
        $("#attr_list").html("");
        $("#sub_list").html("");
        $(".chose_room h4").html("");
        $(".chose_room h3").html("");
        $("#confirm").css({"visibility":"hidden"});
        $(".btn_bu").hide();
        $("#bugua").css({"visibility":"hidden"});
        daojishi(30);   
        break;

//从选择挂号医生返回到选择科室
        case"chose_doctor":
        $("#op_now").val("chose_room");
        $(".mtnum2").hide();
        $(".doctor_list").html("");
        $(".chose_room").show();
        $("#confirm").css({"visibility":"hidden"});
        daojishi(30);   
        break;

//从预约挂号信息返回到选择挂号医生
        case "ic_yuyue_guahao":
        $("#op_now").val("chose_doctor");
        $(".mtnum2").hide();
        $(".chose_doctor").show();
        $("#confirm").css({"visibility":"hidden"});
        daojishi(30);   
        break;


    }
})

/*****返回按钮点击事件结束**********/

/*****退出按钮点击事件开始**********/
$("#tuichu").on("click",function(){
    $("#message").val("");
    $("#jz_card_no").val("");
    $(".btn_area").hide();
    $(".op_now").val("");
      <!--------添加了用户的姓名-------->
    $("#jz_name").html("");
    $("#jz_name1").html("");
    $("#guahao_name").html("");
    $("#keshi_name").html("");
    $("#attr_list").html("");
    $("#time_list").html("");
    $("#sub_list").html("");
    $(".chose_room h4").html("");
    $(".chose_room h3").html("");
    $(".chufang_list").html("<h4></h4>");
    $(".yb_op_area .tips").html('');
    $(".yb_op_area_quhao .tips_guahao").html('');
    key_value="";
    key_value2="";
   // window.external.FreeYBIntfDll();
    //window.external.MoveOutCard();
    //window.external.DisAllowCardIn();
   // window.external.keybord_close();
    $(".inhide").val("");
    $("#downcount").hide();
    for(var key in interval2){
        clearTimeout(interval2[key]);
    }
    for(var key in interval3){
        clearInterval(interval3[key]);
    }
   var zzj_id = $("#zzj_id").val(); 
window.location.href="/hddevice/mz/index.php/ZiZhu/Index/index/id/"+zzj_id;

})

/*****退出按钮点击事件结束**********/


/**
*日志记录函数
**/
function writeLog(logtxt,logtype){
    
    var params = {"log_txt":logtxt,"log_type":logtype,"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"direction":"操作步骤","op_code":$("#op_code").val()};
    $.ajax({
            url:"/hddevice/mz/index.php/ZiZhu/YuYue/writeLogs", 
            type:'post',
            dataType:'json',
            data:params,
            success:function(data){}
    })
}
//读取就诊卡号方法
function getCardNo(){
    var card_no =window.external.GetCardNo();
  //  alert(card_no);
    //var card_no2 = window.external.sfz_card_read();
    //自助挂号业务逻辑
    if($("#business_type").val()=="预约取号")
    { 
        if(card_no!="")
        {
            window.external.send(1,4);
            window.external.Beep();
            $("#jz_card_no_quhao").val(card_no);
            $("#confirm").trigger("click");
        }
    }
}
/**
*轮询调用查询接口是否支付成功
**/
function getResult(out_trade_no,s1){
    var params = {"out_trade_no":out_trade_no};  
    $.ajax({
        url:"/hddevice/mz/index.php/ZiZhu/YuYue/ajaxGetPayStatus", 
        type:'post',
        dataType:'json',
        data:params,
        success:function(data){
            if(data.message.PayStatus=="WAIT_BUYER_PAY"){ 
                $(".tips").html("等待患者付款中...");
                $(".btn_area").hide();
            }
            if(data.message.PayStatus=="TRADE_SUCCESS"){
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
                paySuccessSendToHis($("#card_code").val());
                //$("#p_four .success").show().html("支付成功,5秒后返回");
                /*setTimeout(function(){
                    $("#fanhui").trigger("click");                  
                },5000)
                */
                
            }else{
                
            }
        }
    })      
}
function paySuccessSendToHis(card_code){
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

  if($("#business_type").val()=="预约挂号"){
          //  $('#code').qrcode("http://www.baidu.com");
       var params= {"req_type":$("#req_type").val(),"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"record_sn":$("#record_sn").val(),"response_type":$("#response_type").val(),"charge_total":$("#charge_total").val(),"zhzf":$("#zhzf").val(),"tczf":$("#tczf").val(),"pay_charge_total":$("#cash").val(),"pay_seq":$("#pay_seq").val(),"trade_no":$("#trade_no").val(),"stream_no":$("#stream_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"pay_type":$("#pay_type").val(),"bk_card_no":$("#idCard").val(),"name":$("#pat_name").val(),"total_fee":$("#total_fee").val(),"social_no":$("#social_no").val(),"mobile":$("#mobile").val()};

        $.ajax({
            url:"/hddevice/mz/index.php/ZiZhu/YuYue/yyt_gh_save", 
                type:'post',
                dataType:'json',
                data:params,
                success:function(data){
                
            var now=new Date().Format("yyyy-MM-dd hh:mm");
            

            if(data['execute_flag']==0){
       
                $(".pay_success h3").html("预约成功,请收好预约凭条!");

                var s1="";
                var pos = 0;
                s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>";
               
                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 70) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+" </tr>";
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' > 就诊号："+$("#pat_code").val()+"</tr>";
                if($("#card_code").val()=="20"){
                    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >身份：医保（普通）</tr>";
                }else{
                    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >身份："+$("#reponse_name").val()+"</tr>";
                }
                
                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >预约科室："+$("#unit_name").val()+"</tr>";
               
                s1 += "<tr font='黑体' size='12' x='10' y='" + (pos += 10) +
                                                                "' >---------------------------------------------</tr>";
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' > 预约医师："+$("#doctor_name").val()+"</tr>";
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' > 预约号别："+$("#clinic_name").val()+"</tr>";
                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >预约时间:"+now+"</tr>";
                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >预约就诊日期:"+$("#date").val()+$("#ampm").val()+data['datarow']['gh_sequence']+"号</tr>";
                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >"+data['datarow']['suggest_time']+"</tr>";
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，请勿遗失</tr>";
                
                // s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' ></tr>";

                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' > 请患者本人于就诊当日凭就诊卡或</tr>";
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' > 社保卡到收费窗口或自助机取号</tr>";
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >取号时间:8:00-11:30; 13:00-16:30</tr>";
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >请您尽量选择公共交通工具绿色出行</tr>";
                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
               /* s1 += "<tr font='黑体' size='15' x='35' y='" + (pos += 30) + "' >微信扫码  查看叫号信息</tr>";
                s1 +="<tr font='黑体' size='15' x='35' y='" + (pos += 30) + "' >"+$("#code").val()+"</tr>";*/
                s1 += "</print_info>";
      /*           var doctor_name=$(this).attr("doctor_name");
     var clinic_name=$(this).attr("clinic_name");
     var ampm=$(this).attr("ampm"); */     
    
     
                window.external.paint(s1,$("#pat_code").val(),200,40,50,30);
                //alert(88);
                setTimeout(function(){
                        $("#tuichu").trigger("click"); 
                    },2000)
            }else{
                
                $(".pay_success h3").html(data["message"]);
                
                 var s1="";
                var pos = 0;
                s1 = "<?php echo '<?'; ?>
xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                s1 += "<tr font='黑体' size='14' x='65' y='" +(pos+=10) + "' >北京市海淀医院(自助)</tr>"
                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >姓名:"+$("#pat_name").val()+"  性别："+$("#pat_sex").val()+" </tr>";
                if($("#card_code").val()=="20"){
                    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >身份：医保（普通）</tr>";
                }else{
                    s1 += "<tr font='黑体' size='10' x='20' y='" +(pos += 20) + "' >身份："+$("#reponse_name").val()+"</tr>";
                }
                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >预约科室："+$("#unit_name").val()+"</tr>";
                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >预约时间:"+$("#date").val()+"</tr>";
                
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' > 预约失败！</tr>";
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' > "+data["message"]+"</tr>";
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示:请保存此凭证，请勿遗失</tr>";
                s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >请您尽量选择公共交通工具绿色出行</tr>";
                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 30) + "' >机器名称："+$("#zzj_id").val()+"</tr>";
                s1 += "</print_info>";
                
                window.external.paint(s1,'',200,40,50,30);
                setTimeout(function(){
                        $("#tuichu").trigger("click"); 
                    },2000)
               
          }
            }
        })


    
  }

  if($("#business_type").val()=="预约取号"){
   
    switch(card_code){
            case '21'://就诊卡
            var index_load="";
            var params = {"req_type":$("#req_type").val(),"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"record_sn":$("#record_sn").val(),"response_type":$("#response_type").val(),"charge_total":$("#charge_total").val(),"zhzf":$("#zhzf").val(),"tczf":$("#tczf").val(),"pay_charge_total":$("#cash").val(),"pay_seq":$("#pay_seq").val(),"trade_no":$("#trade_no").val(),"stream_no":$("#stream_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"pay_type":$("#pay_type").val(),"bk_card_no":$("#idCard").val()};
            $.ajax({
                url:"/hddevice/mz/index.php/ZiZhu/YuYue/yyt_qh_save1", 
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
                       // alert(mx['sequence_no']);
                        //alert(mx['outpatient_no']);
/*window.external.PrtJzDateCreatGuaHao(mx['location_info'],mx['ampm'],mx['sequence_no'],mx['outpatient_no'],mx['patient_id'],mx['unit_name'],mx['clinic_name'],mx['patient_name'],mx['sex'],mx['age'],mx['response_type'],mx['total_fee'],mx['fee_zf'],mx['fee_yb'],"垫付："+mx['fee_df'],mx['fee_zf'],$("#zzj_id").val(),mx['receipt_sn'],date1,mx['suggest_time'],$("#idCard").val(),$("#trade_no").val(),$("#stream_no").val(),$("#PayStatus").val(),mx['group_name'],mx['emp_name']);*/
                    

    

                    

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
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡卡号："+$("#idCard").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡金额："+mx['fee_zf']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡流水号："+$("#trade_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >流水号："+$("#stream_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡状态："+$("#PayStatus").val()+"</tr>";

                }
                    
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >*温馨提示：挂号单作为退号凭证,请勿遗失</tr>";
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >本号条当日有效，过期作废</tr>";
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >如需挂号收据,缴费时请提前告知收费员</tr>";
                    s1 += "<tr font='黑体' size='11' x='10' y='" + (pos += 20) + "' >请患者到相应分诊台分诊报到机报到就诊</tr>";    

                    s1 += "</print_info>";
                   // alert($("#pat_code").val());
                   // alert(s1);
                    window.external.paint(s1,$("#pat_code").val(),200,40,120,80);
                    
                     setTimeout(function(){
                                        $("#tuichu").trigger("click"); 
                                    },2000)

                  
                    }else{
                        if($("#pay_type").val()=="alipay")
                        {
                            if(data['result']['execute_flag']==-1)
                            {  
                                window.external.send(5,2);
                                $(".pay_success h3").html(data['pay_result']['SubMsg']);
                                writeLog("进入支付宝退款");
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
                                  /*s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：交款失败,支付宝退款失败,请到二楼收</tr>";
                                 s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >费处进行人工退款！</tr>";*/
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
                                /* setTimeout(function(){
                            $("#tuichu").trigger("click"); 
                                    },2000)*/
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
            var gh_flag="2";
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
                            url:"/hddevice/mz/index.php/ZiZhu/YuYue/YbSfConfirmXmlParseGhao", 
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
                                 //   alert("成功了");
                                    writeLog("his数据接收成功");
                                    $(".pay_success h3").html(data['result']['execute_message']+"<br>请收好您的挂号凭条"+"<br>医保个人账户余额"+yb_zh+"元");
                                    window.external.send(5,2);
                                    /***********开始打印*********/  
                                    mx = data['datarow'];   
                                    var date=mx['enter_date']
                                    var date1=date.replace("T"," ");
                                    
                                   /* window.external.PrtJzDateCreatGuaHao(mx['location_info'],mx['ampm'],mx['sequence_no'],mx['outpatient_no'],mx['patient_id'],mx['unit_name'],mx['clinic_name'],mx['patient_name'],mx['sex'],mx['age'],mx['response_type'],mx['total_fee'],mx['fee_zf'],mx['fee_yb'],"医保个人账户支付:"+mx['fee_zhzf'],mx['fee_zf'],$("#zzj_id").val(),mx['receipt_sn'],date1,mx['suggest_time'],$("#idCard").val(),$("#trade_no").val(),$("#stream_no").val(),$("#PayStatus").val(),mx['group_name'],mx['emp_name']);*/
                                    

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
                    s1 += "<tr font='黑体' size='10' x='35' y='" + (pos += 20) + "' >医保个人账户支付："+mx['fee_zhzf']+"</tr>";
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
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡卡号："+$("#idCard").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡金额："+mx['fee_zf']+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡流水号："+$("#trade_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >流水号："+$("#stream_no").val()+"</tr>";
                    s1 += "<tr font='黑体' size='10' x='25' y='" + (pos += 20) + "' >银行卡状态："+$("#PayStatus").val()+"</tr>";

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
                                     if(data['result']['execute_flag']==-1){


                                        //alert("失败了");

                                            //window.external.send(5,2); 
                                            $(".pay_success h3").html(data['pay_result']['SubMsg']);
                                            writeLog("进入支付宝退款");
                                            //alert(data.pay_result.Code);

                                            if(data.pay_result.Code==10000){
                                                //tk_status = "退款成功";
                                                writeLog("支付宝退款成功");
                                                $(".pay_success h3").html("交款失败,退款成功,请重新交易!");

                                            }else{
                                                //tk_status = "退款失败"+data['pay_result']['SubMsg'];
                                                writeLog("支付宝退款失败");
                                                $(".pay_success h3").html( "交款失败,退款失败,请到二楼收费处进行人工退款!");

                                            }











                                         /*alert("失败了");
                                        //alert(data.pay_result.Code);
                                        $(".pay_success h3").html(data['pay_result']['SubMsg']);
                                         window.external.send(5,2);
                                        writeLog("进入支付宝退款");
                                        alert(data.pay_result.Code);
                                                if(data.pay_result.Code==10000){
                                                //tk_status = "交款失败,支付宝退款成功,请重新交易!";
                                                
                                                $(".pay_success h3").html("交款失败,支付宝退款成功,请重新交易!");
                                            }else{
                                                //tk_status = "交款失败,支付宝退款失败,请到二楼收费处进行人工退款！";
                                                
                                                $(".pay_success h3").html("交款失败,支付宝退款失败,请到二楼收费处进行人工退款！");
                                            }*/
                                    
                                    
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
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >状态：收款失败,支付宝退款失败</tr>";
                                s1 += "<tr font='黑体' size='10' x='20' y='" + (pos += 20) + "' >请到二楼收费处进行人工退款！</tr>";
                                
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
                                       //  window.external.send(5,2);
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
                                    //window.external.send(5,2); 
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
                        var date=mx['enter_date']
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
                                writeLog("进入支付宝退款")
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
                                    var date=mx['enter_date']
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
}




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


//读取身份证方法
function getCardNoS(){
    //var card_no =window.external.GetCardNo();
    var card_no2 = window.external.sfz_card_read();
       if($("#business_type").val()=="预约取号"){
        if(card_no2!="")
        {
            if(card_no2!="请重放身份证"&&card_no2!="开usb失败"&&card_no2!="读卡失败")
            {
                var s = card_no2.split(",");
                $("#jz_card_no_sfz").val(s[5]);
                for(var key in interval3)
                {
                    clearInterval(interval3[key]);
                }
                window.external.send(1,4);
                $("#confirm").trigger("click");
            }
        }
    }
    
}
/********交易记录*******/
function pay_record(dept_code,dept_name,doctor_code,doctor_name,card_type,business_type,pat_card_no,healthcare_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,healthcare_card_trade_state,his_state,bank_card_id,reg_info,trade_no,zzj_id,stream_no,tk_status){
        var params = {"dept_code":dept_code,"dept_name":dept_name,"doctor_code":doctor_code,"doctor_name":doctor_name,"card_type":card_type,"business_type":business_type,"pat_card_no":pat_card_no,"healthcare_card_no":healthcare_card_no,"id_card_no":id_card_no,"pat_id":pat_id,"pat_name":pat_name,"pat_sex":pat_sex,"charge_total":charge_total,"cash":cash,"zhzf":zhzf,"tczf":tczf,"trading_state":trading_state,"healthcare_card_trade_state":healthcare_card_trade_state,"his_state":his_state,"bank_card_id":bank_card_id,"reg_info":tk_status,"trade_no":trade_no,"zzj_id":zzj_id,"stream_no":$("#stream_no").val(),"tk_status":tk_status}; 
        //alert("开始记录交易数据");
        $.ajax({
            url:"/hddevice/mz/index.php/ZiZhu/YuYue/witeJyRecordToDataBase", 
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
                 //alert(data);
            }
        })
}







})

Date.prototype.Format = function (fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}   
</script>
<style type="text/css">
.chose_pat_type ul li{
    font-size: 30px;
    font-weight:bold;
}
.yuyue_list li{
    float:left;
    margin-left:60px; 
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
<title>预约挂号</title>
</head>

<body>
<!--添加了病人隐藏域-->
<div style="display:block;" id="cover"></div>
<input type="hidden" class="inhide" id="patient_id" />
<!--添加了病人隐藏域-->
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
<input type="hidden" class="inhide" id="idCard" value=""/><!--支付宝支付帐号-->
<input type="hidden" class="inhide" id="PayStatus" value=""/>
<input type="hidden" class="inhide" id="times_order_no"  value=""/>
<input type="hidden" class="inhide" id="dept_code" value=""/>
<input type="hidden" class="inhide" id="doctor_code" value=""/>
<input type="hidden"  class="inhide" id="dept_name" value=""/>
<input type="hidden" class="inhide" id="doctor_name" value=""/>
<input type="hidden" class="inhide" id="healthcare_card_no" value=""/>
<input type="hidden" id="zzj_id"  value="<?php echo ($zzj_id); ?>" />
<input type="hidden"  class="inhide" id="tansType" value="00"  />
<input type="hidden" class="inhide" id="reponse_name" value="" />
<input type="hidden" id="daojishi" class="inhide" value=""/>
<input type="hidden" id="business_type" class="inhide" value=""/>
<input type="hidden" id="pay_type" class="inhide" value=""/>
<input type="hidden" id="yb_flag" class="inhide" value=""/>
<input type="hidden" id="record_sn" class="inhide" value=""/>
<input type="hidden" id="gh_sequence" class="inhide" value=""/>
<input type="hidden" id="bank_card_num" class="inhide" value=""/>
<input type="hidden" id="personcount" class="inhide" value=""/>
<input type="hidden" id="req_type" class="inhide" value=""/>
<input type="hidden" id="date" class="inhide" value=""/>
<input type="hidden" id="unit_name" class="inhide" value=""/>
<input type="hidden" id="mobile" class="inhide" value=""/>
<input type="hidden" id="social_no" class="inhide" value=""/>
<input type="hidden" id="total_fee" class="inhide" value=""/>
<input type="hidden" id="gh_flag" class="inhide" value=""/>
<input type="hidden" id="message" class="inhide" value=""/>  


<input type="hidden" id="clinic_name" class="inhide" value=""/>
<input type="hidden" id="ampm" class="inhide" value=""/> 
<!---银联隐藏域-->
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
<!--本地交易流水号-->
<input type="hidden" id="stream_no" value=""/> 
<input type="hidden" id="bank_success" value=""/> 
<!--
\*记录当前操作到哪步了

/*获取自费就诊记录 ic_get_pat_info
/*自费费用确认展示 ic_sf_show
/*自费缴费确认提交 ic_sf_save
/*选择支付方式    pay_chose
/*自费选择支付宝支付  zf_pay_zhifubao
/*在卡类型界面选择了医保卡 chose_yb_card
--> 
<input type="hidden" id="op_code"  value="<?php echo ($op_code); ?>" />
<input type="hidden" id="op_now"  value="" />
    <!-- <div class="code"></div> -->
<div class="main_body">
    <div id="downcount"></div>
    <div id="show_times">2016年10月12日 星期三 &nbsp;16:51</div>
    <div class="main_yuyue">
        <!--预约挂号选择卡类型-->
        <div class="chose_card_type_area mtnum2 " >
            <h3>请您选择卡类型</h3>
            <ul>
                <li class="ic" id="xz_ic">就诊卡</li>
                <li class="sfz" id="xz_sfz">身份证</li>
                <li class="yibao" id="xz_yibao">医保卡</li>
            </ul>
        </div>
        <!--预约挂号选择业务类型-->
        <div class="chose_pat_type mtnum2" style="display:none;">
            <h3>请您选择功能类型</h3>
            <ul>
                <li class="yuyue_quhao" id="xz_yu_quhao">预约取号</li>
                <!-- <li class="bu_guahao" id="xz_bu_guahao">补挂号费</li> -->
                <li class="yuyue_guahao" id="xz_yu_guahao">预约挂号</li>
            </ul>
        </div>
        <!--预约取号就诊卡操作界面-->
        <div class="jiuzhen_op_area_quhao mtnum2" style="display:none;">
            <h3>请扫描就诊卡或输入就诊卡号</h3>
            <input type="text" maxlength="12" class="iptx" value="" id="jz_card_no_quhao" />
            <div class="j_keybord_area_quhao">
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
                <div class="tips_quhao"></div>
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
        <div class="yb_op_area mtnum2" style="display:none;">
            <h3>请插入您的医保卡</h3>
            <div class="tips"></div>
        </div>
         <!--------处方列表-------->
        <div class="yuyue_record mtnum2" style="display:none;">
          <!--------添加了患者的姓名-------->
          <span style="color:#009FD6;font-size:35px;position:absolute;left:135px;" id="jz_name" ></span>
            <h3 style="font-size:35px">请确认预约信息</h3>
            <div class="bar_tit">
                <ul>
                    <li class="one">序号</li>
                    <li class="two">就诊科室</li>
                    <li class="thr">金额</li>
                    <li class="four">日期</li>
                </ul>
            </div>
            <div class="chufang_list"></div>
            <div class="tips"></div>
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
            <div class="chufang_list"></div>
            <div class="tips"></div>
        </div>

         <!--自助挂号选择科室-->
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
        <div class="phone mtnum2" style="display:none;">
            <h3>请输入您的手机号码</h3>
            <input type="text" maxlength="12" class="iptx" value="" id="pat_phone" />
            <div class="j_keybord_area_phone">
                <ul class="img_view">
                <li>温馨提示：</li>
                <li>●请正确输入您的手机号码。</li>
                <li>●若有停诊,我们将通过它与您联系。</li>
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
                    <li class="del">删除</li>
                </ul>
                <div class="tips_phone"></div>
            </div>
        </div>
         <!--预约挂号选择时间-->
        <div class="chose_time mtnum2" style="display:none;">
         <span style="color:#009FD6;font-size:35px;position:absolute;left:135px;top:8px;margin-left:-80px" id="yuyue_guahao_name" ></span>
            <h5 style="margin-top:-20px;">请选择预约挂号的时间</h5>
            <div id='time_list'></div>
            <div id="tixing"></div>
        </div>

 <!--自助挂号选择医生-->
         <div class="chose_doctor mtnum2" style="display:none;">
            <!--<span style="color:#009FD6;font-size:35px;position:absolute;left:105px;" id="keshi_name" ></span>-->
            <h3>请选择挂号医生</h3>
            <div class="doctor_list">
                
           </div>
           <div class="page">
                <span class="total_page"></span>
                <span class="prev">上一页</span>
                <span class="next">下一页</span>
           </div>
        </div>


         <!--预约取号业务流程-->
        <div class="result_quhao mtnum2" style="display:none">
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

     <div class="yy_guahao mtnum2" style="display:none">
            <h3>请确认预约挂号信息</h3>
            <ul>
                <li class="p1"><span class="s1">患者ID：</span><span class="s2"></span></li>
                <li class="p2"><span class="txt txt_1">姓名：</span><span class="uname"></span></li>
                <li class="p3"><span class="txt">性别：</span><span class="sex"></span></li>
                <li class="p6"><span class="fj_cz_room">科室：</span><span class="p_cz_room"></span></li>             <li class="p4"><span class="txt">挂号日期：</span><span class="time"></span></li>
                
            </ul>
        </div>
    
      
        <!--预约取号选择支付页面-->
        <div class="chose_pay_type_area_quhao mtnum2" style="display:none">
            <h3>请您选择支付方式</h3>
            <ul>
                <li class="bank_pay" id="pay_bank_guahao">银行卡</li>
                <li class="zhifubao" id="pay_zhifubao_quhao">支付宝</li>
            </ul>
        </div>
         <!--银行卡-->
        <div class="bank_guahao mtnum2" style="display:none">
            <h3>请插入您的银行卡（如图）</h3>
            <div class="tips" id="bank_tips"></div>
            <p>请将<strong>银联</strong>/VISA<strong>标识在上朝外</strong>插入银行卡</p>
        </div>
        <div class="bank_password_guahao mtnum2" style="display:none;">
            <h3>请输入您的银行卡密码</h3>
            <input type="text" class="PinField1" id="PinFieldGh" />
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
        <div class="pay_success mtnum2" style="display:none">
            <h3></h3>
            
        </div>

    </div>
    <div class="btn_bu" style="display:none;">
    <ul>
        <b><li id="bugua">补挂号费入口</li></b>
    </ul>
    </div>
    <div class="btn_area">
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

<script>
    function GetDateStr(AddDayCount) {

        var dd = new Date();

        dd.setDate(dd.getDate()+AddDayCount);//获取AddDayCount天后的日期

        var y = dd.getFullYear();

        var m = dd.getMonth()+1;//获取当前月份的日期

        var d = dd.getDate();

        return y+"-"+m+"-"+d;

    }



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
    document.getElementById("PinFieldGh").value= "";
    document.getElementById("PinFieldGh").style.display = "block";
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