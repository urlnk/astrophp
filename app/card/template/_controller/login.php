<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>用户登录</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css<?=$static_version?>">
</head>

<body class="login">
<form method="post" action="" name="loginForm">
    <div>
        <dl>
            <dt>商户号</dt>
            <dd>
                <select name="oid">
<?php
$option = '';
foreach ($operators as $operator) {
    $sel = $operator->operator_id == $oid ? 'selected' : '';
    $option .= "<option value=\"$operator->operator_id\" $sel>$operator->operator_name</option>";
}
echo $option;
?>
                </select>
            </dd>
        </dl>
        <dl>
            <dt>手机号</dt>
            <dd>
                <input type="text" name="phone" placeholder="请输入手机号" value="<?=$phone?>" autocomplete="off" />
                <button type="button" onclick="sendsms(this)" id="btnSms">验证码</button>
            </dd>
        </dl>
        <dl>
            <dt>验证码</dt>
            <dd>
                <input type="text" name="code" placeholder="请输入验证码" autocomplete="off" />
            </dd>
        </dl>
    </div>

    <pre><?=$err?></pre>
    
    <blockquote style="<?php if ($err) { echo 'margin-top: 0;'; } ?>">
        <button type="submit">确定</button>
    </blockquote>    
</form>

<script type="text/javascript" src="/js/script.js<?=$static_version?>"></script>
<script type="text/javascript" src="/js/login.js<?=$static_version?>"></script>
</body>
</html>
