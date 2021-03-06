<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>优惠券</title>
    <link rel="stylesheet" href="{!! asset('apps/css/coupon.css') !!}"/>
</head>
<body>
<div class="frontImage">
    <img src="{!! asset('apps/img/coupon/pic.png') !!}" style="width: 100%"/>
</div>
<div class="changed" id="changed">
    <div class="phoneNumber">
        <input type="number" class="phoneNumberInput" id="phoneNumberInput" placeholder="请输入您的手机号"/>
    </div>
    <div class="getBtnBox">
        <div class="getBtn" id="getBtn">马上领取</div>
    </div>
</div>
<div class="nochanged">
    <div class="rulesTitle">
        <p style="width: 0;height: 0;visibility: hidden;display: block;">.</p>
        <hr class="rulesTitleHr"/>
        <div class="rulesTitleBtn">活动规则</div>
    </div>
    <div class="rules">
        <p>1.优惠券新老用户同享。</p>
        <p>2.优惠券可与其他优惠券叠加使用，首单优惠券不可叠加。</p>
        <p>3.优惠券仅限在美丽地图平台下单且选择在线支付时使用。</p>
        <p>4.使用优惠券时下单手机号码必须为抢优惠券时手机号码。</p>
        <p>5.本活动最终解释权归美丽地图所有。</p>
    </div>
</div>
<div id="url">{!! route('coupons') !!}</div>
</body>
<script src="{!! asset('apps/js/coupon.js') !!}"></script>
</html>