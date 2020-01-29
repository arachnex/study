<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    $origin = $_SERVER['HTTP_ORIGIN'];
    header("Access-Control-Allow-Origin: $origin");
}
if ($_SERVER['HTTP_ORIGIN'] == "http://localhost") {
    echo "flag{blah_blah_blah}";
} else {
    echo "No Flag";
}
?>