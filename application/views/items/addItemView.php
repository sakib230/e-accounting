<div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-6">
    <div class="float-right">
        <!--<a href="<?php echo base_url() ?>Items/newItem" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Item</a>-->
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <form id="itemAddForm" class="form-horizontal form-label-right" action="<?php echo base_url() ?>Items/addItem" method="POST">
        <div class="form-group has-feedback">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Item Name</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="itemName" id="itemName" maxlength="200" class="form-control col-md-7 col-xs-12" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="unitName" id="unitName" maxlength="30" class="form-control col-md-7 col-xs-12" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Type</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="itemType" id="itemType" required>
                    <option></option>
                    <option value="<?php echo PRODUCT ?>">Product</option>
                    <option value="<?php echo SERVICE ?>">Service</option>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Rate</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" min="0" name="saleRate" id="saleRate" class="form-control col-md-7 col-xs-12">
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Description</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea name="saleDescription" id="saleDescription" class="form-control" rows="2"></textarea>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Account</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="saleAccount" id="saleAccount">
                    <option></option>
                    <option value="<?php echo GENERAL_INCOME ?>">General Income</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Tax</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="saleTax" id="saleTax">
                    <option></option>
                    <?php
                    foreach ($taxes as $tax) {
                        ?> 
                        <option value="<?php echo $tax['tax_code'] ?>"><?php echo $tax['title'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Rate</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" min="0" name="purchaseRate" id="purchaseRate" class="form-control col-md-7 col-xs-12">
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Description</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea name="purchaseDescription" id="purchaseDescription" class="form-control" rows="2"></textarea>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Account</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="purchaseAccount" id="purchaseAccount">
                    <option></option>
                    <option value="<?php echo COST_OF_GOODS_SOLD ?>">Cost of Goods Sold</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Tax</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="purchaseTax" id="purchaseTax">
                    <option></option>
                    <?php
                    foreach ($taxes as $tax) {
                        ?> 
                        <option value="<?php echo $tax['tax_code'] ?>"><?php echo $tax['title'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="button" class="btn btn-success btn-sm" onclick="addItem()"><i class="fa fa-check"></i> Save</button>
                <a href="<?php echo base_url() ?>Items/item" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Cancel</a>
            </div>
        </div>
    </form>
</div>
<script>
    function addItem() {
        if ($.trim($('#itemName').val()) === "" || $.trim($('#unitName').val()) === "") {
            sweetAlert('Fields are required');
            return false;
        }
        
        showLoader();
        $.ajax({
            type: 'POST',
            data: {itemName: $.trim($('#itemName').val()), itemType: $.trim($('#itemType').val()), addEditFlag: 'add'},
            url: BASE_URL + 'Items/itemDuplicateCheck',
            success: function (result) {
                hideLoader();
                if (result === '1') {
                    $('#itemAddForm').submit();
                } else if (result === '2') {
                    sweetAlert('Duplicate Item');
                    return false;
                }
            }
        });
    }

    $(function () {
        $('#itemAddForm').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
            } else {
            }
        })
    });
</script>
