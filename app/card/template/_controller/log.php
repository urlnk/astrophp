<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>充值记录</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body class="consume">
<ul>
<?php
$li = '';
foreach ($payments as $payment) {
    $li .= <<<HEREDOC
    <li>
        <span class="chongzhi"></span>
        <div>
            <dt>
                <h4>$payment->param_name</h4>
                <time>$payment->order_time</time>
            </dt>
            <dd>
                <em>金额:{$payment->order_amount}元</em>
                <cite></cite>
            </dd> 
        </div>
    </li>
HEREDOC;
}

echo $li;
?>
</ul>

<pre>没有更多数据了</pre>
</body>
</html>
