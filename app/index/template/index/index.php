<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>有链网 ^_^</title>
</head>

<body>
<form id="search_form" action="/s" target="_search">
    <input type="text" name="q" />
    <button type="submit">Go</button>
    <input type="hidden" name="id" id="engine_id">
</form>

<?php
$html = '';
foreach ($arr as $k => $variable) {
	$p = '<p>' . PHP_EOL;
	foreach ($variable as $key => $value) {
		list($id, $name) = $value;
		$button = "<button type=\"button\" onclick=\"go($id)\">$name</button>";
		$p .= $button . PHP_EOL;
	}
	$p .= '</p>' . PHP_EOL;
	$html .= $p;
}
echo $html;
?>

<script type="text/javascript" src="js/search.js"></script>
</body>
</html>
