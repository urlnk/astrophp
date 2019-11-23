<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>账户信息</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body class="account">
<form>
<div>
    <h4>卡信息</h4>
    <ul>
        <dl>
            <dt>卡内码</dt>
            <dd><?=$card->card_code?></dd>
        </dl>
        <dl>
            <dt>卡有效期</dt>
            <dd><?=$card->effective_time?></dd>
        </dl>
    </ul>
    <h4>账户信息</h4>
    <ul>
        <dl>
            <dt>现金账户</dt>
            <dd><?=$arr[3]?>元</dd>
        </dl>
        <dl>
            <dt>补贴账户</dt>
            <dd><?=$arr[1]?>元</dd>
        </dl>
    </ul>
    <h4>积分信息</h4>
    <ul>
        <dl>
            <dt>账户积分</dt>
            <dd>0</dd>
        </dl>
    </ul>
</div>

<blockquote>
    <button>修改超额支付密码</button>
</blockquote>
<p>
    加个账户合计总额
</p>
</form>
</body>
</html>
