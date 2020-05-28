$(function() {
    //扫码枪获取支付二维码(微信支付宝)
    var code = "";
    var lastTime,nextTime;
    var nextCode;

    document.onkeypress = function(e) {
        nextCode = e.which;
        nextTime = new Date().getTime();
        if(lastTime == null)
            lastTime = nextTime;

        if(lastTime != null && nextTime - lastTime <= 30) {
            code += String.fromCharCode(nextCode); 
        } 
        else if(lastTime != null && nextTime - lastTime > 200){
            code = '';
            lastTime = null;
        }
        lastTime = nextTime;
    }
    //捕获回车事件也就是扫描完毕事件
    $(document).keyup(function(e){
        if(e.keyCode == 13)
        {
            //赋值
            if(code != "")
            {
                alert(code);
                document.getElementById('#ewm_code').val(code);
                code = "";
                lastTime = null;
            }
        }
    });
}