<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php
        file_css([
            '/css/lib/style1.css', 
            '/css/lib/style2.css',
            '/css/lib/style3.css'
        ]);

        file_JS([
            '/js/index.js', 
            '/js/lib/boot'
        ]);

        file_CSS('/final');
    ?>
</head>
<body>
    <?php
        echo $status . $test;
    ?>
</body>
</html>