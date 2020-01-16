<?php
class File {
    public $file;

    public function __toString() {
        return file_get_contents($this->file);
    }
}

class User {
    public $name = "guest";

    public function __toString() {
        return "<h1>Hello, $this->name!</h1>"."<p>Can you find <b>flag.php</b>?</p>";
    }
}

$user = new User();

if(!isset($_COOKIE['user'])) {
    $cookie = base64_encode(serialize($user));
    setcookie("user", $cookie);
}

$obj = unserialize(base64_decode($_COOKIE['user']));
echo $obj;

echo "<hr>";
highlight_file("index.php");
?>