<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>消费记录</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body class="consume">
<section>
<dialog>
    <h2>消费详情</h2>
    <ol>
        <dl>
            <b>账户名称</b>
            <i>现金账户</i>
        </dl>
        <dl>
            <b>消费金额</b>
            <i>-11元</i>
        </dl>
        <dl>
            <b>账户余额</b>
            <i>126元</i>
        </dl>
    </ol>
    <p>
        <tt>订单金额</tt>
        <var>11（元）</var>
    </p>

    <blockquote>
        <button>关闭</button>
    </blockquote>
</dialog>
</section>

<ul>
<?php
$li = '';
foreach ($consumes as $consume) {
    $li .= <<<HEREDOC
    <li data-row="{balance:'$consume->balance', order_amount:'$consume->order_amount'}">
        <span></span>
        <div>
            <dt>
                <h4>消费</h4>
                <time>$consume->order_time</time>
            </dt>
            <dd>
                <em>金额:{$consume->payment_amount}元</em>
                <cite>$consume->device_name</cite>
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
