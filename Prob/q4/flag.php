<?php
if ($_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR']) {
    echo "flag{blah_blah_blah}";
} else {
    echo "You need to access by local!";
}
?>