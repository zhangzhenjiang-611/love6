//ocx控件初始化
function OcxInit(){
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