<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>通话记录</title>
</head>

<body>
<table>
    <tr><th>#</th><th>FROM</th><th>TO</th><th>TIME</th><th>LENGTH</th><th>COST</th><th>MEMO</th><th>NOTE</th><th>APP_TAGS</th></tr>
<?php
$list = '';
foreach ($all as $row) {
    extract((array) $row);
    $from_str = $fc ? $fc . '-' : '';
    $from_str .= $f;
    $to_str = $tc ? $tc . '-' : '';
    $to_str .= $t;
    $color = 0 > $length ? '#f00' : '';
    $type = '';
    if ($num) {        
        if ($num == $from_str) {
            $type = '去电';
        } else {
            $to_str = $from_str;
        }
        $from_str = $type;        
    }
    $list .= "<tr><td>$id</td><td>$from_str</td><td style=\"color:$color;\">$to_str</td><td>$time</td><td>$length</td><td>$cost</td><td>$memo</td><td>$note</td><td>$app_tags</td></tr>";
}
echo $list;
?>
</table>
</body>
</html>
