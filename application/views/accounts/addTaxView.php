<div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-6">
    <div class="float-right">
        <!--<a href="<?php echo base_url() ?>Contacts/newTax" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Tax</a>-->
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <form id="taxAddForm" class="form-horizontal form-label-right" action="<?php echo base_url() ?>Accounts/addTax" method="POST">
        <div class="form-group has-feedback">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Tax Name</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="name" id="name" maxlength="200" class="form-control col-md-7 col-xs-12" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Rate(%)</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" min="0" name="rate" id="rate" class="form-control col-md-7 col-xs-12" required>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="button" class="btn btn-success btn-sm" onclick="addTax()"><i class="fa fa-check"></i> Save</button>
                <a href="<?php echo base_url() ?>Accounts/tax" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Cancel</a>
            </div>
        </div>
    </form>
</div>
<script>
    function addTax() {
        if ($.trim($('#name').val()) === "" || $.trim($('#rate').val()) === '') {
            sweetAlert('Fields are required');
            return false;
        }
        showLoader();
        $.ajax({
            type: 'POST',
            data: {name: $.trim($('#name').val()), addEditFlag: 'add'},
            url: BASE_URL + 'Accounts/taxDuplicateCheck',
            success: function (result) {
                hideLoader();
                if (result === '1') {
                    $('#taxAddForm').submit();
                } else if (result === '2') {
                    sweetAlert('Duplicate Tax');
                    return false;
                }
            }
        });
    }

    $(function () {
        $('#taxAddForm').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
            } else {

            }
        })
    });
</script>
