<?php
// DB Info
$host = "localhost";
$username = "admin";
$passwd = "";
$dbname = "test";

$conn = new mysqli($host, $username, $passwd, $dbname);

$db = "create table board(
    name varchar(32),
    msg varchar(256));";
$conn->query($db);

// Check the number of message
$select = "select * from board;";
$result = $conn->query($select);

if ($result->num_rows >= 3) {
    $delete = "delete from board;";
    $conn->query($delete);
}

// Save the message
if($_POST['name'] && $_POST['msg']) {
    $name = addslashes($_POST['name']);
    $msg = addslashes($_POST['msg']);
    $insert = "insert into board (name, msg) values ('$name', '$msg');";
    $conn->query($insert);
}
?>
<!DOCTYPE html>
<html>
    <meta charset="utf-8">
    <script>
        function save() {
            var name = document.getElementsByName("name")[0].value;
            var msg = document.getElementsByName("msg")[0].value;

            if (name == '' || msg == '') {
                window.alert("Fill in the blank");
            } else {
                document.getElementsByTagName("form")[0].submit();
            }
        }

        function refresh() {
            window.location.reload();
        }

        // 플래그가 저장된 사용자의 IP 지정
        var ip = "<?php $_SERVER['REMOTE_ADDR']; ?>";
        if(ip == '') {
            window.setTimeout(refresh, 60000);
        }
    </script>
</html>
<body>
    <form action="" method="POST">
        <table>
            <tr>
                <td>Name</td>
                <td><input type="text" name="name"></td>
            </tr>
            <tr>
                <td>Message</td>
                <td><textarea name="msg" cols="40" rows="5"></textarea></td>
            </tr>
        </table>
        <input type="button" value="Post" onclick="save()">
    </form><hr>
    <?php
    // 메시지 출력
    $result = $conn->query($select);

    while ($row = $result->fetch_assoc()) {
        echo "<p><b>".$row['name']."</b></p>";
        echo "<p>".$row['msg']."</p><hr>";
    }
    ?>
</body>
<?php
$conn->close();
?>