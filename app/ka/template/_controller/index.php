<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>易捷一卡通查询系统</title>
    <link rel="stylesheet" type="text/css" href="/css/touch.css<?=$static_version?>">
    <link rel="stylesheet" href="/css/jquery.cxcalendar.css<?=$static_version?>">
</head>

<body>
<section class="dlg">
    <dialog>请稍后，正在查询卡信息……</dialog>
</section>

<section class="info" onclick="this.style.display = 'none'">
    <dialog>
        <ol>
            <li>
                <label>
                    <span>男</span>
                    <input type="radio" name="sex" value="男" onClick="clk(this)">
                </label>
            </li>
            <li>
                <label>
                    <span>女</span>
                    <input type="radio" name="sex" value="女" onClick="clk(this)">
                </label>
            </li>
        </ol>
    </dialog>
</section>

<section class="tip prompt" onclick="hide(this)">
    <dialog>
        <p>为了您的账户安全，请绑定手机号码！</p>
        <footer>
            <button type="button" onclick="hidePhoneTip()">取消</button>
            <button type="submit" onclick="showPhone('home', '绑定')">立即绑定</button>
        </footer>
    </dialog>
</section>

<section class="tip" data-code="" onclick="hide(this)">
    <dialog>
        <p>修改成功</p>
        <footer>
            <button type="submit" onclick="hideTip()">确定</button>
        </footer>
    </dialog>
</section>

<section class="tip prompt" onclick="hide(this)">
    <dialog>
        <p>请直接刷卡，或者用手机号登录</p>
        <footer>
            <button type="button" onclick="hide(ele.section[4])">取消</button>
            <button type="submit" onclick="showLogin(1)">手机号登录</button>
        </footer>
    </dialog>
</section>

<div id="filter" class="flt" style="display: none">
    <form id="search_form" data-type="0" onsubmit="return filterSubmit()">
        <ol>
            <li>
                <b>从</b>
                <span>
                    <time onclick="cxCalendarApi.show();" id="startDate"></time>
                    <input id="element_id" type="hidden" name="start" value="" data-format="YYYY/MM/DD" onchange="chg(this, 'startDate')">
                </span>
            </li>
            <li>
                <b>至</b>
                <span>
                    <time onclick="cxCalendarApi2.show();" id="endDate"></time>
                    <input id="element_id2" type="hidden" name="end" value="" data-format="YYYY/MM/DD" onchange="chg(this, 'endDate')">
                </span>
            </li>
        </ol>

        <footer>
            <button type="button" onclick="flt()">取消</button>
            <button type="submit">确定</button>
        </footer>
    </form>
</div>

<article class="start">
<div id="start_screen">
    <h1>
        <img src="img/logo.png">
        <b>消费信息查询系统</b>
        <span></span>
        <p>咨询电话：0555-2222114</p>
    </h1>
    <ul>
        <li><a class="btn-acc" href="javascript:" onclick="showSignTip(2)">账户信息</a></li>
        <li><a class="btn-rec" href="javascript:" onclick="showSignTip(3)">账户充值</a></li>
        <li><a class="btn-log" href="javascript:" onclick="showSignTip(4)">充值记录</a></li>
        <li><a class="btn-con" href="javascript:" onclick="showSignTip(5)">消费记录</a></li>
        <li><a class="btn-los" href="javascript:" onclick="showSignTip(6)">挂失解挂</a></li>
        <li><a class="btn-inf" href="javascript:" onclick="showSignTip(7)">完善信息</a></li>
    </ul>
    <ol>
        <dl><a href="javascript:"></a></dl>
    </ol>
    <dir>
        <dt><a class="btn-pho" href="javascript:" onclick="showLogin()">手机号登录</a></dt>
        <dd><a class="btn-car" href="javascript:" onclick="showSwipe()">刷卡登录</a></dd>
    </dir>
</div>
</article>

<article class="users">
<div id="choice_user" style="display: none">
    <header>
        <h2>
            <a href="javascript:" onclick="previous()">返回</a>
            <b>&nbsp;</b>
        </h2>
    </header>
    <h1>请选择用户：</h1>
    <ul id="users_list">
    </ul>
</div>
</article>

<article class="home">
<div id="home" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('home', 'choice_user')">返回</a>
            <a class="lnk-exit" href="javascript:" onclick="exit()">退出</a>
            <b></b>
        </h2>
    </header>
    <ul>
        <li><a class="btn-acc" href="javascript:" onclick="showAccount()" data-id="account">账户信息</a></li>
        <li><a class="btn-rec" href="javascript:" onclick="back('home', 'recharge')" data-id="recharge">账户充值</a></li>
        <li><a class="btn-log" href="javascript:" onclick="showLog()" data-id="log">充值记录</a></li>
        <li><a class="btn-con" href="javascript:" onclick="showConsume()" data-id="consume">消费记录</a></li>
        <li><a class="btn-los" href="javascript:" onclick="showLoss()" data-id="loss">挂失解挂</a></li>
        <li><a class="btn-inf" href="javascript:" onclick="showInfo()" data-id="info">完善信息</a></li>
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
            <dt>商户</dt>
            <dd></dd>
        </dl>
        <dl>
            <dt>组织</dt>
            <dd></dd>
        </dl>
        <dl>
            <dt>用户号</dt>
            <dd></dd>
        </dl>
        <dl>
            <dt>卡内码</dt>
            <dd></dd>
        </dl>
        <dl>
            <dt>开卡时间</dt>
            <dd></dd>
        </dl>
        <dl>
            <dt>卡有效期</dt>
            <dd></dd>
        </dl>
        <dl>
            <dt>现金账户</dt>
            <dd></dd>
        </dl>
        <dl>
            <dt>补贴账户</dt>
            <dd></dd>
        </dl>
        <dl>
            <dt>手机号码</dt>
            <dd></dd>
        </dl>
    </ul>
    <ol>
        <li><a class="btn-acc" href="javascript:" onclick="showPhone('account')" id="bindPhone" data-title="绑定手机">绑定手机号</a></li>
    </ol>
</div>
</article>

<article class="loss">
<div id="recharge" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('recharge', 'home')">返回</a>
            <b>账户充值</b>
        </h2>
    </header>
    <ol>
        <li><a class="btn-rec" href="javascript:">暂未开通在线支付功能</a></li>
    </ol>
</div>
</article>

<article class="log">
<div id="log" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('log', 'home')">返回</a>
            <a class="lnk-log" href="javascript:" onclick="flt()">筛选</a>
            <b>充值记录</b>
        </h2>
    </header>
    <main>
    <ul id="logs_list">
    </ul>
    <p>没有更多数据了</p>
    </main>
</div>
</article>

<article class="consume">
<div id="consume" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="back('consume', 'home')">返回</a>
            <a class="lnk-cons" href="javascript:" onclick="flt()">筛选</a>
            <b>消费记录</b>
        </h2>
    </header>
    <main>
    <ul id="consumes_list">
    </ul>
    <p>没有更多数据了</p>
    </main>
</div>
</article>

<article class="loss">
<div id="loss" style="display: none;" data-status="">
    <header>
        <h2>
            <a href="javascript:" onclick="back('loss', 'home')">返回</a>
            <b>挂失解挂</b>
        </h2>
    </header>
    <ul>
        <dl>
            <dt>卡内码</dt>
            <dd></dd>
        </dl>
        <dl>
            <dt>卡状态</dt>
            <dd></dd>
        </dl>
    </ul>
    <ol>
        <li><a class="btn-los" href="javascript:" onclick="loss()">挂失</a></li>
    </ol>
</div>
</article>

<article class="info">
<div id="info" style="display: none;" data-sex="">
    <header>
        <h2>
            <a href="javascript:" onclick="back('info', 'home')">返回</a>
            <b>完善信息</b>
        </h2>
    </header>
    <ul>
        <dl>
            <dt>姓名</dt>
            <dd>
                <input type="text" name="user_name" value="" placeholder="请输入姓名" autocomplete="off" />
            </dd>
        </dl>
        <dl>
            <dt>性别</dt>
            <dd>
                <tt onclick="sex()"></tt>
                <a href="javascript:" class="sex" onclick="sex()"></a>
            </dd>
        </dl>
        <dl>
            <dt>生日</dt>
            <dd>
                <time onclick="cxCalendarApi3.show();" id="birthdays"></time>
                <input id="element_id3" type="hidden" name="birthday" value="" data-format="YYYY/MM/DD" data-start-date="" data-end-date="" onchange="chg(this, 'birthdays')">
            </dd>
        </dl>
        <dl>
            <dt>身份证号</dt>
            <dd>
                <input type="text" name="identity_card" value="" placeholder="请输入身份证号" autocomplete="off" />
            </dd>
        </dl>
        <dl>
            <dt>联系地址</dt>
            <dd>
                <input type="text" name="address" value="" placeholder="请输入联系地址" autocomplete="off" />
            </dd>
        </dl>
        <dl>
            <dt>车牌号</dt>
            <dd>
                <input type="text" name="license_plate_no" value="" placeholder="请输入车牌号" autocomplete="off" />
            </dd>
        </dl>
    </ul>
    <ol>
        <li><a class="btn-inf" href="javascript:" onclick="info()">修改</a></li>
    </ol>
</div>
</article>

<article class="info phone">
<div id="phone" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="hidePhone()">返回</a>
            <b id="phoneTitle">绑定手机</b>
        </h2>
    </header>
    <ul>
        <dl>
            <dt>手机号</dt>
            <dd>
                <input type="text" name="phone" value="" placeholder="请输入手机号" autocomplete="off" />
            </dd>
        </dl>
        <dl>
            <dt>验证码</dt>
            <dd class="code">
                <input type="text" name="code" placeholder="请输入验证码" autocomplete="off" />
                <button type="button" name="verify" onclick="sendsms(this)" id="btnSms">发送验证码</button>
            </dd>
        </dl>
    </ul>
    <ol>
        <li><a class="btn-pho" href="javascript:" onclick="bind()">确定</a></li>
    </ol>
</div>
</article>

<article class="info phone">
<div id="login" style="display: none;">
    <header>
        <h2>
            <a href="javascript:" onclick="hideLogin()">返回</a>
            <b>手机号登录</b>
        </h2>
    </header>
    <ul>
        <dl>
            <dt>手机号</dt>
            <dd>
                <input type="text" name="phone" value="" placeholder="请输入手机号" autocomplete="off" />
            </dd>
        </dl>
        <dl>
            <dt>验证码</dt>
            <dd class="code">
                <input type="text" name="code" placeholder="请输入验证码" autocomplete="off" />
                <button type="button" name="verify" onclick="sendsms(this)" id="verify">发送验证码</button>
            </dd>
        </dl>
    </ul>
    <ol>
        <li><a class="btn-pho" href="javascript:" onclick="login()">登录</a></li>
    </ol>
    <!--p>温馨提示：如果您需要刷卡登录，请返回主屏幕再刷卡</p-->
</div>
</article>

<blockquote>
    <input id="query" value="" placeholder="请输入明码" onfocus="this.select()" onchange="detect(this)">
</blockquote>

<script>
server = {
    interval: <?=$interval?>,
    hta: <?=$hta?>,
    test: '<?=$testCardCode?>',
    captcha: '<?=$captcha?>'
}
</script>
<script src="/js/jquery.min.js"></script>
<script src="/js/jquery.cxcalendar.js"></script>
<script src="/js/jquery.cxcalendar.languages.js"></script>
<script type="text/javascript" src="/js/start.js<?=$static_version?>"></script>
<script type="text/javascript" src="/js/log.js<?=$static_version?>"></script>
</body>
</html>
