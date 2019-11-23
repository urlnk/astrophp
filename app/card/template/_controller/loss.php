<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>挂失解挂</title>
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
            <dt>卡状态</dt>
            <dd><?=$card->param_name?></dd>
        </dl>
    </ul>
</div>

<blockquote>
    <button>挂失</button>
</blockquote>
</form>
</body>
</html>
