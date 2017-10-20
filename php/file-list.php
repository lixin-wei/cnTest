<?php
require_once "functions.php";
$file_list = scandir("uploads/");


$file_list_view = array();
foreach ($file_list as $index => $file_name) {
    if(!is_dir("uploads/".$file_name)) {
        $args = explode('#', $file_name);
        $name = prettify($args[0]);
        $part = $args[1];
        $type = substr($args[2], 11, 3);
        if(!isset($file_list_view[$name]))
            $file_list_view[$name] = [];
        array_push($file_list_view[$name], array(
            'fileName' => $file_name,
            'partName' => $part,
            'date' => filemtime("uploads/".$file_name),
            'type' => $type
        ));
    }
}
function get_last_time($list) {
    $max_date = 0;
    foreach ($list as $ele) {
        $max_date = max($max_date, $ele['date']);
    }
    return $max_date;
}
function cmp($a, $b) {
    return get_last_time($a) < get_last_time($b);
}

uasort($file_list_view, 'cmp');

//var_dump($file_list_view);
date_default_timezone_set('PRC');
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/tether/1.4.0/css/tether.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
        }
        .card {
            margin-bottom: 20px;
            margin-right: 20px;
            min-width: 350px;
        }
        #search_box {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container" style="padding-top: 25px;">
    <h1>Upload files list</h1>
    <hr>
    <input id="search_box" type="text" class="form-control" placeholder="Search Name Here">
    <div class="card-container">
        <?php foreach ($file_list_view as $name => $file_list): ?>
            <div class="card">
                <h4 class="card-header"><?php echo $name ?></h4>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th style="border-top: 0;">Part Name</th>
                            <th style="border-top: 0;">Upload Date</th>
                        </tr>
                        </thead>
                        <?php foreach ($file_list as $file): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo "uploads/".urlencode($file['fileName'])?>"><?php echo $file['partName'].".".$file['type']?></a>
                                </td>
                                <td>
                                    <?php echo date("Y-m-d, h:i:s", $file['date'])?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
<script>
    function isMatch(item, pattern) {
        var dic_item = {}, dic_pattern = {}, i, c;
        for(i=0; i<26; ++i) {
            c = String.fromCharCode(i+97);
            dic_item[c]=0;
            dic_pattern[c]=0;
        }
        for(i=0; i<item.length; ++i) {
            dic_item[item[i].toLowerCase()]++;
        }
        for(i=0; i<pattern.length; ++i) {
            dic_pattern[pattern[i].toLowerCase()]++;
        }
        for(i=0; i<26; ++i) {
            c = String.fromCharCode(i+97);
            if(dic_item[c] < dic_pattern[c])
                return false;
        }
        return true;
    }
    $cardLilst = $(".card");
    $("#search_box").keyup(function () {
        var pattern = $(this).val(), i, name, card;
        for(i=0; i<$cardLilst.length; ++i) {
            $card = $($cardLilst[i]);
            name = $card.find(".card-header").text();
            if(isMatch(name, pattern)) {
                $card.show();
            }
            else {
                $card.hide();
            }
        }
    });
</script>
</html>