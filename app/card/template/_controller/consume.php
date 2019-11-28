<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>消费记录</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css<?=$static_version?>">
    <link rel="stylesheet" href="/css/jquery.cxcalendar.css<?=$static_version?>">
</head>

<body class="consume">
<div id="filter" class="flt" style="display: none">
    <form>
        <ol>
            <li>
                <b>从</b>
                <span>
                    <time onclick="cxCalendarApi.show();"><?=$start?></time>
                    <input id="element_id" type="hidden" name="start" value="<?=$start?>" data-format="YYYY/MM/DD" data-start-date="<?=$start_date?>" data-end-date="<?=$end_date?>" onchange="chg(this)">
                </span>
            </li>
            <li>
                <b>至</b>
                <span>
                    <time onclick="cxCalendarApi2.show();"><?=$end?></time>
                    <input id="element_id2" type="hidden" name="end" value="<?=$end?>" data-format="YYYY/MM/DD" data-start-date="<?=$start_date?>" data-end-date="<?=$end_date?>" onchange="chg(this, 1)">
                </span>
            </li>
        </ol>

        <footer>
            <button type="button" onclick="flt()">取消</button>
            <button type="submit">确定</button>
        </footer>
    </form>
</div>

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

<header class="top">
    <a href="/card" class="back"></a>
    <a href="javascript:" class="filter" onclick="flt()">筛选</a>
</header>

<ul class="list">
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

<script src="/js/jquery.min.js"></script>
<script src="/js/jquery.cxcalendar.js<?=$static_version?>"></script>
<script src="/js/jquery.cxcalendar.languages.js"></script>
<script type="text/javascript" src="/js/consume.js<?=$static_version?>"></script>
</body>
</html>
