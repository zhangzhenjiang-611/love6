<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=10" />
<link rel="stylesheet" type="text/css" href="/hddevice/mz/Public/zizhu/css/base.css" />
<link rel="stylesheet" type="text/css" href="/hddevice/mz/Public/zizhu/css/index_cx.css" />
<script language="javascript" src="/hddevice/mz/Public/zizhu/js/jquery-1.9.1.js" ></script> 
<script language="javascript" src="/hddevice/mz/Public/zizhu/js/ServerClock.js" ></script>  
<script language="javascript" type="text/javascript" src="/hddevice/mz/Public/layer/layer.js"></script>
<link rel="stylesheet" href="/hddevice/mz/Public/layui/css/layui.css" />
<script language="javascript" type="text/javascript" src="/hddevice/mz/Public/layui/layui.js"></script>
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
<script type="text/javascript" language="javascript">
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
    var data,datarow;
    var interval = "";
    var interval2 = new Array();
    var interval3 = new Array();
    var countdown = 60;
    var jzk_flag = 0;
    var page=1;
    var pagedata=null;
    var jf_pagedata=null;
    var yy_pagedata = null;
    var ocx;
    var cardNum;
    var now_time = null;
    var moring_time = null;
    var moon_time = null;
    var key_value="";
    var key_value2="";
    var register_sn = "";
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

    $(function(){
        writeLog("选择了自助查询");
        $("#business_type").val('自助查询');
        $(".btn_area").show();
        $("#confirm").css({"visibility":"hidden"});
        $("#fanhui").css({"visibility":"visible"});
        $("#tuichu").css({"visibility":"visible"});
        $("#op_now").val("chose_cx_type");
        //$("#confirm").hide();
        daojishi(60);
        var key_value = "";
        $(".j_keybord_area .key li.num").on("mousedown",function(){
            //$(".j_keybord_area .key li").removeClass("active");
            $(this).addClass("active");
            key_value+=$(this).attr("param");
            $("#jz_card_no").val(key_value);

        });
        $(".j_keybord_area .key li.del").on("click",function(){
            var jz_card_no=$("#jz_card_no").val();
            var a="";
            if(jz_card_no.length>0){
                var a=jz_card_no.substr(0,jz_card_no.length-1);
            }
            $("#jz_card_no").val(a);    
            key_value=a;
            key_value2="";
        });
        $(".j_keybord_area .key li").on("mouseup",function(){
            $(".j_keybord_area .key li").removeClass("active");
            //$(this).addClass("active");
        });
        /*******数字键盘区结束********************************************************************************/

        /*******身份证数字键盘开始********************************************************************************/
        $(".j_keybord_area_sfz .key li.num").on("mousedown",function(){
            //$(".j_keybord_area .key li").removeClass("active");
            
            $(this).addClass("active");
            key_value+=$(this).attr("param");
            $("#jz_card_no_sfz").val(key_value);

        });
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

        /******************医保卡数字键盘开始*************************************/

         $(".j_keybord_area_ybk .key li.num").on("mousedown",function(){
            //$(".j_keybord_area .key li").removeClass("active");
            
            $(this).addClass("active");
            key_value+=$(this).attr("param");
            $("#jz_card_no_ybk").val(key_value);

        });
        $(".j_keybord_area_ybk .key li.del").on("click",function(){
            var jz_card_no_ybk=$("#jz_card_no_ybk").val();
             var a="";
             if(jz_card_no_ybk.length>0){
             var a=jz_card_no_ybk.substr(0,jz_card_no_ybk.length-1);
            }
            $("#jz_card_no_ybk").val(a);    
            key_value=a;
            key_value2="";
        })
        $(".j_keybord_area_ybk .key li").on("mouseup",function(){
            $(".j_keybord_area_ybk .key li").removeClass("active");
        //$(this).addClass("active");
        });

        // 查询挂号
        $("#cx_guahao").click(function(){
            $(".mtnum2").hide();
            $(".chose_card_type_area").show();
            $("#confirm").css({"visibility":"hidden"});
            $("#cx_type").val("cx_guahao");
            $("#op_now").val("chose_pat_type");
            writeLog("选择了挂号查询");
            daojishi(60);
          
        });
        // 查询缴费
        $("#cx_jiaofei").click(function(){
            $(".mtnum2").hide();
            $(".chose_card_type_area").show();
            $("#confirm").css({"visibility":"hidden"});
            $("#cx_type").val("cx_jiaofei");
            $("#op_now").val("chose_pat_type");
            writeLog("选择了缴费查询");
            daojishi(60);
        });
        //查询预约记录
        $("#cx_yuyue").click(function(){
            $(".mtnum2").hide();
            $(".chose_card_type_area").show();
            $("#confirm").css({"visibility":"hidden"});
            $("#cx_type").val("cx_yuyue");
            $("#op_now").val("chose_pat_type");
            writeLog("选择了预约查询");
            daojishi(60);
        })

        // 选择了就诊卡
        $("#xz_ic").click(function(){
            $("#xz_ic").attr('name','ic');
            daojishi(60);
            $("#op_code").val(new Date().Format("yyyyMMddhhmmssS"));
            $("#op_now").val("ic_get_pat_info");
            $(".mtnum2").hide();
            $(".jiuzhen_op_area").show();
            $(".btn_area").show();
            $("#confirm").css({"visibility":"visible"});
            //window.external.send(6,2);
            // window.external.send(2,2);
            $("#jz_card_no").focus();
            $("#hz_jz_card_no").val($("#jz_card_no").val());
            $("#card_code").val("21");
            writeLog("选择了就诊卡");
            jzk_flag = window.external.InitIC();
            if(jzk_flag>0){
                $(".jiuzhen_op_area .tips").text("初始化成功");
                interval = setInterval(getCardNo, "1000");
                interval3.push(interval);
            }else{
                $(".jiuzhen_op_area .tips").text("初始化失败");
            }
            //window.external.nTextInput();
            
        });

        // 选择了身份证
        $("#xz_sfz").click(function(){
            $("#xz_ic").attr('name','sfz');
            daojishi(60);
            $("#op_code").val(new Date().Format("yyyyMMddhhmmssS"));
            $("#op_now").val("ic_get_pat_info_sfz");
            $(".mtnum2").hide();
            $(".jiuzhen_op_area_sfz").show();
            $("#confirm").css({"visibility":"visible"});
            writeLog("选择了身份证");
            window.external.send(6,2);
            $("#jz_card_no_sfz").focus();
            $("#hz_jz_card_no_sfz").val($("#jz_card_no_sfz").val());
            $("#card_code").val("21");
            jzk_flag = window.external.InitIC();
            // jzk_flag = 1;
            //window.external.nTextInput();
            if(jzk_flag>0){
                $(".jiuzhen_op_area_sfz .tips").text("初始化成功");
                interval = setInterval(getCardNoS, "1000");
                interval3.push(interval);
            }else{
                $(".jiuzhen_op_area_sfz .tips").text("初始化失败");
            }
            //window.external.nTextInput();
        });

       // 选择医保卡
        $("#xz_yibao").click(function(){
            $("#op_now").val("chose_yb_card");
            $(".mtnum2").hide();
            $(".yb_op_area").show();
            $("#confirm").css({"visibility":"visible"});
            writeLog("选择了医保卡");
            window.external.send(1,2);
            $("#jz_card_no_ybk").focus();
            $(".btn_area").show();
            $(".btn_area ul li").css({"visibility":"visible"});
            window.external.AllowCardIn();//允许医保进卡
            var flag=false;
		    interval = setInterval(function(){
                //读取医保卡的状态
                flag = window.external.ReadStatus();	
                if(flag){
                    $("#confirm").trigger("click");
                    clearInterval(interval);
                }
		    }, "1000");
            interval3.push(interval);
            writeLog("选择了医保卡");
            daojishi(60);
        });

        // 确认
        $("#confirm").on("click",function(){
            var op_now=$("#op_now").val();
            var cx_type=$("#cx_type").val(); 
            for(var key in interval3){
                clearInterval(interval3[key]);
            }    
            // alert(op_now);
            switch(op_now){
                // 就诊卡
                case "ic_get_pat_info":
                    if($("#jz_card_no").val()!=""){
                        if($("#jz_card_no").val().length!='12'){
                            $(".jiuzhen_op_area .tips").text("输入有误,请重新输入");
                            return;
                        }
                        var business_type =$("#business_type").val();
                        var end_week_time = getCurrentDate();
                        var start_week_time = getBeforeWeek(end_week_time);
                        var end_month_time = getCurrentDate();
                        var start_month_time = getBeforeMonth(end_month_time);
                        
                        switch(cx_type){
                            case "cx_guahao":
                                $("#op_now").val("cx_guahao_record");
                                $(".mtnum2").hide();
                                $(".guahao_record").show();
                                $("jiuzhen_op_area").hide();
                                // $("#jz_card_no").val("");
                                $("#confirm").css({"visibility":"visible"});
                                // window.external.send(1,4);
                                var kaid = $("#jz_card_no").val();
                                $(".gh_week_time").click(function(){
                                    $("#gh_flag_time").val($(".gh_week_time").attr('name'));
                                });
                                $(".gh_month_time").click(function(){
                                    $("#gh_flag_time").val($(".gh_month_time").attr('name'));
                                });
                                var index_load = "";
                                var params = {"kaid":kaid,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type,"start_week_time":start_week_time,"end_week_time":end_week_time}; 
                                $.ajax({
                                    url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfo", 
                                    type:'post',
                                    dataType:'json',
                                    data:params,
                                    beforeSend:function(){
                                        index_load = layer.msg('挂号记录查询中,请稍候...', {icon: 16,time:20000});
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"hidden"});
                                    },
                                    
                                    // 130625199407241229
                                    success:function(data)
                                    {
                                        // console.log(data);
                                        layer.close(index_load);
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"visible"});
                                        if(data['patGhInfo']['result']['execute_flag']==0)
                                        {   
                                            var age1=(data['patGhInfo']['datarow']['birthday'].substring(-1,10));
                                            $("#jz_name").text(data['patGhInfo']['datarow']['name']);
                                            $("#pat_code").val(data['patGhInfo']['datarow']['patient_id']); 
                                            $("#card_no").val(data['patGhInfo']['datarow']['card_no']);
                                            $("#card_code").val(data['patGhInfo']['datarow']['card_code']);
                                            $("#pat_name").val(data['patGhInfo']['datarow']['name']);
                                            $("#pat_sex").val(data['patGhInfo']['datarow']['sex_chn']);
                                            $("#response_type").val(data['patGhInfo']['datarow']['response_type']);
                                            $("#pat_age").val(ages(age1));
                                            //alert($("#response_type").val());
                                            $("#reponse_name").val(data['patGhInfo']['datarow']['response_chn']);
                                            if(data['gh_data']['result']['execute_flag']==0){
                                                var html = '';
                                                page = 1;
                                                pagedata = data['gh_data']['datarow'].reverse();
                                                showdata(page);
                                            }else{
                                                $(".guahao_record .guahao_list").text(data['gh_data']['message']);
                                                $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                $(".guahao_record .page").hide();
                                            }
                                                    
                                        }else{
                                            $(".guahao_record .guahao_list").text(data['patGhInfo']['result']['execute_message']);
                                            $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                            $(".guahao_record .page").hide();
                                        }
                                    }
                                });
                                $(".button_gh").click(function(){
                                    $(".guahao_list").html("");
                                    var cx_time = $("#gh_flag_time").val();
                                    if(cx_time == '1'){
                                        var params_week = {"kaid":kaid,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type,"start_week_time":start_week_time,"end_week_time":end_week_time,"cx_time":cx_time}; 
                                        $.ajax({
                                            url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfo", 
                                            type:'post',
                                            dataType:'json',
                                            data:params_week,
                                            beforeSend:function(){
                                                index_load = layer.msg('挂号记录查询中,请稍候...', {icon: 16,time:20000});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                        
                                            success:function(data)
                                            {
                                                // console.log(data);   
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patGhInfo']['result']['execute_flag']==0)
                                                {   
                                                    var age1=(data['patGhInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name").text(data['patGhInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patGhInfo']['datarow']['patient_id']); 
                                                    $("#card_no").val(data['patGhInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patGhInfo']['datarow']['card_code']);
                                                    $("#pat_name").val(data['patGhInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patGhInfo']['datarow']['sex_chn']);
                                                    //alert(data['patGhInfo']['datarow']['response_type']);
                                                    $("#response_type").val(data['patGhInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patGhInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patGhInfo']['datarow']['response_chn']);
                                                    if(data['gh_data']['result']['execute_flag']==0){
                                                        var html = "";
                                                        page = 1;
                                                        pagedata = data['gh_data']['datarow'].reverse();
                                                        showdata(page);
                                                    }else{
                                                        $(".guahao_record .guahao_list").text(data['gh_data']['message']);
                                                        $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".guahao_record .page").hide();
                                                    }
                                                            
                                                }else{
                                                    $(".guahao_record .guahao_list").text(data['patGhInfo']['result']['execute_message']);
                                                    $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".guahao_record .page").hide();
                                                }
                                            }
                                        });
                                    }else if(cx_time=='2'){
                                        var params_month = {"kaid":kaid,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type,"start_month_time":start_month_time,"end_month_time":end_month_time,"cx_time":cx_time}; 
                                        $.ajax({
                                            url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfo", 
                                            type:'post',
                                            dataType:'json',
                                            data:params_month,
                                            beforeSend:function(){
                                                index_load = layer.msg('挂号记录查询中,请稍候...', {icon: 16,time:20000});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                            success:function(data)
                                            {
                                                // console.log(data);   
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patGhInfo']['result']['execute_flag']==0)
                                                {   
                                                    var age1=(data['patGhInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name").text(data['patGhInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patGhInfo']['datarow']['patient_id']); 
                                                    $("#card_no").val(data['patGhInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patGhInfo']['datarow']['card_code']);
                                                    $("#pat_name").val(data['patGhInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patGhInfo']['datarow']['sex_chn']);
                                                    //alert(data['patGhInfo']['datarow']['response_type']);
                                                    $("#response_type").val(data['patGhInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patGhInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patGhInfo']['datarow']['response_chn']);
                                                    if(data['gh_data']['result']['execute_flag']==0){
                                                        
                                                        var html = "";
                                                        page = 1;
                                                        pagedata = data['gh_data']['datarow'].reverse();
                                                        showdata(page);
                                                        
                                                    }else{
                                                        $(".guahao_record .guahao_list").text(data['gh_data']['message']);
                                                        $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".guahao_record .page").hide();
                                                    }       
                                                }else{
                                                    $(".guahao_record .guahao_list").text(data['patGhInfo']['result']['execute_message']);
                                                    $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".guahao_record .page").hide();
                                                }
                                            }
                                        });
                                    }
                                });
                                daojishi(180);

                            break;
                            case "cx_jiaofei":
                                
                                $("jiuzhen_op_area").hide();
                                $(".mtnum2").hide();
                                $(".jiaofei_record2").show();
                                $("#op_now").val("cx_jiaofei_record");
                                var kaid = $("#jz_card_no").val();
                                var index_load = '';
                                var params = {"kaid":kaid,"business_type":business_type,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),'start_week_time_jf':start_week_time,"end_week_time_jf":end_week_time};
                                $.ajax({
                                    url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoJF",
                                    type:'post',
                                    data:params,
                                    dataType:'json',
                                    beforeSend:function(){
                                        index_load = layer.msg('患者缴费记录查询中,请稍候...', {icon: 16,time:20000});
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"hidden"});
                                    },
                                    success:function(data){
                                        // console.log(data);
                                        layer.close(index_load);
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"visible"});
                                        if(data['patInfo']['result']['execute_flag']=='0'){
                                            var age1=(data['patInfo']['datarow']['birthday'].substring(-1,10));
                                            $("#jz_name_jf").text(data['patInfo']['datarow']['name']);
                                            $("#pat_code").val(data['patInfo']['datarow']['patient_id']); 
                                            $("#card_no").val(data['patInfo']['datarow']['card_no']);
                                            $("#card_code").val(data['patInfo']['datarow']['card_code']);
                                            $("#pat_name").val(data['patInfo']['datarow']['name']);
                                            $("#pat_sex").val(data['patInfo']['datarow']['sex_chn']);
                                            //alert(data['patGhInfo']['datarow']['response_type']);
                                            $("#response_type").val(data['patInfo']['datarow']['response_type']);
                                            //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                            $("#pat_age").val(ages(age1));
                                            //alert($("#response_type").val());
                                            $("#reponse_name").val(data['patInfo']['datarow']['response_chn']);
                                            if(data['jf_data']['result']['execute_flag']=='0'){
                                                var html = '';
                                                var sub_html='';
                                                page = 1;
                                                jf_pagedata = data['jf_data']['datarow'].reverse();
                                                jf_showdata(page);
                                                
                                            }else{
                                                $(".jiaofei_record2 .jiaofei_list").text(data['jf_data']['message']);
                                                $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                $(".jiaofei_record2 .jf_page").hide();
                                            }
                                        }else{
                                            
                                            $(".jiaofei_record2 .jiaofei_list").text(data['patInfo']['result']['execute_message']);
                                            $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                            $(".jiaofei_record2 .jf_page").hide();
                                        }
                                    }
                                });
                                $(".jf_week_time").click(function(){
                                    var jf_week_time_flag = $(".jf_week_time").attr('name');
                                    $("#jf_flag_time").val(jf_week_time_flag);
                                });
                                $(".jf_month_time").click(function(){
                                    $("#jf_flag_time").val($(".jf_month_time").attr('name'));
                                });
                                $(".button_jf").click(function(){
                                    var cx_jf_time = $("#jf_flag_time").val();
                                    if(cx_jf_time=='1'){
                                        var params_jf = {'kaid':kaid,'business_type':business_type,'start_week_time_jf':start_week_time,'end_week_time_jf':end_week_time,'op_code':$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'cx_jf_time':cx_jf_time};
                                        $.ajax({
                                            url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoJF",
                                            type:'post',
                                            data:params_jf,
                                            dataType:'json',
                                            beforeSend:function(){
                                                index_load = layer.msg('患者缴费记录查询中...,请稍后',{icon:16,time:20000});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                            success:function(data){
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patInfo']['result']['execute_flag']=='0'){
                                                    var age1 = (data['patInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name_jf").text(data['patInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patInfo']['datarow']['patient_id']);
                                                    $("#card_no").val(data['patInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patInfo']['datarow']['card_code']);

                                                    $("#pat_name").val(data['patInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patInfo']['datarow']['sex_chn']);
                                                    $("#response_type").val(data['patInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patInfo']['datarow']['response_chn']);
                                                    if(data['jf_data']['result']['execute_flag']=='0'){
                                                       
                                                        var html = '';
                                                        var sub_html='';
                                                        page = 1;
                                                        jf_pagedata = data['jf_data']['datarow'].reverse();
                                                        jf_showdata(page);
                                                    }else{
                                                        $(".jiaofei_record2 .jiaofei_list").text(data['jf_data']['message']);
                                                        $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".jiaofei_record2 .jf_page").hide();
                                                    }
                                                }else{
                                                    $(".jiaofei_record2 .jiaofei_list").text(data['patInfo']['result']['execute_message']);
                                                    $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".jiaofei_record2 .jf_page").hide();
                                                }
                                            }
                                        });
                                    }else if(cx_jf_time=='2'){
                                        var params_jf = {'kaid':kaid,'business_type':business_type,'start_week_time_jf':start_month_time,'end_week_time_jf':end_month_time,'op_code':$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'cx_jf_time':cx_jf_time};
                                        $.ajax({
                                            url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoJF",
                                            type:'post',
                                            data:params_jf,
                                            dataType:'json',
                                            beforeSend:function(){
                                                index_load = layer.msg('患者缴费记录查询中...,请稍后',{icon:16,time:20000});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                            success:function(data){
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patInfo']['result']['execute_flag']=='0'){
                                                    var age1 = (data['patInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name_jf").text(data['patInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patInfo']['datarow']['patient_id']);
                                                    $("#card_no").val(data['patInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patInfo']['datarow']['card_code']);
                                                    $("#pat_name").val(data['patInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patInfo']['datarow']['sex_chn']);
                                                    $("#response_type").val(data['patInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patInfo']['datarow']['response_chn']);
                                                    if(data['jf_data']['result']['execute_flag']=='0'){
                                                        var html = '';
                                                        var sub_html='';
                                                        page = 1;
                                                        jf_pagedata = data['jf_data']['datarow'].reverse();
                                                        jf_showdata(page);
                                                    }else{
                                                        $(".jiaofei_record2 .jiaofei_list").text(data['jf_data']['message']);
                                                        $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".jiaofei_record2 .jf_page").hide();
                                                    }
                                                }else{
                                                    $(".jiaofei_record2 .jiaofei_list").text(data['patInfo']['result']['execute_message']);
                                                    $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".jiaofei_record2 .jf_page").hide();
                                                }
                                            }
                                        });
                                    }
                                    
                                });
                                daojishi(180);
                            break;
                            case "cx_yuyue":
                                // 预约查询时间
                                var start_week_time_yy = getCurrentDate();
                                var end_week_time_yy = getAfterWeek(start_week_time_yy);
                                var start_month_time_yy = getCurrentDate();
                                var end_month_time_yy = getAfterMonth(start_month_time_yy);
                                var kaid = $("#jz_card_no").val();
                                $(".yy_week_time").click(function(){
                                    $("#yy_flag_time").val($(".yy_week_time").attr('name'));
                                });
                                $(".yy_month_time").click(function(){
                                    $("#yy_flag_time").val($(".yy_month_time").attr('name'));
                                });
                               
                                $("jiuzhen_op_area").hide();
                                $(".mtnum2").hide();
                                $(".yuyue_record2").show();
                                $("#op_now").val("cx_yuyue_record");
                                var business_type = $("#business_type").val();
                                var params_yy = {'kaid':kaid,'op_code':$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'business_type':business_type,"start_week_time":start_week_time_yy,"end_week_time":end_week_time_yy};
                                var index_load = '';
                                $.ajax({
                                    url:'/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoYY',
                                    type:'post',
                                    data:params_yy,
                                    dataType:'json',
                                    beforeSend:function(){
                                        index_load = layer.msg('患者预约记录查询中...,请稍后',{icon:16,time:20000});
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"hidden"});
                                    },
                                    success:function(data){
                                        // console.log(data);
                                        layer.close(index_load);
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"visible"});
                                        if(data['patYyInfo']['result']['execute_flag']=='0'){
                                            var age1 = (data['patYyInfo']['datarow']['birthday'].substring(-1,10));
                                            $("#jz_name_yuyue").text(data['patYyInfo']['datarow']['name']);
                                            $("#pat_code").val(data['patYyInfo']['datarow']['patient_id']);
                                            $("#card_no").val(data['patYyInfo']['datarow']['card_no']);
                                            $("#card_code").val(data['patYyInfo']['datarow']['card_code']);

                                            $("#pat_name").val(data['patYyInfo']['datarow']['name']);
                                            $("#pat_sex").val(data['patYyInfo']['datarow']['sex_chn']);
                                            $("#response_type").val(data['patYyInfo']['datarow']['response_type']);
                                            //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                            $("#pat_age").val(ages(age1));
                                            //alert($("#response_type").val());
                                            $("#reponse_name").val(data['patYyInfo']['datarow']['response_chn']);
                                            if(data['yy_data']['result']['execute_flag']=='0'){
                                                var html = '';
                                                page = 1;
                                                yy_pagedata = data['yy_data']['datarow'];
                                                
                                                yy_showdata(page);
                                            }else{
                                                $(".yuyue_record2 .yuyue_list").text(data['yy_data']['message']);
                                                $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                $(".yuyue_record2 .yuyue_page").hide();
                                            }
                                        }else{
                                            $(".yuyue_record2 .yuyue_list").text(data['patYyInfo']['result']['execute_message']);
                                            $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                            $(".yuyue_record2 .yuyue_page").hide();
                                        }
                                    }
                                });

                                $(".button_yy").click(function(){
                                    $(".yuyue_list").html("");
                                    var cx_time = $("#yy_flag_time").val();
                                    if(cx_time=='1'){
                                        var business_type = $("#business_type").val();
                                        var params_yy_week = {'kaid':kaid,'op_code':$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'business_type':business_type,"start_week_time":start_week_time_yy,"end_week_time":end_week_time_yy,"cx_time":cx_time};
                                        var index_load = '';
                                        $.ajax({
                                            url:'/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoYY',
                                            type:'post',
                                            data:params_yy_week,
                                            dataType:'json',
                                            beforeSend:function(){
                                                index_load = layer.msg('患者预约记录查询中...,请稍后',{icon:16,time:20000});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                            success:function(data){
                                                // console.log(data);
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patYyInfo']['result']['execute_flag']=='0'){
                                                    var age1 = (data['patYyInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name_yuyue").text(data['patYyInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patYyInfo']['datarow']['patient_id']);
                                                    $("#card_no").val(data['patYyInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patYyInfo']['datarow']['card_code']);

                                                    $("#pat_name").val(data['patYyInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patYyInfo']['datarow']['sex_chn']);
                                                    $("#response_type").val(data['patYyInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patYyInfo']['datarow']['response_chn']);
                                                    if(data['yy_data']['result']['execute_flag']=='0'){
                                                        var html = '';
                                                        page = 1;
                                                        yy_pagedata = data['yy_data']['datarow'];
                                                        
                                                        yy_showdata(page);
                                                    }else{
                                                        $(".yuyue_record2 .yuyue_list").text(data['yy_data']['message']);
                                                        $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".yuyue_record2 .yuyue_page").hide();
                                                    }
                                                }else{
                                                    $(".yuyue_record2 .yuyue_list").text(data['patYyInfo']['result']['execute_message']);
                                                    $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".yuyue_record2 .yuyue_page").hide();
                                                }
                                            }
                                        });
                                    }else if(cx_time=='2'){
                                        var business_type = $("#business_type").val();
                                        var params_yy_month = {'kaid':kaid,'op_code':$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'business_type':business_type,"start_month_time":start_month_time_yy,"end_month_time":end_month_time_yy,"cx_time":cx_time};
                                        var index_load = '';
                                        $.ajax({
                                            url:'/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoYY',
                                            type:'post',
                                            data:params_yy_month,
                                            dataType:'json',
                                            beforeSend:function(){
                                                index_load = layer.msg('患者预约记录查询中...,请稍后',{icon:16,time:20000});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                            success:function(data){
                                                // console.log(data);
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patYyInfo']['result']['execute_flag']=='0'){
                                                    var age1 = (data['patYyInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name_yuyue").text(data['patYyInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patYyInfo']['datarow']['patient_id']);
                                                    $("#card_no").val(data['patYyInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patYyInfo']['datarow']['card_code']);

                                                    $("#pat_name").val(data['patYyInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patYyInfo']['datarow']['sex_chn']);
                                                    $("#response_type").val(data['patYyInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patYyInfo']['datarow']['response_chn']);
                                                    if(data['yy_data']['result']['execute_flag']=='0'){
                                                        var html = '';
                                                        page = 1;
                                                        yy_pagedata = data['yy_data']['datarow'];
                                                        
                                                        yy_showdata(page);
                                                    }else{
                                                        $(".yuyue_record2 .yuyue_list").text(data['yy_data']['message']);
                                                        $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".yuyue_record2 .yuyue_page").hide();
                                                    }
                                                }else{
                                                    $(".yuyue_record2 .yuyue_list").text(data['patYyInfo']['result']['execute_message']);
                                                    $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".yuyue_record2 .yuyue_page").hide();
                                                }
                                            }
                                        });
                                    }
                                });
                                daojishi(180);
                            break;
                        }
                    }else{
                        var message = "就诊卡号或身份证号错误";
                        layer.alert(message,{
                            icon: 6,
                            skin: 'layer-ext-moon',
                            time:5000,
                        });
                    }
                   
                break;

                // 身份证
                // 110228199010065427
                case "ic_get_pat_info_sfz":
                    if($("#jz_card_no_sfz").val()!=""){
                        if($("#jz_card_no_sfz").val().length!='18'  && $("#jz_card_no_sfz").val().length!='15'){
                            $(".jiuzhen_op_area_sfz .tips").text("输入有误,请重新输入");
                            return;
                        }
                        var business_type =$("#business_type").val();
                        var end_week_time = getCurrentDate();
                        var start_week_time = getBeforeWeek(end_week_time);
                        var end_month_time = getCurrentDate();
                        var start_month_time = getBeforeMonth(end_month_time);

                        
                        switch(cx_type){
                            case "cx_guahao":
                                $("#op_now").val("cx_guahao_record");
                                $(".mtnum2").hide();
                                $(".guahao_record").show();
                                $("jiuzhen_op_area").hide();
                                $("#confirm").css({"visibility":"visible"});
                                // window.external.send(1,4);
                                var kaid = $("#jz_card_no_sfz").val();
                                $(".gh_week_time").click(function(){
                                    $("#gh_flag_time").val($(".gh_week_time").attr('name'));
                                });
                                $(".gh_month_time").click(function(){
                                    $("#gh_flag_time").val($(".gh_month_time").attr('name'));
                                });
                                var index_load = "";
                                var params = {"kaid":kaid,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type,"start_week_time":start_week_time,"end_week_time":end_week_time}; 
                                $.ajax({
                                    url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfo", 
                                    type:'post',
                                    dataType:'json',
                                    data:params,
                                    beforeSend:function(){
                                        index_load = layer.msg('挂号记录查询中,请稍候...', {icon: 16,time:20000});
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"hidden"});
                                    },
                                    
                                    // 130625199407241229
                                    success:function(data)
                                    {
                                        // console.log(data);
                                        layer.close(index_load);
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"visible"});
                                        if(data['patGhInfo']['result']['execute_flag']==0)
                                        {   
                                            var age1=(data['patGhInfo']['datarow']['birthday'].substring(-1,10));
                                            $("#jz_name").text(data['patGhInfo']['datarow']['name']);
                                            $("#pat_code").val(data['patGhInfo']['datarow']['patient_id']); 
                                            $("#card_no").val(data['patGhInfo']['datarow']['card_no']);
                                            $("#card_code").val(data['patGhInfo']['datarow']['card_code']);

                                            $("#pat_name").val(data['patGhInfo']['datarow']['name']);
                                            $("#pat_sex").val(data['patGhInfo']['datarow']['sex_chn']);
                                            $("#response_type").val(data['patGhInfo']['datarow']['response_type']);
                                       
                                            $("#pat_age").val(ages(age1));
                                            //alert($("#response_type").val());
                                            $("#reponse_name").val(data['patGhInfo']['datarow']['response_chn']);
                                            if(data['gh_data']['result']['execute_flag']==0){
                                                var html = '';
                                                page = 1;
                                                pagedata = data['gh_data']['datarow'].reverse();
                                                showdata(page);
                                            }else{
                                                $(".guahao_record .guahao_list").text(data['gh_data']['message']);
                                                $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                $(".guahao_record .page").hide();
                                            }
                                                    
                                        }else{
                                            $(".guahao_record .guahao_list").text(data['patGhInfo']['result']['execute_message']);
                                            $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                            $(".guahao_record .page").hide();
                                        }
                                    }
                                });
                                $(".button_gh").click(function(){
                                    $(".guahao_list").html("");
                                    var cx_time = $("#gh_flag_time").val();
                                    if(cx_time == '1'){
                                        var params_week = {"kaid":kaid,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type,"start_week_time":start_week_time,"end_week_time":end_week_time,"cx_time":cx_time}; 
                                        $.ajax({
                                            url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfo", 
                                            type:'post',
                                            dataType:'json',
                                            data:params_week,
                                            beforeSend:function(){
                                                index_load = layer.msg('挂号记录查询中,请稍候...', {icon: 16,time:20000});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                        
                                            success:function(data)
                                            {
                                                // console.log(data);   
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patGhInfo']['result']['execute_flag']==0)
                                                {   
                                                    var age1=(data['patGhInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name").text(data['patGhInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patGhInfo']['datarow']['patient_id']); 
                                                    $("#card_no").val(data['patGhInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patGhInfo']['datarow']['card_code']);
                                                    $("#pat_name").val(data['patGhInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patGhInfo']['datarow']['sex_chn']);
                                                    //alert(data['patGhInfo']['datarow']['response_type']);
                                                    $("#response_type").val(data['patGhInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patGhInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patGhInfo']['datarow']['response_chn']);
                                                    if(data['gh_data']['result']['execute_flag']==0){
                                                        var html = "";
                                                        page = 1;
                                                        pagedata = data['gh_data']['datarow'].reverse();
                                                        showdata(page);
                                                    }else{
                                                        $(".guahao_record .guahao_list").text(data['gh_data']['message']);
                                                        $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".guahao_record .page").hide();
                                                    }
                                                            
                                                }else{
                                                    $(".guahao_record .guahao_list").text(data['patGhInfo']['result']['execute_message']);
                                                    $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".guahao_record .page").hide();
                                                }
                                            }
                                        });
                                    }else if(cx_time=='2'){
                                        var params_month = {"kaid":kaid,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type,"start_month_time":start_month_time,"end_month_time":end_month_time,"cx_time":cx_time}; 
                                        $.ajax({
                                            url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfo", 
                                            type:'post',
                                            dataType:'json',
                                            data:params_month,
                                            beforeSend:function(){
                                                index_load = layer.msg('挂号记录查询中,请稍候...', {icon: 16,time:20000});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                            success:function(data)
                                            {
                                                // console.log(data);   
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patGhInfo']['result']['execute_flag']==0)
                                                {   
                                                    var age1=(data['patGhInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name").text(data['patGhInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patGhInfo']['datarow']['patient_id']); 
                                                    $("#card_no").val(data['patGhInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patGhInfo']['datarow']['card_code']);
                                                    $("#pat_name").val(data['patGhInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patGhInfo']['datarow']['sex_chn']);
                                                    //alert(data['patGhInfo']['datarow']['response_type']);
                                                    $("#response_type").val(data['patGhInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patGhInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patGhInfo']['datarow']['response_chn']);
                                                    if(data['gh_data']['result']['execute_flag']==0){
                                                        
                                                        var html = "";
                                                        page = 1;
                                                        pagedata = data['gh_data']['datarow'].reverse();
                                                        showdata(page);
                                                        
                                                    }else{
                                                        $(".guahao_record .guahao_list").text(data['gh_data']['message']);
                                                        $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".guahao_record .page").hide();
                                                    }       
                                                }else{
                                                    $(".guahao_record .guahao_list").text(data['patGhInfo']['result']['execute_message']);
                                                    $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".guahao_record .page").hide();
                                                }
                                            }
                                        });
                                    }
                                });
                                daojishi(180);

                            break;
                            case "cx_jiaofei":
                                $("#op_now").val("cx_jiaofei_record");
                                $("jiuzhen_op_area").hide();
                                $(".mtnum2").hide();
                                $(".jiaofei_record2").show();
                                $("#op_now").val("cx_jiaofei_record");
                                var kaid = $("#jz_card_no_sfz").val();
                                var index_load = '';
                                var params = {"kaid":kaid,"business_type":business_type,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),'start_week_time_jf':start_week_time,"end_week_time_jf":end_week_time};
                                $.ajax({
                                    url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoJF",
                                    type:'post',
                                    data:params,
                                    dataType:'json',
                                    beforeSend:function(){
                                        index_load = layer.msg('患者缴费记录查询中,请稍候...', {icon: 16,time:20000});
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"hidden"});
                                    },
                                    success:function(data){
                                        // console.log(data);
                                        layer.close(index_load);
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"visible"});
                                        if(data['patInfo']['result']['execute_flag']=='0'){
                                            var age1=(data['patInfo']['datarow']['birthday'].substring(-1,10));
                                            $("#jz_name_jf").text(data['patInfo']['datarow']['name']);
                                            $("#pat_code").val(data['patInfo']['datarow']['patient_id']); 
                                            $("#card_no").val(data['patInfo']['datarow']['card_no']);
                                            $("#card_code").val(data['patInfo']['datarow']['card_code']);
                                            $("#pat_name").val(data['patInfo']['datarow']['name']);
                                            $("#pat_sex").val(data['patInfo']['datarow']['sex_chn']);
                                            //alert(data['patGhInfo']['datarow']['response_type']);
                                            $("#response_type").val(data['patInfo']['datarow']['response_type']);
                                            //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                            $("#pat_age").val(ages(age1));
                                            
                                            //alert($("#response_type").val());
                                            $("#reponse_name").val(data['patInfo']['datarow']['response_chn']);
                                            if(data['jf_data']['result']['execute_flag']=='0'){
                                                var html = '';
                                                var sub_html='';
                                                page = 1;
                                                jf_pagedata = data['jf_data']['datarow'].reverse();
                                                jf_showdata(page);
                                                
                                            }else{
                                                $(".jiaofei_record2 .jiaofei_list").text(data['jf_data']['message']);
                                                $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                $(".jiaofei_record2 .jf_page").hide();
                                            }
                                        }else{
                                            $(".jiaofei_record2 .jiaofei_list").text(data['patInfo']['result']['execute_message']);
                                            $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                            $(".jiaofei_record2 .jf_page").hide();
                                        }
                                    }
                                });
                                $(".jf_week_time").click(function(){
                                    var jf_week_time_flag = $(".jf_week_time").attr('name');
                                    $("#jf_flag_time").val(jf_week_time_flag);
                                });
                                $(".jf_month_time").click(function(){
                                    $("#jf_flag_time").val($(".jf_month_time").attr('name'));
                                });
                                $(".button_jf").click(function(){
                                    var cx_jf_time = $("#jf_flag_time").val();
                                    if(cx_jf_time=='1'){
                                        var params_jf = {'kaid':kaid,'business_type':business_type,'start_week_time_jf':start_week_time,'end_week_time_jf':end_week_time,'op_code':$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'cx_jf_time':cx_jf_time};
                                        $.ajax({
                                            url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoJF",
                                            type:'post',
                                            data:params_jf,
                                            dataType:'json',
                                            beforeSend:function(){
                                                index_load = layer.msg('患者缴费记录查询中...,请稍后',{icon:16,time:20000});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                            success:function(data){
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patInfo']['result']['execute_flag']=='0'){
                                                    var age1 = (data['patInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name_jf").text(data['patInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patInfo']['datarow']['patient_id']);
                                                    $("#card_no").val(data['patInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patInfo']['datarow']['card_code']);

                                                    $("#pat_name").val(data['patInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patInfo']['datarow']['sex_chn']);
                                                    $("#response_type").val(data['patInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patInfo']['datarow']['response_chn']);
                                                    if(data['jf_data']['result']['execute_flag']=='0'){
                                                       
                                                        var html = '';
                                                        var sub_html='';
                                                        page = 1;
                                                        jf_pagedata = data['jf_data']['datarow'].reverse();
                                                        jf_showdata(page);
                                                    }else{
                                                        $(".jiaofei_record2 .jiaofei_list").text(data['jf_data']['message']);
                                                        $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".jiaofei_record2 .jf_page").hide();
                                                    }
                                                }else{
                                                    $(".jiaofei_record2 .jiaofei_list").text(data['patInfo']['result']['execute_message']);
                                                    $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".jiaofei_record2 .jf_page").hide();
                                                }
                                            }
                                        });
                                    }else if(cx_jf_time=='2'){
                                        var params_jf = {'kaid':kaid,'business_type':business_type,'start_week_time_jf':start_month_time,'end_week_time_jf':end_month_time,'op_code':$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'cx_jf_time':cx_jf_time};
                                        $.ajax({
                                            url:"/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoJF",
                                            type:'post',
                                            data:params_jf,
                                            dataType:'json',
                                            beforeSend:function(){
                                                index_load = layer.msg('患者缴费记录查询中...,请稍后',{icon:16,time:20000});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                            success:function(data){
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patInfo']['result']['execute_flag']=='0'){
                                                    var age1 = (data['patInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name_jf").text(data['patInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patInfo']['datarow']['patient_id']);
                                                    $("#card_no").val(data['patInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patInfo']['datarow']['card_code']);

                                                    $("#pat_name").val(data['patInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patInfo']['datarow']['sex_chn']);
                                                    $("#response_type").val(data['patInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patInfo']['datarow']['response_chn']);
                                                    if(data['jf_data']['result']['execute_flag']=='0'){
                                                        var html = '';
                                                        var sub_html='';
                                                        page = 1;
                                                        jf_pagedata = data['jf_data']['datarow'].reverse();
                                                        jf_showdata(page);
                                                    }else{
                                                        $(".jiaofei_record2 .jiaofei_list").text(data['jf_data']['message']);
                                                        $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".jiaofei_record2 .jf_page").hide();
                                                    }
                                                }else{
                                                    $(".jiaofei_record2 .jiaofei_list").text(data['patInfo']['result']['execute_message']);
                                                    $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".jiaofei_record2 .jf_page").hide();
                                                }
                                            }
                                        });
                                    }
                                    
                                });
                                daojishi(180);
                            break;
                            case "cx_yuyue":
                                // 预约查询时间
                                var start_week_time_yy = getCurrentDate();
                                var end_week_time_yy = getAfterWeek(start_week_time_yy);
                                var start_month_time_yy = getCurrentDate();
                                var end_month_time_yy = getAfterMonth(start_month_time_yy);
                                var kaid = $("#jz_card_no_sfz").val();
                                $(".yy_week_time").click(function(){
                                    $("#yy_flag_time").val($(".yy_week_time").attr('name'));
                                });
                                $(".yy_month_time").click(function(){
                                    $("#yy_flag_time").val($(".yy_month_time").attr('name'));
                                });
                                $("#op_now").val("cx_yuyue_record");
                                $("jiuzhen_op_area").hide();
                                $(".mtnum2").hide();
                                $(".yuyue_record2").show();
                                $("#op_now").val("cx_yuyue_record");
                                var business_type = $("#business_type").val();
                                var params_yy = {'kaid':kaid,'op_code':$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'business_type':business_type,"start_week_time":start_week_time_yy,"end_week_time":end_week_time_yy};
                                var index_load = '';
                                $.ajax({
                                    url:'/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoYY',
                                    type:'post',
                                    data:params_yy,
                                    dataType:'json',
                                    beforeSend:function(){
                                        index_load = layer.msg('患者预约记录查询中...,请稍后',{icon:16,time:20000});
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"hidden"});
                                    },
                                    success:function(data){
                                        // console.log(data);
                                        layer.close(index_load);
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"visible"});
                                        if(data['patYyInfo']['result']['execute_flag']=='0'){
                                            var age1 = (data['patYyInfo']['datarow']['birthday'].substring(-1,10));
                                            $("#jz_name_yuyue").text(data['patYyInfo']['datarow']['name']);
                                            $("#pat_code").val(data['patYyInfo']['datarow']['patient_id']);
                                            $("#card_no").val(data['patYyInfo']['datarow']['card_no']);
                                            $("#card_code").val(data['patYyInfo']['datarow']['card_code']);

                                            $("#pat_name").val(data['patYyInfo']['datarow']['name']);
                                            $("#pat_sex").val(data['patYyInfo']['datarow']['sex_chn']);
                                            $("#response_type").val(data['patYyInfo']['datarow']['response_type']);
                                            //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                            $("#pat_age").val(ages(age1));
                                            //alert($("#response_type").val());
                                            $("#reponse_name").val(data['patYyInfo']['datarow']['response_chn']);
                                            if(data['yy_data']['result']['execute_flag']=='0'){
                                                var html = '';
                                                page = 1;
                                                yy_pagedata = data['yy_data']['datarow'];
                                                
                                                yy_showdata(page);
                                            }else{
                                                $(".yuyue_record2 .yuyue_list").text(data['yy_data']['message']);
                                                $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                $(".yuyue_record2 .yuyue_page").hide();
                                            }
                                        }else{
                                            
                                            $(".yuyue_record2 .yuyue_list").text(data['patYyInfo']['result']['execute_message']);
                                            $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                            $(".yuyue_record2 .yuyue_page").hide();
                                        }
                                    }
                                });

                                $(".button_yy").click(function(){
                                    $(".yuyue_list").html("");
                                    var cx_time = $("#yy_flag_time").val();
                                    if(cx_time=='1'){
                                        var business_type = $("#business_type").val();
                                        var params_yy_week = {'kaid':kaid,'op_code':$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'business_type':business_type,"start_week_time":start_week_time_yy,"end_week_time":end_week_time_yy,"cx_time":cx_time};
                                        var index_load = '';
                                        $.ajax({
                                            url:'/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoYY',
                                            type:'post',
                                            data:params_yy_week,
                                            dataType:'json',
                                            beforeSend:function(){
                                                index_load = layer.msg('患者预约记录查询中...,请稍后',{icon:16,time:20000});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                            success:function(data){
                                                // console.log(data);
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patYyInfo']['result']['execute_flag']=='0'){
                                                    var age1 = (data['patYyInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name_yuyue").text(data['patYyInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patYyInfo']['datarow']['patient_id']);
                                                    $("#card_no").val(data['patYyInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patYyInfo']['datarow']['card_code']);

                                                    $("#pat_name").val(data['patYyInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patYyInfo']['datarow']['sex_chn']);
                                                    $("#response_type").val(data['patYyInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patYyInfo']['datarow']['response_chn']);
                                                    if(data['yy_data']['result']['execute_flag']=='0'){
                                                        var html = '';
                                                        page = 1;
                                                        yy_pagedata = data['yy_data']['datarow'];
                                                       
                                                        yy_showdata(page);
                                                    }else{
                                                        $(".yuyue_record2 .yuyue_list").text(data['yy_data']['message']);
                                                        $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".yuyue_record2 .yuyue_page").hide();
                                                    }
                                                }else{
                                                    $(".yuyue_record2 .yuyue_list").text(data['patYyInfo']['result']['execute_message']);
                                                    $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".yuyue_record2 .yuyue_page").hide();
                                                }
                                            }
                                        });
                                    }else if(cx_time=='2'){
                                        var business_type = $("#business_type").val();
                                        var params_yy_month = {'kaid':kaid,'op_code':$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'business_type':business_type,"start_month_time":start_month_time_yy,"end_month_time":end_month_time_yy,"cx_time":cx_time};
                                        var index_load = '';
                                        $.ajax({
                                            url:'/hddevice/mz/index.php/ZiZhu/ChaXun/getCxPatInfoYY',
                                            type:'post',
                                            data:params_yy_month,
                                            dataType:'json',
                                            beforeSend:function(){
                                                index_load = layer.msg('患者预约记录查询中...,请稍后',{icon:16,time:20000});
                                                $("#confirm").css({"visibility":"hidden"});
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"hidden"});
                                            },
                                            success:function(data){
                                                // console.log(data);
                                                layer.close(index_load);
                                                $("#fanhui").css({"visibility":"hidden"});
                                                $("#tuichu").css({"visibility":"visible"});
                                                if(data['patYyInfo']['result']['execute_flag']=='0'){
                                                    var age1 = (data['patYyInfo']['datarow']['birthday'].substring(-1,10));
                                                    $("#jz_name_yuyue").text(data['patYyInfo']['datarow']['name']);
                                                    $("#pat_code").val(data['patYyInfo']['datarow']['patient_id']);
                                                    $("#card_no").val(data['patYyInfo']['datarow']['card_no']);
                                                    $("#card_code").val(data['patYyInfo']['datarow']['card_code']);

                                                    $("#pat_name").val(data['patYyInfo']['datarow']['name']);
                                                    $("#pat_sex").val(data['patYyInfo']['datarow']['sex_chn']);
                                                    $("#response_type").val(data['patYyInfo']['datarow']['response_type']);
                                                    //$("#pat_age").val(data['patInfo']['datarow']['age']);
                                                    $("#pat_age").val(ages(age1));
                                                    //alert($("#response_type").val());
                                                    $("#reponse_name").val(data['patYyInfo']['datarow']['response_chn']);
                                                    if(data['yy_data']['result']['execute_flag']=='0'){
                                                        var html = '';
                                                        page = 1;
                                                        yy_pagedata = data['yy_data']['datarow'];
                                                        
                                                        yy_showdata(page);
                                                    }else{
                                                        $(".yuyue_record2 .yuyue_list").text(data['yy_data']['message']);
                                                        $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".yuyue_record2 .yuyue_page").hide();
                                                    }
                                                }else{
                                                    $(".yuyue_record2 .yuyue_list").text(data['patYyInfo']['result']['execute_message']);
                                                    $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                    $(".yuyue_record2 .yuyue_page").hide();
                                                }
                                            }
                                        });
                                    }
                                });
                                daojishi(180);
                            break;
                        }
                    }else{
                        var message = "就诊卡号或身份证号错误";
                        layer.alert(message,{
                            icon: 6,
                            skin: 'layer-ext-moon',
                            time:5000,
                        });
                    }
                   
                break;

                // 医保患者
                case "chose_yb_card":
                    daojishi(180);
                    $(".yb_op_area .tips").html("医保患者数据读取中...,请稍后");
                    var flag;
                    var patinfo;
                    var business_type =$("#business_type").val();
                    var end_week_time = getCurrentDate();
                    var start_week_time = getBeforeWeek(end_week_time);
                    var end_month_time = getCurrentDate();
                    var start_month_time = getBeforeMonth(end_month_time);
                    
                    switch(cx_type){
                        case "cx_guahao":
                            $("#op_now").val("yb_cx_gh_record");
                            $(".btn_area").hide();
                            setTimeout(function() {
                                flag = window.external.InitYbIntfDll();
                                if(flag == 0){
                                    writeLog("开始读取医保读卡器,医保读卡器初始化成功","INFO");
                                    patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
                                    setTimeout(function(){
                                        if(patinfo!=""){
                                            var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"start_week_time":start_week_time,"end_week_time":end_week_time,'zzj_id':$("#zzj_id").val(),'business_type':$("#business_type").val()};
                                            $.ajax({
                                                url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YbXmlParseGhao",
                                                type:"post",
                                                dataType:"json",
                                                data:params,
                                                beforeSend:function(){
                                                    $(".yb_op_area .tips").html("挂号信息查询中,请稍后...");
                                                },
                                                success:function(data){
                                                    $(".mtnum2").hide();
                                                    $(".guahao_record").show();
                                                    $(".btn_area").show();
                                                    $("#confirm").css({"visibility":"hidden"});
                                                    $("#fanhui").css({"visibility":"hidden"});
                                                    $("#tuichu").css({"visibility":"visible"});
                                                    if(data['result']['execute_flag']=='0'){
                                                        $("#jz_name").text(data['yb_input_data']['name']);
                                                        $("#pat_code").val(data['yb_input_data']['patient_id']); 
                                                        $("#card_no").val(data['yb_input_data']['card_no']);
                                                        $("#card_code").val(data['yb_input_data']['card_code']);
                                                        $("#pat_name").val(data['yb_input_data']['name']);
                                                        if(data['gh_data']['result']['execute_flag']=='0'){
                                                            var html = '';
                                                            page = 1;
                                                            pagedata = data['gh_data']['datarow'].reverse();
                                                            showdata(page);
                                                        }else{
                                                          
                                                            $(".guahao_record .guahao_list").text(data['gh_data']['message']);
                                                            $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                            $(".guahao_record .page").hide();
                                                        }
                                                    }else{
                                                        $(".guahao_record .guahao_list").text(data['result']['execute_message']);
                                                        $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".guahao_record .page").hide();
                                                    }
                                                }
                                            });
                                        }else{
                                            $(".yb_op_area .tips").html("未知错误");
                                        }
                                    }, 2000);
                                }else{
                                    $(".yb_op_area .tips").html("医保读卡器初始化失败");
                                    window.external.MoveOutCard();
                                    $(".btn_area").show();
                                    $("#op_now").val("choose_yb_card");
                                }
                            }, 500);
                            $(".gh_week_time").click(function(){
                                $("#gh_flag_time").val($(".gh_week_time").attr('name'));
                            });
                            $(".gh_month_time").click(function(){
                                $("#gh_flag_time").val($(".gh_month_time").attr('name'));
                            });
                            $(".button_gh").click(function(){
                                var index_load= "";
                                index_load = layer.msg('医保患者数据读取中,请稍候...', {icon: 16,time:20000});
                                $(".guahao_list").html("");
                                var cx_time = $("#gh_flag_time").val();
                                if(cx_time=='1'){
                                    setTimeout(function() {
                                        flag = window.external.InitYbIntfDll();
                                        if(flag == 0){
                                            writeLog("开始读取医保读卡器,医保读卡器初始化成功","INFO");
                                            patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
                                            setTimeout(function(){
                                                if(patinfo!=""){
                                                    var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"start_week_time":start_week_time,"end_week_time":end_week_time,'cx_time':cx_time,'zzj_id':$("#zzj_id").val(),'business_type':$("#business_type").val()};
                                                    
                                                    $.ajax({
                                                        url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YbXmlParseGhao",
                                                        type:"post",
                                                        dataType:"json",
                                                        data:params,
                                                         beforeSend:function(){
                                                             index_load = layer.msg('挂号患者数据查询中,请稍候...', {icon: 16,time:20000});
                                                        },
                                                        success:function(data){
                                                            // console.log(data); 
                                                            layer.close(index_load);
                                                           
                                                            $(".mtnum2").hide();
                                                            $(".guahao_record").show();
                                                            $(".btn_area").show();
                                                            $("#confirm").css({"visibility":"hidden"});
                                                            $("#fanhui").css({"visibility":"hidden"});
                                                            $("#tuichu").css({"visibility":"visible"});
                                                            if(data['result']['execute_flag']=='0'){
                                                                $("#jz_name").text(data['yb_input_data']['name']);
                                                                $("#pat_code").val(data['yb_input_data']['patient_id']); 
                                                                $("#card_no").val(data['yb_input_data']['card_no']);
                                                                $("#card_code").val(data['yb_input_data']['card_code']);
                                                                $("#pat_name").val(data['yb_input_data']['name']);
                                                                if(data['gh_data']['result']['execute_flag']=='0'){
                                                                    var html = "";
                                                                    page = 1;
                                                                    pagedata = data['gh_data']['datarow'].reverse();
                                                                    showdata(page);
                                                                }else{
                                                                    $(".guahao_record .guahao_list").text(data['gh_data']['message']);
                                                                    $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                    $(".guahao_record .page").hide();
                                                                }
                                                            }else{
                                                                $(".guahao_record .guahao_list").text(data['result']['execute_message']);
                                                                $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                $(".guahao_record .page").hide();
                                                            }
                                                        }
                                                    });
                                                }else{
                                                    $(".yb_op_area .tips").html("未知错误");
                                                }
                                            }, 2000);
                                        }else{
                                            $(".yb_op_area .tips").html("医保读卡器初始化失败");
                                            window.external.MoveOutCard();
                                            $(".btn_area").show();
                                            $("#op_now").val("choose_yb_card");
                                        }
                                    }, 500);
                                }else if(cx_time=='2'){
                                   
                                    setTimeout(function() {
                                        flag = window.external.InitYbIntfDll();
                                        if(flag == 0){
                                            writeLog("开始读取医保读卡器,医保读卡器初始化成功","INFO");
                                            patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
                                            setTimeout(function(){
                                                if(patinfo!=""){
                                                    var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"start_month_time":start_month_time,"end_month_time":end_month_time,'cx_time':cx_time,'zzj_id':$("#zzj_id").val(),'business_type':$("#business_type").val()};
                                                   
                                                    $.ajax({
                                                        url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YbXmlParseGhao",
                                                        type:"post",
                                                        dataType:"json",
                                                        data:params,
                                                        beforeSend:function(){
                                                            index_load = layer.msg('挂号患者数据查询中,请稍候...', {icon: 16,time:20000});
                                                        },
                                                        success:function(data){
                                                             layer.close(index_load);
                                                            // console.log(data); 
                                                            $(".mtnum2").hide();
                                                            $(".guahao_record").show();
                                                            $(".btn_area").show();
                                                            $("#confirm").css({"visibility":"hidden"});
                                                            $("#fanhui").css({"visibility":"hidden"});
                                                            $("#tuichu").css({"visibility":"visible"});
                                                            if(data['result']['execute_flag']=='0'){
                                                                $("#jz_name").text(data['yb_input_data']['name']);
                                                                $("#pat_code").val(data['yb_input_data']['patient_id']); 
                                                                $("#card_no").val(data['yb_input_data']['card_no']);
                                                                $("#card_code").val(data['yb_input_data']['card_code']);
                                                                $("#pat_name").val(data['yb_input_data']['name']);
                                                                if(data['gh_data']['result']['execute_flag']=='0'){
                                                                    var html = "";
                                                                    page = 1;
                                                                    pagedata = data['gh_data']['datarow'].reverse();
                                                                    showdata(page);
                                                                }else{
                                                                    $(".guahao_record .guahao_list").text(data['gh_data']['message']);
                                                                    $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                    $(".guahao_record .page").hide();
                                                                }
                                                               
                                                            }else{
                                                                 $(".guahao_record .guahao_list").text(data['result']['execute_message']);
                                                                $(".guahao_record .guahao_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                $(".guahao_record .page").hide();
                                                            }
                                                        }
                                                    });
                                                }else{
                                                    $(".yb_op_area .tips").html("未知错误");
                                                }
                                            }, 2000);
                                        }else{
                                            $(".yb_op_area .tips").html("医保读卡器初始化失败");
                                            window.external.MoveOutCard();
                                            $(".btn_area").show();
                                            $("#op_now").val("choose_yb_card");
                                        }
                                       
                                    }, 500);
                                }
                            });
                        break;
                        case "cx_jiaofei":
                            $("#op_now").val("yb_cx_jf_record");
                            $(".btn_area").hide();
                            setTimeout(function() {
                                flag = window.external.InitYbIntfDll();
                                if(flag == 0){
                                    writeLog("开始读取医保读卡器,医保读卡器初始化成功","INFO");
                                    patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
                                    setTimeout(function(){
                                        if(patinfo!=""){
                                            var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"start_week_time":start_week_time,"end_week_time":end_week_time};
                                            $.ajax({
                                                url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YbXmlParseJF",
                                                type:"post",
                                                dataType:"json",
                                                data:params,
                                                beforeSend:function(){
                                                    $(".yb_op_area .tips").html("就诊信息查询中,请稍后...");
                                                },
                                                success:function(data){
                                                    // console.log(data); 
                                                    $(".mtnum2").hide();
                                                    $(".jiaofei_record2").show();
                                                    $(".btn_area").show();
                                                    $("#confirm").css({"visibility":"hidden"});
                                                    $("#fanhui").css({"visibility":"hidden"});
                                                    $("#tuichu").css({"visibility":"visible"});
                                                    if(data['result']['execute_flag']=='0'){
                                                        $("#jz_name_jf").text(data['yb_input_jf_data']['name']);
                                                        $("#pat_code").val(data['yb_input_jf_data']['patient_id']); 
                                                        $("#card_no").val(data['yb_input_jf_data']['card_no']);
                                                        $("#card_code").val(data['yb_input_jf_data']['card_code']);
                                                        $("#pat_name").val(data['yb_input_jf_data']['name']);
                                                        if(data['jf_data']['result']['execute_flag']=='0'){
                                                            var html = '';
                                                            var sub_html='';
                                                            page = 1;
                                                            jf_pagedata = data['jf_data']['datarow'].reverse();
                                                            jf_showdata(page);
                                                        }else{
                                                            $(".jiaofei_record2 .jiaofei_list").text(data['jf_data']['message']);
                                                            $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                            $(".jiaofei_record2 .jf_page").hide();
                                                        }
                                                        
                                                    }else{
                                                       
                                                        $(".jiaofei_record2 .jiaofei_list").text(data['result']['execute_message']);
                                                        $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".jiaofei_record2 .jf_page").hide();
                                                    }
                                                }
                                            });
                                        }else{
                                            $(".yb_op_area .tips").html("未知错误");
                                        }
                                    }, 2000);
                                }else{
                                    $(".yb_op_area .tips").html("医保读卡器初始化失败");
                                    window.external.MoveOutCard();
                                    $(".btn_area").show();
                                    $("#op_now").val("choose_yb_card");
                                }
                               
                            }, 500);

                            $(".jf_week_time").click(function(){
                                $("#jf_flag_time").val($(".jf_week_time").attr('name'));
                            });
                            $(".jf_month_time").click(function(){
                                $("#jf_flag_time").val($(".jf_month_time").attr('name'));
                            });
                            $(".button_jf").click(function(){
                                $(".jiaofei_list").html("");
                                var index_load= "";
                                index_load = layer.msg('医保患者数据读取中,请稍候...', {icon: 16,time:20000});
                                var cx_time = $("#jf_flag_time").val();
                                if(cx_time=='1'){
                                    setTimeout(function() {
                                        flag = window.external.InitYbIntfDll();
                                        if(flag == 0){
                                            writeLog("开始读取医保读卡器,医保读卡器初始化成功","INFO");
                                            patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
                                            setTimeout(function(){
                                                if(patinfo!=""){
                                                    var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"start_week_time":start_week_time,"end_week_time":end_week_time,'cx_time':cx_time,'zzj_id':$("#zzj_id").val(),'business_type':$("#business_type").val()};
                                                    
                                                    $.ajax({
                                                        url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YbXmlParseJF",
                                                        type:"post",
                                                        dataType:"json",
                                                        data:params,
                                                        beforeSend:function(){
                                                           index_load = layer.msg('患者数据查询中,请稍候...', {icon: 16,time:20000});
                                                        },
                                                        success:function(data){
                                                            // console.log(data); 
                                                             layer.close(index_load);
                                                            $(".mtnum2").hide();
                                                            $(".jiaofei_record2").show();
                                                            $(".btn_area").show();
                                                            $("#confirm").css({"visibility":"hidden"});
                                                            $("#fanhui").css({"visibility":"hidden"});
                                                            $("#tuichu").css({"visibility":"visible"});
                                                            if(data['result']['execute_flag']=='0'){
                                                                $("#jz_name_jf").text(data['yb_input_jf_data']['name']);
                                                                $("#pat_code").val(data['yb_input_jf_data']['patient_id']); 
                                                                $("#card_no").val(data['yb_input_jf_data']['card_no']);
                                                                $("#card_code").val(data['yb_input_jf_data']['card_code']);
                                                                $("#pat_name").val(data['yb_input_jf_data']['name']);
                                                                if(data['jf_data']['result']['execute_flag']=='0'){
                                                                    var html = '';
                                                                    var sub_html='';
                                                                    page = 1;
                                                                    jf_pagedata = data['jf_data']['datarow'].reverse();
                                                                    jf_showdata(page);
                                                                }else{
                                                                    $(".jiaofei_record2 .jiaofei_list").text(data['jf_data']['message']);
                                                                    $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                    $(".jiaofei_record2 .jf_page").hide();
                                                                }
                                                                
                                                            }else{
                                                                $(".jiaofei_record2 .jiaofei_list").text( data['result']['execute_message']);
                                                                $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                $(".jiaofei_record2 .jf_page").hide();
                                                            }
                                                        }
                                                    });
                                                }else{
                                                    $(".yb_op_area .tips").html("未知错误");
                                                }
                                            }, 2000);
                                        }else{
                                            $(".yb_op_area .tips").html("医保读卡器初始化失败");
                                            window.external.MoveOutCard();
                                            $(".btn_area").show();
                                            $("#op_now").val("choose_yb_card");
                                        }
                                       
                                    }, 500);
                                }else if(cx_time=='2'){
                                    setTimeout(function() {
                                        flag = window.external.InitYbIntfDll();
                                        if(flag == 0){
                                            writeLog("开始读取医保读卡器,医保读卡器初始化成功","INFO");
                                            patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val()); 
                                            setTimeout(function(){
                                                if(patinfo!=""){
                                                    var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"start_month_time":start_month_time,"end_month_time":end_month_time,'cx_time':cx_time,'zzj_id':$("#zzj_id").val(),'business_type':$("#business_type").val()};
                                                    
                                                    $.ajax({
                                                        url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YbXmlParseJF",
                                                        type:"post",
                                                        dataType:"json",
                                                        data:params,
                                                        beforeSend:function(){
                                                            index_load = layer.msg('患者数据查询中,请稍候...', {icon: 16,time:20000});
                                                        },
                                                        success:function(data){
                                                                                                                                                                                                                layer.close(index_load);
                                                            // console.log(data); 
                                                            $(".mtnum2").hide();
                                                            $(".jiaofei_record2").show();
                                                            $(".btn_area").show();
                                                            $("#confirm").css({"visibility":"hidden"});
                                                            $("#fanhui").css({"visibility":"hidden"});
                                                            $("#tuichu").css({"visibility":"visible"});
                                                            if(data['result']['execute_flag']=='0'){
                                                                $("#jz_name_jf").text(data['yb_input_jf_data']['name']);
                                                                $("#pat_code").val(data['yb_input_jf_data']['patient_id']); 
                                                                $("#card_no").val(data['yb_input_jf_data']['card_no']);
                                                                $("#card_code").val(data['yb_input_jf_data']['card_code']);
                                                                $("#pat_name").val(data['yb_input_jf_data']['name']);
                                                                if(data['jf_data']['result']['execute_flag']=='0'){
                                                                    var html = '';
                                                                    var sub_html='';
                                                                    page = 1;
                                                                    jf_pagedata = data['jf_data']['datarow'].reverse();
                                                                    jf_showdata(page);
                                                                }else{
                                                                    $(".jiaofei_record2 .jiaofei_list").text(data['jf_data']['message']);
                                                                    $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                    $(".jiaofei_record2 .jf_page").hide();
                                                                }
                                                                
                                                            }else{
                                                                 $(".jiaofei_record2 .jiaofei_list").text( data['result']['execute_message']);
                                                                $(".jiaofei_record2 .jiaofei_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                $(".jiaofei_record2 .jf_page").hide();
                                                            }
                                                        }
                                                    });
                                                }else{
                                                    $(".yb_op_area .tips").html("未知错误");
                                                }
                                            }, 2000);
                                        }else{
                                            $(".yb_op_area .tips").html("医保读卡器初始化失败");
                                            window.external.MoveOutCard();
                                            $(".btn_area").show();
                                            $("#op_now").val("choose_yb_card");
                                        }
                                       
                                    }, 500);
                                }
                            });
                        break;
                        case "cx_yuyue":
                            //预约查询时间    
                            var start_week_time_yy = getCurrentDate();
                            var end_week_time_yy = getAfterWeek(start_week_time_yy);
                            var start_month_time_yy = getCurrentDate();
                            var end_month_time_yy = getAfterMonth(start_month_time_yy);
                            $("#op_now").val("yb_cx_yy_record");
                            $(".btn_area").hide();
                            setTimeout(function() {
                                flag = window.external.InitYbIntfDll();
                                if(flag == 0){
                                    writeLog("开始读取医保读卡器,医保读卡器初始化成功","INFO");
                                    patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
                                    setTimeout(function(){
                                        if(patinfo!=""){
                                            var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"start_week_time":start_week_time_yy,"end_week_time":end_week_time_yy,'zzj_id':$("#zzj_id").val(),'business_type':$("#business_type").val()};
                                            $.ajax({
                                                url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YbXmlParseYY",
                                                type:"post",
                                                dataType:"json",
                                                data:params,
                                                beforeSend:function(){
                                                    $(".yb_op_area .tips").html("就诊信息查询中,请稍后...");
                                                },
                                                success:function(data){
                                                    // console.log(data); 
                                                    $(".mtnum2").hide();
                                                    $(".yuyue_record2").show();
                                                    $(".btn_area").show();
                                                    $("#confirm").css({"visibility":"hidden"});
                                                    $("#fanhui").css({"visibility":"hidden"});
                                                    $("#tuichu").css({"visibility":"visible"});
                                                    if(data['result']['execute_flag']=='0'){
                                                        $("#jz_name_yuyue").text(data['yb_input_yy_data']['name']);
                                                        $("#pat_code").val(data['yb_input_yy_data']['patient_id']); 
                                                        $("#card_no").val(data['yb_input_yy_data']['card_no']);
                                                        $("#card_code").val(data['yb_input_yy_data']['card_code']);
                                                        $("#pat_name").val(data['yb_input_yy_data']['name']);
                                                        if(data['yy_data']['result']['execute_flag']=='0'){
                                                            var html = '';
                                                            page = 1;
                                                            yy_pagedata = data['yy_data']['datarow'];
                                                            
                                                            yy_showdata(page);
                                                        }else{
                                                            $(".yuyue_record2 .yuyue_list").text(data['yy_data']['message']);
                                                            $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                            $(".yuyue_record2 .yuyue_page").hide();
                                                        }
                                                    }else{
                                                      
                                                        $(".yuyue_record2 .yuyue_list").text(data['result']['execute_message']);
                                                        $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                        $(".yuyue_record2 .yuyue_page").hide();
                                                    }
                                                }
                                            });
                                        }else{
                                            $(".yb_op_area .tips").html("未知错误");
                                        }
                                    }, 2000);
                                }else{
                                    $(".yb_op_area .tips").html("医保读卡器初始化失败");
                                    window.external.MoveOutCard();
                                    $(".btn_area").show();
                                    $("#op_now").val("choose_yb_card");
                                }
                            }, 500);
                            $(".yy_week_time").click(function(){
                                $("#yy_flag_time").val($(".yy_week_time").attr('name'));
                            });
                            $(".yy_month_time").click(function(){
                                $("#yy_flag_time").val($(".yy_month_time").attr('name'));
                            });
                            $(".button_yy").click(function(){
                                var index_load= "";
                                index_load = layer.msg('医保患者数据读取中,请稍候...', {icon: 16,time:20000});
                                $(".yuyue_list").html("");
                                $(".yuyue_record2 .tips").html("医保患者数据获取中,请稍后...");
                                var cx_time = $("#yy_flag_time").val();
                                if(cx_time=='1'){
                                    setTimeout(function() {
                                        flag = window.external.InitYbIntfDll();
                                       
                                        if(flag == 0){
                                            writeLog("开始读取医保读卡器,医保读卡器初始化成功","INFO");
                                            patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
                                            setTimeout(function(){
                                                if(patinfo!=""){
                                                    var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"start_week_time":start_week_time_yy,"end_week_time":end_week_time_yy,'cx_time':cx_time,'zzj_id':$("#zzj_id").val(),'business_type':$("#business_type").val()};
                                                    
                                                    $.ajax({
                                                        url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YbXmlParseYY",
                                                        type:"post",
                                                        dataType:"json",
                                                        data:params,
                                                        beforeSend:function(){
                                                            index_load = layer.msg('患者数据查询中,请稍候...', {icon: 16,time:20000});
                                                        },
                                                        success:function(data){
                                                            // console.log(data); 
                                                                                                                                                                                                                layer.close(index_load);
                                                            $(".mtnum2").hide();
                                                            $(".yuyue_record2").show();
                                                            $(".btn_area").show();
                                                            $("#confirm").css({"visibility":"hidden"});
                                                            $("#fanhui").css({"visibility":"hidden"});
                                                            $("#tuichu").css({"visibility":"visible"});
                                                            if(data['result']['execute_flag']=='0'){
                                                                $("#jz_name_yuyue").text(data['yb_input_yy_data']['name']);
                                                                $("#pat_code").val(data['yb_input_yy_data']['patient_id']); 
                                                                $("#card_no").val(data['yb_input_yy_data']['card_no']);
                                                                $("#card_code").val(data['yb_input_yy_data']['card_code']);
                                                                $("#pat_name").val(data['yb_input_yy_data']['name']);
                                                                if(data['yy_data']['result']['execute_flag']=='0'){
                                                                    var html = '';
                                                                    page = 1;
                                                                    yy_pagedata = data['yy_data']['datarow'];
                                                                    
                                                                    yy_showdata(page);
                                                                }else{
                                                                    $(".yuyue_record2 .yuyue_list").text(data['yy_data']['message']);
                                                                    $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                    $(".yuyue_record2 .yuyue_page").hide();
                                                                }
                                                               
                                                            }else{
                                                                $(".yuyue_record2 .yuyue_list").text(data['result']['execute_message']);
                                                                $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                $(".yuyue_record2 .yuyue_page").hide();
                                                            }
                                                        }
                                                    });
                                                }else{
                                                    $(".yb_op_area .tips").html("未知错误");
                                                }
                                            }, 2000);
                                        }else{
                                            $(".yb_op_area .tips").html("医保读卡器初始化失败");
                                            window.external.MoveOutCard();
                                            $(".btn_area").show();
                                            $("#op_now").val("choose_yb_card");
                                        }
                                       
                                    }, 500);
                                }else if(cx_time=='2'){
                                    
                                    setTimeout(function() {
                                        flag = window.external.InitYbIntfDll();
                                        if(flag == 0){
                                            writeLog("开始读取医保读卡器,医保读卡器初始化成功","INFO");
                                            patinfo = window.external.YYT_YB_GET_PATI($("#zzj_id").val(),$("#business_type").val());
                                            // pathinfo = 1;
                                            setTimeout(function(){
                                                if(patinfo!=""){
                                                    var params = {"input_xml":patinfo,"op_code":$("#op_code").val(),"start_month_time":start_month_time_yy,"end_month_time":end_month_time_yy,'cx_time':cx_time,'zzj_id':$("#zzj_id").val(),'business_type':$("#business_type").val()};
                                                    var index_load = "";
                                                    $.ajax({
                                                        url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YbXmlParseYY",
                                                        type:"post",
                                                        dataType:"json",
                                                        data:params,
                                                        beforeSend:function(){
                                                            index_load = layer.msg('患者数据查询中,请稍候...', {icon: 16,time:20000});
                                                        },
                                                        success:function(data){                                            layer.close(index_load);
                                                            // console.log(data); 
                                                            $(".mtnum2").hide();
                                                            $(".yuyue_record2").show();
                                                            $(".btn_area").show();
                                                            $("#confirm").css({"visibility":"hidden"});
                                                            $("#fanhui").css({"visibility":"hidden"});
                                                            $("#tuichu").css({"visibility":"visible"});
                                                            if(data['result']['execute_flag']=='0'){
                                                                $("#jz_name_yuyue").text(data['yb_input_yy_data']['name']);
                                                                $("#pat_code").val(data['yb_input_yy_data']['patient_id']); 
                                                                $("#card_no").val(data['yb_input_yy_data']['card_no']);
                                                                $("#card_code").val(data['yb_input_yy_data']['card_code']);
                                                                $("#pat_name").val(data['yb_input_yy_data']['name']);
                                                                if(data['yy_data']['result']['execute_flag']=='0'){
                                                                    var html = '';
                                                                    page = 1;
                                                                    yy_pagedata = data['yy_data']['datarow'];
                                                                   
                                                                    yy_showdata(page);
                                                                }else{
                                                                    $(".yuyue_record2 .yuyue_list").text(data['yy_data']['message']);
                                                                    $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                    $(".yuyue_record2 .yuyue_page").hide();
                                                                }
                                                               
                                                            }else{
                                                                $(".yuyue_record2 .yuyue_list").text(data['result']['execute_message']);
                                                                $(".yuyue_record2 .yuyue_list").css({"font-size":"45px","color":"red","font-family":"黑体","float":"left","margin-top":"35px","text-align":"center"});
                                                                $(".yuyue_record2 .yuyue_page").hide();
                                                            }
                                                        }
                                                    });
                                                }else{
                                                    $(".yb_op_area .tips").html("未知错误");
                                                }
                                            }, 2000);
                                        }else{
                                            $(".yb_op_area .tips").html("医保读卡器初始化失败");
                                            window.external.MoveOutCard();
                                            $(".btn_area").show();
                                            $("#op_now").val("choose_yb_card");
                                        }
                                        
                                    }, 500);
                                }
                            });
                        break;
                    }
                break;

            }
        });

        // 返回操作处理
        $("#fanhui").on("click",function(){
            var op_now=$("#op_now").val();
            // alert(op_now);
            switch(op_now){
                case "chose_cx_type":
                    var zzj_id = $("#zzj_id").val();
                    window.location.href="/hddevice/mz/index.php/ZiZhu/Index/index/id/"+zzj_id;
                break;
                case "chose_pat_type":
                    $(".mtnum2").hide();
                    $(".chose_pat_type").show();
                    $("#confirm").css({"visibility":"hidden"});
                    $("#op_now").val("chose_cx_type");
                break;
                case "chose_yb_card":
                    $(".mtnum2").hide();
                    $(".chose_card_type_area").show();
                    $("#op_now").val("chose_pat_type");
                    window.external.send(1,4);
                    window.external.MoveOutCard();
                    window.external.DisAllowCardIn();
                    window.external.FreeYBIntfDll();
                    daojishi(30);
                break;
                case "ic_get_pat_info":

                    $(".mtnum2").hide();
                    $("#jz_card_no").val("");
                    $("#jz_name").html("");
                    $(".jiuzhen_op_area .tips").text("");
                    $(".chose_card_type_area").show();
                    $("#op_now").val("chose_pat_type");
                    $("#confirm").css({"visibility":"hidden"});
                    key_value="";
                    key_value2="";
                break;
                case "ic_get_pat_info_sfz":
                    $(".mtnum2").hide();
                    $("#jz_card_no_sfz").val("");
                    $("#jz_name").html("");
                    $(".jiuzhen_op_area_sfz .tips").text("");
                    $(".chose_card_type_area").show();
                    $("#op_now").val("chose_pat_type");
                    $("#confirm").css({"visibility":"hidden"});
                    key_value="";
                    key_value2="";
                break;
                case "cx_guahao_record":
                    if($("#xz_ic").attr('name')=='ic'){
                        $(".mtnum2").hide();
                        $(".jiuzhen_op_area").show();
                        $("#jz_card_no").val("");
                        $(".jiuzhen_op_area .tips").text("");
                        $(".guahao_record .guahao_list").html("");
                        $("#op_now").val("ic_get_pat_info");
                        $("#confirm").css({"visibility":"visible"});
                        key_value="";
                        key_value2="";
                    }else{
                        $(".mtnum2").hide();
                        $(".jiuzhen_op_area_sfz").show();
                        $("#jz_card_no_sfz").val("");
                        $(".jiuzhen_op_area_sfz .tips").text("");
                        $(".guahao_record .guahao_list").html("");
                        $("#op_now").val("ic_get_pat_info_sfz");
                        $("#confirm").css({"visibility":"visible"});
                        key_value="";
                        key_value2="";
                    }
                break;
                case "cx_jiaofei_record":
                    if($("#xz_ic").attr('name')=='ic'){
                        $(".mtnum2").hide();
                        $("#jz_card_no").val("");
                        $(".jiuzhen_op_area .tips").text("");
                        $(".jiuzhen_op_area").show();
                        $(".jiaofei_record2 .jiaofei_list").html("");
                        $("#op_now").val("ic_get_pat_info");
                        $("#confirm").css({"visibility":"visible"});
                        key_value="";
                        key_value2="";
                    }else{
                        $(".mtnum2").hide();
                        $("#jz_card_no_sfz").val("");
                        $(".jiuzhen_op_area_sfz").show();
                        $(".jiuzhen_op_area_sfz .tips").text("");
                        $(".jiaofei_record2 .jiaofei_list").html("");
                        $("#op_now").val("ic_get_pat_info_sfz");
                        $("#confirm").css({"visibility":"visible"});
                        key_value="";
                        key_value2="";
                    }
                break;
                case "cx_yuyue_record":
                    if($("#xz_ic").attr('name')=='ic'){
                        $("#jz_name_yuyue").text("");//增加了隐藏姓名
                        $(".mtnum2").hide();
                        $("#jz_card_no").val("");
                        $(".jiuzhen_op_area .tips").text("");
                        $(".jiuzhen_op_area").show();
                        $(".yuyue_record2 .yuyue_list").html("");
                        $("#op_now").val("ic_get_pat_info");
                        $("#confirm").css({"visibility":"visible"});
                        key_value="";
                        key_value2="";
                    }else{
                        $("#jz_name_yuyue").text("");//增加了隐藏姓名
                        $(".mtnum2").hide();
                        $("#jz_card_no_sfz").val("");
                        $(".jiuzhen_op_area_sfz .tips").text("");
                        $(".jiuzhen_op_area_sfz").show();
                        $(".yuyue_record2 .yuyue_list").html("");
                        $("#op_now").val("ic_get_pat_info_sfz");
                        $("#confirm").css({"visibility":"visible"});
                        key_value="";
                        key_value2="";
                    }
                break;
                case "yb_cx_gh_record":
                    $(".mtnum2").hide();
                    $(".yb_op_area").show();
                    $("#op_now").val("chose_yb_card");
                    $("#confirm").css({"visibility":"hidden"});
                    $(".yb_op_area .tips").html('');
                    daojishi(60);
                break;
                case "yb_cx_jf_record":
                    $(".mtnum2").hide();
                    $(".yb_op_area").show();
                    $("#op_now").val("chose_yb_card");
                    $("#confirm").css({"visibility":"hidden"});
                    $(".yb_op_area .tips").html('');
                    daojishi(60);
                break;
                case "yb_cx_yy_record":
                    $(".mtnum2").hide();
                    $(".yb_op_area").show();
                    $("#op_now").val("chose_yb_card");
                    $("#confirm").css({"visibility":"hidden"});
                    $(".yb_op_area .tips").html('');
                    daojishi(60);
                break;
            }
        });

        /*****退出按钮点击事件开始**********/
        $("#tuichu").on("click",function(){
            $("#message").val("");
            $("#jz_card_no").val("");
            $(".btn_area").hide();
            $("#op_now").val("");
              <!--------添加了用户的姓名-------->
            $("#jz_name").html("");
            $("#jz_name_jf").html("");
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
            window.external.FreeYBIntfDll();
            window.external.MoveOutCard();
            window.external.DisAllowCardIn();
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
        });

        // 日志函数
        function writeLog(logtxt,logtype){
            var params = {"log_txt":logtxt,"log_type":logtype,"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"direction":"操作步骤","op_code":$("#op_code").val()};
            $.ajax({
                    url:"/hddevice/mz/index.php/ZiZhu/ChaXun/writeLogs", 
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
            if(card_no!="")
            {
                window.external.send(1,4);//关闭所有灯带
                window.external.Beep();
                $("#jz_card_no").val(card_no);
                $("#confirm").trigger("click");
            }
            if($("#business_type").val()=="自助挂号")
            {
                if(card_no!="")
                {
                    window.external.send(1,4);
                    window.external.Beep();
                    $("#jz_card_no").val(card_no);
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

            // 自助查询业务逻辑
            if($("#business_type").val()=='自助查询'){
                if(card_no!=''){
                    window.external.send(1,4);
                    window.external.Beep();
                    $("#jz_card_no").val(card_no);
                    $("#confirm").trigger('click');
                }
            }
        }
        //读取身份证方法
        function getCardNoS(){
            //var card_no =window.external.GetCardNo();
            var card_no2 = window.external.sfz_card_read();
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
            //自助挂号业务逻辑
            if($("#business_type").val()=="自助挂号")
            {
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

            // 自助查询业务逻辑
            if($("#business_type").val()=="自助查询"){
                if(card_no2!=""){
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

        //算年纪
        function ages(str){  
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

        // 获取当前时间
        function getCurrentDate() {
            var date = new Date();
            var seperator1 = "-";
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var strDate = date.getDate();
            if (month >= 1 && month <= 9) {
            month = "0" + month;
            }
            if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
            }
            var currentdate = year + seperator1 + month + seperator1 + strDate;
            return currentdate;
        }

        //获取指定日期的前一周时间
        function getBeforeWeek(d){
            d = new Date(d);
            d = +d - 1000*60*60*24*6;
            d = new Date(d);
            var year = d.getFullYear();
            var mon = d.getMonth()+1;
            var day = d.getDate();
            s = year+"-"+(mon<10?('0'+mon):mon)+"-"+(day<10?('0'+day):day);
            return s;
        }

        // 获取指定日期的后一周时间
        function getAfterWeek(d){
            d = new Date(d);
            d = +d + 1000*60*60*24*6;
            d = new Date(d);
            var year = d.getFullYear();
            var mon = d.getMonth()+1;
            var day = d.getDate();
            s = year+"-"+(mon<10?('0'+mon):mon)+"-"+(day<10?('0'+day):day);
            return s;
        }
        // 获取指定日期的前一个月的时间
        function getBeforeMonth(d){
            d = new Date(d);
            d = +d - 1000*60*60*24*29;
            d = new Date(d);
            var year = d.getFullYear();
            var mon = d.getMonth()+1;
            var day = d.getDate();
            s = year+"-"+(mon<10?('0'+mon):mon)+"-"+(day<10?('0'+day):day);
            return s;
        }

        // 获取指定日期的后一个月的时间
        function getAfterMonth(d){
            d = new Date(d);
            d = +d + 1000*60*60*24*29;
            d = new Date(d);
            var year = d.getFullYear();
            var mon = d.getMonth()+1;
            var day = d.getDate();
            s = year+"-"+(mon<10?('0'+mon):mon)+"-"+(day<10?('0'+day):day);
            return s;
        }

        // 缴费明细下拉隐藏操作
        $(document).on("click",".jiaofei_record2 .jiaofei_list ul li.six",function(){
            if($(this).next(".detail").is(':hidden')){
                $(".chufang_list ul li.five").removeClass("z2");
                $(this).addClass("z2");
                $(".detail").hide();
                $(this).next(".detail").show();
            }else{
                $(this).removeClass("z2");
                $(this).next(".detail").hide();
            }

        });

        // 挂号查询分页显示
        function showdata(page){
            var html='';
            var pagesize = 4;//每页显示条数
            var totalpage = Math.ceil(pagedata.length/pagesize);//总页数
            var end = (page * pagesize) - 1;//13
            var start = end - pagesize + 1;//02
            
            if(page==totalpage){
                $(".next").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");
            }else{
                $(".next").css("background","url(/hddevice/mz/Public/zizhu/img/ft.png)"); 
                $(".next").css("color","#fff");
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
            $.each(pagedata,function(key,val){
                if(key>=start&&key<=end){
                    if(val['fee_zf']==0){
                        html+="<ul><li class='one'>"+(key+1)+"</li><li class='two'>"+val['unit_name']+"</li><li class='thr'>"+val['req_name']+"</li><li class='four'>"+val['fee_zf']+"</li><li class='five'>"+val['gh_date']+"</li><li class='six'>（已退号）</li></ul>";
                    }else {
                        html += "<ul><li class='one'>" + (key + 1) + "</li><li class='two'>" + val['unit_name'] + "</li><li class='thr'>" + val['req_name'] + "</li><li class='four'>" + val['fee_zf'] + "</li><li class='five'>" + val['gh_date'] + "</li></ul>";
                    }
                }
            });

            if(html){
                $(".guahao_record .guahao_list").css({"font-size":"","color":"","font-family":"","float":"","margin-top":"","text-align":""});
                $(".guahao_list").html(html);
                $(".total_page").html("共"+totalpage+"页/第"+page+"页");
            }else{
                return false;
            }
            
        }

        //下一页
        $(".next").click(function () {
            page++;
            daojishi(180);
            if(showdata(page)===false){
                page--;
                $(".next").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");

            }
        });

        //上一页
        $(".prev").click(function () {
            daojishi(180);
            if (page == 1) {
                return false;
            }
            page--;
            showdata(page);
        });

        // 缴费分页显示
        function jf_showdata(page){
            var html='';
            var sub_html = '';
            var pagesize = 4;//每页显示条数
            var totalpage = Math.ceil(jf_pagedata.length/pagesize);//总页数
            var end = (page*pagesize)-1;
            var start = end-pagesize+1;
            if(page==totalpage){
                $(".jf_next").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");
            }else{
                $(".jf_next").css("background","url(/hddevice/mz/Public/zizhu/img/ft.png)"); 
                $(".jf_next").css("color","#fff");
            }

            if(page==1){
                $(".jf_prev").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");
            }else{
                $(".jf_prev").css("background","url(/hddevice/mz/Public/zizhu/img/ft.png)");
                $(".jf_prev").css("color","#FFF");
            }
            if(jf_pagedata.length<=4){
                $(".jf_prev").hide();
                $(".jf_next").hide();
            }else{
                $(".jf_prev").show();
                $(".jf_next").show();
            }
            // console.log(jf_pagedata);
            
            for(var i=0;i<jf_pagedata.length;i++){
                if(i>=start&&i<=end){
                    var sub_html = "";
                    var sub_item = jf_pagedata[i]['sub'];
                    for(var m=0;m<sub_item.length;m++){
                        sub_html+="<tr><td align='center'>"+(m+1)+"</td><td>"+sub_item[m]['bill_name']+"</td><td>"+sub_item[m]['charge_name']+"</td><td>"+sub_item[m]['charge_amount']+"</td><td>"+sub_item[m]['charge_price']+"</td></tr>"; 
                    }
                    var price_date = jf_pagedata[i].attr.price_date;
                    var time = price_date.substr(0,10);
                    html+="<ul><li class='one'>"+(i+1)+"</li><li class='two'>"+time+"</li><li class='thr'>"+jf_pagedata[i].attr.responce_name+"</li><li class='four'>"+jf_pagedata[i].attr.order_charge+"</li><li class='five'>"+jf_pagedata[i].attr.pay_self+"</li><li class='six z1'>展开</li><table class='detail' style='display:none;'><tr><th>序号</th><th>费用名称</th><th>项目名称</th><th>数量</th><th>单价</th></tr>"+sub_html+"</table></ul>";
                }
                
            } 
            if(html){
                $(".jiaofei_record2 .jiaofei_list").css({"font-size":"","color":"","font-family":"","float":"","margin-top":"","text-align":""});

                $(".jiaofei_list").html(html);
                $(".jf_total_page").html("共"+totalpage+"页/第"+page+"页");
            }else{
                return false;
            }

        }

        //下一页
        $(".jf_next").click(function () {
            page++;
            daojishi(180);
            if(jf_showdata(page)===false){
                page--;
                $(".jf_next").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");
            }
        });

        //上一页
        $(".jf_prev").click(function () {
            daojishi(180);
            if (page == 1) {
                return false;
            }
            page--;
            jf_showdata(page);
        });

        // 预约分页显示
        function yy_showdata(page){
            var html='';
            var pagesize = 4;//每页显示条数
            var totalpage = Math.ceil(yy_pagedata.length/pagesize);//总页数
            var end = (page*pagesize)-1;
            var start = end-pagesize+1;
            if(page==totalpage){
                $(".yy_next").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");
            }else{
                $(".yy_next").css("background","url(/hddevice/mz/Public/zizhu/img/ft.png)"); 
                $(".yy_next").css("color","#fff");
            }

            if(page==1){
                $(".yy_prev").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");
            }else{
                $(".yy_prev").css("background","url(/hddevice/mz/Public/zizhu/img/ft.png)");
                $(".yy_prev").css("color","#FFF");
            }
            if(yy_pagedata.length<=4){
                $(".yy_prev").hide();
                $(".yy_next").hide();
            }else{
                $(".yy_prev").show();
                $(".yy_next").show();
            }

           $.each(yy_pagedata,function(key,val){
                if(key>=start&&key<=end){
                    var request_date = val['request_date'];//2019-04-15T00:00:00
                    var apm = '';
                    if(val['ampm']=='a'){
                        apm = '上午';
                    }else if(val['ampm']=='p'){
                        apm = '下午';
                    }
                    // alert(apm);return;
                    var yy_time = request_date.substr(0,10)+' '+apm;
                    var register_flag='';
                    
                    $("#record_id").val(val['record_id']);
                    $("#gh_sequence").val(val['gh_sequence']);
                    $("#req_type").val(val['req_type']);
                    $("#req_name").val(val['req_name']);
                    $("#yy_time").val(yy_time);
                        
                    if(val['register_flag']=='0'){
                        register_flag = '正常(可取号)';
                    }else if(val['register_flag']=='1'){
                        register_flag = '已取号';
                    }else if(val['register_flag']=='2'){
                        register_flag = '逾期未取';
                    }else if(val['register_flag']=='5'){
                        register_flag = '停诊';
                    }else if(val['register_flag']=='9'){
                        register_flag = '已取消';
                    }
                    
                    if(val['register_flag']=='0'){
                        var am = 0;
                        var pm = 1;
                        // var abc = new Date().Format("yyyy-MM-dd");
                        var date = request_date.substr(0,10)+' 12:00:00';

                        var limit =  Date.parse(date.replace("-","/"));
                        // var limit = getTime(date);
                        
                        var date1 = new Date().Format("yyyy-MM-dd hh:mm:ss");
                        // var date1 = new Date().Format("hh:mm:ss");
                        
                        var now = Date.parse(date1.replace("-","/"));
                        // var now = getTime(date1);

                        // alert(limit);alert(now);return;
                        var orderState = am;
                        var nowState = am;
                        // 当前时间过了12点就是下午
                        if(now>limit){
                            nowState = pm;
                        }
                       
                        // 预约就诊时间含有下午关键字就是下午
                        if(yy_time.search("下午") !=-1){
                            orderState = pm;
                        }

                        // alert(nowState);alert(orderState);return;
                        // 如果就诊时间是上午并且当前是下午 则过期
                        if(orderState == am && nowState == pm){
                            register_flag = '已过期';
                        }
                        
                        if(register_flag =='已过期'){
                            html+="<ul><li class='one'>"+(key+1)+"</li><li class='two'>"+val['unit_name']+"</li><li class='thr'>"+val['charge']+"</li><li class='four'>"+yy_time+"</li><li class='five'>"+val['req_name']+"</li><li class='six'>"+register_flag+"</li></ul>";
                        }else{
                            html += "<ul><li class='one'>" + (key + 1) + "</li><li class='two'>" + val['unit_name'] + "</li><li class='thr'>" + val['charge'] + "</li><li class='four'>" + yy_time + "</li><li class='five'>" + val['req_name'] + "</li><li class='six'>" + register_flag + "</li><li class='seven' name='" + val['req_name'] + "' time='" + yy_time + "' register_sn='" + val['register_sn'] + "'>预约取消</li></ul>";
                        }
                    }else{
                        html+="<ul><li class='one'>"+(key+1)+"</li><li class='two'>"+val['unit_name']+"</li><li class='thr'>"+val['charge']+"</li><li class='four'>"+yy_time+"</li><li class='five'>"+val['req_name']+"</li><li class='six'>"+register_flag+"</li></ul>";
                    }
                }
            });

            if(html){
                $(".yuyue_record2 .yuyue_list").css({"font-size":"","color":"","font-family":"","float":"","margin-top":"","text-align":""});

                $(".yuyue_list").html(html);
                $(".yy_total_page").html("共"+totalpage+"页/第"+page+"页");
            }else{
                return false;
            }
        }

        //下一页
        $(".yy_next").click(function () {
            page++;
            daojishi(180);
            if(yy_showdata(page)===false){
                page--;
                $(".yy_next").css("background","url(/hddevice/mz/Public/zizhu/img/no.png)");
            }
        });

        //上一页
        $(".yy_prev").click(function () {
            daojishi(180);
            if (page == 1) {
                return false;
            }
            page--;
            yy_showdata(page);
        });

        // 预约取消
        $(".yuyue_list").on("click",".seven",function(){
            writeLog("选择了预约取消");
            // $("#op_now").val("yuyue_cancel");
            register_sn = $(this).attr('register_sn');
            // alert(register_sn);
            // register_sn = $("input:radio[name='times_order_no']:checked").val();
            $("#register_sn").val(register_sn);
            // if(register_sn !=null){
                var req_name = $(this).attr('name');
                var time = $(this).attr('time');
                // register_sn = $(this).attr('register_sn');
             var aaaa=$(this).parent().find('li').eq(5); //无刷界面改变样式
             var bbbb=$(this).parent().find('li').eq(6); //无刷界面改变样式
                layer.open({
                    content:'确定取消吗？',
                    btn:['确定','取消'],
                    yes:function(index,layero){
                        layer.close(index);
                        if(req_name=='诊间预约号' || req_name=='窗口预约号'){
                            var zzj_id = $("#zzj_id").val();
                            var patient_id = $("#pat_code").val();
                            var card_code = $("#card_code").val();
                            var card_no = $("#card_no").val();
                            var register_sn = $("#register_sn").val();
                            var record_id = $("#record_id").val();
                            var req_type = $("#req_type").val();
                            var gh_sequence = $("#gh_sequence").val();
                            var index_load= '';
                            var params = {"zzj_id":zzj_id,"patient_id":patient_id,"card_code":card_code,"card_no":card_no,'register_sn':register_sn,'record_id':record_id,'gh_sequence':gh_sequence,'req_type':req_type,'op_code':$("#op_code").val()};
                            // console.log(params);return;
                            $.ajax({
                                url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YyRecordCancel",
                                type:"post",
                                data:params,
                                dataType:"json",
                                beforeSend:function(){
                                        index_load = layer.msg('预约取消中,请稍候...', {icon: 16,time:20000});
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"hidden"});
                                },
                                success:function(data){
                                    layer.close(index_load);
                                    if(data['execute_flag']=='0'){
                                        // $(".yuyue_list").html("<h4>取消成功</h4>");
                                        aaaa.html("<li class='six'>已取消</li>");
                                        bbbb.remove();
                                        $(".btn_area").show();
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#fanhui").css({"visibility":"visible"});
                                        $("#tuichu").css({"visibility":"visible"});    

                                    }else{
                                        $(".yy_total_page").html("");
                                        $(".yuyue_list").html("<h4>"+data['execute_message']+"</h4>");
                                        $(".btn_area").show();
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#fanhui").css({"visibility":"visible"});
                                        $("#tuichu").css({"visibility":"visible"});    
                                    }
                                }
                            })
                        }else if(req_name=='114预约' && new Date(time).toDateString()=== new Date().toDateString()){
                            var zzj_id = $("#zzj_id").val();
                            var patient_id = $("#pat_code").val();
                            var card_code = $("#card_code").val();
                            var card_no = $("#card_no").val();
                            var register_sn = $("#register_sn").val();
                            var record_id = $("#record_id").val();
                            var req_type = $("#req_type").val();
                            var gh_sequence = $("#gh_sequence").val();
                            var index_load = '';
                            var params = {"zzj_id":zzj_id,"patient_id":patient_id,"card_code":card_code,"card_no":card_no,'register_sn':register_sn,'record_id':record_id,'gh_sequence':gh_sequence,'req_type':req_type,'op_code':$("#op_code").val()};
                            $.ajax({
                                url:"/hddevice/mz/index.php/ZiZhu/ChaXun/YyRecordCancel",
                                type:"post",
                                data:params,
                                dataType:"json",
                                beforeSend:function(){
                                        index_load = layer.msg('预约取消中,请稍候...', {icon: 16,time:20000});
                                        $("#fanhui").css({"visibility":"hidden"});
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#tuichu").css({"visibility":"hidden"});
                                },
                                success:function(data){
                                    layer.close(index_load);
                                    if(data['execute_flag']=='0'){
                                        // $(".yuyue_list").html("<h4>取消成功</h4>");
                                        aaaa.html("<li class='six'>已取消</li>");
                                        bbbb.remove();
                                        $(".btn_area").show();
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#fanhui").css({"visibility":"visible"});
                                        $("#tuichu").css({"visibility":"visible"});    
                                    }else{
                                        $(".yy_total_page").html("");
                                        $(".yuyue_list").html("<h4>"+data['execute_message']+"</h4>");
                                        $(".btn_area").show();
                                        $("#confirm").css({"visibility":"hidden"});
                                        $("#fanhui").css({"visibility":"visible"});
                                        $("#tuichu").css({"visibility":"visible"});    
                                    }
                                }
                            })
                        }else{
                            $(".yuyue_list").html("<h4>请去114取消</h4>");
                            $(".btn_area").show();
                            $("#confirm").css({"visibility":"hidden"});
                            $("#fanhui").css({"visibility":"visible"});
                            $("#tuichu").css({"visibility":"visible"}); 
                        }
                    },
                    cancel:function(index,layero){
                        layer.close(index);
                        return false;
                    }
                })
            // }else{
            //     layer.alert('请先选中要取消的订单',{
            //         icon:6,
            //         skin:'layer-ext-moon',
            //         time:10000,
            //     });
            // }
		})
    });
</script>

<title>查询</title>
<body>
<!--添加了病人隐藏域-->
    <div style="display:block;" id="cover"></div>
    <input type="hidden" class="inhide" id="hz_jz_card_no" value="">
    <input type="hidden" class="inhide" id="hz_jz_card_no_sfz" value="">
    <!-- 预约编号 -->
    <input type="hidden" class="inhide" id="register_sn" value="">
    <!-- 预约密码 -->
    <input type="hidden" class="inhide" id="record_id" value="">
    <!-- 顺序号 -->
    <input type="hidden" class="inhide" id="gh_sequence" value="">
    <!-- 预约类型 -->
    <input type="hidden" class="inhide" id="req_type" value="">
    <!-- 预约来源 -->
    <input type="hidden" class="inhide" id="req_name" value="">
    <input type="hidden" class="inhide" id="yy_time" value="">

    <!-- 查询时间 -->
    <input type="hidden" class="inhide" id="gh_flag_time" value="">
    <input type="hidden" class="inhide" id="jf_flag_time" value="">
    <input type="hidden" class='inhide' id='yy_flag_time' value="">
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
    <input type="hidden" class="inhide" id="cx_type" value=""/>

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
    <div class="main_body">
        <div id="downcount"></div>
        <div id="show_times"></div>
        <div class="main_yuyue">
            <!--预约挂号选择卡类型-->
            <div class="chose_card_type_area mtnum2 " style="display:none;">
                <h3>请您选择卡类型</h3>
                <ul>
                    <li class="ic" id="xz_ic">就诊卡</li>
                    <li class="sfz" id="xz_sfz">身份证</li>
                    <li class="yibao" id="xz_yibao">医保卡</li>
                </ul>
            </div>
            <!--预约挂号选择业务类型-->
            <div class="chose_pat_type mtnum2" >
                <h3>请您选择功能类型</h3>
                <ul>
                    <li class="cx_guahao" id="cx_guahao">挂号查询</li>
                    <li class="cx_jiaofei" id="cx_jiaofei">缴费查询</li>
                    <li class="cx_yuyue" id="cx_yuyue">预约查询</li>
                </ul>
            </div>

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

            <!-- 医保卡操作界面 -->
            <div class="yb_op_area mtnum2" style="display:none;">
                <h3>请插入您的医保卡</h3>
                <div class="tips"></div>
            </div>
            
             <!--------查询挂号记录-------->
            <div class="guahao_record mtnum2" style="display:none;">
              <!--------添加了患者的姓名-------->
                <span style="color:#009FD6;font-size:35px;position:absolute;left:135px;" id="jz_name" ></span>
                <h3 style="font-size:35px">挂号记录查询</h3>
                <span class="btn">
                    <button class="layui-btn layui-btn-normal gh_week_time button_gh" name='1'>一周以内</button>
                    <button class="layui-btn layui-btn-normal gh_month_time button_gh" name='2'>一月以内</button>
                </span>
                <div class="bar_tit">
                    <ul>
                        <li class="one">序号</li>
                        <li class="two">就诊科室</li>
                        <li class="thr">号类</li>
                        <li class="four">金额</li>
                        <li class='five'>就诊日期</li>
                        
                    </ul>
                </div>
                <div class="guahao_list">
                    
                </div>
                <div class="tips"></div>
                <div class="page">
                    <span class="total_page"></span>
                    <span class="prev">上一页</span>
                    <span class="next">下一页</span>
                </div>
            </div>

            <!-- 查询缴费记录 -->
            <div class="jiaofei_record2 mtnum2" style="display:none;">
              <!--------添加了患者的姓名-------->
                <span style="color:#009FD6;font-size:35px;position:absolute;left:135px;" id="jz_name_jf" ></span>
                <h3 style="font-size:35px">缴费查询记录</h3>
                <span class="btn">
                    <button class='layui-btn layui-btn-normal jf_week_time button_jf' name='1'>一周以内</button>
                    <button class='layui-btn layui-btn-normal jf_month_time button_jf' name='2'>一月以内</button>
                </span>
                <div class="bar_tit">
                    <ul>
                        <li class="one">序号</li>
                        <li class="two">时间</li>
                        <li class="thr">缴费类型</li>
                        <li class="four">金额</li>
                        <li class="five">自付金额</li>
                       
                    </ul>
                </div>
                <div class="jiaofei_list">
                    
                </div>
                <div class="tips"></div>
                <div class='jf_page'>
                    <span class="jf_total_page"></span>
                    <span class="jf_prev">上一页</span>
                    <span class="jf_next">下一页</span>
                </div>
            </div>
            
            <!-- 预约挂号查询 -->
            <div class="yuyue_record2 mtnum2" style="display:none;">
              <!--------添加了患者的姓名-------->
                <span style="color:#009FD6;font-size:35px;position:absolute;left:135px;" id="jz_name_yuyue" ></span>
                <h3 style="font-size:35px">预约查询记录</h3>
                <span class="btn">
                    <button class="layui-btn layui-btn-normal yy_week_time button_yy" name='1'>一周以内</button>
                    <button class="layui-btn layui-btn-normal yy_month_time button_yy" name='2'>一月以内</button>
                </span>
                <div class="bar_tit">
                    <ul>
                        <li class="one">序号</li>
                        <li class="two">就诊科室</li>
                        <li class="thr">金额</li>
                        <li class="four">就诊日期</li>
                        <li class='five'>预约来源</li>
                        <li class="six">预约状态</li>
                    </ul>
                </div>
                <div class="yuyue_list">
                    
                </div>
                <div class="tips"></div>
                <div class="yuyue_page">
                    <span class="yy_total_page"></span>
                    <span class="yy_prev">上一页</span>
                    <span class="yy_next">下一页</span>
                </div>
            </div>
        </div>

        <!-- 按钮区域 -->
        <div class="btn_area" style="display:none">
            <ul>
                <li id="confirm">确定</li>
                <li id="fanhui">返回</li>
                <li id="tuichu">退出</li>
            </ul>
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
        function UMS_GetOnePass(){
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
        function UMS_GetOnePassGh(){
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
        function PinProcess(){
            document.getElementById("PinFieldGh").value= "";
            document.getElementById("PinFieldGh").style.display = "block";
            PinCounter=0;
            time=setInterval(UMS_GetOnePass,100);        
        }
        function PinProcessGh(){
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
            /*$("#cover").click(function(){
                $("send-msg").hide();
                $(this).hide();
            })*/
        });

    </script>
</body>
</head>
</html>