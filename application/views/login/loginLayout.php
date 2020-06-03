<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Test New</title>

        <!-- Bootstrap -->
        <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>assets/css/nprogress.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>assets/css/prettify.min.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>assets/css/custom.css" rel="stylesheet">
    </head>

    <body class="login">

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <?php
                    $this->load->view($pageUrl);
                    ?>
                </section>
            </div>
        </div>
         <script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
       <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
    </body>
</html>
