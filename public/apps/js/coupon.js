window.onload = function(){
    bindGetBtn();

    if(getCookieValueByKey("phone")){
        document.getElementById("changed").style.visibility = "hidden";
        var phoneNumber = getCookieValueByKey("phone");
        var url = document.getElementById("url").innerHTML;
        getCoupon(url,phoneNumber,function(json){
        	//console.log(JSON.parse(json));
            if(json.status == "success" || json.status == "already"){
                showCoupon(json.money,phoneNumber);
                document.getElementById("changed").style.visibility = "visible";
            }
            else if(json.status == "error"){
                alert("未知错误");
            }
            else{
                alert("服务器错误");
            }
        });
    }
};


//绑定修改显示手机号按钮
function bindChangePhoneBtn(){
    var changePhoneBtn = document.getElementById("changeCurrentPhone");
    changePhoneBtn.onclick = function(){
        showAlterPhone();
        bindGetBtn();
    };
}


//显示修改手机号
function showAlterPhone(){
    document.getElementById("changed").innerHTML = '<div class="phoneNumber">' +
        '<input type="number" class="phoneNumberInput" id="phoneNumberInput" placeholder="请输入您的手机号"/>' +
        '</div>' +
        '<div class="getBtnBox">' +
        '<div class="getBtn" id="getBtn">马上领取</div>' +
        '</div>';
}

//优惠券调整大小
function resizeCoupon(){
    var oDivCouponImg = document.getElementById("couponImg");
    oDivCouponImg.style.height = oDivCouponImg.offsetWidth / 6 * 2.49 + "px";
    var oDivCouponText = document.getElementById("couponText");
    oDivCouponText.style.lineHeight = oDivCouponText.offsetHeight + "px";
    oDivCouponText.style.fontSize = oDivCouponText.offsetHeight / 4 + "px";
}

//向服务器请求优惠券
function getCoupon(url,phoneNumber,callback){
    var ajax = window.XMLHttpRequest?(new XMLHttpRequest()):(new ActiveXObject(Microsoft.XMLHTTP));
    ajax.open("post",url,true);
    ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    ajax.send("phoneNumber="+phoneNumber);
    ajax.onreadystatechange = function(){
        if(ajax.readyState==4 && ajax.status==200){
            callback(JSON.parse(ajax.responseText));
        }
    };
}

//显示优惠券
function showCoupon(money,phoneNumber){
    document.getElementById("changed").innerHTML = '<div class="coupon">' +
            '<div class="couponImg" id="couponImg">' +
            '<div class="couponText" id="couponText">' +
            '<p> <span class="rmb">￥</span>' +
            '<span class="rmbNumber">' + money + '</span> </p>' +
            '</div> </div> </div> <div class="currentPhone">' +
            '<span class="currentPhoneText">优惠券已放入账户</span>' +
            '<span class="currentPhoneNumber">' + phoneNumber + '</span>' +
            '<span class="changeCurrentPhone" id="changeCurrentPhone">修改</span>' +　
            '</div>';
    (function(){
        resizeCoupon();
        window.addEventListener('resize',resizeCoupon);
    })();
    bindChangePhoneBtn();
}

//绑定提交手机号按钮
function bindGetBtn(){
    var getBtn = document.getElementById("getBtn");
    getBtn.onclick = function(){
        var phoneNumber = document.getElementById("phoneNumberInput").value;
        var phoneReg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
        if(phoneReg.test(phoneNumber)){
            setCookie("phone",phoneNumber,"d30");
            var url = document.getElementById("url").innerHTML;
            getCoupon(url,phoneNumber,function(json){
                //var phoneNumber = getCookieValueByKey("phone");
                if(json.status == "success"){
                    showCoupon(json.money,phoneNumber);
                }
                else if(json.status == "already"){
                    showCoupon(json.money,phoneNumber);
                    alert("您已经领取过了");
                }
                else if(json.status == "error"){
                    alert("未知错误");
                }
                else{
                    alert("服务器错误");
                }
            });
        }
        else{
            alert("手机号码格式不正确！");
        }
    };
}

//获取cookie中某个key的值
function getCookieValueByKey(key) {
    var cookieString = document.cookie.toString();
    var indexStart = cookieString.indexOf(key+"=");
    if(indexStart == -1){
        return null;
    }
    var result = cookieString.slice(indexStart+key.length+1);
    var indexEnd = result.indexOf(";");
    if(indexEnd != -1){
        result = result.slice(0,indexEnd);
    }
    return result;
};


//设置cookie
function setCookie(key,value,time)
{
    var strsec = getsec(time);
    var exp = new Date();
    exp.setTime(exp.getTime() + strsec*1);
    document.cookie = key + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function getsec(str)
{
    var str1=str.substring(1,str.length)*1;
    var str2=str.substring(0,1);
    if (str2=="s")
    {
        return str1*1000;
    }
    else if (str2=="h")
    {
        return str1*60*60*1000;
    }
    else if (str2=="d")
    {
        return str1*24*60*60*1000;
    }
}
