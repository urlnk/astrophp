<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>完善信息</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body class="login">
<form method="post" action="">
    <div>
        <dl>
            <dt>姓名</dt>
            <dd>
                <input type="text" name="name" value="<?=$user->user_name?>" placeholder="请输入姓名" />
            </dd>
        </dl>
        <dl>
            <dt>性别</dt>
            <dd>
                <tt><?=$user->sex?></tt>
                <a href="" class="sex"></a>
            </dd>
        </dl>
        <dl>
            <dt>生日</dt>
            <dd>
                <time><?=$user->birthday?></time>
            </dd>
        </dl>
    </div>
    <blockquote>
        <button type="button">修改</button>
    </blockquote>
</form>
</body>
</html>
