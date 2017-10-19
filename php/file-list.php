<?php

$file_list = scandir("uploads/");

$file_list_view = [];
foreach ($file_list as $index => $name) {
    if(!is_dir($name)) {
        array_push($file_list_view, array(
            'name' => $name,
            'date' => filemtime("uploads/".$name)
        ));
    }
}
usort($file_list_view, function($a, $b) {
    return $a['date']<$b['date'];
});
date_default_timezone_set('PRC');
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/tether/1.4.0/css/tether.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<div class="container" style="padding-top: 25px;">
    <h1>Upload files list</h1>
    <table class="table table-striped table-hover">
        <thead class="thead-inverse">
            <tr>
                <th>File Name</th>
                <th>Upload Date</th>
            </tr>
        </thead>
        <?php
        foreach ($file_list_view as $f) {
            echo "<tr>";
            echo "<td><a href='uploads/{$f['name']}'>{$f['name']}</a></td>";
            echo "<td>".date("Y-m-d H:i:s", $f['date'])."</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>