<script>
    function addUser() {
        if ($.trim($('#fullName').val()) === "" || $.trim($('#mobile').val()) === "" || $.trim($('#userRole').val()) === "") {
            sweetAlert('Fields are required');
            return false;
        }
        showLoader();
        $.ajax({
            type: 'POST',
            data: {fullName: $.trim($('#fullName').val()), mobileNo: $.trim($('#mobile').val()), addEditFlag: 'add'},
            url: BASE_URL + 'UserManagement/userDuplicateCheck',
            success: function (result) {
                hideLoader();
                if (result === '1') {
                    $('#userAddForm').submit();
                } else if (result === '2') {
                    sweetAlert('Duplicate User');
                    return false;
                }
            }
        });
    }
    
</script>

<div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-6">
    <div class="float-right">
        <a href="<?php echo base_url() ?>UserManagement/newUser" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New User</a>
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <?php
    if ($msg) {
        ?>
<!--        <div class="alert alert-<?php echo $msgFlag ?> alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <?php echo $msg ?>
        </div>-->
        <?php
    }
    ?>

    <form id="userAddForm" class="form-horizontal form-label-right" action="<?php echo base_url() ?>UserManagement/addUser" method="post">

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Full Name <span class="danger">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="fullName" id="fullName" required="required" class="form-control col-md-7 col-xs-12">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Mobile No <span class="danger">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="mobile" name="mobile" required="required" onchange="mobileNoValidation(this.value, this.id)"  class="form-control col-md-7 col-xs-12">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="email" class="form-control col-md-7 col-xs-12" onchange="emailValidation(this.value, this.id)" type="text" name="email">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">User Role <span class="danger">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="userRole" id="userRole" required="">
                    <option value="">Choose User Role</option>
                    <?php
                    foreach ($userRoles as $userRole) {
                        echo "<option value='" . $userRole['role_code'] . "'>" . $userRole['role_title'] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>


        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="button" class="btn btn-success btn-sm" onclick="addUser()"><i class="fa fa-check"></i> Save</button>
                <a href="<?php echo base_url() ?>UserManagement/user" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Cancel</a>
            </div>
        </div>

    </form>
</div>
