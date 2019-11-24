<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>消费记录</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body class="consume">
<section style="display: none;">
<dialog>
    <h2>消费详情</h2>
    <ol>
        <dl>
            <b>账户名称</b>
            <i></i>
            <s></s>
        </dl>
        <dl>
            <b>消费金额</b>
            <i></i>
            <s></s>
        </dl>
        <dl>
            <b>账户余额</b>
            <i></i>
            <s></s>
        </dl>
    </ol>
    <p>
        <tt>订单金额</tt>
        <var></var>
    </p>

    <blockquote>
        <button type="button" onclick="document.getElementsByTagName('section')[0].style.display = 'none';">关闭</button>
    </blockquote>
</dialog>
</section>

<ul>
<?php
$li = '';
foreach ($consumes as $consume) {
    $data = '';
    if (is_array($consume)) {
        foreach ($consume as $value) {
            if (3 == $value->acct_type_no) {
                $data .= 'data-row';
            } else {
                $data .= 'data-cash';
            }
            $data .= "='{\"balance\":\"$value->balance\", \"order_amount\":\"$value->payment_amount\"}' ";
        }
        $consume = $value;
    } else {
        if (3 == $consume->acct_type_no) {
            $data .= 'data-row';
        } else {
            $data .= 'data-cash';
        }
        $data .= "='{\"balance\":\"$consume->balance\", \"order_amount\":\"$consume->payment_amount\"}' ";
    }

    $li .= <<<HEREDOC
    <li $data onclick="show(this)" data-amount="$consume->order_amount">
        <span></span>
        <div>
            <dt>
                <h4>$consume->param_name</h4>
                <time>$consume->order_time</time>
            </dt>
            <dd>
                <em>金额:{$consume->order_amount}元</em>
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
<script>
function show(obj) {
    document.getElementsByTagName('section')[0].style.display = 'block'
    a = document.getElementsByTagName('i')
    s = document.getElementsByTagName('s')
    v = document.getElementsByTagName('var')[0]
    for (i = 0; i < 3; i++) {
        a[i].innerHTML = ''
        s[i].innerHTML = ''
    }

    cash = obj.getAttribute('data-cash')
    if (cash) {
        c = JSON.parse(cash)
        a[0].innerHTML = '现金账户'
        a[1].innerHTML = c.order_amount + '元'
        a[2].innerHTML = c.balance + '元'
    }

    row = obj.getAttribute('data-row')
    if (row) {
        r = JSON.parse(row)
        s[0].innerHTML = '补贴账户'
        s[1].innerHTML = r.order_amount + '元'
        s[2].innerHTML = r.balance + '元'
    }

    amount = obj.getAttribute('data-amount')
    v.innerHTML = amount + '（元）'

}
</script>
</body>
</html>
