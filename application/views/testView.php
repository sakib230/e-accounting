<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Admin Panel</title>
        <!-- Favicon-->
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/validator.js"></script>
    </head>
    <body>
        <div id="myForm">
            <div class="form-group">
                <label for="inputUsername" class="control-label">Username: </label>
                <input type="text" class="form-control" id="inputUsername" maxlength="5" required>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputEmail" class="control-label">Email: </label>
                <input type="email" class="form-control" id="inputEmail">
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                <div class="help-block with-errors">Hey look, this one has feedback icons!</div>
            </div>

<!--            <div class="form-group">
                <label for="inputName" class="control-label">Name</label>
                <input type="text" class="form-control" id="inputName" placeholder="Cina Saffary" required>
            </div>
            <div class="form-group has-feedback">
                <label for="inputTwitter" class="control-label">Twitter</label>
                <div class="input-group">
                    <span class="input-group-addon">@</span>
                    <input type="text" pattern="^[_A-z0-9]{1,}$" maxlength="15" class="form-control" id="inputTwitter" placeholder="1000hz" required>
                </div>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                <div class="help-block with-errors">Hey look, this one has feedback icons!</div>
            </div>
            <div class="form-group">
                <label for="inputEmail" class="control-label">Email</label>
                <input type="email" class="form-control" id="inputEmail" placeholder="Email" data-error="Bruh, that email address is invalid" required>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="control-label">Password</label>
                <div class="form-inline row">
                    <div class="form-group col-sm-6">
                        <input type="password" data-minlength="6" class="form-control" id="inputPassword" placeholder="Password" required>
                        <div class="help-block">Minimum of 6 characters</div>
                    </div>
                    <div class="form-group col-sm-6">
                        <input type="password" class="form-control" id="inputPasswordConfirm" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="radio">
                    <label>
                        <input type="radio" name="underwear" required>
                        Boxers
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="underwear" required>
                        Briefs
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="terms" data-error="Before you wreck yourself" required>
                        Check yourself
                    </label>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>-->
        </div>
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
    </body>
    <script>
        $(function () {
            $('#myForm').validator();
        });

    </script>
