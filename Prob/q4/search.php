<?php
$target = base64_decode($_POST['target']);
$query = $_POST['query'];
$url = $target.$query;
echo file_get_contents($url);
?>