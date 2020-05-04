<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>支出记录</title>
</head>

<body>
<table>
    <tr><th>#</th><th>日期</th><th>金额</th><th>项目</th><th>下单应用</th><th>用户</th><th>支付应用</th><th>账号</th><th>卡号</th><th>备注</th></tr>
<?php
$list = '';
foreach ($all as $row) {
    extract((array) $row);
    $list .= "<tr><td>$id</td><td>$paid</td><td>$title</td><td>$item</td><td>$order_app</td><td>$order_account</td><td>$app</td><td>$account_name</td><td>$number</td><td>$note</td></tr>";
}
echo $list;
?>
</table>
</body>
</html>
