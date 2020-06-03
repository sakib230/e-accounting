<form action="<?php echo base_url() ?>Login/checkLogin" method="post">
    <h1>Login Form</h1>

    <?php
    if ($msg) {
        ?>
        <div class="alert alert-<?php echo $msgFlag ?> alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <?php echo $msg ?>
        </div>
        <?php
    }
    ?>


    <div>
        <input type="text" name="username" class="form-control" placeholder="Username" required="required" />
    </div>
    <div>
        <input type="password" name="password" class="form-control" placeholder="Password" required="" />
    </div>
    <div>
        <button type="submit" class="btn btn-default submit">Log In</button>
        <a class="reset_pass" href="#">Lost your password?</a>
    </div>

    <div class="clearfix"></div>

    <div class="separator">
        <!--<p class="change_link">Registration </p>-->
        <div class="clearfix"></div>
        <br />
        <div>
            <h1><i class="fa fa-book"></i> <?php echo PROJECT_NAME?></h1>
            <p>©<?php echo date('Y')?> All Rights Reserved By <b><?php echo PROJECT_NAME?></b></p>
        </div>
    </div>
</form>