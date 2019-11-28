<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>挂失解挂</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css<?=$static_version?>">
</head>

<body class="account form-layout">
<form method="post" onsubmit="document.getElementsByTagName('button')[0].innerHTML = '请稍后……';">
<div>
    <h4>卡信息</h4>
    <ul>
        <dl>
            <dt>卡内码</dt>
            <dd><?=$card->card_code?></dd>
        </dl>
        <dl>
            <dt>卡状态</dt>
            <dd><?=$card->param_name?></dd>
        </dl>
    </ul>
</div>

<input type="hidden" name="card_status" value="<?=$card->card_status?>">

<blockquote>
    <button type="submit"><?php echo $status = 'LOST' == $card->card_status ? '解挂' : '挂失'; ?></button>
</blockquote>
</form>
</body>
</html>
