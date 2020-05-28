<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>联系人</title>
</head>

<body>
<?php
extract((array) $row);
?>
<table>
<tr><th>姓名</th><td><?=$NickName?></td></tr>
<tr><th>备注</th><td><?=$note?></td></tr>
<tr><th>备忘</th><td><?=$memo?></td></tr>
</table>

<h2>电话</h2>
<table>
    <tr><th>#</th><th>号码</th><th>备注</th></tr>
<?php
$list = '';
$i = 1;
foreach ($all as $row) {
    extract((array) $row);
    $list .= "<tr><td>$i</td><td>$phone_number</td><td>$note</td></tr>";
    $i++;
}
echo $list;
?>
</table>

<h2>APP</h2>
<table>
    <tr><th>#</th><th>APP</th><th>ID</th><th>电话</th></tr>
<?php
$list = '';
$i = 1;
foreach ($app as $row) {
    extract((array) $row);
    $list .= "<tr><td>$i</td><td>$name</td><td>$app_account</td><td>$phone</td></tr>";
    $i++;
}
echo $list;
?>
</table>
</body>
</html>
