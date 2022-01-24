<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>完善信息</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css<?=$static_version?>">
    <link rel="stylesheet" href="/css/jquery.cxcalendar.css<?=$static_version?>">
</head>

<body class="login info">
<section style="display: none">
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

<section style="display: <?=$tip ? 'block' : 'none'?>;">
    <dialog>
        <p>
            修改成功
        </p>

        <footer>
            <button type="button" onclick="document.getElementsByTagName('section')[1].style.display = 'none';">确定</button>
        </footer>
    </dialog>
</section>

<form method="post" action="">
    <div>
        <dl>
            <dt>姓名</dt>
            <dd>
                <input type="text" name="user_name" value="<?=$user->user_name?>" placeholder="请输入姓名" />
            </dd>
        </dl>
        <dl>
            <dt>性别</dt>
            <dd>
                <tt><?=$user->sex?></tt>
                <a href="javascript:" class="sex" onclick="document.getElementsByTagName('section')[0].style.display = 'block';"></a>
            </dd>
        </dl>
        <dl>
            <dt>生日</dt>
            <dd>
                <time onclick="cxCalendarApi.show();"><?=$user->birthday?></time>
                <input id="element_id" type="hidden" name="birthday" value="<?=$user->birthday?>" data-format="YYYY/MM/DD" data-start-date="<?=$year - 100?>" data-end-date="<?=$year?>" onchange="chg(this)">
            </dd>
        </dl>
        <dl>
            <dt>身份证号</dt>
            <dd>
                <input type="text" name="identity_card" value="<?=$user->identity_card?>" placeholder="请输入身份证号" />
            </dd>
        </dl>
    </div>
    <input type="hidden" name="sex" value="<?=$user->sex?>" />

    <pre><?=$err?></pre>

    <blockquote style="<?php if ($err) { echo 'margin-top: 0;'; } ?>">
        <button type="submit">修改</button>
    </blockquote>
</form>

<script src="/js/jquery.min.js"></script>
<script src="/js/jquery.cxcalendar.js<?=$static_version?>"></script>
<script src="/js/jquery.cxcalendar.languages.js"></script>
<script type="text/javascript" src="/js/info.js<?=$static_version?>"></script>
</body>
</html>
