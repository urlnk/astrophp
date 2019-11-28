<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>充值记录</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" href="/css/jquery.cxcalendar.css">
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

<header class="top">
    <a href="/card" class="back"></a>
    <a href="javascript:" class="filter" onclick="flt()">筛选</a>
</header>

<ul class="list">
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

<script src="/js/jquery.min.js"></script>
<script src="/js/jquery.cxcalendar.js"></script>
<script src="/js/jquery.cxcalendar.languages.js"></script>
<script type="text/javascript" src="/js/consume.js"></script>
</body>
</html>
