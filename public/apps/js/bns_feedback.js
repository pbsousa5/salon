/**
 * Created by foisonocean on 2015/9/12.
 */
var phoneReg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
var phoneInput = document.getElementById("contactContent");
var suggestInput = document.getElementById("suggestContent");
var wordsCountDiv = document.getElementById("wordsCount");
var submitBtn = document.getElementById("submitBtn");
var canNotSubmit = document.getElementById("canNotSubmit");
var submitSuccess = document.getElementById("submitSuccess");
var submitFail = document.getElementById("submitFail");
var maxWordsCount = 200;        /*反馈意见最大字数*/
phoneInput.flag = false;
suggestInput.flag = false;
if(phoneInput.value){
    var phoneNumber = phoneInput.value;
    if(phoneReg.test(phoneNumber)){
        phoneInput.flag = true;
    }
}
phoneInput.onblur = function(){
    var phoneNumber = this.value;
    if(phoneReg.test(phoneNumber)){
        this.flag = true;
        this.onfocus = null;
    }
    else{
        phoneWrong();
    }
};
suggestInput.onfocus = function(){
    wordsCountDiv.style.height = "auto";
    wordsCountDiv.style.visibility = "visible";
};
suggestInput.oninput = function(){
    var currentWordsCount = this.value.length;
    wordsCountDiv.innerHTML = currentWordsCount + "/" + maxWordsCount;
    if(currentWordsCount > maxWordsCount){
        wordsCountDiv.style.color = "#F74336";
        suggestInput.flag = false;
    }
    else if(currentWordsCount <= 0){
        suggestInput.flag = false;
    }
    else{
        wordsCountDiv.style.removeProperty("color");
        suggestInput.flag = true;
    }
};
submitBtn.onclick = function(){
    canNotSubmit.className = "";
    if((!phoneInput.flag)&&(!phoneInput.style.color)){
        phoneWrong();
    }
    if(phoneInput.flag&&suggestInput.flag){
        canNotSubmit.style.visibility = "hidden";
        var text = getFormDate("feedback");
        var ajax = new XMLHttpRequest();
        ajax.open("post","http://121.199.62.235:8888/public/api/v1/app/add_feedback",true);
        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        ajax.send(text);
        ajax.onreadystatechange = function(){
            if(ajax.readyState==4&&ajax.status==200){
                if(ajax.responseText=="success"){
                    submitAnimation(submitSuccess);
                }
                else if(ajax.responseText=="error"){
                    submitAnimation(submitFail);
                }
            }
        };
    }
    else{
        canNotSubmit.style.visibility = "visible";
        setTimeout(function(){
            canNotSubmit.className = "canNotSubmitAnimation";
        },10);
    }
};
//submitBtn.touchStart = function(){alert("a")};
submitBtn.addEventListener("touchstart",function(){
    this.style.backgroundColor = "#DB654A"
});
submitBtn.addEventListener("touchend",function(){
    this.removeAttribute("style");
});

function submitAnimation(divObj){
    var stayTime = 3000;        /*出现提交提示界面的时间为3000毫秒*/
    divObj.style.display = "block";
    setTimeout(function(){
        divObj.style.opacity = "1";
    },20);
    setTimeout(function(){
        divObj.style.opacity = "0";
        setTimeout(function(){
            divObj.removeAttribute("style");
        },1000);
    },stayTime);
}

function getFormDate(formID){
    var oForm = document.getElementById(formID);
    var aInput = oForm.getElementsByTagName("input");
    var aTextarea = oForm.getElementsByTagName("textarea");
    var text = "";
    for(var i=0; i<aInput.length; ++i){
        text += aInput[i].name + "=" + aInput[i].value + "&";
    }
    for(var i=0; i<aTextarea.length; ++i){
        text += aTextarea[i].name + "=" + aTextarea[i].value + "&";
    }
    text = text.slice(0,text.length-1);

    return text;
}

function phoneWrong(){
    phoneInput.flag = false;
    phoneInput.currentNumber = phoneInput.value;
    phoneInput.value = "手机号码格式不正确";
    phoneInput.style.color = "#F74336";

    phoneInput.onfocus = function() {
        phoneInput.value = "";
        phoneInput.removeAttribute("style");
        phoneInput.value = phoneInput.currentNumber;
    };
}
