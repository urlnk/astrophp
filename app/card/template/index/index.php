<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>用户中心</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body class="home">
<div>
    <blockquote><img src="/img/IMG_1849.JPG"></blockquote>
    <h2>卡内码 <?=$card->card_code?></h2>
    <p>
        <button>退出</button>
    </p>
</div>

<form>
<ul>
    <li><a href="/card/account?uid=<?=$user->user_id?>">账户信息</a></li>
    <li><a href="/card/recharge?uid=<?=$user->user_id?>">账户充值</a></li>
    <li><a href="/card/log?uid=<?=$user->user_id?>">充值记录</a></li>
    <li><a href="/card/consume?uid=<?=$user->user_id?>">消费记录</a></li>
    <li><a href="/card/loss?uid=<?=$user->user_id?>">挂失解挂</a></li>
    <li><a href="/card/info?uid=<?=$user->user_id?>">完善信息</a></li>
</ul>
</form>
</body>
</html>
