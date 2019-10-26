<!doctype html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo SA0_TOOL_TITLE ?></title>
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
            <?php echo $this->message ?>
        </div>
        <div><?php echo SA0_TOOL_TITLE.' '.SA0_TOOL_VERSION ?> | <?php echo SA0_TOOL_AUTHOR ?></div>
        <div><?php echo SA0_TOOL_CONTENT ?></div>
    </div>
</div>
</body>
</html>