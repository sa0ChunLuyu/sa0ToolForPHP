<?php

$appConfig = array(
    'title' => 'sa0Test',
    'content' => 'Yo',
    'power' => 'sa0Test PHP',
    'author' => 'sa0ChunLuyu',
    'describe' => 'HAPPY HACKING',
);

?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $appConfig['title'] ?></title>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .page-wrapper {
            align-items: center;
            display: flex;
            height: 100vh;
            justify-content: center;
            position: relative;
        }

        .page-container {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .mb-3 {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="page-container">
        <div class="title mb-3">
            <?php echo $appConfig['content'] ?>
        </div>
        <div><?php echo $appConfig['power'] ?> | <?php echo $appConfig['author'] ?></div>
        <div><?php echo $appConfig['describe'] ?></div>
    </div>
</div>
</body>
</html>
