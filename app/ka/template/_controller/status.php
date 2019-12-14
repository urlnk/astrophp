<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>设备状态和访问统计</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css<?=$static_version?>">
</head>

<body>
<table border="1">
    <tr>
        <th>#</th>
        <th>编号</th>
        <th>名称</th>
        <th>更新时间</th>
        <th>更新次数</th>
    </tr>
<?php
$i = 0;
$tr = '';
foreach ($all as $row) {
    $i++;
    $tr .= "<tr>
        <td>$i</td>
        <td>$row->no</td>
        <td>$row->title</td>
        <td>$row->updated</td>
        <td>$row->updates</td>
    </tr>";
}
echo $tr;
?>
</table>
</body>
</html>
