<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script language="javascript">
$(function(){
	$("#online").click(function(){
		var params = {"doctor_code":$("#doctor_code").val(),"dept_code":$("#dept_code").val(),"room":$("#room").val(),"expert":$("#expert").val()}; 
		$.ajax({
			url:"client.php", 
			type:'post',
			dataType:'json',
			data:params,
			success:function(data){
				if(data.success==1){
					if(data.jz_normal==1){
						$("#jpz").val("接专诊");
					}else{
						$("#jpz").val("接普诊");
					}
					fresh_list(data.data);
				}
			}
		})		
	})
})
</script>
<table cellpadding="4" cellspacing="2">
<tr>
<td height="30">诊室号：</td>
<td><input type="text" id="room" /></td>
<td height="30">科室编码：</td>
<td><input type="text" id="dept_code"/></td>
<td height="30">医生编码：</td>
<td><input type="text" id="doctor_code" /></td>

<td height="30">是否专家：</td>
<td>
<select id="expert">
<option value="1">是</option>
<option value="0">否</option>
</select>
</td>

<td>
<input type="button" value="上线" id="online"/>
<input type="button" value="下线" id="offline" />
</td>
</tr>
</table>


<?php 
ini_set("soap.wsdl_cache_enabled","0");
$soap = new SoapClient('http://172.168.1.242/haidian/soap/Service.php?wsdl');
//echo $soap->login("1","2031","45","1");
class soap_client{
public function login(){
	echo "333";
}
public function logout(){
	
}

}
?> 
