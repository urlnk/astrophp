<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="format-detection" content="telephone=no, email=no, address=no">
    <title>账户信息</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css<?=$static_version?>">
</head>

<body class="account">
<section style="display: none;">
<dialog>
    <form method="post" action="/card/api/password" onsubmit="document.getElementsByTagName('section')[0].style.display = 'none'; return false;">
        <ol>
            <li>
                <b>手机号</b>
                <span>
                    <input type="text" name="phone" value="<?=$_SESSION['phone']?>" placeholder="">
                </span>
            </li>
            <li>
                <b>
                    <a href="javascript:">验证码</a>
                </b>
                <span>
                    <input type="text" name="code" value="" placeholder="请输入验证码">
                </span>
            </li>
            <li>
                <b>新密码</b>
                <span>
                    <input type="text" name="password" value="" placeholder="请输入新密码">
                </span>
            </li>
            <li>
                <b>确认密码</b>
                <span>
                    <input type="text" name="pwd" value="" placeholder="请再次输入新密码">
                </span>
            </li>
        </ol>

        <footer>
            <button type="button" onclick="document.getElementsByTagName('section')[0].style.display = 'none';">取消</button>
            <button type="submit">确定</button>
        </footer>
    </form>
</dialog>
</section>

<form class="form-layout">
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
            <dd><?=$arr[1]?>元</dd>
        </dl>
        <dl>
            <dt>补贴账户</dt>
            <dd><?=$arr[3]?>元</dd>
        </dl>
<?php
if ($arr[3]) {
    $arr[3] += $arr[1];
    echo "<dl>
            <dt>账户合计</dt>
            <dd>{$arr[3]}元</dd>
        </dl>";
}
?>
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
    <button type="button" onclick="document.getElementsByTagName('section')[0].style.display = 'block';">修改超额支付密码</button>
</blockquote>
</form>
</body>
</html>
