<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Lỗi | VHMIS</title>

        <!-- Style -->
        <link href="<?php echo $config['site']['fullclient']; ?>css/default.css" rel="stylesheet">
    </head>
    <body>
        <div class="container container-message">
            <div class="alert alert-block alert-error">
                <h4><?php if(isset($title)) echo $title; ?></h4>
                <br>
                <?php if(isset($message)) echo $message; ?>
            </div>
        </div>
    </body>
</html>