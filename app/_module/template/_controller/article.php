<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>文章列表</title>
</head>

<body>
<table>
    <tr><th>#</th><th>标题</th><th>作者</th><th>写作年份</th></tr>
<?php
$list = '';
foreach ($all as $row) {
    extract((array) $row);
    $list .= "<tr><td>$id</td><td>$title</td><td>$name</td><td>$writeYear</td></tr>";
}
echo $list;
?>
</table>
</body>
</html>
