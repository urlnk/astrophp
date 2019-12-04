<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>易捷一卡通查询系统</title>
    <link rel="stylesheet" type="text/css" href="/css/touch.css<?=$static_version?>">
    <link rel="stylesheet" href="/css/jquery.cxcalendar.css<?=$static_version?>">
</head>

<body>
<section>
    <dialog>
        请稍后，正在查询卡信息……
    </dialog>
</section>

<div id="filter" class="flt" style="display: none">
    <form id="search_form">
        <ol>
            <li>
                <b>从</b>
                <span>
                    <time onclick="cxCalendarApi.show();">1990/01/01</time>
                    <input id="element_id" type="hidden" name="start" value="2019/01/01" data-format="YYYY/MM/DD" data-start-date="1919" data-end-date="2019" onchange="chg(this)">
                </span>
            </li>
            <li>
                <b>至</b>
                <span>
                    <time onclick="cxCalendarApi2.show();">1990/01/01</time>
                    <input id="element_id2" type="hidden" name="end" value="2019/11/28" data-format="YYYY/MM/DD" data-start-date="2019-01-01" data-end-date="2019/11/28" onchange="chg(this, 1)">
                </span>
            </li>
        </ol>

        <footer>
            <button type="button" onclick="flt()">取消</button>
            <button type="button">确定</button>
        </footer>
    </form>
</div>

<article class="start">
<div id="start_screen" style="display0: none">
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
</article>

<article class="users">
<div id="choice_user" style="display: none">
    <header>
        <h2>
            <a href="javascript:" onclick="back('choice_user', 'start_screen')">返回</a>
            <b>&nbsp;</b>
        </h2>
    </header>
    <h1>请选择用户：</h1>
    <ul id="users_list">
        <li>
            <a href="javascript:" onclick="choice(this)" data-uid="100">
                <u>测试调试卡测试调试卡测试调试卡</u>
                <p>1566555166015665551660</p>
                <p>马鞍山慈湖高新区马鞍山慈湖高新区</p>
                <p>默认机构默认机构默认机构默认机构</p>
            </a>
        </li>
        <li>
            <a href="javascript:">
                <u>测试调试卡</u>
                <p>15665551660</p>
                <p>马鞍山慈湖高新区</p>
                <p>默认机构</p>
            </a>
        </li>
        <li>
            <a href="javascript:">
                <u>测试调试卡</u>
                <p>15665551660</p>
                <p>马鞍山慈湖高新区</p>
                <p>默认机构</p>
            </a>
        </li>
        <li>
            <a href="javascript:">
                <u>测试调试卡</u>
                <p>15665551660</p>
                <p>马鞍山慈湖高新区</p>
                <p>默认机构</p>
            </a>
        </li>
        <li>
            <a href="javascript:">
                <u>测试调试卡</u>
                <p>15665551660</p>
                <p>马鞍山慈湖高新区</p>
                <p>默认机构</p>
            </a>
        </li>
    </ul>
</div>
</article>

<article class="home">
<div id="home" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('home', 'choice_user')">返回</a>
            <a href="javascript:" onclick="exit()">退出</a>
            <b>周晓明</b>
        </h2>
    </header>
    <ul>
        <li><a href="javascript:" onclick="showAccount()">账户信息</a></li>
        <li><a href="javascript:" onclick="show('home', 'recharge')">账户充值</a></li>
        <li><a href="javascript:" onclick="show('home', 'log')">充值记录</a></li>
        <li><a href="javascript:" onclick="show('home', 'consume')">消费记录</a></li>
        <li><a href="javascript:" onclick="show('home', 'loss')">挂失解挂</a></li>
        <li><a href="javascript:" onclick="show('home', 'info')">完善信息</a></li>
    </ul>
</div>
</article>

<article class="account">
<div id="account" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('account', 'home')">返回</a>
            <b>账户信息</b>
        </h2>
    </header>
    <ul>
        <dl>
            <dt>卡内码</dt>
            <dd>3009340722</dd>
        </dl>
        <dl>
            <dt>卡有效期</dt>
            <dd>2022-01-15 00:59:26</dd>
        </dl>
        <dl>
            <dt>现金账户</dt>
            <dd>100.00元</dd>
        </dl>
        <dl>
            <dt>补贴账户</dt>
            <dd>200.00元</dd>
        </dl>
        <dl>
            <dt>手机号码</dt>
            <dd>17621113580</dd>
        </dl>
    </ul>
</div>
</article>

<article class="account">
<div id="recharge" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('recharge', 'home')">返回</a>
            <b>账户充值</b>
        </h2>
    </header>
</div>
</article>

<article class="log">
<div id="log" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('log', 'home')">返回</a>
            <a href="javascript:" onclick="flt()">筛选</a>
            <b>充值记录</b>
        </h2>
    </header>
    <ul>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
        <li>
            <dfn>现金</dfn>
            <var>200.00元</var>
            <time>2019-10-05 15:43:52</time>
        </li>
    </ul>
</div>
</article>

<article class="account">
<div id="consume" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('consume', 'home')">返回</a>
            <b>消费记录</b>
        </h2>
    </header>
</div>
</article>

<article class="account">
<div id="loss" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('loss', 'home')">返回</a>
            <b>挂失解挂</b>
        </h2>
    </header>
</div>
</article>

<article class="account">
<div id="info" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('info', 'home')">返回</a>
            <b>完善信息</b>
        </h2>
    </header>
</div>
</article>

<blockquote>
    <input id="query" value="" placeholder="请输入明码" onfocus="this.select()" onchange="detect(this)">
</blockquote>

<script src="/js/jquery.min.js"></script>
<script src="/js/jquery.cxcalendar.js"></script>
<script src="/js/jquery.cxcalendar.languages.js"></script>
<script type="text/javascript" src="/js/start.js<?=$static_version?>"></script>
<script type="text/javascript" src="/js/log.js<?=$static_version?>"></script>
</body>
</html>
