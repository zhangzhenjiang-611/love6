<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=10" />
<link rel="stylesheet" type="text/css" href="/rw2.0_payment/Public/jinriguahao/css/base.css" />
<link rel="stylesheet" type="text/css" href="/rw2.0_payment/Public/jinriguahao/css/index.css" />
<script language="javascript" src="/rw2.0_payment/Public/jinriguahao/js/jquery-1.9.1.js" ></script>
<script language="javascript" src="/rw2.0_payment/Public/jinriguahao/js/ServerClock.js" ></script>
<script language="javascript" type="text/javascript" src="/rw2.0_payment/Public/layer/layer.js"></script>
<script language="javascript" type="text/javascript" src="/rw2.0_payment/Public/laydate/laydate.js"></script>
<script language="javascript" type="text/javascript" src="/rw2.0_payment/Public/jinriguahao/js/my.js"></script>
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

</SCRIPT>
<script language="javascript">
    window.onload = function (){
        //导航栏文字变色和粗
        //换一张被选择的图片引入
        $(".choseCard").css({'color':'#f57336','font-weight':'bold'});
        $(".choseCardDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
        stime();
        daojishi(60);
        $(".btn_area").show();
        $("#confirm").css({"visibility":"hidden"});
        $("#fanhui").css({"visibility":"hidden"});
        $("#tuichu").css({"visibility":"visible"});
        $("#business_type").val("自助挂号");
        $("#op_code").val(new Date().Format("yyyyMMddhhmmssS")/*GetRandomNum(10000,99999)*/);
        $("#op_now").val("guahao_chose_card_type");
       // var re = window.external.getUserId();
        $("#zzj_id").val(re);
       // writeLog("自助挂号");
        document.onselectstart = function(){
            return false;
        }
    }
    var c = 0;
    <?php
 $weekary = array("日","一","二","三","四","五","六"); $W = $weekary[date("w")]; ?>
    var Y =<?php echo date('Y')?>, M =<?php echo date('n')?>, D =<?php echo date('j')?>;
    function stime(){
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
            url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/getdangqian_timme",
            type:'post',
            dataType:'json',
            success:function(data){
                // $("#show_times").html(data['rq']+"<span style='margin-right:34px;'>"+data['xq']+"</span>")
                $("#show_times").html(Y + '年&nbsp;&nbsp;' + M + '月' + D + '日 ' +"<span style='margin-left:75px;color:#fff;'>星期<?=$W?></span>")
            }
        })
        /********************改为一秒执行一次***********************/
        setTimeout("stime()", 30000);
    }
</script>
<script  language="javascript">
    function setCalls(pat_code,noon_flag){
        var params = {"pat_code":pat_code};
        $.ajax({
            url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/setCalls",
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
var page = 1;//分页显示第一页
var page_ks = 1;//分页显示第一页
var page_sj = 1;//分页显示第一页
var pagedata = null;//排版每页显示记录数
var pagedata_ks = null;//科室每页显示记录数
var ocx;
var cardNum;
var now_time = null;
var moring_time = null;
var moon_time = null;
var ghnum = 1;
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
    // for(var key in interval2){
    //     clearTimeout(interval2[key]);
    // }
    // countdown = etime;
    // settime(etime);
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

//获取打印纸的状态
function print_status(zzj_id){
    var params = {"zzj_id":zzj_id};
    $.ajax({
        url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/print_status",
        type:'post',
        dataType:'json',
        data:params,
        success:function(data){
        }
    })
}


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
    // alert( $("#patient_id").val()+"患者id");
    // alert( $("#patient_name").val()+"患者姓名");
    // alert( $("#stream_no").val()+"订单号");
    // alert( $("#card_code").val()+"交易类型");
    // alert($("#pay_type").val()+"支付方式");
    // alert( $("#business_type").val()+"交易内容");
    // alert( original_cost+"原价");
    // alert(current_price+"现价");
    // alert($("#zzj_id").val()+"自助机id");

    var params = {"patient_id":$("#patient_id").val(),"user_name":$("#patient_name").val(),"out_trade_no":$("#stream_no").val(),"transaction_type":$("#card_code").val(),"transaction_mode":$("#pay_type").val(),"transaction_conente":$("#business_type").val(),"original_cost":original_cost,"current_price":current_price,'zzj_id':$("#zzj_id").val()};
    $.ajax({
        url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/transaction_logs",
        type:'post',
        dataType:'json',
        data:params,
        success:function(data){}
    })


}

/*******金属密码键盘明文调用结束***********************/
$(function(){
print_status($("#zzj_id").val());
/******************************数字键盘区开始**********************************************/
var key_value = "";
$(".j_keybord_area .key li.num").on("mousedown",function(){
    //$(".j_keybord_area .key li").removeClass("active");
    // $(this).addClass("active");
    // key_value+=$(this).attr("param");
    // $("#jz_card_no").val(key_value);
    var jz_card_no=$("#jz_card_no").val();
    var key_value="";
    if(jz_card_no.length>0){
        var key_value=jz_card_no.substr(0,jz_card_no.length+1);
    }
    //alert(key_value);
    $(this).addClass("active");
    key_value+=$(this).attr("param");
    //alert(key_value);
    $("#jz_card_no").val(key_value);
});

$(".j_keybord_area .key li.del").on("click",function(){
    var jz_card_no=$("#jz_card_no").val();
     var a="";
     if(jz_card_no.length>0){
     var a=jz_card_no.substr(0,jz_card_no.length-1);
    }
    $("#jz_card_no").val(a);
    key_value="";
    key_value2="";
});

$(".j_keybord_area .key li").on("mouseup",function(){
    $(".j_keybord_area .key li").removeClass("active");
    //$(this).addClass("active");
})
/*****************************数字键盘区结束**************************************/
/*****************************建卡输入数字键盘区开始*****************************/
var key_value = "";
$(".jianka_phone_area .key li.num").on("mousedown",function(){
    //$(".j_keybord_area .key li").removeClass("active");
    $(".jianka_phone_area .tips_jianka").text("");
    $(this).addClass("active");
    var key_value=$("#jianka_phone").val();
    key_value+=$(this).attr("param");
    $("#jianka_phone").val(key_value);
    $("#jk_phone").val(key_value);

})
$(".jianka_phone_area .key li.del").on("click",function(){
    $(".jianka_phone_area .tips_jianka").text("");
    var jianka_phone=$("#jianka_phone").val();
    var a="";
    if(jianka_phone.length>0){
        var a=jianka_phone.substr(0,jianka_phone.length-1);
    }
    $("#jianka_phone").val(a);
    $("#jk_phone").val(a);
    key_value="";
    key_value2="";
})
$(".jianka_phone_area .key li").on("mouseup",function(){
    $(".jianka_phone_area .key li").removeClass("active");
    //$(this).addClass("active");
})
/******************建卡输入数字键盘区结束*************************/
/******************身份证数字键盘开始******************************/
$(".j_keybord_area_sfz .key li.num").on("mousedown",function(){
    //$(".j_keybord_area .key li").removeClass("active");
    /*$(this).addClass("active");
    key_value+=$(this).attr("param");
    $("#jz_card_no_sfz").val(key_value);*/
    //$(".j_keybord_area .key li").removeClass("active");
    var jz_card_no_sfz=$("#jz_card_no_sfz").val();
    var key_value="";
    if(jz_card_no_sfz.length>0){
        var key_value=jz_card_no_sfz.substr(0,jz_card_no_sfz.length+1);
    }
    //alert(key_value);
    $(this).addClass("active");
    key_value+=$(this).attr("param");
    //alert(key_value);
    $("#jz_card_no_sfz").val(key_value);

})
$(".j_keybord_area_sfz .key li.del").on("click",function(){
    var jz_card_no_sfz=$("#jz_card_no_sfz").val();
     var a="";
     if(jz_card_no_sfz.length>0){
     var a=jz_card_no_sfz.substr(0,jz_card_no_sfz.length-1);
    }
    $("#jz_card_no_sfz").val(a);
})
$(".j_keybord_area_sfz .key li").on("mouseup",function(){
    $(".j_keybord_area_sfz .key li").removeClass("active");
    //$(this).addClass("active");
})
/*******住院输入充值金额数字键盘开始*************************************/
var key_value1 = "";
$(".j_keybord_area_chongzhi_shuru .key li.num").on("mousedown",function(){

    //$(".j_keybord_area .key li").removeClass("active");
    $(this).addClass("active");
    key_value1+=$(this).attr("param");
    $("#chongzhi_money").val(key_value1);

})
$(".j_keybord_area_chongzhi_shuru .key li.del").on("click",function(){
    var chongzhi_money=$("#chongzhi_money").val();
    var a="";
    if(chongzhi_money.length>0){
        var a=chongzhi_money.substr(0,chongzhi_money.length-1);
    }
    $("#chongzhi_money").val(a);

})
$(".j_keybord_area_chongzhi_shuru .key li").on("mouseup",function(){
    $(".j_keybord_area_chongzhi_shuru .key li").removeClass("active");
    //$(this).addClass("active");
})

$("#tuika").click(function(){
    window.external.MoveOutCard();
    //禁止进卡
    window.external.DisAllowCardIn();
})

$('#jz_card_no').keydown(function(e){
    if(e.keyCode==13){
        $("#confirm").trigger("click");
    }
});

$('#jz_card_no_sfz').keydown(function(e){
    if(e.keyCode==13){
      $("#confirm").trigger("click");
    }
});


/************选择了就诊卡***************/
$("#xz_ic").on("click",function(){
    $("#card_code").val("3");
    //writeLog("选择了就诊卡");
    $(".chose_card_type_area").hide();
    $(".jiuzhen_op_area").show();
   /* $(".pay_error").show();
    return;
*/
    $(".jiuzhen_op_area .tips").text("");
    $("#op_now").val("ic_get_pat_info");
    $(".btn_area").show();
    $("#fanhui").css({"visibility":"visible"});
    $("#confirm").css({"visibility":"hidden"});
    $("#tuichu").css({"visibility":"visible"});
    //writeLog("定时判断患者是否放就诊卡");
   // window.external.send(5,2);   //就诊卡
    interval = setInterval(getIcCardNo,"1000");
    interval3.push(interval);
    daojishi(60);
});

/****************选择了身份证*******************/
$("#xz_sfz").on("click",function(){
    $("#card_code").val("2");
    // writeLog("选择了身份证");
    $(".chose_card_type_area").hide();
    $(".jiuzhen_op_area_sfz").show();
    $(".jiuzhen_op_area_sfz .tips").text("");
    $("#jz_card_no_sfz").focus();
    setInterval(function(){
        $("#jz_card_no_sfz").focus();
    },"1000");
    $("#op_now").val("sfz_put_sfz");
    $(".btn_area").show();
    $("#confirm").css({"visibility":"visible"});
    $("#fanhui").css({"visibility":"visible"});
    // writeLog("定时判断患者是否放身份证");
    // window.external.send(4,2);   //身份证
    //interval = setInterval(getCardNoS,"5000");
    //interval3.push(interval);
    //daojishi(60);
});

/****************选择了医保卡*******************/
$("#xz_yibao").on("click",function(){
    $("#card_code").val("1");
    // writeLog("选择了医保卡");
    $(".mtnum2").hide();
    $(".yb_op_area").show();
    $(".yb_op_area .tips").text("");
    $("#op_now").val("chose_yb_card");
    $(".btn_area").show();
    $("#confirm").css({"visibility":"hidden"});
    $("#fanhui").css({"visibility":"visible"});
    // window.external.send(2,2);   //开启社保卡灯带
    // window.external.AllowCardIn();//允许社保进卡
   interval = setInterval(getYibaoInfo,"1000");
    interval3.push(interval);
    daojishi(60);
});



//退出程序
$("#close").on("click",function(){
    window.external.closeWindow();
})



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
//选择银行卡支付
$("#pay_bank").on("click",function(){
    $("#op_now").val("xz_yhkpay");
    window.external.send(3,2); //开启银行卡灯带
    $("#pay_type").val("yhk");
    writeLog("选择了银行卡");
    var total_amount = cash($("#cash").val());

    $("#total_amount").val(total_amount);
    bank_info  = window.external.CreditTrans("0000"+$("#zzj_id").val()+"0000"+$("#zzj_id").val()+"C"+total_amount+"");
    writeLog("银行卡交易串："+bank_info); //日志
    var out_trade_no = $("#zzj_id").val()+'gh'+new Date().Format("yyyyMMddhhmmssS");  //银行交易单号
    $("#stream_no").val(out_trade_no);
    var  RespCode = bank_info.slice(0,2); //交易成功码
    if(RespCode == "00"){
        $(".mtnum2").hide();
        $(".pay_ma_show").hide();
        $(".pay_success").show();
        for(var key in interval2){
            clearTimeout(interval2[key]);
        }
        $(".pay_success h3").show().html("费用确认中,请稍候...");
        var  patient_name  = $("#patient_name").val();
        var  total_amount  = $("#cash").val();
        var  patient_id  = $("#patient_id").val();
        if( patient_name !=undefined || total_amount != ""  || patient_id != "" ){
            var params = {"bank_info":bank_info,"total_amount":$("#cash").val(),"subject":"患者"+$("#patient_name").val()+"自助挂号","out_trade_no":out_trade_no,"patient_id":$("#patient_id").val()};
            var index_load = "";
            $.ajax({
                url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/yhk_pay",
                type:'post',
                dataType:'json',
                data:params,
                success:function(data){
                    for(var key in interval2){
                        clearTimeout(interval2[key]);
                    }
                    window.external.send(1,4); //关闭灯带
                    if(data["RespCode"]=="00"){
                        // 买家银行卡账号写入隐藏域
                        // alert(data['idCard']+"卡号");
                        // alert(data['ck_no']+"参考号");
                        // 买家银行卡交易号写入隐藏域
                        $("#idCard").val(data['idCard']);
                        // 买家银行卡凭证号写入隐藏域
                        $("#Auth").val(data['pz_no']);
                        // 买家银行卡授权号写入隐藏域
                        if( data['sc_no'] != undefined){
                            $("#Memo").val(data['sc_no']);
                        }else{
                            $("#Memo").val("无");
                        }
                        // 买家银行卡交易日期时间写入隐藏域
                        $("#TransDate").val(data['jy_no']);
                        //银行卡交易状态写入隐藏域
                        $("#trade_no").val(data["ck_no"]);

                        /****开始调用缴费确认接口***/
                        $("#PayStatus").val("银行卡支付成功");
                        writeLog("银行卡支付成功");
                        $("#fanhui").css({"visibility":"hidden"});
                        $("#tuichu").css({"visibility":"hidden"});
                        $("#confirm").css({"visibility":"hidden"});
                        paySuccessSendToHis($("#card_code").val());
                    }
                }
            })
        }else{
            var params = {"bank_info":bank_info,"total_amount":$("#cash").val(),"subject":"患者"+$("#patient_name").val()+"自助挂号","out_trade_no":out_trade_no,"patient_id":$("#patient_id").val()};
            var index_load = "";
            $.ajax({
                url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/yhk_pay",
                type:'post',
                dataType:'json',
                data:params,
                success:function(data){
                    for(var key in interval2){
                        clearTimeout(interval2[key]);
                    }
                    window.external.send(1,4); //关闭灯带
                    if(data["RespCode"]=="00"){
                        // 买家银行卡账号写入隐藏域
                        // alert(data['idCard']+"卡号");
                        // alert(data['ck_no']+"参考号");
                        // 买家银行卡交易号写入隐藏域
                        $("#idCard").val(data['idCard']);
                        // 买家银行卡凭证号写入隐藏域
                        $("#Auth").val(data['pz_no']);
                        // 买家银行卡授权号写入隐藏域
                        if( data['sc_no'] != undefined){
                            $("#Memo").val(data['sc_no']);
                        }else{
                            $("#Memo").val("无");
                        }
                        // 买家银行卡交易日期时间写入隐藏域
                        $("#TransDate").val(data['jy_no']);
                        //银行卡交易状态写入隐藏域
                        $("#trade_no").val(data["ck_no"]);
                        /****开始调用缴费确认接口***/
                        $("#PayStatus").val("银行卡支付成功");
                        writeLog("银行卡支付成功");
                    }
                }
            });
        }
    }else{
        writeLog("银行卡支付失败");
        // $("#op_now").val("chose_pay_type");
         $("#tuichu").trigger("click");
        window.external.send(1,4); //关闭灯带
   }
})

//选择支付宝支付
$("#pay_zhifubao").on("click",function(){
    // window.external.send(1,4); //关闭灯带
    // $("#op_now").val("xz_alipay");
    $("#pay_type").val("alipay");
    // writeLog("选择了支付宝");
    var out_trade_no = $("#zzj_id").val()+'gh'+new Date().Format("yyyyMMddhhmmssS");
    $("#stream_no").val(out_trade_no);
    var total_amount = $("#cash").val();

    var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#patient_name").val()+"自助挂号","pay_type":$("#pay_type").val()};
    var index_load = "";
    // $.ajax({
    //     url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/ajaxGetPayUrl",
    //     type:'post',
    //     dataType:'json',
    //     data:params,
    //     beforeSend:function(){
    //         index_load = layer.msg('支付二维码请求中,请稍候...', {icon: 16,time:20000});
    //     },
    //     success:function(data){
            layer.close(index_load);
            daojishi(300);
            $(".mtnum2").hide();
            $(".pay_ma_show").show();
            $("#fanhui").css({"visibility":"hidden"});
            $("#confirm").css({"visibility":"hidden"});
            // $("#tuichu").css({"visibility":"hidden"});
            $("#tuichu").css({"visibility":"visible"});
            $("#stream_no").val(out_trade_no);
            $(".pay_ma_show h3").text("支付宝扫一扫付款");
            $(".pay_ma_show .pay_val").text("￥"+total_amount);
            $(".pay_ma_show .erweima").html("<img src='/rw2.0_payment/Public/jinriguahao/img/zuixin/code/alipaycode.png' width='240px' /><p class='tips'>请在五分钟内付款</p>");
            s1 = setInterval(function(){
                getResult(out_trade_no);
            },2000);
            interval2.push(s1);
    //     }
    // })
})

//选择微信支付
$("#pay_weixin").on("click",function(){
   // window.external.send(1,4); //关闭灯带
    // $("#op_now").val("xz_weixin");
    $("#pay_type").val("wxpay");
    writeLog("选择了微信");
    var out_trade_no = $("#zzj_id").val()+"gh"+new Date().Format("yyyyMMddhhmmssS");
    $("#stream_no").val(out_trade_no);
    var total_amount = $("#cash").val();

    var params = {"out_trade_no":out_trade_no,"total_amount":total_amount,"subject":"患者"+$("#patient_name").val()+"自助挂号","pay_type":$("#pay_type").val()};
    var index_load = "";
    $.ajax({
        url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/ajaxGetPayUrl",
        type:'post',
        dataType:'json',
        data:params,
        beforeSend:function(){
            index_load = layer.msg('支付二维码请求中,请稍候...', {icon: 16,time:20000});
        },
        success:function(data){
            layer.close(index_load);
            $(".mtnum2").hide();
            $(".pay_ma_show").show();
            $("#fanhui").css({"visibility":"hidden"});
            $("#confirm").css({"visibility":"hidden"});
            $("#tuichu").css({"visibility":"hidden"});
            // $("#tuichu").css({"visibility":"visible"});
            daojishi(300);
            // $("#stream_no").val(out_trade_no);
             $(".pay_ma_show h3").text("微信扫一扫付款");
            $(".pay_ma_show .pay_val").text("￥"+total_amount);
            $(".pay_ma_show .erweima").html("<img src='/rw2.0_payment/Public/jinriguahao/img/zuixin/code/wxcode.png' width='240px' /><p class='tips'>请在五分钟内付款</p>");
            s1 = setInterval(function(){
                getResult(out_trade_no,s1);
            },2000);
            interval2.push(s1);
        }
    })
})
/***轮询调用查询接口是否支付成功**/
function getResult(out_trade_no,s1){
    var pay_type = $("#pay_type").val();
    var params = {"out_trade_no":out_trade_no,"pay_type":pay_type};
    $.ajax({
        url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/ajaxGetPayStatus",
        type:'post',
        dataType:'json',
        data:params,
        success:function(data){
            switch(pay_type){
                case "alipay":
                    // if(data.message.PayStatus=="WAIT_BUYER_PAY")
                    // {
                        // $("#erweima_show .tips2").hide();
                        $("#tuichu").css({"visibility":"hidden"});
                        $(".pay_ma_show .tips").show().html("<p>等待患者付款中，请在五分钟内完成付款....</p>");
                    //}
                    //data.message.PayStatus=="TRADE_SUCCESS" && data.message.OutTradeNo == $("#stream_no").val()
                    if(true){
                        // 买家支付宝账号写入隐藏域
                        //$("#idCard").val(data['message']['BuyerLogonId']);
                        // 买家支付宝交易号写入隐藏域
                       // $("#trade_no").val(data.message.TradeNo);
                        //支付宝交易状态写入隐藏域
                        $("#PayStatus").val("支付宝支付成功");
                        /****开始调用缴费确认接口***/
                        $(".pay_ma_show").hide();
                        $(".pay_success").show();
                        $(".pay_success h3").show().html("费用确认中,请稍候...");
                        for(var key in interval2){
                            clearTimeout(interval2[key]);
                        }
                        writeLog("支付宝支付成功");
                        $("#fanhui").css({"visibility":"hidden"});
                        $("#tuichu").css({"visibility":"hidden"});
                        $("#confirm").css({"visibility":"hidden"});
                        //通知HIS保存
                        paySuccessSendToHis($("#card_code").val());
                    }
                break;
                case "wxpay":
                //data.message.return_code=="SUCCESS" && data.message.result_code=="SUCCESS" && data.message.out_trade_no == $("#stream_no").val()
                    if(true){
                        var abc = 'SUCCESS';//data.message.PayStatus
                        switch(abc){
                            case "NOTPAY":
                                $("#erweima_show .tips2").hide();
                                // $("#erweima_show .tips").show().html("<p>等待用户付款中，请在三分钟内完成付款....</p>");
                            break;
                            case "SUCCESS":
                                // $("#erweima_show .pic").hide();
                                $("#erweima_show .tips").html("").hide();
                                $("#erweima_show .tips2").hide();
                                $("#erweima_show .jine").hide();
                                // 买家微信账号openid(用户在商户appid下的唯一标识)写入隐藏域
                               // $("#idCard").val(data['message']['openid']);
                                // 买家微信订单号写入隐藏域
                              //  $("#trade_no").val(data.message.transaction_id);
                                //微信交易状态写入隐藏域
                                $("#PayStatus").val("微信支付成功");
                                /****开始调用缴费确认接口***/
                                $(".pay_ma_show").hide();
                                $(".pay_success").show();
                                $(".pay_success h3").show().html("费用确认中,请稍候...");
                                for(var key in interval2){
                                    clearTimeout(interval2[key]);
                                }
                                // writeLog("微信支付成功");
                                $("#fanhui").css({"visibility":"hidden"});
                                $("#tuichu").css({"visibility":"hidden"});
                                $("#confirm").css({"visibility":"hidden"});
                                //通知HIS保存
                                paySuccessSendToHis($("#card_code").val());
                            break;
                        }
                    }
                break;
            }
        }
    })
}
/***支付成功回写HIS库***/
function paySuccessSendToHis(card_code){
    if($("#business_type").val()=="自助挂号" ){
        $("#tuichu").css({"visibility":"hidden"});
        if(ghnum == "1"){
            ghnum== ghnum+1;
                   switch(card_code){
                    case '3'://就诊卡
                        var index_load="";
                        var params = {"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#patient_id").val(),"meddate":$("#meddate").val(),"hao_type":$("#hao_type").val(),"orderid":$("#orderid").val(),"apm":$("#apm").val(),"department_id":$("#ksdm").val(),"doc_no":$("#ysdm").val(),"pay_type":$("#pay_type").val(),"medpay":$("#medflag").val(),"rctp_no":$("#rctpno").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"stream_no":$("#stream_no").val(),"trade_no":$("#trade_no").val(),"bankno":$("#idCard").val()};

                        $.ajax({
                            url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/ic_guahao_save",
                            type:'post',
                            dataType:'json',
                            data:params,
                            success:function(data){
                                var tk_status="";//退款状态
                                var mx="";//明细
                                var pt="";
                                var date  = new Date().Format("yyyy-MM-dd hh:mm:ss");

                                if(data['head']['succflag']=="1"){
                                    // writeLog("HIS挂号保存成功");
                                    // window.external.send(1,2);   //开启凭条灯带
                                    $(".pay_success h3").html("支付成功"+"<br>请收好就诊凭证,就诊卡。");
                                    setInterval(function(){
                                       // window.location.reload();
                                       window.location.href="/rw2.0_payment/index.php/ZiZhu/Index/index";
                                    },3000);
                                    return;

                                    //开始往数据库添加数据
                                    Transaction($("#cash").val(),$("#cash").val());
                                    var sex = $("#patient_sex").val();//患者性别;
                                    if(sex == "1"){
                                        sex = '男';
                                    }else{
                                        sex = '女';
                                    }
                                    var age = data['body']['age'];//患者年龄
                                    var ID = data['body']['medid'];//患者ID  门诊号
                                    var riqi =  data['body']['meddate'];//HIS返回挂号日期时间
                                    var medtime =  data['body']['medtime'];//建议就诊时间
                                    if ($("#apm").val() == '下午') {
                                        var regid =  "100"+data['body']['regid']; //获取序号
                                    }else{
                                        var regid =  data['body']['regid']; //获取序号
                                    }
                                    var docname =  data['body']['docname']; //医生姓名
                                    var docrank =  data['body']['docrank']; //职称
                                    var departmentname =  data['body']['departmentname']; //科室
                                    var ysfwf  =  data['body']['regamt']; //挂号金额 医事服务费
                                    var regaddr  =  data['body']['regaddr']; //位置
                                    var s1="";
                                    var pos = 0;
                                    s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                    s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                                    s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 55) + "' >卡类型:就诊卡</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 15) + "' >---------------------------------------</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 15) + "' >门诊号:"+ID+"</tr>";
                                   /* s1 += "<tr font='黑体' type='text'  size='13' x='150' y='" + (pos += 0) + "' >"+sf+":"+MZID+"</tr>";*/
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >时间:"+$("#apm").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >序号:"+regid+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='150' y='" + (pos += 0) + "' >年龄:"+age+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医生："+docname+"</tr>";
                                     s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >职称："+docrank+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >就诊科室："+departmentname+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 25) + "' >就诊位置："+regaddr+"</tr>";
                                    // s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 25) + "' >建议就诊时间："+medtime+"</tr>";

                                    s1 += "<tr font='黑体' type='qrCode' width='100' height='100'  x='50' y='" + (pos += 30) + "' >"+data['pos_url']+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='170' y='" + (pos += 20) + "' >请使用微信</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='170' y='" + (pos += 20) + "' >扫描二维码</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='170' y='" + (pos += 20) + "' >获取导航信息</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 60) + "' >建议就诊时间："+medtime+"</tr>";

                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费: "+ysfwf+"元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                                    if($("#pay_type").val()=="alipay"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                                    }
                                    if($("#pay_type").val()=="yhk"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                                    }
                                    if($("#pay_type").val()=="wxpay"){
                                       s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                                    }
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户：0元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 15) + "' >---------------------------------------</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                                    // s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号时间:"+riqi+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >打印时间:"+date+"</tr>";
                                    if($("#pay_type").val()=="alipay"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                                        }
                                    if($("#pay_type").val()=="yhk"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易单号："+$("#stream_no").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行凭证号："+$("#Auth").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行授权号："+$("#Memo").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易时间："+$("#TransDate").val()+"</tr>";
                                        }
                                    if($("#pay_type").val()=="wxpay"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信交易单号："+$("#stream_no").val()+"</tr>";
                                        }
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:"+$("#PayStatus").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号单当日有效,退号有效期为24小时,逾期作废。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*候诊提示：请到分诊台签到候诊，过号需重新</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >签到</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证,做为退费凭证。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：持该凭证打印发票，请勿遗失。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：医生签字后进行退费。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                                    s1 += "</print_info>";
                                    // alert(s1);

                                    // window.external.paint(s1);
                                }else{
                                    writeLog("HIS挂号保存失败");
                                    window.external.send(1,2);   //开启凭条灯带
                                    var paramss = {"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#patient_id").val(),"trade_no":$("#trade_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val()};
                                    $.ajax({
                                        url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/his_record",
                                        type:'post',
                                        dataType:'json',
                                        data:paramss,
                                        success:function(rel){
                                            if(rel['success']=="FAIL"){
                                                //保存失败
                                                //银行卡进行回退
                                                if($("#pay_type").val()=="yhk"  && data['head']['succflag']=="2"){
                                                    var total_amount = $("#total_amount").val();
                                                    var  bank_info1  = window.external.CreditTrans("0000"+$("#zzj_id").val()+"0000"+$("#zzj_id").val()+"H"+total_amount+""+$("#Auth").val());
                                                    writeLog("银行卡交易成功his回退串："+bank_info1); //日志
                                                    var paramssyhk = {"tk_status":bank_info1,"zzj_id":$("#zzj_id").val(),"out_trade_no":$("#stream_no").val()}
                                                     $.ajax({
                                                            url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/yhk_tk",
                                                            type:'post',
                                                            dataType:'json',
                                                            data:paramssyhk,
                                                            success:function(){}
                                                    })

                                                    $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",银行卡已退费");
                                                }else if($("#pay_type").val()=="wxpay" && data['head']['succflag']=="2"){
                                                    $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",微信正在退费");
                                                    writeLog("his失败,微信可以退款");
                                                    do_tuikuan($("#trade_no").val(),$("#stream_no").val(),$("#cash").val(),$("#zzj_id").val());
                                                }else if($("#pay_type").val()=="alipay" && data['head']['succflag']=="2"){
                                                    $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",支付宝正在退费");
                                                    writeLog("his失败,支付宝可以退款");
                                                    do_tuikuan($("#trade_no").val(),$("#stream_no").val(),$("#cash").val(),$("#zzj_id").val());
                                                  }
                                                var riqi = $("#czsj").val();//HIS返回挂号日期时间
                                                var sex = $("#patient_sex").val();//患者性别;
                                                if(sex == "1"){
                                                      sex = '男';
                                                }else{
                                                      sex = '女';
                                                }
                                                var age =  $("#patient_age").val();//患者年龄
                                                var s1="";
                                                var pos = 0;
                                                s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                                s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                                                s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='35' y='" + (pos += 55) + "' >卡类型:就诊卡</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";

                                                s1 += "<tr font='黑体' type='text'  size='25' x='80' y='" + (pos += 30) + "' >缴费失败</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='45' y='" + (pos += 40) + "' >请保存此凭证</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='1' y='" + (pos += 40) + "' >支付金额已回退到账户</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >年龄:"+age+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费:"+$("#ysfwf").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                   s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户：0元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号时间:"+riqi+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >打印时间:"+date+"</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易单号："+$("#stream_no").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行凭证号："+$("#Auth").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行授权号："+$("#Memo").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易时间："+$("#TransDate").val()+"</tr>";
                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信交易单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:支付成功</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，遗失不补。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，作为退费凭证。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                                                s1 += "</print_info>";
                                                // alert(s1);
                                                window.external.paint(s1);
                                            }else{
                                              $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",请去收费窗口处理");
                                               var riqi = $("#czsj").val();//HIS返回挂号日期时间
                                                var sex = $("#patient_sex").val();//患者性别;
                                                if(sex == "1"){
                                                      sex = '男';
                                                }else{
                                                      sex = '女';
                                                }
                                                var age =  $("#patient_age").val();//患者年龄
                                                var s1="";
                                                var pos = 0;
                                                s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                                s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                                                s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='35' y='" + (pos += 55) + "' >卡类型:就诊卡</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='80' y='" + (pos += 30) + "' >缴费成功</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='45' y='" + (pos += 40) + "' >请保存此凭证</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='1' y='" + (pos += 40) + "' >信息保存异常</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='10' y='" + (pos += 40) + "' >请当天去窗口处理</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >年龄:"+age+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费:"+$("#ysfwf").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                   s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户：0元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号时间:"+riqi+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >打印时间:"+date+"</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易单号："+$("#stream_no").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行凭证号："+$("#Auth").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行授权号："+$("#Memo").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易时间："+$("#TransDate").val()+"</tr>";

                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信交易单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:支付成功</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，遗失不补。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，作为退费凭证。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                                                s1 += "</print_info>";
                                                // alert(s1);
                                                window.external.paint(s1);
                                            }
                                        }
                                    });
                                }
                                //开始记录交易信息
                                // 支付类型(如自助缴费)
                                var business_type = $("#business_type").val();
                                // 卡类型(0是就诊卡 1是医保卡)
                                var card_type = $("#card_code").val();
                                // 就诊卡卡号或者医保卡号
                                var pat_card_no = $("#card_no").val();
                                // 买家支付宝账号
                                var id_card_no = $("#idCard").val();
                                // 病人ID
                                var pat_id = $("#patient_id").val();
                                // 病人姓名
                                var pat_name = $("#patient_name").val();
                                // 病人性别
                                var pat_sex = $("#patient_sex").val();
                                // 自费加医保支付总金额
                                var charge_total = $("#chargetamt").val();
                                // 支付宝,微信支付金额
                                var cash = $("#cash").val();
                                // 医保个人封闭账号支付
                                var zhzf = 0;
                                // 医保统筹支付
                                var tczf = $("#chargetamt").val()-$("#selfPay").val();
                                // 支付宝微信交易状态
                                var trading_state = $("#PayStatus").val();
                                // HIS状态
                                var his_state = data['head']['retcode'];
                                // 买家支付宝，微信交易号
                                var trade_no = $("#trade_no").val();
                                // 自助机id
                                var zzj_id =$("#zzj_id").val();
                                // 订单支付时传入的商户订单号
                                var stream_no = $("#stream_no").val();
                                // 支付方式(如alipay,wxpay)
                                var pay_type = $("#pay_type").val();
                                pay_record(business_type,card_type,pat_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,his_state,trade_no,zzj_id,stream_no,pay_type);
                                daojishi(10);
                                // setTimeout(function(){
                                //  $("#fanhui").trigger("click");
                                // },10000);
                            }
                        });
                    break;
                    case '2'://身份证
                        var index_load="";
                        var params = {"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#patient_id").val(),"meddate":$("#meddate").val(),"hao_type":$("#hao_type").val(),"orderid":$("#orderid").val(),"apm":$("#apm").val(),"department_id":$("#ksdm").val(),"doc_no":$("#ysdm").val(),"pay_type":$("#pay_type").val(),"medpay":$("#medflag").val(),"rctp_no":$("#rctpno").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"stream_no":$("#stream_no").val(),"trade_no":$("#trade_no").val(),"bankno":$("#idCard").val()};

                        $.ajax({
                            url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/sfz_guahao_save",
                            type:'post',
                            dataType:'json',
                            data:params,
                            success:function(data){
                                var tk_status="";//退款状态
                                var mx="";//明细
                                var pt="";
                                var date  = new Date().Format("yyyy-MM-dd hh:mm:ss");
                                if(true){
                                    // writeLog("HIS挂号保存成功");
                                    // window.external.send(1,2);   //开启凭条灯带
                                    $(".pay_success h3").html("支付成功"+"<br>请收好就诊凭证,身份证。");
                                    setInterval(function(){
                                       // window.location.reload();
                                       window.location.href="/rw2.0_payment/index.php/ZiZhu/Index/index";
                                    },3000);
                                    return;
                                    //开始往数据库添加数据
                                    Transaction($("#cash").val(),$("#cash").val());
                                    var sex = $("#patient_sex").val();//患者性别;
                                    if(sex == "1"){
                                        sex = '男';
                                    }else{
                                        sex = '女';
                                    }
                                    var age = data['body']['age'];//患者年龄
                                    var ID = data['body']['medid'];//患者ID  门诊号
                                    var riqi =  data['body']['meddate'];//HIS返回挂号日期时间
                                    var medtime =  data['body']['medtime'];//建议就诊时间
                                    if ($("#apm").val() == '下午') {
                                        var regid =  "100"+data['body']['regid']; //获取序号
                                    }else{
                                        var regid =  data['body']['regid']; //获取序号
                                    }
                                    var docname =  data['body']['docname']; //医生姓名
                                    var docrank =  data['body']['docrank']; //职称
                                    var departmentname =  data['body']['departmentname']; //科室
                                    var ysfwf  =  data['body']['regamt']; //挂号金额 医事服务费
                                    var regaddr  =  data['body']['regaddr']; //位置
                                    var s1="";
                                    var pos = 0;
                                    s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                    s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                                    s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 55) + "' >卡类型:身份证</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 15) + "' >---------------------------------------</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 15) + "' >门诊号:"+ID+"</tr>";
                                   /* s1 += "<tr font='黑体' type='text'  size='13' x='150' y='" + (pos += 0) + "' >"+sf+":"+MZID+"</tr>";*/
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >时间:"+$("#apm").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >序号:"+regid+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='150' y='" + (pos += 0) + "' >年龄:"+age+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医生："+docname+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >职称："+docrank+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >就诊科室："+departmentname+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 25) + "' >就诊位置："+regaddr+"</tr>";
                                    // s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 25) + "' >建议就诊时间："+medtime+"</tr>";
                                    s1 += "<tr font='黑体' type='qrCode' width='100' height='100'  x='50' y='" + (pos += 30) + "' >"+data['pos_url']+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='170' y='" + (pos += 20) + "' >请使用微信</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='170' y='" + (pos += 20) + "' >扫描二维码</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='170' y='" + (pos += 20) + "' >获取导航信息</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 60) + "' >建议就诊时间："+medtime+"</tr>";

                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费: "+ysfwf+"元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                                    if($("#pay_type").val()=="alipay"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                                    }
                                    if($("#pay_type").val()=="yhk"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                                    }
                                    if($("#pay_type").val()=="wxpay"){
                                       s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                                    }
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户：0元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 15) + "' >---------------------------------------</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                                    // s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号时间:"+riqi+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >打印时间:"+date+"</tr>";
                                    if($("#pay_type").val()=="alipay"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                                        }
                                    if($("#pay_type").val()=="yhk"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易单号："+$("#stream_no").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行凭证号："+$("#Auth").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行授权号："+$("#Memo").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易时间："+$("#TransDate").val()+"</tr>";
                                        }
                                    if($("#pay_type").val()=="wxpay"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信交易单号："+$("#stream_no").val()+"</tr>";
                                        }
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >支付状态:"+$("#PayStatus").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号单当日有效,退号有效期为24小时,逾期作废。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*候诊提示：请到分诊台签到候诊，过号需重新</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >签到</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证,做为退费凭证。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：持该凭证打印发票，请勿遗失。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：医生签字后进行退费。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                                    s1 += "</print_info>";
                                    // alert(s1);
                                    window.external.paint(s1);
                                }else{
                                     writeLog("HIS挂号保存失败");
                                    window.external.send(1,2);   //开启凭条灯带
                                    var paramss = {"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#patient_id").val(),"trade_no":$("#trade_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val()};
                                    $.ajax({
                                        url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/his_record",
                                        type:'post',
                                        dataType:'json',
                                        data:paramss,
                                        success:function(rel){
                                            if(rel['success']=="FAIL"){
                                                //保存失败
                                                //银行卡进行回退
                                                if($("#pay_type").val()=="yhk"  && data['head']['succflag']=="2"){
                                                    var total_amount = $("#total_amount").val();
                                                    var  bank_info1  = window.external.CreditTrans("0000"+$("#zzj_id").val()+"0000"+$("#zzj_id").val()+"H"+total_amount+""+$("#Auth").val());
                                                    writeLog("银行卡交易成功his回退串："+bank_info1); //日志
                                                    var paramssyhk = {"tk_status":bank_info1,"zzj_id":$("#zzj_id").val(),"out_trade_no":$("#stream_no").val()}
                                                     $.ajax({
                                                            url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/yhk_tk",
                                                            type:'post',
                                                            dataType:'json',
                                                            data:paramssyhk,
                                                            success:function(){}
                                                    })

                                                    $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",银行卡已退费");
                                                }else if($("#pay_type").val()=="wxpay" && data['head']['succflag']=="2"){
                                                    $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",微信正在退费");
                                                    writeLog("his失败,微信可以退款");
                                                    do_tuikuan($("#trade_no").val(),$("#stream_no").val(),$("#cash").val(),$("#zzj_id").val());
                                                }else if($("#pay_type").val()=="alipay" && data['head']['succflag']=="2"){
                                                    $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",支付宝正在退费");
                                                    writeLog("his失败,支付宝可以退款");
                                                    do_tuikuan($("#trade_no").val(),$("#stream_no").val(),$("#cash").val(),$("#zzj_id").val());
                                                  }
                                                var riqi = $("#czsj").val();//HIS返回挂号日期时间
                                                var sex = $("#patient_sex").val();//患者性别;
                                                if(sex == "1"){
                                                      sex = '男';
                                                }else{
                                                      sex = '女';
                                                }
                                                var age =  $("#patient_age").val();//患者年龄
                                                var s1="";
                                                var pos = 0;
                                                s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                                s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                                                s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='35' y='" + (pos += 55) + "' >卡类型:身份证</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";

                                                s1 += "<tr font='黑体' type='text'  size='25' x='80' y='" + (pos += 30) + "' >缴费失败</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='45' y='" + (pos += 40) + "' >请保存此凭证</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='1' y='" + (pos += 40) + "' >支付金额已回退到账户</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >年龄:"+age+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费:"+$("#ysfwf").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                   s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户：0元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号时间:"+riqi+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >打印时间:"+date+"</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易单号："+$("#stream_no").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行凭证号："+$("#Auth").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行授权号："+$("#Memo").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易时间："+$("#TransDate").val()+"</tr>";
                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信交易单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:支付成功</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，遗失不补。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，作为退费凭证。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                                                s1 += "</print_info>";
                                                // alert(s1);
                                                window.external.paint(s1);
                                            }else{
                                              $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",请去收费窗口处理");
                                               var riqi = $("#czsj").val();//HIS返回挂号日期时间
                                                var sex = $("#patient_sex").val();//患者性别;
                                                if(sex == "1"){
                                                      sex = '男';
                                                }else{
                                                      sex = '女';
                                                }
                                                var age =  $("#patient_age").val();//患者年龄
                                                var s1="";
                                                var pos = 0;
                                                s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                                s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                                                s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='35' y='" + (pos += 55) + "' >卡类型:身份证</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='80' y='" + (pos += 30) + "' >缴费成功</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='45' y='" + (pos += 40) + "' >请保存此凭证</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='1' y='" + (pos += 40) + "' >信息保存异常</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='10' y='" + (pos += 40) + "' >请当天去窗口处理</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >年龄:"+age+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费:"+$("#ysfwf").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                   s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户：0元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号时间:"+riqi+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >打印时间:"+date+"</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易单号："+$("#stream_no").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行凭证号："+$("#Auth").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行授权号："+$("#Memo").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易时间："+$("#TransDate").val()+"</tr>";

                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信交易单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:支付成功</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，遗失不补。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，作为退费凭证。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                                                s1 += "</print_info>";
                                                // alert(s1);
                                                window.external.paint(s1);
                                            }
                                        }
                                    });
                                }
                                //开始记录交易信息
                                // 支付类型(如自助缴费)
                                var business_type = $("#business_type").val();
                                // 卡类型(0是就诊卡 1是医保卡)
                                var card_type = $("#card_code").val();
                                // 就诊卡卡号或者医保卡号
                                var pat_card_no = $("#card_no").val();
                                // 买家支付宝账号
                                var id_card_no = $("#idCard").val();
                                // 病人ID
                                var pat_id = $("#patient_id").val();
                                // 病人姓名
                                var pat_name = $("#patient_name").val();
                                // 病人性别
                                var pat_sex = $("#patient_sex").val();
                                // 自费加医保支付总金额
                                var charge_total = $("#chargetamt").val();
                                // 支付宝,微信支付金额
                                var cash = $("#cash").val();
                                // 医保个人封闭账号支付
                                var zhzf = 0;
                                // 医保统筹支付
                                var tczf = $("#chargetamt").val()-$("#selfPay").val();
                                // 支付宝微信交易状态
                                var trading_state = $("#PayStatus").val();
                                // HIS状态
                                var his_state = data['head']['retcode'];
                                // 买家支付宝，微信交易号
                                var trade_no = $("#trade_no").val();
                                // 自助机id
                                var zzj_id =$("#zzj_id").val();
                                // 订单支付时传入的商户订单号
                                var stream_no = $("#stream_no").val();
                                // 支付方式(如alipay,wxpay)
                                var pay_type = $("#pay_type").val();
                                pay_record(business_type,card_type,pat_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,his_state,trade_no,zzj_id,stream_no,pay_type);
                                daojishi(10);
                                // setTimeout(function(){
                                //  $("#fanhui").trigger("click");
                                // },10000);
                            }
                        });
                    break;
                    case '1'://医保卡
                       var index_load="";
                       // alert($("#card_code").val()+"卡类型");
                       //  alert($("#card_no").val()+"卡号");
                       //  alert($("#patient_id").val()+"病人id号");
                       //  alert($("#meddate").val()+"就诊时间");
                       //  alert($("#hao_type").val()+"号别");
                       //  alert($("#orderid").val()+"排班ID");
                       //  alert($("#apm").val()+"上下午名称");
                       //  alert($("#ksdm").val()+"科室id");
                       //  alert($("#ysdm").val()+"医生id");
                       //  alert($("#pay_type").val()+"支付方式");
                       //  alert($("#medflag").val()+"医保状态");
                       //  alert($("#rctpno").val()+"收据号");
                       //  alert($("#op_code").val()+"操作码");
                       //  alert($("#zzj_id").val()+"自助机id");
                       //  alert($("#trade_no").val()+"交易流水号");
                        var params = {"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#patient_id").val(),"meddate":$("#meddate").val(),"hao_type":$("#hao_type").val(),"orderid":$("#orderid").val(),"apm":$("#apm").val(),"department_id":$("#ksdm").val(),"doc_no":$("#ysdm").val(),"pay_type":$("#pay_type").val(),"medpay":$("#medflag").val(),"rctp_no":$("#rctpno").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"stream_no":$("#stream_no").val(),"trade_no":$("#trade_no").val(),"bankno":$("#idCard").val(),"cash":$("#cash").val(),"mzjsfs":$("#gsbr").val(),"charge_total":$("#charge_total").val(),"tczf":$("#tczf").val()};
                        $.ajax({
                            url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/yb_guahao_save",
                            type:'post',
                            dataType:'json',
                            data:params,
                            success:function(data){
                                var tk_status="";//退款状态
                                var mx="";//明细
                                var pt="";
                                var  date = new Date().Format("yyyy-MM-dd hh:mm:ss");
                                // window.external.MoveOutCard();
                                // window.external.DisAllowCardIn();
                                if(true){
                                    // writeLog("HIS挂号保存成功");
                                    // window.external.send(1,2);   //开启凭条灯带
                                    $(".pay_success h3").html("支付成功"+"<br>请收好就诊凭证,医保卡。");
                                    setInterval(function(){
                                       // window.location.reload();
                                       window.location.href="/rw2.0_payment/index.php/ZiZhu/Index/index";
                                    },3000);
                                    return;
                                    //开始往数据库添加数据
                                    Transaction($("#charge_total").val(),$("#cash").val());
                                    var sex = $("#patient_sex").val();//患者性别;
                                    if(sex == "1"){
                                        sex = '男';
                                    }else{
                                        sex = '女';
                                    }
                                    var age = data['body']['age'];//患者年龄
                                    var ID = data['body']['medid'];//患者ID  门诊号
                                    var riqi =  data['body']['meddate'];//HIS返回挂号日期时间
                                    var medtime =  data['body']['medtime'];//建议就诊时间
                                    if ($("#apm").val() == '下午') {
                                        var regid =  "100"+data['body']['regid']; //获取序号
                                    }else{
                                        var regid =  data['body']['regid']; //获取序号
                                    }
                                    var docname =  data['body']['docname']; //医生姓名
                                    var docrank =  data['body']['docrank']; //职称
                                    var departmentname =  data['body']['departmentname']; //科室
                                    var ysfwf  =  data['body']['regamt']; //挂号金额 医事服务费
                                    var regaddr  =  data['body']['regaddr']; //位置
                                    var s1="";
                                    var pos = 0;
                                  /*  alert($("#patient_id").val()+"门诊号");
                                    alert($("#hbmc").val()+"号别");*/
                                    s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                    s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                                    s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 55) + "' >卡类型:医保卡</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 15) + "' >---------------------------------------</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 15) + "' >门诊号:"+ID+"</tr>";
                                   /* s1 += "<tr font='黑体' type='text'  size='13' x='150' y='" + (pos += 0) + "' >"+sf+":"+MZID+"</tr>";*/
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >时间:"+$("#apm").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >序号:"+regid+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='150' y='" + (pos += 0) + "' >年龄:"+age+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医生："+docname+"</tr>";
                                     s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >职称："+docrank+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >就诊科室："+departmentname+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 25) + "' >就诊位置："+regaddr+"</tr>";
                                    // s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 25) + "' >建议就诊时间："+medtime+"</tr>";

                                    s1 += "<tr font='黑体' type='qrCode' width='100' height='100'  x='50' y='" + (pos += 30) + "' >"+data['pos_url']+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='170' y='" + (pos += 20) + "' >请使用微信</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='170' y='" + (pos += 20) + "' >扫描二维码</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='13' x='170' y='" + (pos += 20) + "' >获取导航信息</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 60) + "' >建议就诊时间："+medtime+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费: "+ysfwf+"元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                                    if($("#pay_type").val()=="alipay"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                                    }
                                    if($("#pay_type").val()=="yhk"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                                    }
                                    if($("#pay_type").val()=="wxpay"){
                                       s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                                    }
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户："+$("#zhzf").val()+"元</tr>";
                                     // s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户余额："+$("#personcount").val()+"元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 15) + "' >---------------------------------------</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                                    // s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号时间:"+riqi+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号时间:"+date+"</tr>";
                                    if($("#pay_type").val()=="alipay"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                                        }
                                    if($("#pay_type").val()=="yhk"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易单号："+$("#stream_no").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行凭证号："+$("#Auth").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行授权号："+$("#Memo").val()+"</tr>";
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易时间："+$("#TransDate").val()+"</tr>";
                                    }
                                    if($("#pay_type").val()=="wxpay"){
                                        s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信单号："+$("#stream_no").val()+"</tr>";
                                    }
                                    s1 += "<tr font='黑体' type='text'  size='13' x='10' y='" + (pos += 20) + "' >支付状态:"+$("#PayStatus").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号单当日有效,退号有效期为24小时,逾期作废。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*候诊提示：请到分诊台签到候诊，过号需重新</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >签到</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证,做为退费凭证。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：持该凭证打印发票，请勿遗失。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：医生签字后进行退费。</tr>";
                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                                    s1 += "</print_info>";
                                    //alert(s1);
                                    window.external.paint(s1);
                                }else{
                                    writeLog("HIS挂号保存失败");
                                    window.external.send(1,2);   //开启凭条灯带
                                     var paramss = {"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#patient_id").val(),"trade_no":$("#trade_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val()};
                                    $.ajax({
                                        url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/his_record",
                                        type:'post',
                                        dataType:'json',
                                        data:paramss,
                                        success:function(rel){
                                            if(rel['success']=="FAIL"){
                                                writeLog("HIS无记录");
                                                //银行卡进行回退
                                                if($("#pay_type").val()=="yhk"  && data['head']['succflag']=="2"){
                                                    var total_amount = $("#total_amount").val();
                                                    var  bank_info1  = window.external.CreditTrans("0000"+$("#zzj_id").val()+"0000"+$("#zzj_id").val()+"H"+total_amount+""+$("#Auth").val());
                                                    writeLog("银行卡交易成功his回退串："+bank_info1); //日志
                                                    var paramssyhk = {"tk_status":bank_info1,"zzj_id":$("#zzj_id").val(),"out_trade_no":$("#stream_no").val()}
                                                     $.ajax({
                                                            url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/yhk_tk",
                                                            type:'post',
                                                            dataType:'json',
                                                            data:paramssyhk,
                                                            success:function(){}
                                                    })
                                                       $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",银行卡已退费");
                                                    //微信进行回退
                                                }else if($("#pay_type").val()=="wxpay" && data['head']['succflag']=="2"){
                                                    $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",微信正在退费");
                                                    writeLog("his失败,微信可以退款");
                                                    do_tuikuan($("#trade_no").val(),$("#stream_no").val(),$("#cash").val(),$("#zzj_id").val());
                                                }else if($("#pay_type").val()=="alipay" && data['head']['succflag']=="2"){
                                                   // 支付宝进行回退
                                                    $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",支付宝正在退费");
                                                    writeLog("his失败,支付宝可以退款");
                                                    do_tuikuan($("#trade_no").val(),$("#stream_no").val(),$("#cash").val(),$("#zzj_id").val());
                                                }
                                                var retcode = data["head"]["retcode"];
                                                //保存失败
                                                if(retcode == "3032"){
                                                    sblx = "医保卡缴费失败";
                                                }else{
                                                    sblx = "HIS缴费保存失败";
                                                }
                                                var riqi = $("#czsj").val();//HIS返回挂号日期时间
                                                var sex = $("#patient_sex").val();//患者性别;
                                                if(sex == "1"){
                                                    sex = '男';
                                                }else{
                                                    sex = '女';
                                                }
                                                var age =  $("#patient_age").val();//患者年龄
                                                var s1="";
                                                var pos = 0;
                                                s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                                s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                                                s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='35' y='" + (pos += 55) + "' >卡类型:医保卡</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";

                                                s1 += "<tr font='黑体' type='text'  size='25' x='80' y='" + (pos += 30) + "' >缴费失败</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='45' y='" + (pos += 40) + "' >请保存此凭证</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='1' y='" + (pos += 40) + "' >支付金额已回退到账户</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >年龄:"+age+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费:"+$("#ysfwf").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                   s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户："+$("#zhzf").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                                                // s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号时间:"+riqi+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >打印时间:"+date+"</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易单号："+$("#stream_no").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行凭证号："+$("#Auth").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行授权号："+$("#Memo").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易时间："+$("#TransDate").val()+"</tr>";
                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:支付成功</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >失败类型："+sblx+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，遗失不补。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，作为退费凭证。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                                                s1 += "</print_info>";
                                                // alert(s1);
                                                window.external.paint(s1);
                                            }else{
                                                 writeLog("HIS有记录");
                                                 $(".pay_success h3").show().html("支付成功,"+data['head']['retmsg']+",请去收费窗口处理");
                                                var retcode = data["head"]["retcode"];
                                                //保存失败
                                                if(retcode == "3032"){
                                                    sblx = "医保卡缴费失败";
                                                }else{
                                                    sblx = "HIS缴费保存失败";
                                                }
                                                var riqi = $("#czsj").val();//HIS返回挂号日期时间
                                                var sex = $("#patient_sex").val();//患者性别;
                                                if(sex == "1"){
                                                      sex = '男';
                                                }else{
                                                      sex = '女';
                                                }
                                                var age =  $("#patient_age").val();//患者年龄
                                                var s1="";
                                                var pos = 0;

                                                s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                                                s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                                                s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='35' y='" + (pos += 55) + "' >卡类型:医保卡</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";

                                                s1 += "<tr font='黑体' type='text'  size='25' x='80' y='" + (pos += 30) + "' >缴费成功</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='45' y='" + (pos += 40) + "' >请保存此凭证</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='1' y='" + (pos += 40) + "' >信息保存异常</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='25' x='10' y='" + (pos += 40) + "' >请当天去窗口处理</tr>";


                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >年龄:"+age+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费:"+$("#ysfwf").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                                                 s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                   s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户："+$("#zhzf").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                                                // s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >挂号时间:"+riqi+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >打印时间:"+date+"</tr>";
                                                if($("#pay_type").val()=="alipay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                if($("#pay_type").val()=="yhk"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡号："+$("#idCard").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易单号："+$("#stream_no").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行凭证号："+$("#Auth").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行授权号："+$("#Memo").val()+"</tr>";
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行交易时间："+$("#TransDate").val()+"</tr>";

                                                }
                                                if($("#pay_type").val()=="wxpay"){
                                                    s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信单号："+$("#stream_no").val()+"</tr>";
                                                }
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:支付成功</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                                                 s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >失败类型："+sblx+"</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，遗失不补。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，作为退费凭证。</tr>";
                                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                                                s1 += "</print_info>";
                                                // alert(s1);
                                                window.external.paint(s1);
                                            }
                                        }
                                    });
                                }
                                window.external.MoveOutCard();
                                window.external.DisAllowCardIn();
                                //开始记录交易信息
                                // 支付类型(如自助缴费)
                                var business_type = $("#business_type").val();
                                // 卡类型(0是就诊卡 1是医保卡)
                                var card_type = $("#card_code").val();
                                // 就诊卡卡号或者医保卡号
                                var pat_card_no = $("#card_no").val();
                                // 买家支付宝账号
                                var id_card_no = $("#idCard").val();
                                // 病人ID
                                var pat_id = $("#patient_id").val();
                                // 病人姓名
                                var pat_name = $("#patient_name").val();
                                // 病人性别
                                var pat_sex = $("#patient_sex").val();
                                // 自费加医保支付总金额
                                var charge_total = $("#chargetamt").val();
                                // 支付宝,微信支付金额
                                var cash = $("#cash").val();
                                // 医保个人封闭账号支付
                                var zhzf = 0;
                                // 医保统筹支付
                                var tczf = $("#chargetamt").val()-$("#selfPay").val();
                                // 支付宝微信交易状态
                                var trading_state = $("#PayStatus").val();
                                // HIS状态
                                var his_state = data['head']['retcode'];
                                // 买家支付宝，微信交易号
                                var trade_no = $("#trade_no").val();
                                // 自助机id
                                var zzj_id =$("#zzj_id").val();
                                // 订单支付时传入的商户订单号
                                var stream_no = $("#stream_no").val();
                                // 支付方式(如alipay,wxpay)
                                var pay_type = $("#pay_type").val();
                                pay_record(business_type,card_type,pat_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,his_state,trade_no,zzj_id,stream_no,pay_type);
                                daojishi(10);
                                // setTimeout(function(){
                                //  $("#fanhui").trigger("click");
                                // },10000);
                            }
                        });
                    break;
                    default:
                    layer.msg("未知错误", {icon: 14,time:2000});
                    break;
                }
        }else{
            return;
        }

    }
}
/***退出按钮事件***/
$("#tuichu").on("click",function(){
   window.location.href="/rw2.0_payment/index.php/ZiZhu/Index"
    // // print_status($("#zzj_id").val());
    // // window.external.send(1,4);
    // // writeLog("点击退出退到主界面");
    // $(".mtnum2").hide();
    // $("#jz_card_no").val("");
    // $("#jz_card_no_guahao").val("");
    // $("#jz_card_no_guahao_sfz").val("");
    // $("#jz_card_no_sfz").val("");
    // $(".btn_area").hide();
    // $(".op_now").val("");
    // /*添加了用户的姓名*/
    // $("#jz_name").html("");
    // $("#guahao_name").html("");
    // $("#keshi_name").html("");
    // $("#attr_list").html("");
    // $("#sub_list").html("");
    // $(".chose_room h4").html("");
    // $(".tips").html("");
    // $(".pay_ma_show .tips").html("请用手机支付宝扫描付款");
    // $(".pay_ma_show .erweima").html("");
    // $(".pay_ma_show .pay_val").html("");


    // $(".yb_op_area .tips").html('');
    // $(".yb_op_area_guahao .tips_guahao").html('');
    // key_value="";
    // key_value2="";
    // // window.external.send(1,4);
    // // window.external.FreeYBIntfDll();
    // //正式库要开启 退卡  禁止进卡
    // // window.external.MoveOutCard();
    // // window.external.DisAllowCardIn();

    // //window.external.KeyBoardComClose();
    // //window.external.keybord_close();
    // // window.external.Out_UMS_CardClose();
    // //window.external.Out_UMS_EjectCard();
    // //UMS_EjectCard();
    // //UMS_CardClose();
    // $(".inhide").val("");
    // $("#downcount").hide();
    // for(var key in interval2){
    //     clearTimeout(interval2[key]);
    // }
    // for(var key in interval3){
    //     clearInterval(interval3[key]);
    // }
    // window.external.webBrowserNavigateHome();
});
/*****确定按钮点击事件**********/
$("#confirm").on("click",function(){
    var op_now = $("#op_now").val();

    //alert(op_now);
    for(var key in interval3){
        clearInterval(interval3[key]);
    }

    /**获取就诊卡患者信息**/
    switch(op_now){
        case "ic_get_pat_info":

           // window.external.send(1,4); //关闭灯带
            $(".jiuzhen_op_area .tips").text("");
            // 获取就诊卡卡号
            var card_no = $("#jz_card_no").val();
            // 清空输入框中的值
           /* if(card_no.length!="8"){
                $(".jiuzhen_op_area .tips").text("输入有误,请重新输入");
                return;
            }*/
          //  writeLog("输入完就诊卡号点击确定");
            //获取就诊卡号的值
            var card_no = $("#card_no").val();
            var business_type = $("#business_type").val();
            var params = {"card_code":$("#card_code").val(),"card_no":card_no,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type};
            var index_load = "";
            $.ajax({
                url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/ic_getPatInfo",
                type:'post',
                dataType:'json',
                data:params,
                beforeSend:function(){
                    index_load = layer.msg('患者就诊数据查询中,请稍候...', {icon: 16,time:20000});
                    $("#fanhui").css({"visibility":"hidden"});
                    $("#confirm").css({"visibility":"hidden"});
                    $("#tuichu").css({"visibility":"hidden"});
                    $(".jiuzhen_op_area .tips").text("");
                },
                success:function(data){
                    layer.close(index_load);
                    if(data["head"]["succflag"]=="1")
                    {
            //******************将选择卡类型的图片更换并且字体更换//
            $(".choseCard").css({'color':'#333','font-weight':'normal'});
            $(".choseCardDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
            //同样操作于选择科室
            $(".choseRoom").css({'color':'#f57336','font-weight':'bold'});
            $(".choseRoomDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
            //*******************操作结束************************//
                        daojishi(60);
                        $(".mtnum2").hide();
                        $(".chose_room").show();
                        $("#confirm").css({"visibility":"hidden"});
                        $("#fanhui").css({"visibility":"hidden"});
                        $("#tuichu").css({"visibility":"visible"});
                        var pat_id = data["body"]['medid'];//患者ID
                        var pat_name = data["body"]["name"];//患者姓名
                        var patient_sex = data["body"]['sex'];//患者性别
                        if(patient_sex == "1"){
                           var  sex = '男';
                        }else{
                            var sex = '女';
                        }
                        var patient_age   =  data["body"]['age'];  //患者年龄
                        var medflag   =  data["body"]['medflag'];  //医保标志
                        $("#patient_id").val(pat_id); // 病人ID放入隐藏域
                        // alert( $("#patient_id").val());
                        $("#patient_name").val(pat_name); // 病人姓名放入隐藏域
                        $("#patient_sex").val(patient_sex); // 病人年龄放入隐藏域
                        $("#patient_age").val(patient_age); //病人性别放入隐藏域
                        $("#medflag").val(medflag); //医保标志放入隐藏域
                        $(".chose_hao_type .jz_name").text(pat_name+" "+sex);
                        layer.close(index_load);

                        $(".jz_name").text($("#patient_name").val());
                        $("#op_now").val("yijikeshi_show");
                        if(data['yijikeshi']!=null)
                        {
                            var sub_html = "";
                            var sub_list= data['yijikeshi'];
                            var page_ks=1;
                            pagedata_ks = data.yijikeshi;
                            showdata_ks(page_ks=1);
                        }else
                        {
                            daojishi(20);
                            $(".chose_room .page").css({"visibility":"hidden"});
                            $(".chose_room .cuowu").html("无可以挂号的科室");
                        }
                    }else
                    {
                        $("#confirm").css({"visibility":"visible"});
                        $("#fanhui").css({"visibility":"visible"});
                        $("#tuichu").css({"visibility":"visible"});
                        $(".jiuzhen_op_area .tips").text(data["head"]["retmsg"]);
                        $("#jz_card_no").val("")
                        $("#card_no").val("");
                    }
                }
            });
        break;
        case "sfz_put_sfz":
            // 获取身份证号
            var sfz_no = $("#jz_card_no_sfz").val();
            // alert(sfz_no);
            // 清空输入框中的值
            $("#jz_card_no_sfz").val("");
            key_value = "";
            $(".jiuzhen_op_area_sfz .tips").text("");
            // if(sfz_no.length!="18" && sfz_no.length!="15"){
            //     interval = setInterval(getCardNoS,"1000");
            //     interval3.push(interval);
            //     $(".jiuzhen_op_area_sfz .tips").text("输入有误,请重新输入");
            //     return;
            // }
            // window.external.send(1,4); //关闭灯带
            //将身份证号写入隐藏域
            $("#card_no").val(sfz_no);
            // writeLog("输入完身份证号点击确定");
            //获取就诊卡号的值
            var card_no = $("#card_no").val();
            // 身份证号
            // writeLog("身份证号:"+sfz_no);
            var params = {"card_code":$("#card_code").val(),"card_no":card_no,"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"business_type":business_type};
            var index_load = "";
            $.ajax({
                url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/sfz_getPatInfo",
                type:'post',
                dataType:'json',
                data:params,
                beforeSend:function(){
                    index_load = layer.msg('患者就诊数据查询中,请稍候...', {icon: 16,time:20000});
                    $("#fanhui").css({"visibility":"hidden"});
                    $("#confirm").css({"visibility":"hidden"});
                    $("#tuichu").css({"visibility":"hidden"});
                    $(".jiuzhen_op_area_sfz .tips").text("");
                },
                success:function(data){
                    layer.close(index_load);
                    if(data["head"]["succflag"]=="1"){
                    //******************将选择卡类型的图片更换并且字体更换//
            $(".choseCard").css({'color':'#333','font-weight':'normal'});
            $(".choseCardDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
            //同样操作于选择科室
            $(".choseRoom").css({'color':'#f57336','font-weight':'bold'});
            $(".choseRoomDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
            //*******************操作结束************************//
                       daojishi(60);
                        $("#confirm").css({"visibility":"hidden"});
                        $("#fanhui").css({"visibility":"hidden"});
                        $("#tuichu").css({"visibility":"visible"});
                        $(".mtnum2").hide();
                        var pat_id = data["body"]['medid'];//患者ID
                        var pat_name = data["body"]["name"];//患者姓名
                        var patient_sex = data["body"]['sex'];//患者性别
                        if(patient_sex == "1"){
                           var  sex = '男';
                        }else{
                            var sex = '女';
                        }
                        var patient_age   =  data["body"]['age'];  //患者年龄
                        var medflag   =  data["body"]['medflag'];  //医保标志
                        $("#patient_id").val(pat_id); // 病人ID放入隐藏域
                        // alert( $("#patient_id").val());
                        $("#patient_name").val(pat_name); // 病人姓名放入隐藏域
                        $("#patient_sex").val(patient_sex); // 病人年龄放入隐藏域
                        $("#patient_age").val(patient_age); //病人性别放入隐藏域
                        $("#medflag").val(medflag); //医保标志放入隐藏域
                        $(".chose_hao_type .jz_name").text(pat_name+" "+sex);
                        layer.close(index_load);
                        daojishi(60);
                        $(".mtnum2").hide();
                        $(".chose_room").show();
                        $(".jz_name").text($("#patient_name").val());
                        $("#op_now").val("yijikeshi_show");
                        if(data['yijikeshi']!=null){
                            var sub_html = "";
                            var sub_list= data['yijikeshi'];
                            var page_ks=1;
                            pagedata_ks = data.yijikeshi;
                            showdata_ks(page_ks=1);
                        }else{
                            daojishi(20);
                            $(".chose_room .page").css({"visibility":"hidden"});
                            $(".chose_room .cuowu").html("无可以挂号的科室");
                        }
                    }else{
                        $("#confirm").css({"visibility":"visible"});
                        $("#fanhui").css({"visibility":"visible"});
                        $("#tuichu").css({"visibility":"visible"});
                        $(".jiuzhen_op_area_sfz .tips").text(data["head"]["retmsg"]);
                        $("#jz_card_no").val("")
                        $("#card_no").val("");
                       /* interval = setInterval(getCardNoS,"1000");
                        interval3.push(interval);*/
                    }
                }
            });
        break;
        case "chose_yb_card":
            window.external.AllowCardIn();//允许社保进卡
            // interval = setInterval(getYibaoInfo,"1000");
            interval3.push(interval);
        break;
        case "jianka_xz_yibao_input_phone":
            // writeLog("医保输入手机号点击确定");
            // 清空手机号输入错误时的提示信息
            $(".jianka_phone_area .tips_jianka").text("");
            var jk_phone = $("#jk_phone").val();
            var reg = /^1(3|4|5|7|8)\d{9}$/;
            if(!reg.test(jk_phone)){
                　$(".jianka_phone_area .tips_jianka").text("手机号输入错误");
                return;
            }
            // alert(jk_phone);
            /*if(jk_phone==""){
                $(".jianka_phone_area .tips_jianka").text("输入错误");
                return;
            }*/
           // 医保卡激活
            var params = {"patient_phone":$("#jk_phone").val(),"op_code":$("#op_code").val(),"card_code":$("#card_code").val(),"zzj_id":$("#zzj_id").val()};
            $.ajax({
                url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/jianka_yb_save",
                type:'post',
                dataType:'json',
                data:params,
                beforeSend:function(){
                    index_load = layer.msg('医保卡激活中,请稍候...', {icon: 16,time:20000});
                    $("#fanhui").css({"visibility":"hidden"});
                    $("#confirm").css({"visibility":"hidden"});
                    $("#tuichu").css({"visibility":"hidden"});
                },
                success:function(data){
                    layer.close(index_load);
                    if(data["head"]["succflag"]=="1"){
                        $("#op_now").val("chose_yb_card");
                        interval = setInterval(getYibaoInfo,"1000");
                        interval3.push(interval);
                        daojishi(60);
                    }else{
                       // writeLog("自助机医保卡激活失败");
                        $(".mtnum2").hide();
                        $(".pay_success").show();
                        $("#confirm").css({"visibility":"hidden"});
                        $("#fanhui").css({"visibility":"hidden"});
                        $("#tuichu").css({"visibility":"visible"});
                        daojishi(10);
                        $(".pay_success h3").text(data["head"]["retmsg"]);
                        window.external.MoveOutCard();
                        window.external.DisAllowCardIn();

                    }
                }
            });
        break;
        case "chose_yb_jiandang":
                var params = {"op_code":$("#op_code").val(),"card_code":$("#card_code").val(),"zzj_id":$("#zzj_id").val()};
               writeLog("选择医保卡建档");
                $.ajax({
                     url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/yibao_getPat_lb_Info",
                     type:'post',
                     dataType:'json',
                     data:params,
                     beforeSend:function(){
                        index_load = layer.msg('医保卡类别查询中,请稍候...', {icon: 16,time:20000});
                        $("#fanhui").css({"visibility":"hidden"});
                        $("#confirm").css({"visibility":"hidden"});
                        $("#tuichu").css({"visibility":"hidden"});
                        $(".pay_success h3").text("");
                       },
                     success:function(data){
                       layer.close(index_load);
                       if(data["head"]["succflag"]=="1"){
                           daojishi(60);
                           var sfyzjg = data["body"]["sfyzjg"];
                           var  fylb = data["body"]["fylb"];
                           var  szyz = data["body"]["szyz"];
                           var sfy  =  sfyzjg.slice(0,1);
                            var gx = $("#gx").val();
                           if (sfy == "1"){
                               var sfy  =  sfyzjg.slice(0,2);
                             }
                             $(".mtnum2").hide();
                             $("#op_now").val("yb_jianka");
                             $("#confirm").css({"visibility":"visible"});
                             $("#fanhui").css({"visibility":"visible"});
                             $("#tuichu").css({"visibility":"visible"});
                             $(".pay_success").show();
                               // 0 不在红名单  12 不在定点医院  8B 城乡持卡    8A征地超转  85 老年医保   86 无业居民
                           if(  sfy == "0" ){
                               $(".pay_success h3").text("不在红名单，建档后在本院费用按医保外处理");

                             }else if( sfy == "12"){
                                  $(".pay_success h3").text("不在定点医院，建档后在本院费用按医保外处理");

                             }else if( fylb == "8B"){
                                $(".pay_success h3").text("城乡持卡人员首次门诊就诊必须到一级及以下(含社区)医院,否则全部费用按医保外处理");
                                //不在红名单  非定点
                             }else if(szyz == "1" ){
                                $(".pay_success h3").text(data["body"]["Msg"]+"建档后在本院费用按医保外处理");

                             }else if( fylb == "86" ){
                                $(".pay_success h3").text("无保障老年人或无业人员首次门诊就诊必须到一级及以下(含社区)医院,否则全部费用按医保外处理");
                             }else if(gx == "y"){
                                //更新建档信息
                                  window.location.href="/rw2.0_payment/index.php/ZiZhu/JianKaGx/index/zzj_id/"+zzj_id;

                             }else{
                               window.location.href="/rw2.0_payment/index.php/ZiZhu/JianKa/index/zzj_id/"+zzj_id;
                             }



                     }else{
                          daojishi(60);
                         $(".mtnum2").hide();
                         $("#op_now").val("chose_yb_jiandang");
                         $("#confirm").css({"visibility":"hidden"});
                         $("#fanhui").css({"visibility":"visible"});
                         $("#tuichu").css({"visibility":"visible"});
                         $(".pay_success").show();
                         $(".pay_success h3").text(data["head"]["retmsg"]);
                         }

                     }

             });
        break;
        case "yb_jianka":
           var gx = $("#gx").val();
           if(gx == "y"){
                //更新建档信息
                window.location.href="/rw2.0_payment/index.php/ZiZhu/JianKaGx/index/zzj_id/"+zzj_id;
           }else{
              window.location.href="/rw2.0_payment/index.php/ZiZhu/JianKa/index/zzj_id/"+zzj_id;
           }
        break;
        case "guahao_xinxi_queren":
            if($("#cash").val()==0){
                var out_trade_no = $("#zzj_id").val()+$("#pat_code").val()+new Date().Format("yyyyMMddmmss");
                $("#stream_no").val(out_trade_no);
                $(".mtnum2").hide();
                $(".pay_success").show();
                $(".pay_success h3").html("费用确认中,请稍候...");
                $("#confirm").css({"visibility":"hidden"});
                $("#fanhui").css({"visibility":"hidden"});
                $("#tuichu").css({"visibility":"hidden"});
                $("#pay_type").val("mianfei");
                paySuccessSendToHis($("#card_code").val());
            }else{
                //******************将选择卡类型的图片更换并且字体更换//
            $(".confirmInfo").css({'color':'#333','font-weight':'normal'});
            $(".confirmInfoDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
            //同样操作于选择科室
            $(".chosePayType").css({'color':'#f57336','font-weight':'bold'});
            $(".chosePayTypeDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
            //*******************操作结束************************//
                daojishi(180)
                // window.external.send(1,4); //关闭灯带
                $(".mtnum2").hide();
                $(".guahao_queren").hide();
                $(".chose_pay_type_area").show();
                $("#confirm").css({"visibility":"hidden"});
                $("#op_now").val("chose_pay_type");
            }
        break;
        default:
            return false;
        break;
    }
        return false;
});
/********返回按钮功能处理****/
$("#fanhui").on("click",function(){
    //alert($("#op_now").val());
    switch($("#op_now").val()){
        //从选择卡类型界面返回到首页
        case "guahao_chose_card_type":
            $("#op_now").val("guahao_chose_card_type");
            window.location.href="/rw2.0_payment/index.php/ZiZhu/Index";
        break;
        // 输入就诊卡号,返回到选择卡类型页面
        case "ic_get_pat_info":
            $("#jz_card_no").val("");
            $(".jiuzhen_op_area").hide();
            $(".chose_card_type_area").show();
            $("#confirm").css({"visibility":"hidden"});
            $("#fanhui").css({"visibility":"hidden"});
            $("#op_now").val("guahao_chose_card_type");
            //window.external.send(1,4); //关闭灯带
            daojishi(60);
        break;
        //从选择二级科室返回到选择一级科室
        case "erjikeshi_show":
            $(".mtnum2").hide();
            $("#op_now").val("yijikeshi_show");
            daojishi(60);
            $("#confirm").css({"visibility":"hidden"});
            $("#fanhui").css({"visibility":"hidden"});
            $("#tuichu").css({"visibility":"visible"});
            $(".mtnum2").hide();
            $(".chose_room").show();
        break;
        //从选择医生返回到选择二级科室
        case "chose_doctor":
         //******************将选择卡类型的图片更换并且字体更换//
            $(".choseDoc").css({'color':'#333','font-weight':'normal'});
            $(".choseDocDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
            //同样操作于选择科室
            $(".choseRoom").css({'color':'#f57336','font-weight':'bold'});
            $(".choseRoomDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
            //*******************操作结束************************//
            $(".mtnum2").hide();
            $("#op_now").val("erjikeshi_show");
            daojishi(60);
            $("#confirm").css({"visibility":"hidden"});
            $("#fanhui").css({"visibility":"visible"});
            $("#tuichu").css({"visibility":"visible"});
            $(".mtnum2").hide();
            $(".chose_room2").show();
        break;
        // 输入身份证号,返回到选择卡类型页面
        case "sfz_put_sfz":
            $("#jz_card_no").val("");
            $(".jiuzhen_op_area_sfz").hide();
            $(".chose_card_type_area").show();
            $("#confirm").css({"visibility":"hidden"});
            $("#fanhui").css({"visibility":"hidden"});
            $("#op_now").val("guahao_chose_card_type");
            //window.external.send(1,4); //关闭灯带
            daojishi(60);
        break;
        // 从插入医保卡界面返回到选择卡类型界面
        case "chose_yb_card":
            //window.external.send(1,4); //关闭灯带
            //window.external.MoveOutCard();
            //window.external.DisAllowCardIn();
            // for(var key in interval3){
            //     clearTimeout(interval3[key]);
            // }
            $(".yb_op_area").hide();;
            $(".chose_card_type_area").show();
            $("#confirm").css({"visibility":"hidden"});
            $("#fanhui").css({"visibility":"hidden"});
            $("#tuichu").css({"visibility":"visible"});
            $("#op_now").val("guahao_chose_card_type");
            daojishi(60);
        break;
        // 从挂号确认信息页面返回到选择医生页面
        case "guahao_xinxi_queren":
            //******************将选择卡类型的图片更换并且字体更换//
            $(".confirmInfo").css({'color':'#333','font-weight':'normal'});
            $(".confirmInfoDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
            //同样操作于选择科室
            $(".choseDoc").css({'color':'#f57336','font-weight':'bold'});
            $(".choseDocDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
            //*******************操作结束************************//
            $(".mtnum2").hide();
            $(".chose_doctor").show();
            $("#confirm").css({"visibility":"hidden"});
            $("#op_now").val("chose_doctor");
            daojishi(60);
        break;

        // 从选择支付方式返回到专家号，普通号信息确认页面
        case "chose_pay_type":
        //******************将选择卡类型的图片更换并且字体更换//
            $(".chosePayType").css({'color':'#333','font-weight':'normal'});
            $(".chosePayTypeDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
            //同样操作于选择科室
            $(".confirmInfo").css({'color':'#f57336','font-weight':'bold'});
            $(".confirmInfoDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
            //*******************操作结束************************//
            daojishi(60)
            $(".mtnum2").hide();
            $(".chose_pay_type_area").hide();
            $(".guahao_queren").show();
            $("#op_now").val("guahao_xinxi_queren");
           /* if($("#hao_type").val()=="1"){
                $("#op_now").val("zj_guahao_xinxi_queren");
                writeLog("专家出诊信息确认");
            }else{
                $("#op_now").val("pt_guahao_xinxi_queren");
                writeLog("普通出诊信息确认");
            }*/
            $("#fanhui").css({"visibility":"visible"});
            $("#tuichu").css({"visibility":"visible"});
            $("#confirm").css({"visibility":"visible"});
            $(".guahao_queren .p1 .uname").text($("#patient_name").val());
            $(".guahao_queren .p2 .card_no").text($("#card_no").val());
            $(".guahao_queren .p6 .hbmc").text($("#hbmc").val());
            $(".guahao_queren .p6 .ksmc").text($("#ksmc").val());
            $(".guahao_queren .p6 .ysxm").text($("#ysxm").val());
            $(".guahao_queren .p6 .ysfwf").text($("#ysfwf").val());
            $(".guahao_queren .p6 .czsj").text($("#czsj").val());
            $(".guahao_queren .p6 .charge_total").text($("#charge_total").val());
            $(".guahao_queren .p6 .cash").text($("#cash").val());
            $(".guahao_queren .p6 .ybzf").text($("#tczf").val());
        break;
    }
});
})

/********交易记录*******/
function pay_record(business_type,card_type,pat_card_no,id_card_no,pat_id,pat_name,pat_sex,charge_total,cash,zhzf,tczf,trading_state,his_state,trade_no,zzj_id,stream_no,pay_type){
        //alert(dept_code+","+dept_name+","+doctor_code+","+doctor_name+","+card_type+","+business_type+","+pat_card_no+","+healthcare_card_no+","+id_card_no+","+pat_id+","+pat_name+","+pat_sex+","+charge_total+","+cash+","+zhzf+","+tczf+","+trading_state+","+healthcare_card_trade_state+","+his_state+","+bank_card_id+","+reg_info+","+trade_no+","+zzj_id+","+stream_no);
        var params = {"business_type":business_type,"card_type":card_type,"pat_card_no":pat_card_no,"id_card_no":id_card_no,"pat_id":pat_id,"pat_name":pat_name,"pat_sex":pat_sex,"charge_total":charge_total,"cash":cash,"zhzf":zhzf,"tczf":tczf,"trading_state":trading_state,"his_state":his_state,"trade_no":trade_no,"zzj_id":zzj_id,"stream_no":stream_no,"pay_type":pay_type};
        //alert("开始记录交易数据");
        $.ajax({
            url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/witeJyRecordToDataBase",
            type:'post',
            dataType:'json',
            data:params,
            success:function(data){

            }
        })
}
/**
*日志记录函数
**/
function writeLog(logtxt,logtype){
    // var params = {"log_txt":logtxt,"log_type":logtype,"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#patient_id").val(),"direction":"操作步骤","op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val()};
    // $.ajax({
    //         url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/writeLogs",
    //         type:'post',
    //         dataType:'json',
    //         data:params,
    //         success:function(data){}
    // })
}
//读取就诊卡
function getIcCardNo(){
    // var IcCardNo = window.external.DcrfGetSecBlockData();
    // var IcCardNo = '91224916';
    var reg = /^[0-9]*$/;
    var IcCardNo = '009';
    var success_CardNo =reg.test(IcCardNo);
    if(IcCardNo!="" && success_CardNo)
    {
       // writeLog("就诊卡号读取成功");
        for(var key in interval3)
        {
            clearInterval(interval3[key]);
        }
        // 把门诊号放入隐藏域中
        $("#card_no").val(IcCardNo);
        $("#confirm").trigger("click");
    }else
    {

    }
}
//读取身份证方法
function getCardNoS(){
    //var card_no =window.external.GetCardNo();
    // var card_no2 = window.external.sfz_card_read();
    card_no2 = '2';
    if(card_no2!=""){
        if(card_no2!="请重放身份证" && card_no2!="开usb失败" && card_no2!="读卡失败"){
            // writeLog("二代身份证读卡器获取身份证信息成功");
            var sfz = card_no2.split(",");
            for(var key in interval3)
            {
                clearInterval(interval3[key]);
            }
            // var s0 = sfz[0];//姓名
            // var s1 = sfz[1];//性别
            // var s2 = sfz[2];//民族
            // var s3 = sfz[3];//出生日期
            // var s4 = sfz[4];//住址
            // var s5 = sfz[5];//身份证号
            // var s6 = sfz[6];//签发机关
            // var s7 = sfz[7];//有效期开始日期
            // var s8 = sfz[8];//有效期截止日期else
            // 身份证作为卡号放入输入框中
            $("#jz_card_no_sfz").val('222');
            $("#confirm").trigger("click");
        }else
        {
            $(".put_shenfenzheng .tips").html(card_no2);
        }
    }
}
// 定时跳转页面
function jumpurl(){
    window.location.href="/rw2.0_payment/index.php/ZiZhu/JianKa/index/zzj_id/"+zzj_id;
}
/*****************获取医保患者信息************************/
function getYibaoInfo(){
    var flag ="";
    // flag = window.external.ReadStatus();//获取读卡器状态, 有卡返回true, 无卡返回false
    flag = true;
    if(flag){
        for(var key in interval3){
            clearTimeout(interval3[key]);
        }
        setTimeout(function(){
            // $(".yb_op_area .tips").show().text('医保患者数据读取中,请稍后...');
            // 查询医保卡信息
            var params = {"op_code":$("#op_code").val(),"card_code":$("#card_code").val(),"zzj_id":$("#zzj_id").val()};
            $.ajax({
                url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/yibao_getPatInfo",
                type:'post',
                dataType:'json',
                data:params,
                beforeSend:function(){
                    index_load = layer.msg('患者数据查询中,请稍候...', {icon: 16,time:20000});
                    $("#fanhui").css({"visibility":"hidden"});
                    $("#confirm").css({"visibility":"hidden"});
                    $("#tuichu").css({"visibility":"hidden"});
                },
                success:function(data){
                    layer.close(index_load);
                    // window.external.send(1,4); //关闭灯带
                    $(".yb_op_area .tips").show().text('');
                    if(data["head"]["succflag"]=="1"){
            //******************将选择卡类型的图片更换并且字体更换//
            $(".choseCard").css({'color':'#333','font-weight':'normal'});
            $(".choseCardDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
            //同样操作于选择科室
            $(".choseRoom").css({'color':'#f57336','font-weight':'bold'});
            $(".choseRoomDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
            //*******************操作结束************************//
                        // 获取医保状态
                        daojishi(60);
                        var sfyzjg = data["body"]["sfyzjg"];
                        var fylb = data["body"]["fylb"];
                        var sfy  =  sfyzjg.slice(0,1);
                        var addr =   data["body"]["addr"];
                        var phone =   data["body"]["phone"];
                        var tsbzdm = data["body"]["tsbzdm"];      //工伤病人
                        var tsb = tsbzdm.slice(0,1);

                        $("#gsbr").val(tsb);   //工伤病人
                        if(sfy == "1"){
                            var sfy  =  sfyzjg.slice(0,2);
                        }
                        // 0 不在红名单  12 不在定点医院  8B 城乡持卡    8A征地超转  85 老年医保   86 无业居民
                        if(sfy == "0" ){
                            layer.alert('温馨提示：不在红名单，在本院费用按医保外处理', {
                                icon: 1,
                                skin: 'layer-ext-moon',
                                time:5000,
                            });
                        }else if( sfy == "12"){
                            // layer.alert('温馨提示：不在定点医院，在本院费用按医保外处理', {
                            //     icon: 1,
                            //     skin: 'layer-ext-moon',
                            //     time:5000,
                            // });
                            layer.open({
                                    // title:'温馨提示：如果您需要挂工伤号请到窗口处理,点击确定继续',
                                    type: 1,
                                    skin: 'layui-layer-rim', //加上边框
                                    area: ['500px', '320px'], //宽高
                                    btn: ['确定'],
                                    content: '<div style="margin:20px;font-size:30px;color:red">温馨提示：<br>　　不在定点医院，在本院费用按自费处理</div>'
                                });
                        }else if( fylb == "8B"){
                            /*$(".chose_hao_type .tishi").text();
                            layer.alert('温馨提示：'+data["body"]["Msg"], {
                                icon: 1,
                                skin: 'layer-ext-moon',
                                time:5000,
                            });*/
                        }else if(fylb == "85" || fylb == "86"){
                            layer.alert('温馨提示：无保障老年人或无业人员首次门诊就诊必须到一级及以下(含社区)医院,否则全部费用按医保外处理', {
                                icon: 1,
                                skin: 'layer-ext-moon',
                                time:5000,
                            });
                        }else if(fylb == "8A" ){
                            /*layer.alert(data["body"]["Msg"], {
                                icon: 1,
                                skin: 'layer-ext-moon',
                                time:5000,
                            });  */
                        }else if(tsb == "1"){

                                    layer.open({
                                    // title:'温馨提示：如果您需要挂工伤号请到窗口处理,点击确定继续',
                                    type: 1,
                                    skin: 'layui-layer-rim', //加上边框
                                    area: ['500px', '320px'], //宽高
                                    btn: ['确定'],
                                    content: '<div style="margin:20px;font-size:30px;color:red">温馨提示：<br>　　如果您需要挂工伤号请到窗口办理,挂医保号请点击确定</div>'
                                });


                        }
                        var pat_id = data["body"]['medid'];//患者ID
                        var pat_name = data["body"]["name"];//患者姓名
                        var patient_sex = data["body"]['sex'];//患者性别
                        if(patient_sex == "1"){
                           var   sex = '男';
                        }else{
                            var  sex = '女';
                        }
                        var patient_age   =  data["body"]['age'];  //患者年龄
                        var medcardno  = data["body"]["medcardno"]; //医保卡号
                        // var personcount =  data['body']['Personcount'];
                        // alert(personcount);
                        // $("#personcount").val(personcount);
                        $("#card_no").val(medcardno);  //将医保卡号存在隐藏域
                        $("#patient_name").val(pat_name); // 病人姓名放入隐藏域
                        $("#patient_id").val(pat_id); // 病人ID放入隐藏域
                        $("#patient_sex").val(patient_sex); // 病人性别放入隐藏域
                        $("#patient_age").val(patient_age); //病人性别放入隐藏域
                        $(".mtnum2").hide();
                        $(".chose_room").show();
                        $("#confirm").css({"visibility":"hidden"});
                        $("#fanhui").css({"visibility":"hidden"});
                        $("#tuichu").css({"visibility":"visible"});
                        $(".jz_name").text($("#patient_name").val());
                        $("#op_now").val("yijikeshi_show");
                        if(data['yijikeshi']!=null){
                            var sub_html = "";
                            var sub_list= data['yijikeshi'];
                            var page_ks=1;
                            pagedata_ks = data.yijikeshi;
                            showdata_ks(page_ks=1);
                        }else{
                            daojishi(20);
                            $(".chose_room .page").css({"visibility":"hidden"});
                            $(".chose_room .cuowu").html("无可以挂号的科室");
                        }
                    }else{
                        /*if(data["head"]["retcode"]=="1101"){
                            $("#op_now").val("jianka_xz_yibao_input_phone");
                            // 选择卡类型隐藏
                            $(".mtnum2").hide();
                            $(".yb_op_area").hide();
                            $(".jianka_input_phone").show();
                            $("#confirm").css({"visibility":"visible"});
                            $("#fanhui").css({"visibility":"visible"});
                            $("#tuichu").css({"visibility":"visible"});
                            $(".pay_success").show();
                            $(".pay_success h3").text("医保卡未建档，请先建档,点击确定跳转到建档页面");
                            daojishi(60);
                        }else{
                            window.external.MoveOutCard();
                            window.external.DisAllowCardIn();
                            $(".mtnum2").hide();
                            $(".pay_success").show();
                            $("#confirm").css({"visibility":"hidden"});
                            $("#fanhui").css({"visibility":"hidden"});
                            $("#tuichu").css({"visibility":"visible"});
                            daojishi(10);
                            $(".pay_success h3").text("医保卡识别不成功");
                        }*/
                        if(data['head']['retmsg'] == "识别卡信息有效性不成功(\\)!"){
                            layer.close(index_load);
                            $(".mtnum2").hide();
                            $(".yb_op_area").show();
                            daojishi(60);
                            $("#fanhui").css({"visibility":"visible"});
                            $("#confirm").css({"visibility":"hidden"});
                            $("#tuichu").css({"visibility":"visible"});
                            $(".yb_op_area .tips").html("");
                            $(".yb_op_area .tips").html("识别卡信息有效性不成功(\\)!，请重新插卡！");
                            window.external.MoveOutCard();
                            interval = setInterval(getYibaoInfo, "1000");
                            interval3.push(interval);
                        }else if(data['head']['retmsg'] == "该诊疗卡未建档!"){
                            $("#jianka_phone").val("");
                            $(".jianka_phone_area .tips_jianka").text("");
                            $("#op_now").val("jianka_xz_yibao_input_phone");
                            $(".mtnum2").hide();
                            $(".yb_op_area").hide();
                            $(".jianka_input_phone").show();
                            daojishi(120);
                            key_value = "";
                            $("#fanhui").css({"visibility":"hidden"});
                            $("#confirm").css({"visibility":"visible"});
                            $("#tuichu").css({"visibility":"visible"});
                        }else{
                            window.external.MoveOutCard();
                            window.external.DisAllowCardIn();
                            $(".mtnum2").hide();
                            $(".pay_success").show();
                            $("#confirm").css({"visibility":"hidden"});
                            $("#fanhui").css({"visibility":"hidden"});
                            $("#tuichu").css({"visibility":"visible"});
                            daojishi(10);
                            $(".pay_success h3").text("医保卡识别不成功");
                        }
                    }
                }
            });
        }, 1000);

    }
}
/*****************显示一级科室分页开始************************/
function showdata_ks(page_ks){
    var sub_html = '';
    var pagesize_ks = 16;//每页显示记录数
    var totalpage_ks = Math.ceil(pagedata_ks.length/pagesize_ks);//总页数
    var end = (page_ks * pagesize_ks) - 1;
    var start = end - pagesize_ks + 1;
    //如果医生的总数小于4,不显示上下页的按钮
     // alert(pagedata_ks.length);
    if(page_ks >= totalpage_ks){
        $(".next_ks").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/no.png)");
    }else{
        $(".next_ks").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/ft.png)");
        $(".next_ks").css("color","#FFF");
    }

    if(page_ks==1){
        $(".prev_ks").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/no.png)");
    }else{
        $(".prev_ks").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/ft.png)");
        $(".prev_ks").css("color","#FFF");
    }

    if(pagedata_ks.length<=20){
        $(".prev_ks").hide();
        $(".next_ks").hide();
    }else{
        $(".prev_ks").show();
        $(".next_ks").show();
    }

    $.each(pagedata_ks, function (key, val){
        // alert(val['departmentname']);
        if (key >= start && key <= end){
            sub_html+="<span class='chose_unit'  id ='chose_unit'   ksdm='"+val['departmentid']+"' unit_name="+val['departmentname']+" >"+val['departmentname']+"</span>";
        }
    });

    if(sub_html){

        $("#sub_list").html(sub_html);
        $(".chose_room .total_page").html("共"+totalpage_ks+"页/第"+page_ks+"页")
    }else{
        //biaoji
        return false;
    }
}

//下一页
$(document).on("click",".next_ks",function(){
    page_ks++;
    // countDown(60);
    if(showdata_ks(page_ks)===false)
    {
        page_ks--;
    }
});
//上一页
$(document).on("click",".prev_ks",function(){
     // countDown(60);
    if (page_ks == 1)
    {
        return false;
    }
    page_ks--;
    showdata_ks(page_ks);
});
/*****************显示一级科室分页结束************************/

/*****************显示二级科室分页开始************************/
function showdata_ks2(page_ks){
    var sub_html2 = '';
    var pagesize_ks = 16;//每页显示记录数
    var totalpage_ks = Math.ceil(pagedata_ks2.length/pagesize_ks);//总页数
    var end = (page_ks * pagesize_ks) - 1;
    var start = end - pagesize_ks + 1;
    //如果医生的总数小于4,不显示上下页的按钮
     // alert(pagedata_ks.length);
    if(page_ks >= totalpage_ks){
        $(".next_ks2").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/no.png)");
    }else{
        $(".next_ks2").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/ft.png)");
        $(".next_ks2").css("color","#FFF");
    }

    if(page_ks==1){
        $(".prev_ks2").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/no.png)");
    }else{
        $(".prev_ks2").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/ft.png)");
        $(".prev_ks2").css("color","#FFF");
    }

    if(pagedata_ks2.length<=12){
        $(".prev_ks2").hide();
        $(".next_ks2").hide();
    }else{
        $(".prev_ks2").show();
        $(".next_ks2").show();
    }

    $.each(pagedata_ks2, function (key, val){
        // alert(val['departmentname']);
        if (key >= start && key <= end){
            sub_html2+="<span class='chose_unit2'  id ='chose_unit2'   ksdm='"+val['departmentid']+"' unit_name="+val['departmentname']+" >"+val['departmentname']+"</span>";

        }
    });

    if(sub_html2){

        $("#sub_list2").html(sub_html2);
        $(".chose_room2 .total_page2").html("共"+totalpage_ks+"页/第"+page_ks+"页")
    }else{
        //biaoji
        return false;
    }
}

//下一页
$(document).on("click",".next_ks2",function(){
    page_ks++;
    // countDown(60);
    if(showdata_ks2(page_ks)===false)
    {
        page_ks--;
    }
});
//上一页
$(document).on("click",".prev_ks2",function(){
     // countDown(60);
    if (page_ks == 1)
    {
        return false;
    }
    page_ks--;
    showdata_ks2(page_ks);
});
/*****************显示二级科室分页结束************************/
//查询二级科室
$(document).on("click",".chose_unit",function(){
    writeLog("查询二级科室");
    var ksdm = $(this).attr("ksdm");
    $("#ksdm").val(ksdm);
    var sex = $("#patient_sex").val();
    var age =  $("#patient_age").val();
    // 儿科大于16岁 不能挂
    if( age >16 && ksdm =="22"){

        layer.alert('温馨提示：年龄大于16岁不能挂儿科', {
            icon: 1,
            skin: 'layer-ext-moon',
            time:5000,
        });
        return;
    }
     // 老年科年龄小于60岁 不能挂
    if( age <60 && ksdm =="9"){

        layer.alert('温馨提示：年龄小于60岁不能挂老年医学科', {
            icon: 1,
            skin: 'layer-ext-moon',
            time:5000,
        });
        return;
    }
     // 男性不能挂  产科 21002  妇产科 210  妇科21001
    if( (sex =="1"&& ksdm =="21")){

        layer.alert('温馨提示：男性不能挂女性科室', {
            icon: 1,
            skin: 'layer-ext-moon',
            time:5000,
        });
        return;
    }
    var index_load = "";
    var params = {"card_no":$("#card_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"patient_id":$("#patient_id").val(),"ksdm":$("#ksdm").val()," regclass":$("#hao_type").val()};
    $.ajax({
        url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/erjikeshi_list",
        type:'post',
        dataType:'json',
        data:params,
        beforSend:function(){
            index_load = layer.msg('可预约号源信息查询中,请稍候...', {icon: 16,time:20000});
            $("#fanhui").css({"visibility":"hidden"});
            $("#confirm").css({"visibility":"hidden"});
            $("#tuichu").css({"visibility":"hidden"});
        },
        success:function(data){
            daojishi(60);
            layer.close(index_load);
            $(".mtnum2").hide();
            $(".chose_room2").show();
            $("#confirm").css({"visibility":"hidden"});
            $("#fanhui").css({"visibility":"visible"});
            $("#tuichu").css({"visibility":"visible"});
            $("#op_now").val("erjikeshi_show");
            if(data["head"]["succflag"] == "1"){
                if(data['erjikeshi'].length !=""){
                    var sub_html2 = "";
                    var sub_list2= data['erjikeshi'];
                    var page_ks=1;
                    pagedata_ks2 = data.erjikeshi;
                    showdata_ks2(page_ks=1);
                }else{
                    $(".chose_room2 .page2").css({"visibility":"hidden"});
                    $(".chose_room2 .cuowu2").html("无可以挂号的科室");
                }
            }else{
                daojishi(20);
                $(".chose_room2 .page2").css({"visibility":"hidden"});
                $(".chose_room2 .cuowu2").html("无可以挂号的科室");
            }

        }
    })
});
//查询医生排班
$(document).on("click",".chose_unit2",function(){
    var ksdm = $(this).attr("ksdm");
    $("#ksdm").val(ksdm);
    var sex = $("#patient_sex").val();
    var age =  $("#patient_age").val();
     // 儿科大于16岁 不能挂
    if( age >16 && ksdm =="204"){
        layer.alert('温馨提示：年龄大于16岁不能挂儿科', {
            icon: 1,
            skin: 'layer-ext-moon',
            time:5000,
        });
        return;
    }
    // 男性不能挂  产科 21002  妇产科 210  妇科21001
    if( (sex =="1"&& ksdm =="24303")|| (sex =="1"&& ksdm =="21001")||(sex =="1"&& ksdm =="21002")){
        layer.alert('温馨提示：男性不能挂女性科室', {
            icon: 1,
            skin: 'layer-ext-moon',
            time:5000,
        });
        return;
    }
    var index_load = "";
    var params = {"card_no":$("#card_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"patient_id":$("#patient_id").val(),"ksdm":$("#ksdm").val()," regclass":"9"};
    $.ajax({
        url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/get_paiban_list",
        type:'post',
        dataType:'json',
        data:params,
        beforSend:function(){
            index_load = layer.msg('可预约号源信息查询中,请稍候...', {icon: 16,time:20000});
            $("#fanhui").css({"visibility":"hidden"});
            $("#confirm").css({"visibility":"hidden"});
            $("#tuichu").css({"visibility":"hidden"});
        },
        success:function(data){
            daojishi(60);
            layer.close(index_load);
            $(".mtnum2").hide();
            $(".doctor_list").show();
            $(".chose_doctor").show();
            $("#op_now").val("chose_doctor");
            if(data["head"]["succflag"]=="1"){
            //******************将选择卡类型的图片更换并且字体更换//
            $(".choseRoom").css({'color':'#333','font-weight':'normal'});
            $(".choseRoomDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
            //同样操作于选择科室
            $(".choseDoc").css({'color':'#f57336','font-weight':'bold'});
            $(".choseDocDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
            //*******************操作结束************************//
                $("#confirm").css({"visibility":"hidden"});
                var doctor = data['doctor'];
                if(doctor.length!=0){
                    $(".chose_doctor .cuowu").html("");
                    $(".chose_doctor .cuowu").hide();
                    var html="";
                    var sub_list= data["doctor"];
                    var page=1;
                    pagedata = data.doctor;
                    showdata(page=1);
                }else{
                    $("#confirm").css({"visibility":"hidden"});
                    $("#fanhui").css({"visibility":"visible"});
                    $(".chose_doctor .page").css({"visibility":"hidden"});
                    $(".chose_doctor .cuowu").show();
                    $(".chose_doctor .cuowu").html("暂无医生排班信息");
                    $(".doctor_list ").html("");
                    daojishi(20);
                }
            }else{
                //查his失败
                $("#confirm").css({"visibility":"hidden"});
                $("#fanhui").css({"visibility":"visible"});
                $(".chose_doctor .page").css({"visibility":"hidden"});
                $(".chose_doctor .cuowu").show();
                $(".chose_doctor .cuowu").html(data["head"]["retmsg"]);
                $(".doctor_list ").html("");
               /* $(".doctor_list ").html(data["head"]["retmsg"]);
                $('.doctor_list ').css("font-size","60px");
                $('.doctor_list ').css("text-align","center");
                $('.doctor_list ').css("color","red");*/
                daojishi(20);
            }
        }
    });

});

/*****************挂号医生分页展示方法开始************************/
function showdata(page){
    var html = '';
    var pagesize = 5;//每页显示记录数

    var totalpage = Math.ceil(pagedata.length/pagesize);//总页数
    var end = (page * pagesize) - 1;
    var start = end - pagesize + 1;
    //如果医生的总数小于4,不显示上下页的按钮
      // alert(page);
    if(page == totalpage){
        $(".next").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/no.png)");
    }else{
        $(".next").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/ft.png)");
        $(".next").css("color","#FFF");
    }

    if(page==1){
        $(".prev").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/no.png)");
    }else{
        $(".prev").css("background","url(/rw2.0_payment/Public/jinriguahao/img/zuixin/ft.png)");
        $(".prev").css("color","#FFF");
    }

    if(pagedata.length<=4){
        $(".prev").hide();
        $(".next").hide();
    }else{
        $(".prev").show();
        $(".next").show();
    }

    $.each(pagedata, function (key, val){
        if (key >= start && key <= end){
            //根据regclass挂号类别判断号别，0急诊 1普通，2主任，3副主任，4知名专家
            if(val["regclass"]=='1'){
                var hbmc = "普通";
            }else if(val["regclass"]=='3'){
                var hbmc = "副主任";
            }else if(val["regclass"]=='2'){
                var hbmc = "主任";
            }else if(val["regclass"]=='4'){
                var hbmc = "知名专家";
            }else if(val["docrank"]=='0'){
                var hbmc = "急诊";
            }
            if(val['restnum']>0)
            {
                //此时有号
                if(val['streak'] == '上午')
                {
                    html+="<div class='swyh' id = 'doctor_list_pt'><ul><li style='color:red'>科室:"+val["departmentname"]+"</li><li >日期:"+val['meddate']+"</li><li>挂号类型:"+val["docrank"]+"</li><li>专家姓名:"+val["docname"]+"</li><li >医事服务费："+val['regamt']+"</li><li>剩余号源:"+val['restnum']+"</li></ul><span style='margin-top:70px;' class='sure_ghao' docname='"+val['docname']+"' docno='"+val['docno']+"'departmentname='"+val['departmentname']+"' departmentid='"+val['departmentid']+"'streak='"+val['streak']+"' orderid='"+val['orderid']+"'  preid='"+val['preid']+"' meddate='"+val['meddate']+"' regamt='"+val['regamt']+"' docrank='"+val['docrank']+"' regclass='"+val['regclass']+"'>挂号</span></div>";
                }else
                {
                    html+="<div class='xwyh' id = 'doctor_list_pt'><ul><li style='color:red'>科室:"+val["departmentname"]+"</li><li >日期:"+val['meddate']+"</li><li>挂号类型:"+val["docrank"]+"</li><li>专家姓名:"+val["docname"]+"</li><li >医事服务费："+val['regamt']+"</li><li>剩余号源:"+val['restnum']+"</li></ul><span style='margin-top:70px;' class='sure_ghao' docname='"+val['docname']+"' docno='"+val['docno']+"'departmentname='"+val['departmentname']+"' departmentid='"+val['departmentid']+"'streak='"+val['streak']+"' orderid='"+val['orderid']+"'  preid='"+val['preid']+"' meddate='"+val['meddate']+"' regamt='"+val['regamt']+"' docrank='"+val['docrank']+"' regclass='"+val['regclass']+"'>挂号</span></div>";
                }
            }else
            {
                if(val['streak'] == '上午')
                {
                    html+="<div class='swmh' id = 'doctor_list_pt'><ul><li style='color:red'>科室:"+val["departmentname"]+"</li><li >日期:"+val['meddate']+"</li><li>挂号类型:"+val["docrank"]+"</li><li>专家姓名:"+val["docname"]+"</li><li >医事服务费："+val['regamt']+"</li><li>剩余号源:"+val['restnum']+"</li></ul><span style='margin-top:70px;' class='disabled' docname='"+val['docname']+"' docno='"+val['docno']+"'departmentname='"+val['departmentname']+"' departmentid='"+val['departmentid']+"'streak='"+val['streak']+"' orderid='"+val['orderid']+"'  preid='"+val['preid']+"' meddate='"+val['meddate']+"' regamt='"+val['regamt']+"' docrank='"+val['docrank']+"' regclass='"+val['regclass']+"'>已挂完</span></div>";
                }else
                {
                   html+="<div class='xwmh' id = 'doctor_list_pt'><ul><li style='color:red'>科室:"+val["departmentname"]+"</li><li >日期:"+val['meddate']+"</li><li>挂号类型:"+val["docrank"]+"</li><li>专家姓名:"+val["docname"]+"</li><li >医事服务费："+val['regamt']+"</li><li>剩余号源:"+val['restnum']+"</li></ul><span style='margin-top:70px;' class='disabled' docname='"+val['docname']+"' docno='"+val['docno']+"'departmentname='"+val['departmentname']+"' departmentid='"+val['departmentid']+"'streak='"+val['streak']+"' orderid='"+val['orderid']+"'  preid='"+val['preid']+"' meddate='"+val['meddate']+"' regamt='"+val['regamt']+"' docrank='"+val['docrank']+"' regclass='"+val['regclass']+"'>已挂完</span></div>";
                }
            }
        }
    });
    if(html){
        $(".chose_doctor .doctor_list").html(html);
        $(".chose_doctor .total_page").html("共"+totalpage+"页/第"+page+"页")
    }else{
        //biaoji
        return false;
    }
 }
//下一页

$(document).on("click",".next",function(){
    page++;
   daojishi(60);
    if(showdata(page)===false)
    {
        page--;
    }

});
//上一页
$(document).on("click",".prev",function(){
    daojishi(60);
    if (page == 1)
    {
        return false;
    }
    page--;
    showdata(page);
});
/*****************挂号医生分页展示方法结束************************/

//选择医生,显示确认挂号信息
$(document).on("click",".sure_ghao ",function(){
   // alert("显示确认挂号信息");
    var index_load = "";
    index_load = layer.msg('划价分解中,请稍后...', {icon: 16,time:20000});
    //挂号信息确认
    var medpay  = $("#medflag").val();
    var meddate = $(this).attr("meddate");//日期
    var sxw = $(this).attr("streak");//上下午代码
    var orderid = $(this).attr("orderid"); //排班ID
    //var seqnum = $(this).attr("seqnum"); //顺序号
    var preid = $(this).attr("preid"); //预约单号
    var apm = $(this).attr("streak");//上下午名称
    $("#apm").val(apm);
    var ksmc = $(this).attr("departmentname");//科室名称
    var ksdm = $(this).attr("departmentid");
    var ysxm = $(this).attr("docname");//医生姓名
    var ysdm = $(this).attr("docno");//医生代码
    var ysfwf = $(this).attr("regamt");//医事服务费
    var docrank = $(this).attr("docrank");//医生级别 docrank有五种，普通，副主任，主任，知名专家，特需门诊
    var regclass = $(this).attr("regclass");//根据regclass挂号类别判断号别，0急诊 1普通，2主任，3副主任，4知名专家
    $("#hao_type").val(regclass);
    $("#ksmc").val(ksmc);
    $("#ysxm").val(ysxm);
    $("#ysfwf").val(ysfwf);
    $("#meddate").val(meddate); //就诊日期
    $("#orderid").val(orderid); //排班ID
    $("#ksdm").val(ksdm);  //科室代码
    $("#ysdm").val(ysdm);
    //$("#seqnum").val(seqnum);//顺序号
    // alert(sxw);
    var czsj =  meddate+" "+sxw; //就诊时间
    $("#czsj").val(czsj);
    $("#sxw").val(sxw);
    $("#hbbm").val(hbbm);
    $("#hbmc").val(docrank);//医生级别 docrank有五种，普通，副主任，主任，知名专家，特需门诊
   /* // 放入隐藏域
    $("#yyid").val(yyid);*/
    // alert($("#card_code").val());
    //  alert($("#czsj").val());
    switch($("#card_code").val()){
        case "3"://就诊卡
            var params ={"card_no":$("#card_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"patient_id":$("#patient_id").val(),"streak":sxw,"orderid":orderid,"preid":preid,"meddate":meddate,"medpay":medpay};
            $.ajax({
                url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/ic_guahao_huajia",
                type:'post',
                dataType:'json',
                data:params,
                beforSend:function(){
                    index_load = layer.msg('划价分解中,请稍后...', {icon: 16,time:20000});
                    $("#fanhui").css({"visibility":"hidden"});
                    $("#confirm").css({"visibility":"hidden"});
                    $("#tuichu").css({"visibility":"hidden"});
                },
                success:function(data){
                    daojishi(60000);
                    layer.close(index_load);
                    if(data['head']['succflag']=="1"){
                        //******************将选择卡类型的图片更换并且字体更换//
                        $(".choseDoc").css({'color':'#333','font-weight':'normal'});
                        $(".choseDocDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
                        //同样操作于选择科室
                        $(".confirmInfo").css({'color':'#f57336','font-weight':'bold'});
                        $(".confirmInfoDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
                        //*******************操作结束************************//
                        $("#fanhui").css({"visibility":"visible"});
                        $("#tuichu").css({"visibility":"visible"});
                        $("#confirm").css({"visibility":"visible"});
                        $(".mtnum2").hide();
                        $(".chose_time").hide();
                        $(".guahao_queren").show();
                        $("#op_now").val("guahao_xinxi_queren");
                        $(".guahao_queren .p1 .uname").text($("#patient_name").val());
                        $(".guahao_queren .p2 .card_no").text($("#card_no").val());
                        $(".guahao_queren .p6 .hbmc").text($("#hbmc").val());
                        $(".guahao_queren .p6 .ksmc").text($("#ksmc").val());
                        $(".guahao_queren .p6 .ysxm").text($("#ysxm").val());
                        $(".guahao_queren .p6 .ysfwf").text($("#ysfwf").val());
                        $(".guahao_queren .p6 .czsj").text($("#czsj").val());
                        var charge_total = data['body']['chargetamt'];//总金额
                        var cash = data['body']['personamt'];//个人支付
                        // var zhzf = data['body']['zhzf'];//账户支付
                        var tczf = data['body']['ybzf'];//医保支付
                        var rctpno =  data['body']['rctpno']; //收据号

                        $("#charge_total").val(charge_total);//将自费加医保总金额放入隐藏域
                        $("#cash").val(cash);//将自费金额放入隐藏域
                        // $("#zhzf").val(zhzf);//将丈夫支付金额放入隐藏域
                        $("#tczf").val(tczf);//将医保支付金额放入隐藏
                        $("#rctpno").val(rctpno);//将收据号放入隐藏域
                        $(".guahao_queren .p6 .charge_total").text($("#charge_total").val());
                        $(".guahao_queren .p6 .cash").text($("#cash").val());
                        $(".guahao_queren .p6 .ybzf").text($("#tczf").val());


                    }else{
                        $("#fanhui").css({"visibility":"visible"});
                        $("#tuichu").css({"visibility":"visible"});
                        $("#confirm").css({"visibility":"hidden"});
                        // $("#op_now").val("guahao_xinxi_queren");
                        /*if($("#hao_type").val()=="2"){
                            $("#op_now").val("guahao_xinxi_queren");
                            writeLog("专家出诊信息确认");
                        }else{
                            $("#op_now").val("guahao_xinxi_queren");
                            writeLog("普通出诊信息确认");
                        }*/
                        layer.alert(data['head']['retmsg'],{
                            icon: 6,
                            skin: 'layer-ext-moon',
                            time:5000,
                        });

                    }

                }
            });
        break;
        case "2"://身份证
            var params ={"card_no":$("#card_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"patient_id":$("#patient_id").val(),"streak":sxw,"orderid":orderid,"preid":preid,"meddate":meddate,"medpay":medpay};
            $.ajax({
                url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/sfz_guahao_huajia",
                type:'post',
                dataType:'json',
                data:params,
                beforSend:function(){
                    index_load = layer.msg('划价分解中,请稍后...', {icon: 16,time:20000});
                    $("#fanhui").css({"visibility":"hidden"});
                    $("#confirm").css({"visibility":"hidden"});
                    $("#tuichu").css({"visibility":"hidden"});
                },
                success:function(data){
                    daojishi(60);
                    layer.close(index_load);
                    if(data['head']['succflag']=="1"){
                        //******************将选择卡类型的图片更换并且字体更换//
                        $(".choseDoc").css({'color':'#333','font-weight':'normal'});
                        $(".choseDocDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
                        //同样操作于选择科室
                        $(".confirmInfo").css({'color':'#f57336','font-weight':'bold'});
                        $(".confirmInfoDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
                        //*******************操作结束************************//
                        $("#fanhui").css({"visibility":"visible"});
                        $("#tuichu").css({"visibility":"visible"});
                        $("#confirm").css({"visibility":"visible"});
                        $(".mtnum2").hide();
                        $(".chose_time").hide();
                        $(".guahao_queren").show();
                        $("#op_now").val("guahao_xinxi_queren");
                        /*if($("#hao_type").val()=="2"){
                            $("#op_now").val("guahao_xinxi_queren");
                            var hbmc = "专家号";
                            $("#hbmc").val(hbmc);
                            writeLog("专家出诊信息确认");
                        }else{
                            $("#op_now").val("guahao_xinxi_queren");
                            var hbmc = "普通号";
                            $("#hbmc").val(hbmc);
                            $("#ysxm").val("普通号");
                            writeLog("普通出诊信息确认");
                        }*/
                        $(".guahao_queren .p1 .uname").text($("#patient_name").val());
                        $(".guahao_queren .p2 .card_no").text($("#card_no").val());
                        $(".guahao_queren .p6 .hbmc").text($("#hbmc").val());
                        $(".guahao_queren .p6 .ksmc").text($("#ksmc").val());
                        $(".guahao_queren .p6 .ysxm").text($("#ysxm").val());
                        $(".guahao_queren .p6 .ysfwf").text($("#ysfwf").val());
                        $(".guahao_queren .p6 .czsj").text($("#czsj").val());
                        var charge_total = data['body']['chargetamt'];//总金额
                        var cash = data['body']['personamt'];//个人支付
                        // var zhzf = data['body']['zhzf'];//账户支付
                        var tczf = data['body']['ybzf'];//医保支付
                        var rctpno =  data['body']['rctpno']; //收据号

                        $("#charge_total").val(charge_total);//将自费加医保总金额放入隐藏域
                        $("#cash").val(cash);//将自费金额放入隐藏域
                        // $("#zhzf").val(zhzf);//将丈夫支付金额放入隐藏域
                        $("#tczf").val(tczf);//将医保支付金额放入隐藏
                        $("#rctpno").val(rctpno);//将收据号放入隐藏域
                        $(".guahao_queren .p6 .charge_total").text($("#charge_total").val());
                        $(".guahao_queren .p6 .cash").text($("#cash").val());
                        $(".guahao_queren .p6 .ybzf").text($("#tczf").val());


                    }else{
                        $("#fanhui").css({"visibility":"visible"});
                        $("#tuichu").css({"visibility":"visible"});
                        $("#confirm").css({"visibility":"hidden"});
                        // $("#op_now").val("guahao_xinxi_queren");
                        /*if($("#hao_type").val()=="2"){
                            $("#op_now").val("guahao_xinxi_queren");
                            writeLog("专家出诊信息确认");
                        }else{
                            $("#op_now").val("guahao_xinxi_queren");
                            writeLog("普通出诊信息确认");
                        }*/
                        layer.alert(data['head']['retmsg'],{
                            icon: 6,
                            skin: 'layer-ext-moon',
                            time:5000,
                        });

                    }

                }
            });
        break;
        case "1"://医保卡
           var params ={"card_no":$("#card_no").val(),"op_code":$("#op_code").val(),"zzj_id":$("#zzj_id").val(),"card_code":$("#card_code").val(),"patient_id":$("#patient_id").val(),"streak":sxw,"orderid":orderid,"preid":preid,"meddate":meddate,"medpay":medpay,"mzjsfs":$("#gsbr").val()};
            $.ajax({
                url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/yb_guahao_huajia",
                type:'post',
                dataType:'json',
                data:params,
                beforSend:function(){
                    index_load = layer.msg('划价分解中,请稍后...', {icon: 16,time:20000});
                    $("#fanhui").css({"visibility":"hidden"});
                    $("#confirm").css({"visibility":"hidden"});
                    $("#tuichu").css({"visibility":"hidden"});
                },
                success:function(data){
                    daojishi(60);
                    layer.close(index_load);
                    if(data['head']['succflag']=="1"){
                        //******************将选择卡类型的图片更换并且字体更换//
                        $(".choseDoc").css({'color':'#333','font-weight':'normal'});
                        $(".choseDocDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/nochose.png) no-repeat 0px 17px"});
                        //同样操作于选择科室
                        $(".confirmInfo").css({'color':'#f57336','font-weight':'bold'});
                        $(".confirmInfoDaot").css({'background':"url(/rw2.0_payment/Public/jinriguahao/img/zuixin/chosed.png) no-repeat 0px 7px"});
                        //*******************操作结束************************//
                        $("#fanhui").css({"visibility":"visible"});
                        $("#tuichu").css({"visibility":"visible"});
                        $("#confirm").css({"visibility":"visible"});
                        $(".mtnum2").hide();
                        $(".chose_time").hide();
                        $(".guahao_queren").show();
                        $("#op_now").val("guahao_xinxi_queren");
                        /*if($("#hao_type").val()=="2"){
                            $("#op_now").val("zj_guahao_xinxi_queren");
                            var hbmc = "专家号";
                             $("#hbmc").val(hbmc);
                            writeLog("专家出诊信息确认");
                        }else{
                            $("#op_now").val("pt_guahao_xinxi_queren");
                            var hbmc = "普通号";
                             $("#hbmc").val(hbmc);
                             $("#ysxm").val("普通号");
                            writeLog("普通出诊信息确认");
                        }*/
                        $(".guahao_queren .p1 .uname").text($("#patient_name").val());
                        $(".guahao_queren .p2 .card_no").text($("#card_no").val());
                        $(".guahao_queren .p6 .hbmc").text($("#hbmc").val());
                        $(".guahao_queren .p6 .ksmc").text($("#ksmc").val());
                        $(".guahao_queren .p6 .ysxm").text($("#ysxm").val());
                        $(".guahao_queren .p6 .ysfwf").text($("#ysfwf").val());
                        $(".guahao_queren .p6 .czsj").text($("#czsj").val());
                        var charge_total = data['body']['chargetamt'];//总金额
                        var cash = data['body']['personamt'];//个人支付
                        var zhzf = data['body']['Grzhzf'];//账户支付
                        var tczf = data['body']['ybzf'];//医保支付
                        var rctpno =  data['body']['rctpno']; //收据号
                        $("#charge_total").val(charge_total);//将自费加医保总金额放入隐藏域
                        $("#cash").val(cash);//将自费金额放入隐藏域
                        $("#zhzf").val(zhzf);//将丈夫支付金额放入隐藏域
                        $("#tczf").val(tczf);//将医保支付金额放入隐藏域
                        $("#rctpno").val(rctpno);//将收据号放入隐藏域
                        $(".guahao_queren .p6 .charge_total").text($("#charge_total").val());
                        $(".guahao_queren .p6 .cash").text($("#cash").val());
                        $(".guahao_queren .p6 .ybzf").text($("#tczf").val());
                        $(".guahao_queren .p6 .p_zhzf").text($("#zhzf").val());
                       //  $("#personcount").val(ersoncount);
                       // $(".guahao_queren .p6 .zfye").text(ersoncount);

                    }else{
                        $("#fanhui").css({"visibility":"visible"});
                        $("#tuichu").css({"visibility":"visible"});
                        $("#confirm").css({"visibility":"hidden"});
                         /*if($("#hao_type").val()=="2"){
                            $("#op_now").val("zj_guahao_xinxi_queren");
                            writeLog("专家出诊信息确认");
                        }else{
                            $("#op_now").val("pt_guahao_xinxi_queren");
                            writeLog("普通出诊信息确认");
                        }*/

                        layer.alert(data['head']['retmsg'],{
                            icon: 6,
                            skin: 'layer-ext-moon',
                            time:5000,
                        });

                    }

                }
            });
        break;
    }
});
/********退款********/
function do_tuikuan(trade_no,out_trade_no,total_amount,operator_id){
    var pay_type = $("#pay_type").val();
    var params = {"pay_type":pay_type,"trade_no":trade_no,"out_trade_no":out_trade_no,"total_amount":total_amount,"operator_id":$("#zzj_id").val()};
    $.ajax({
        url:"/rw2.0_payment/index.php/ZiZhu/JinRiGuaHao/ajax_tuikuan",
        type:'post',
        dataType:'json',
        data:params,
        success:function(data1){
            switch(pay_type){
                case "alipay":
                    if(data1.message.Code=="10000"){
                        writeLog("支付宝退款成功");
                        $(".pay_success h3").show().html("缴费失败,退款成功,请重新操作！");
                    }else{
                        writeLog("支付宝退款失败");
                        $(".pay_success h3").show().html("缴费失败,请去收费窗口处理");
                        daojishi(10);
                        if($("#card_code").val() == "2" || $("#card_code").val() == "3"){
                            //保存失败
                            var riqi = $("#czsj").val();//HIS返回挂号日期时间
                            var sex = $("#patient_sex").val();//患者性别;
                            if(sex == "1"){
                                sex = '男';
                            }else{
                                sex = '女';
                            }
                            var age =  $("#patient_age").val();//患者年龄
                            var s1="";
                            var pos = 0;
                            s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                            s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                            s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                            if($("#card_code").val()=="2"){
                                var card_code ="身份证";
                            }
                            if($("#card_code").val()=="3"){
                                var card_code ="就诊卡";
                            }
                            s1 += "<tr font='黑体' type='text'  size='10' x='35' y='" + (pos += 55) + "' >卡类型:"+card_code+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='80' y='" + (pos += 30) + "' >缴费失败</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='45' y='" + (pos += 40) + "' >请保存此凭证</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='10' y='" + (pos += 40) + "' >请去缴费窗口处理</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >年龄:"+age+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费:"+$("#ysfwf").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                            if($("#pay_type").val()=="alipay"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                            }
                            if($("#pay_type").val()=="yhk"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                            }
                            if($("#pay_type").val()=="wxpay"){
                               s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                            }
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户：0元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                            if($("#pay_type").val()=="alipay"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                            }
                            if($("#pay_type").val()=="wxpay"){
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信单号："+$("#stream_no").val()+"</tr>";
                            }
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:支付成功</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，遗失不补。</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，作为退费凭证。</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                            s1 += "</print_info>";
                            // alert(s1);
                            window.external.paint(s1);
                        }else{
                            var riqi = $("#czsj").val();//HIS返回挂号日期时间
                            var sex = $("#patient_sex").val();//患者性别;
                            if(sex == "1"){
                                sex = '男';
                            }else{
                                sex = '女';
                            }
                            var age =  $("#patient_age").val();//患者年龄
                            var s1="";
                            var pos = 0;
                            //alert($("#patient_id").val()+"门诊号-失败");
                            s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                            s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                            s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='35' y='" + (pos += 55) + "' >卡类型:医保卡</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='80' y='" + (pos += 30) + "' >缴费失败</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='45' y='" + (pos += 40) + "' >请保存此凭证</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='10' y='" + (pos += 40) + "' >请去缴费窗口处理</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >年龄:"+age+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费:"+$("#ysfwf").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                            if($("#pay_type").val()=="alipay"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                            }
                            if($("#pay_type").val()=="yhk"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                            }
                            if($("#pay_type").val()=="wxpay"){
                               s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                            }
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户："+$("#zhzf").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                            if($("#pay_type").val()=="alipay"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                            }
                            if($("#pay_type").val()=="wxpay"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信单号："+$("#stream_no").val()+"</tr>";
                            }
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:支付成功</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >失败类型：his失败</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，遗失不补。</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，作为退费凭证。</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                            s1 += "</print_info>";
                            // alert(s1);
                            window.external.paint(s1);
                        };
                    }
                break;
                case "wxpay":
                    if(data1.message.result_code=="SUCCESS"){
                        daojishi(15);
                        writeLog("微信退款成功");
                        $(".pay_success h3").show().html("缴费失败,退款成功,请重新操作！");
                    }else{
                        writeLog("微信退款失败");
                        $(".pay_success h3").show().html("缴费失败,请去收费窗口处理");
                        daojishi(10);
                        if($("#card_code").val() == "2" || $("#card_code").val() == "3"){
                            //保存失败
                            var riqi = $("#czsj").val();//HIS返回挂号日期时间
                            var sex = $("#patient_sex").val();//患者性别;
                            if(sex == "1"){
                                  sex = '男';
                            }else{
                                  sex = '女';
                            }
                            var age =  $("#patient_age").val();//患者年龄
                            var s1="";
                            var pos = 0;
                            s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                            s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                            s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                            if($("#card_code").val()=="2"){
                                var card_code ="身份证";
                            }
                            if($("#card_code").val()=="3"){
                                var card_code ="就诊卡";
                            }
                            s1 += "<tr font='黑体' type='text'  size='10' x='35' y='" + (pos += 55) + "' >卡类型:"+card_code+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='80' y='" + (pos += 30) + "' >缴费失败</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='45' y='" + (pos += 40) + "' >请保存此凭证</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='10' y='" + (pos += 40) + "' >请去缴费窗口处理</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >年龄:"+age+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费:"+$("#ysfwf").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                            if($("#pay_type").val()=="alipay"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                            }
                            if($("#pay_type").val()=="yhk"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                            }
                            if($("#pay_type").val()=="wxpay"){
                               s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                            }
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户：0元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";
                            if($("#pay_type").val()=="alipay"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                            }
                            if($("#pay_type").val()=="wxpay"){
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信单号："+$("#stream_no").val()+"</tr>";
                            }
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:支付成功</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，遗失不补。</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，作为退费凭证。</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                            s1 += "</print_info>";
                            // alert(s1);
                            window.external.paint(s1);
                        }else{
                            var riqi = $("#czsj").val();//HIS返回挂号日期时间
                            var sex = $("#patient_sex").val();//患者性别;
                            if(sex == "1"){
                                  sex = '男';
                            }else{
                                  sex = '女';
                            }
                            var age =  $("#patient_age").val();//患者年龄
                            var s1="";
                            var pos = 0;
                            //alert($("#patient_id").val()+"门诊号-失败");
                            s1 = "<?xml version='1.0' encoding='utf-8'?>" + "<print_info width='300' height='500'>";
                            s1 += "<tr font='黑体' type='text'  size='12' x='10' y='" + (pos += 10) + "' >首都医科大学附属北京潞河医院(挂号)</tr>";
                            s1 += "<tr font='黑体' type='barcode' width='115' height='55' size='10' x='100' y='" + (pos += 30) + "' >"+$("#patient_id").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='35' y='" + (pos += 55) + "' >卡类型:医保卡</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='80' y='" + (pos += 30) + "' >缴费失败</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='45' y='" + (pos += 40) + "' >请保存此凭证</tr>";
                            s1 += "<tr font='黑体' type='text'  size='25' x='10' y='" + (pos += 40) + "' >请去缴费窗口处理</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >姓名:"+$("#patient_name").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >性别:"+sex+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >年龄:"+age+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >号别:"+$("#hbmc").val()+"</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医事服务费:"+$("#ysfwf").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >总费用："+$("#charge_total").val()+"元</tr>";
                             s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >医保基金支付："+$("#tczf").val()+"元</tr>";
                            if($("#pay_type").val()=="alipay"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝支付："+$("#cash").val()+"元</tr>";
                            }
                            if($("#pay_type").val()=="yhk"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >银行卡支付："+$("#cash").val()+"元</tr>";
                            }
                            if($("#pay_type").val()=="wxpay"){
                               s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信支付："+$("#cash").val()+"元</tr>";
                            }
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >个人账户："+$("#zhzf").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >实收:"+$("#cash").val()+"元</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >机器编号:"+$("#zzj_id").val()+"</tr>";

                            if($("#pay_type").val()=="alipay"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝帐号："+$("#idCard").val()+"</tr>";
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付宝单号："+$("#stream_no").val()+"</tr>";
                            }

                            if($("#pay_type").val()=="wxpay"){
                                s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >微信单号："+$("#stream_no").val()+"</tr>";
                            }
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >支付状态:支付成功</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >流水号："+$("#trade_no").val()+"</tr>";
                             s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >失败类型：his失败</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 10) + "' >---------------------------------------------</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，遗失不补。</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 20) + "' >*温馨提示：请保存此凭证，作为退费凭证。</tr>";
                            s1 += "<tr font='黑体' type='text'  size='10' x='10' y='" + (pos += 40) + "' >.</tr>";
                            s1 += "</print_info>";
                            // alert(s1);
                            window.external.paint(s1);
                        };
                    }
                break;
            }
        }
    })
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
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}
</script>
<style type="text/css">
</style>
<title>今日挂号</title>
</head>
<body>
<!--医保自助建卡电话-->
<input type="hidden" class="inhide" id="jk_phone" />
<!-- 自助机ID -->
<input type="hidden" id="zzj_id"  value="<?php echo ($zzj_id); ?>" />
<!-- 更新标志-->
<input type="hidden"  class="inhide" id="gx"  />
<!-- 预约日期 -->
<input type="hidden" class="inhide" id="yuyue_riqi" />
<!-- 一级科室代码 -->
<input type="hidden" class="inhide" id="yjksdm" />
<!-- 二级科室代码 -->
<input type="hidden" class="inhide" id="ksdm" />
<!-- 二级科室名称 -->
<input type="hidden" class="inhide" id="ksmc" />
<!-- 医生姓名 -->
<input type="hidden" class="inhide" id="ysxm" />
<!-- 医生代码 -->
<input type="hidden" class="inhide" id="ysdm" />
<!-- 挂号级别代码 号别编码 -->
<input type="hidden" class="inhide" id="hbbm" />
<!-- 工伤病人 号别编码 -->
<input type="hidden" class="inhide" id="gsbr" />
<!-- 挂号级别名称 号别名称 -->
<input type="hidden" class="inhide" id="hbmc" />
<!-- 上下午代码 -->
<input type="hidden" class="inhide" id="sxw" />
<!-- 上下午名称 -->
<input type="hidden" class="inhide" id="apm" />
<!-- 出诊时间 -->
<input type="hidden" class="inhide" id="czsj" />
<!-- 出诊日期-->
<input type="hidden" class="inhide" id="meddate" />
<!-- 顺序号-->
<input type="hidden" class="inhide" id="seqnum" />
<!-- 排班id-->
<input type="hidden" class="inhide" id="orderid" />
<!--添加了号源类别的隐藏域 1是专家号,2是普通号-->
<input type="hidden" class="inhide" id="hao_type" />
<!-- 预约ID -->
<input type="hidden" class="inhide" id="yyid" />
<!-- 操作码 -->
<input type="hidden" class="inhide" id="op_code" value=""/>
<input type="hidden" class="inhide" id="dingshi" value=""/>
<!-- 卡号 -->
<input type="hidden" class="inhide" id="card_no" value=""/>
<!-- 卡类型 -->
<input type="hidden" class="inhide" id="card_code" value=""/>

<input type="hidden" class="inhide" id="times" value="" />
<!--添加了病人ID-->
<input type="hidden" class="inhide" id="patient_id" />
<!-- 病人姓名 -->
<input type="hidden" class="inhide" id="patient_name" value="" />
<!--病人性别  -->
<input type="hidden" class="inhide" id="patient_sex" value="" />
<!-- 病人年龄 -->
<input type="hidden" class="inhide" id="patient_age" value="" />
<!-- 医保标志 -->
<input type="hidden" class="inhide" id="medflag" value="" />
<!-- 医事服务费 -->
<input type="hidden" class="inhide" id="ysfwf" value=""/>
<!-- 医保,自费总金额 -->
<input type="hidden" class="inhide" id="charge_total" value=""/>
<!-- 医保,自费余额 -->
<input type="hidden" class="inhide" id="personcount" value=""/>
<!-- 自费金额 -->
<input type="hidden" class="inhide" id="cash" />
<!-- 银行卡金额 -->
<input type="hidden" class="inhide" id="total_amount" />
<!-- 收据号 -->
<input type="hidden" class="inhide" id="rctpno" />
<!-- 医保支付金额 -->
<input type="hidden" class="inhide" id="tczf" />
<!-- 账户支付 -->
<input type="hidden" class="inhide" id="zhzf" value=""/>
<input type="hidden" class="inhide" id="pay_seq" value=""/>
<!--本地交易流水号-->
<input type="hidden" id="stream_no" value=""/>
<!-- 买家支付宝，微信交易流水号 -->
<input type="hidden" class="inhide" id="trade_no" value=""/>
<!-- 买家支付宝,微信账号 -->
<input type="hidden" class="inhide" id="idCard" value=""/>
<!-- 支付宝，微信交易状态 -->
<input type="hidden" class="inhide" id="PayStatus" value=""/>
<input type="hidden" class="inhide" id="times_order_no"  value=""/>
<input type="hidden" class="inhide" id="dept_code" value=""/>
<input type="hidden"  class="inhide" id="tansType" value="00"  />
<input type="hidden" class="inhide" id="reponse_name" value="" />
<input type="hidden" id="daojishi" class="inhide" value=""/>
<input type="hidden" id="business_type" class="inhide" value=""/>
<input type="hidden" id="pay_type" class="inhide" value=""/>
<input type="hidden" id="yb_flag" class="inhide" value=""/>
<input type="hidden" id="record_sn" class="inhide" value=""/>
<input type="hidden" id="bank_card_num" class="inhide" value=""/>
<!-- <input type="hidden" id="personcount" class="inhide" value=""/> -->
<!--银联隐藏域-->
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

<input type="hidden" id="bank_success" value=""/>
<!-- 记录当前操作步骤 -->
<input type="hidden" id="op_now" />
<div class="main_body">
    <div style="width:70px; height:70px;float:left;" id="close"></div>
    <div id="downcount"></div>
    <div id="show_times"><?php echo ($riqi); ?> <?php echo ($xq); ?> &nbsp;<?php echo ($sj); ?></div>
    <!-- 导航条开始 -->
    <div class='navigation'>
        <ul class='navigationDaot'>
            <li class='choseCardDaot'></li>
            <li class='choseRoomDaot'></li>
            <li class='choseDocDaot'></li>
            <li class='confirmInfoDaot'></li>
            <li class='chosePayTypeDaot'></li>
        </ul>
        <ul class='navigationBar'>
            <li class='choseCard'>选择卡类型</li>
            <li class='choseRoom'>选择科室</li>
            <li class='choseDoc'>选择医生</li>
            <li class='confirmInfo'>确认挂号信息</li>
            <li class='chosePayType'>选择支付方式</li>
        </ul>
    </div>
    <!-- 导航条结束 -->
   <div class="main_left_2"  calss="block">
        <div class="print_notice mtnum2" style="display:none">
            <h3>打印机缺纸请联系工作人员</h3>
        </div>
        <!--选择卡类型-->
        <div class="chose_card_type_area mtnum2" style="display:block;">
            <h3>请您选择卡类型</h3>
            <div class='wall'></div>
            <ul class='allCardType'>
                <li class="ic" id="xz_ic">就诊卡</li>
                <li class="sfz" id="xz_sfz">身份证</li>
                <li class="yibao" id="xz_yibao">医保卡</li>
            </ul>
        </div>

        <!----就诊卡操作界面---->
       <div class="jiuzhen_op_area mtnum2" style="display:none;">
            <h3>请按图示放就诊卡</h3>
            <div class="j_keybord_area">
                <ul class="img_view"></ul>
                <div class="tips"></div>
            </div>
        </div>
        <!----S身份证操作界面---->
        <div class="jiuzhen_op_area_sfz mtnum2" style="display:none;">
            <h3>请刷身份证或输入身份证号</h3>
            <div class="j_keybord_area_sfz">
                <ul class="img_view" id="img_view_sfz">
                </ul>
                <input type="text" class="iptx" value="" id="jz_card_no_sfz" />
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
                    <li class="num" param="x">X</li>
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

        <!-- 挂号选择一级科室 -->
        <div class="chose_room mtnum2" style="display:none;">
            <div class='roomtitle'>
                 <span class="jz_name" ></span>
                 <h5 style="margin-top:-20px;">请选择一级科室</h5>
            </div>
            <div class="room_list" style="margin-top:20px">
                <div id='sub_list'></div>
            </div>
            <div class="page">
                <span class="total_page"></span>
                <span class="prev_ks">上一页</span>
                <span class="next_ks">下一页</span>
            </div>
        </div>
        <!-- 挂号选择一级科室 -->


        <!-- 挂号选择二级科室 -->
       <div class="chose_room2 mtnum2" style="display:none;">
            <div class='roomtitle2'>
                <span class="jz_name" ></span>
                <h5 style="margin-top:20px;">请选择二级科室</h5>
            </div>
            <div class="room_list2" style="margin-top:20px">
                <div id='sub_list2'></div>
            </div>
            <div class="page2">
                <span class="total_page2"></span>
                <span class="prev_ks2">上一页</span>
                <span class="next_ks2">下一页</span>
            </div>
        </div>
        <!-- 挂号选择二级科室结束 -->

         <!-- 挂号选择医生 -->
        <div class="chose_doctor mtnum2" style="display:none;">

            <div class='roomtitle3'>
                <span class="jz_name" ></span>
                <h5>请选择挂号医生<h5/>
            </div>

            <div class="doctor_list">

            </div>
            <div class="page">
                <span class="total_page"></span>
                <span class="prev">上一页</span>
                <span class="next">下一页</span>
            </div>
        </div>
         <!-- 挂号选择医生 -->


         <!-- 确认挂号信息开始 -->
        <div class="guahao_queren mtnum2" style="display:none">
            <h3>请确认挂号信息</h3>
            <div class='info_show'>
                <ul class='info_left'>
                    <li>
                        <span class='info_name'>姓名:</span>
                        <span class='info_val'>陈三炮</span>
                    </li>
                    <li>
                        <span class='info_name'>科室:</span>
                        <span class='info_val'>内科</span>
                    </li>
                    <li>
                        <span class='info_name'>医生:</span>
                        <span class='info_val'>王金祥</span>
                    </li>
                    <li>
                        <span class='info_name'>医事服务费:</span>
                        <span class='info_money'>100.00</span>
                    </li>
                    <li>
                        <span class='info_name'>个人账户支付:</span>
                        <span class='info_money'>20.00</span>
                    </li>
                </ul>
                <ul class='info_right'>
                    <li>
                        <span class='info_name'>卡号:</span>
                        <span class='info_val'>123456789</span>
                    </li>
                    <li>
                        <span class='info_name'>就诊日期:</span>
                        <span class='info_val'>2019-01-01 上午</span>
                    </li>
                    <li>
                        <span class='info_name'>号别:</span>
                        <span class='info_val'>专家</span>
                    </li>
                    <li>
                        <span class='info_name'>医保支付:</span>
                        <span class='info_money'>80.00</span>
                    </li>
                </ul>
            </div>
        </div>
         <!-- 确认挂号信息结束 -->

        <div class="print_notice mtnum2" style="display:none">
            <h3>打印机缺纸请联系工作人员</h3>
        </div>

        <!--选择卡类型开始-->
        <div class="chose_pay_type_area mtnum2" style="display:none">
            <h3>请选择支付方式</h3>
            <ul>
                <li id="pay_zhifubao" class="zhifubao">支付宝</li>
                <li id="pay_weixin" class="weixin">微信</li>
                <li id="pay_bank" class="bank_s">银行卡</li>
            </ul>
        </div>
        <!--选择卡类型开始-->

        <!--支付宝二维码显示区-->
        <div class="pay_ma_show mtnum2" style="display:none">
           <h3>扫一扫付款</h3>
           <div class='personInfo'>
               <p class='p1'>请核对您的支付信息</p>
               <ul>
                   <li>
                        <span class='payname'>姓名:</span>
                        <span class='info_val'>陈三炮</span>
                   </li>
                   <li>
                       <span class='payname'>支付金额:</span>
                       <span class='paynum'> 80.00</span>
                   </li>
               </ul>
               <p class='p2'>若信息不一致,请勿支付</p>
           </div>
            <div class="erweima">
                 <img src="" width="240" />
            </div>
        </div>
         <!--支付宝二维码显示区-->


        <!--银行卡开始-->
        <div class="bank mtnum2" style="display:none">
            <h3>请插入您的银行卡</h3>
            <div class="tips" id="bank_tips"></div>
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
        <!--银行卡结束-->



        <!--------------操作成功页面-------------->
        <div class="pay_success mtnum2" style="display:none;">
            <div class='suc_tipsimg'></div>
            <h3>操作完毕 !</h3>
        </div>
        <!--------------操作成功页面-------------->


        <!--------------操作失败页面-------------->
        <div class="pay_error mtnum2" style="display:none;">
            <div class='err_tipsimg'></div>
            <h3>操作完毕 !</h3>

        </div>
        <!--------------操作失败页面-------------->
   </div>

   <!-- 返回确认按钮 -->
   <div class="btn_area" style="display:none;">
    <ul class='button1'>
        <li id="confirm" class='queren1'></li>
        <li id="fanhui" class='fanhui1'></li>
        <li id="tuichu" class='tuichu1'></li>
    </ul>
   </div>
   <!-- 返回确认按钮 -->

</div>
</div>
</body>
</html>