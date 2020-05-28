$(function(){
	//退出壳程序
	$("#close").on("click",function(){ 
		window.external.closeForm();
	});
	//自助机编号
	try{
	$("#f_z_c").html(window.external.getMachineName());
	}catch(e){}
});
var yb_patinfo_debug = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow hao_ming="09" code_value="" patient_name="" business_type=""/></data></commitdata><returndata><data><datarow patient_id="000673159200" times="17" name="陈兴超" sex_chn="男" sex="1" birthday="1985/6/3" age="34" response_type="97" contract_code="" occupation_type="01" charge_type="05" haoming_code="09" code="112277483006" visit_dept="" doctor_code="" visit_date="2019/8/19 16:52:34" social_no="130524198506033517" real_haoming_code="09" home_district="110000" home_street="." hic_no="" addition_no1="112277483006" home_tel="" relation_name="" relation_code="" relation_tel="." real_times="17" p_bar_code="112277483" black_flag="1" max_receipt_sn="0" inpatient_no="" outpatient_no="" marry_code="" insurl_code="BJFZZB" school="" class="" card_no="112277483006" ic_no="11227748300S" id_no="130524198506033517" personname="陈兴超" fromhosp="" fromhospdate="1899/12/30" fundtype="3" isyt="0" jclevel="0" hospflag="0" persontype="11" isinredlist="true" isspecifiedhosp="1" ischronichosp="" personcount="0" chroniccode="" balance="0" yb_card_no="112277483006" union_flag="1" trade_no="" card_code="20" yb_flag="1"/></data></returndata><operateinfo><info method="YYT_YB_GET_PATI" opt_id="ZZ006" opt_name="ZZ006" opt_ip="80000001" opt_date="2019-08-19" guid="{1566204747}" token="AUTO-YYRMYY-2019-08-19"/></operateinfo><result><info execute_flag="0" execute_message="操作成功!" account="2019-08-19 16:52:35"/></result></root>';

var yjt_patinfo_debug = '<?xml version="1.0" encoding="utf8" standalone="yes"?> <root version="2.1.1"><state success="true" needconfirm="false"></state><cardinfo><cardsn>20000001630194</cardsn><cardno>98001001630194</cardno><medicalalliancecode>98001001630194</medicalalliancecode><idtype>1</idtype><idno>370404198210160043</idno><name>贾妍章</name><sex>2</sex><birthday>19821016</birthday><nationality>1</nationality><phone>13863271209</phone><recordaddress></recordaddress><presentaddress></presentaddress><balance>0.00</balance><cardstatus>0</cardstatus><lastoptime></lastoptime><lasttradeno></lasttradeno><countryinfo>1</countryinfo><marriageinfo>1</marriageinfo><localityinfo>1</localityinfo><unit></unit><areacode></areacode><contactname>贾妍章</contactname><contactsex>1</contactsex><contactidtype>1</contactidtype><contactidno>370404198210160043</contactidno><contactphone>13863271209</contactphone><contactpresentaddress></contactpresentaddress><deposit>10.00</deposit><photo>V0xmAH4AMgAA/4UeUVFRPnEN1WTzLkeNA6tPimcgRMdiU0/EkivLm/PjLb6JgItNE/YrsloF4S2Q+tx+G6GxRimE1gp8t4Vz8FEzJi9W2HtK/j9YGb+NE9NB+RVRrtVSUVFaPoTqMrABK+iY7jeCBS48duComt38aS62syD5L3mtukwhqI1iHeSqu2fycvrHcacBpNxnQ4lPSaSXkPK86dkFppiM7cd++aAhqm58Z/07lW91dVNd2jZMGlO3gg35It1jewB2Dsp2gmDWydWR15ozb15YNNTLfg6rclz8XbQjLh0IIi8jc3OA0JMvWVwknZK1u8HFG9ZJ4d44KCuM2rvSXV6RdurJCkliZvroAYbwqiSrkP3dAgWoYeK/5Hgp/Yl2BKHLYICTVfUtBOgXD/GPkfGjEKOaP8TkvR80GxsJpOTGozE12kW1OaUIMVQXU010+p3L+ZNTFJCFF7D6FZDNuzhqj/fm5JSIj/vlLsmVeAMcpXP9lhO7V79YBZsOzd2uUXxd2My+lQNJKaEvWdc65OxQHyKZgXrljRqjiqbZLlM/vSEzMZv34IT8iykiT2VsCBrl+QDKj6oB58HdCp8MFVNVVb1Y6Ql9KhzD//YOVW6ztUPlj3nS3n86PUi5XPnvPDfINFT2E+OyOET56cUW4OD0WhCgJusc+bMkV1wgP6ymh1XGaxyDXqzbi+djBQmGCJni1Y/VoQnS+qe5rf3phDeOxn/zc2XA9G1+5DkX5qjyqj9XGT7jCRXl9Qv5FibKRNuwRb5CX2TqhUx9BeZCpFP1JQ6soSFyYLG69udNuEGlEg6wgp0goN0qXUHJzh3dJ5T3U3WqV+iZu2hWIRf2WTOtxBz2R/Ghj9beotOv7Ib0joR5QOUa/ZMl6BrwKoAiUeMQ4/2D9DGrkxNkThdKt3XjFAqvIYJef31JbhR2UwLiey4H5QLV+f3hJfEJksqXZe4IsnBDD99+h+jb26ml3+k1Nmc3BnKEYhFxypgF+Q+Mz/S/LlNDaBcvGD86l+GHOB5sZU0vx4DFHRl3TfN1owh70/ENyWWT2S66uqFxKfuR8S7Wm+FBINWmEQ4UU4yzt0eyZglHogHUraEca6GgKP/9XWmw3nxp6HoJnzivCvZxWeE4LWG8wUyC5l6nuy5dcQxaPjAAakmyQFw5rlGCBul0DUIQeWjscbK8fIwadPQTuWu079jtqQP8PPopa+AsRiyKn2qH8Ww0LCzoJzX5NGGbX1U5dmk6OBD8Mcq66dsu5udaPhwX7UwNWAX9GdBottl9wotPt0/+22G9sf24G1WZbn+wtAnyzTOb4AjYxIbjugTFl509ERCE3Eqo2lRsnCeclay255Fg+Xh4pA==</photo><cardtype>1</cardtype><netinfo><creatcardtime>20171201141036</creatcardtime><creatcardhospcode>H105791</creatcardhospcode><creatcardhospname>中国中医科学院广安门医院</creatcardhospname><islocalblacklist>false</islocalblacklist><reportloststatus>0</reportloststatus><reportlosttime></reportlosttime><reason></reason><accountstatus>1</accountstatus></netinfo></cardinfo></root>';

var sfz_patinfo_debug = '陈兴超,男,汉,19850603,河北邢台,230183199011070814,柏乡县公安局,20090324,20190324';

var jyt_card_code = '05';
var sfz_card_code = '06';
var yb_card_code = '09';
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
/********
**获取随机数
*/
function GetRandomNum(Min,Max)
{   
	var Range = Max - Min;   
	var Rand = Math.random();   
	return(Min + Math.round(Rand * Range));   
}  
/***
***倒计时函数
********/
function daojishi(etime){
	if(typeof(interval2) != "undefined")
	{
		for(key in interval2){
			clearTimeout(interval2[key]);
		}
	}
	countdown = etime;
	settime(etime);
}
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
/**
*日志记录函数
**/
function writeLog(logtxt,logtype,logxml){
	if(!logtype)
	{
		logtype = 'INFO';
	}
	var params = {"log_txt":logtxt,"log_type":logtype,"card_code":$("#card_code").val(),"card_no":$("#card_no").val(),"patient_id":$("#pat_code").val(),"direction":"操作步骤","op_code":$("#op_code").val(),'zzj_id':$("#zzj_id").val(),'log_xml':logxml};
	$.ajax({
		url:URL+"/writeLogs", 
		type:'post',
		dataType:'json',
		data:params,
		success:function(data){}
	});
}
function stime() {
   $.ajax({
		url:URL+"/getdangqian_time", 
		type:'post',
		dataType:'json',
		data:{'zzj_id':$("#zzj_id").val()},
		success:function(data){
			$("#show_times").html(data['rq']+"<span style='margin-right:34px;'>"+data['xq']+"</span>"+data['sj'])
		}
	})
	/********************改为一秒执行一次***********************/
	setTimeout("stime()", 30000); 
}
//明泰读卡器退卡
function Mt_Tk()
{
	if(window.external.getMachineName() == 'zzj_dev')
		var MT_Motor_MoveOutCard = window.external.MT_Motor_MoveCard('52');
	else
		var MT_Motor_MoveOutCard = window.external.MT_Motor_MoveCard('48');
	var MT_M_D = window.external.MT_Motor_DenieInsertion();
}
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
//获取年月日时分秒
function getDate(){

   var myDate = new Date();

    //获取当前年
   var year = myDate.getFullYear();

   //获取当前月
   var month = myDate.getMonth() + 1;

    //获取当前日
    var date = myDate.getDate();
    var h = myDate.getHours(); //获取当前小时数(0-23)
    var m = myDate.getMinutes(); //获取当前分钟数(0-59)
    var s = myDate.getSeconds();

   //获取当前时间

   var now = year + '-' + conver(month) + "-" + conver(date) + " " + conver(h) + ':' + conver(m) + ":" + conver(s);
   return now;
}
//日期时间处理
function conver(s) {
	return s < 10 ? '0' + s : s;
}