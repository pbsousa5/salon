<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>反馈意见</title>
    <link rel="stylesheet" href="{!! asset('apps/css/bns_feedback.css') !!}"/>
</head>
<body>
<form id="feedback" action="{!! route('feedback') !!}">
{!! csrf_field() !!}
<input type="hidden" name="user_type" value="{{ $inputs['user_type'] }}">
<div class="box">
    <div class="column centerColumn">
        <div style="width: 100%;">
            <input type="text" name="mobile" value="{{ $inputs['mobile'] }}" id="contactContent" placeholder="请输入您的手机号" maxlength="20"/>
        </div>
    </div>
    <div class="column leftColumn">联系方式</div>
</div>
<div class="box">
    <div class="column centerColumn">
        <div style="width: 100%;">
            <textarea id="suggestContent" name="feedback_txt" rows="5" placeholder="请输入您的宝贵意见"></textarea>
        </div>
    </div>
    <div class="column leftColumn">反馈意见</div>
    <p style="width: 0;height: 0;display: block;visibility: hidden;clear: both;">.</p>
    <div id="wordsCount" style="">0/200</div>
</div>
</form>
<div id="submit">
    <div id="canNotSubmit" class="canNotSubmitAnimation">您提交的反馈格式不正确</div>
    <div id="submitBtn">提交</div>
</div>
<div class="submitResult" id="submitSuccess">
    <div class="resultIcon">
        <img class="svgIcon" src="{!! asset('apps/img/svg/submitSuccess.svg') !!}">
    </div>
    <div class="resultInfo">提交成功</div>
</div>
<div class="submitResult" id="submitFail">
    <div class="resultIcon">
        <img class="svgIcon" src="{!! asset('apps/img/svg/submitError.svg') !!}">
    </div>
    <div class="resultInfo">提交失败</div>
</div>
</body>
<script src="{!! asset('apps/js/bns_feedback.js') !!}"></script>
</html>