<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>用户中心</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css<?=$static_version?>">
</head>

<body class="home">
<div>
    <blockquote><img src="/img/IMG_1849.JPG"></blockquote>
    <h2><?=$user->user_name?></h2>
    <p>
        <button type="button" onclick="window.location.href = '/card/logout';">退出</button>
    </p>
</div>

<form>
<ul>
    <li><a href="/card/account">账户信息</a></li>
    <li><a href="/card/recharge">账户充值</a></li>
    <li><a href="/card/log">充值记录</a></li>
    <li><a href="/card/consume">消费记录</a></li>
    <li><a href="/card/loss">挂失解挂</a></li>
    <li><a href="/card/info">完善信息</a></li>
</ul>
</form>
</body>
</html>
