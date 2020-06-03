<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Test New </title>

        <!-- Bootstrap -->
        <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="<?php echo base_url() ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="<?php echo base_url() ?>assets/css/nprogress.css" rel="stylesheet">
        <!-- bootstrap-wysiwyg -->
        <link href="<?php echo base_url() ?>assets/css/prettify.min.css" rel="stylesheet">
        <!-- animate -->
        <link href="<?php echo base_url() ?>assets/css/animate.css" rel="stylesheet">
        <!-- Custom styling plus plugins -->
        <link href="<?php echo base_url() ?>assets/css/custom.css" rel="stylesheet">
        <!-- data table -->
        <link href="<?php echo base_url() ?>assets/css/dataTables.bootstrap.css" rel="stylesheet" />
        <!-- bootstrap-daterangepicker -->
        <link href="<?php echo base_url() ?>assets/css/daterangepicker.css" rel="stylesheet">
        <!-- bootstrap-datetimepicker -->
        <link href="<?php echo base_url() ?>assets/css/bootstrap-datetimepicker.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>assets/css/jquery.mCustomScrollbar.min.css" rel="stylesheet">
        <!-- jQuery -->
        <script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/validator.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
        <!-- sweet alert -->
        <link href="<?php echo base_url() ?>assets/css/sweetalert.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>assets/js/sweetalert.min.js"></script>
        <!-- data table -->
        <script src="<?php echo base_url() ?>assets/js/jquery.dataTables.js"></script>
        <script src="<?php echo base_url() ?>assets/js/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url() ?>assets/js/dataTable.js"></script>
        <!-- notify -->
        <script src="<?php echo base_url() ?>assets/js/bootstrap-notify.js"></script>
        <script src="<?php echo base_url() ?>/assets/js/moment.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/daterangepicker.js"></script>
        <script src="<?php echo base_url() ?>assets/js/bootstrap-datetimepicker.min.js"></script>
        <!-- bootstrp select -->
        <link href="<?php echo base_url() ?>assets/css/bootstrap-select.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>assets/js/bootstrap-select.js"></script>
        <script>
            var BASE_URL = '<?php echo base_url() ?>';
            var type = '<?php echo $msgFlag ?>';
            var msg = '<?php echo $msg ?>';
            var icon = "glyphicon glyphicon-ok";
            var title = "Success<br>";
            if (type === 'danger') {
                icon = "glyphicon glyphicon-exclamation-sign";
                title = "Failed<br>";
            }
            $(function () {
                if (type !== "") {
                    $.notify({
                        // options
                        icon: icon,
                        title: title,
                        message: msg

                    }, {
                        // settings
                        element: 'body',
                        position: null,
                        type: type,
                        allow_dismiss: true,
                        newest_on_top: false,
                        showProgressbar: false,
                        placement: {
                            from: "top",
                            align: "center"
                        },
                        offset: 20,
                        spacing: 10,
                        z_index: 1031,
                        delay: 50000,
                        timer: 1000,
                        url_target: '_blank',
                        mouse_over: null,
                        animate: {
                            enter: 'animated fadeInDown',
                            exit: 'animated fadeOutUp'
                        },
                        onShow: null,
                        onShown: null,
                        onClose: null,
                        onClosed: null,
                        icon_type: 'class',
                        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                '<span data-notify="icon"></span> ' +
                                '<span data-notify="title">{1}</span> ' +
                                '<span data-notify="message">{2}</span>' +
                                '<div class="progress" data-notify="progressbar">' +
                                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                                '</div>' +
                                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                                '</div>'
                    });
                }
            });
        </script>

    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!--                -->
                <?php
                $this->load->view('layout/leftMenu');
                $this->load->view('layout/topMenu');
                ?>
                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="">
                        <div class="page-title">
                            <div class="title_left">
                                <h3><?php echo $pageHeading ?> </h3>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_content">
                                        <div class="row">
                                            <div id="overlay1" style="display: none">
                                                <div class="spinnera"></div> 
                                            </div>
                                            <?php
                                            $this->load->view($pageUrl);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /page content -->

                <!-- footer content -->
                <footer class="">
                    <div class="pull-right">
                        All rights reserved by E-Accounting
                    </div>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->
            </div>
        </div>


        <!-- FastClick -->
        <script src="<?php echo base_url() ?>assets/js/fastclick.js"></script>
        <!-- NProgress -->
        <script src="<?php echo base_url() ?>assets/js/nprogress.js"></script>
        <!-- bootstrap-wysiwyg -->
        <script src="<?php echo base_url() ?>assets/js/bootstrap-wysiwyg.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.hotkeys.js"></script>
        <script src="<?php echo base_url() ?>assets/js/prettify.min.js"></script>
        
        <script src="<?php echo base_url() ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>

        <!-- Custom Theme Scripts -->
        <script src="<?php echo base_url() ?>assets/js/custom.js"></script>
        <script src="<?php echo base_url() ?>assets/js/myScript.js"></script>

    </body>
</html>