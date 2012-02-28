<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <?php if(isset($time) && isset($url)) { echo '<meta http-equiv="refresh" content="' . $time . ';url=' . $config['site']['fullpath'] . $url . '">' . "\n"; } ?>
        <title><? if(isset($title)) echo $title . ' | Thông báo | VHMIS'; else echo 'VHMIS'; ?></title>

        <!-- Style -->
        <link href="/VHMIS_WWW/client/default/css/default.css" rel="stylesheet">
    </head>
    <body>
        <div class="container container-message">
            <div class="alert alert-block">
                <h4><?php if(isset($title)) echo $title; ?></h4>
                <br>
                <?php if(isset($message)) echo $message; ?>

            </div>
        </div>
    </body>
</html>