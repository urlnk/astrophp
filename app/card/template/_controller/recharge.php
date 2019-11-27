<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>账户充值</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body class="recharge">
<form method="post" action="">
    <div>
        <h4>请选择充值账户</h4>
        <ul>
            <dl>
                <dt>现金账户</dt>
                <dd>
                    <s class="sel"></s>
                    <var><?=$arr[1]?> 元</var>
                </dd>
            </dl>
            <dl>
                <dt>补贴账户</dt>
                <dd>
                    <s></s>
                    <var><?=$arr[3]?> 元</var>
                </dd>
            </dl>
        </ul>
    </div>

    <div class="pay">
        <tt>充值金额(元)</tt>
        <i></i>
        <span>
            <input type="text" name="" placeholder="请输入充值金额">
        </span>
    </div>

    <blockquote>
        <button type="button">暂未开通在线支付功能</button>
    </blockquote>    
</form>
</body>
</html>
