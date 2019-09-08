<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
<form action="<?=$url?>" method="post">
<?php
foreach ($params as $key => $value) {
    $key = htmlspecialchars($key);
    $value = htmlspecialchars($value);
    echo $html = "<input name=\"$key\" value=\"$value\" />";
}
?>
<button type="submit">Submit</button>
</form>
</body>
</html>
