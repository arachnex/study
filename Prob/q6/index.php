<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h1>CORS</h1>
    <p>Origin: http://localhost</p>
    <div id="demo"></div>
    <br><br><br><i>Reference: bee-box</i>
    <script>
        fetch("flag.php").then(t => t.text()).then(res => document.getElementById("demo").innerHTML = res);
    </script>
</body>
</html>