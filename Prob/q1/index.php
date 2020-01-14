<!DOCTYPE html>
<html>
    <meta charset="utf-8">
    <title>Index</title>
</html>
<body>
    <pre><?php echo shell_exec("ls -al"); ?></pre>
    <hr>
    <textarea name="" id="" cols="80" rows="10"><?php
        $file = $_GET['file'];
        if(isset($file) && ($file != "index")) {
            include "$file.php";
        } else {
            header("Location: ?file=hello");
        }
    ?></textarea>
    <p><i>webhacking.kr old-25 format<br>it is just for study...</i></p>
</body>