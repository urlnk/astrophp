<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>易捷一卡通查询系统</title>
    <link rel="stylesheet" type="text/css" href="/css/touch.css<?=$static_version?>">
</head>

<body class="start">
<section>
    <dialog>
        请稍后，正在查询卡信息……
    </dialog>
</section>

<div id="choice_user" style="display: none;">
    <header style="display: block; margin: 0;">
        <h2>
            <a href="javascript:" style="float: left">返回</a>
            <b style="padding: 0 120px 0 0;">&nbsp;</b>
        </h2>
    </header>
    <h1>请选择用户：</h1>
    <ul id="users_list">
    </ul>
</div>

<div id="start_screen">
    <header>
        <h2>
            <a href="javascript:">退出</a>
            <b>周晓明</b>
        </h2>
    </header>
    <h1>易捷一卡通查询系统</h1>
    <ol>
        <dl><a href="javascript:">手机号登录</a></dl>
        <dl><a href="javascript:">绑定手机号</a></dl>
    </ol>
    <ul>
        <li><a href="javascript:">账户信息</a></li>
        <li><a href="javascript:">账户充值</a></li>
        <li><a href="javascript:">充值记录</a></li>
        <li><a href="javascript:">消费记录</a></li>
        <li><a href="javascript:">挂失解挂</a></li>
        <li><a href="javascript:">完善信息</a></li>
    </ul>
</div>

<blockquote>
    <input id="query" value="" placeholder="请输入明码" onfocus="this.select()" onchange="detect(this)">
</blockquote>

<script type="text/javascript" src="/js/start.js<?=$static_version?>"></script>
</body>
</html>
