<div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-6">
    <div class="float-right">
        <a href="<?php echo base_url() ?>Items/newItem" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Item</a>
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <form id="itemEditForm" class="form-horizontal form-label-right" action="<?php echo base_url() ?>Items/editItem" method="POST">
        <div class="form-group has-feedback">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Item Name</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="itemName" id="itemName" value="<?php echo $itemDetail[0]['title']?>" maxlength="200" class="form-control col-md-7 col-xs-12" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="unitName" id="unitName" value="<?php echo $itemDetail[0]['unit_name']?>" maxlength="30" class="form-control col-md-7 col-xs-12" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Type</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="itemType" id="itemType" required>
                    <option value="<?php echo $itemDetail[0]['item_type']?>"><?php echo ucfirst($itemDetail[0]['item_type'])?></option>
                    <option value="<?php echo PRODUCT ?>">Product</option>
                    <option value="<?php echo SERVICE ?>">Service</option>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Rate</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" min="0" name="saleRate" id="saleRate" value="<?php echo $itemDetail[0]['sale_rate']?>" class="form-control col-md-7 col-xs-12">
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Description</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea name="saleDescription" id="saleDescription" class="form-control" rows="2"><?php echo $itemDetail[0]['sale_description']?></textarea>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Account</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="saleAccount" id="saleAccount">
                    <option value="<?php echo $itemDetail[0]['sale_account']?>"><?php echo $itemDetail[0]['sale_account_name']?></option>
                    <option value="<?php echo GENERAL_INCOME ?>">General Income</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Tax</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="saleTax" id="saleTax">
                    <option value="<?php echo $itemDetail[0]['sale_tax']?>"><?php echo $itemDetail[0]['sale_tax_name']?></option>
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
                <input type="number" min="0" name="purchaseRate" id="purchaseRate" value="<?php echo $itemDetail[0]['purchase_rate']?>" class="form-control col-md-7 col-xs-12">
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Description</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea name="purchaseDescription" id="purchaseDescription" class="form-control" rows="2"><?php echo $itemDetail[0]['purchase_description']?></textarea>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Account</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="purchaseAccount" id="purchaseAccount">
                    <option value="<?php echo $itemDetail[0]['purchase_account']?>"><?php echo $itemDetail[0]['purchase_account_name']?></option>
                    <option value="<?php echo COST_OF_GOODS_SOLD ?>">Cost of Goods Sold</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Tax</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="purchaseTax" id="purchaseTax">
                    <option value="<?php echo $itemDetail[0]['purchase_tax']?>"><?php echo $itemDetail[0]['purchase_tax_name']?></option>
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
                <button type="button" class="btn btn-success btn-sm" onclick="editItem()"><i class="fa fa-check"></i> Save</button>
                <a href="<?php echo base_url() ?>Items/item" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Cancel</a>
            </div>
        </div>
        <input type="hidden" name="itemCode" id="itemCode" value="<?php echo $itemDetail[0]['item_code']?>">
    </form>
</div>
<script>
    function editItem() {
        if ($.trim($('#itemName').val()) === "" || $.trim($('#unitName').val()) === "") {
            sweetAlert('Fields are required');
            return false;
        }
        
        showLoader();
        $.ajax({
            type: 'POST',
            data: {itemName: $.trim($('#itemName').val()), itemType: $.trim($('#itemType').val()), itemCode: $.trim($('#itemCode').val()), addEditFlag: 'edit'},
            url: BASE_URL + 'Items/itemDuplicateCheck',
            success: function (result) {
                hideLoader();
                if (result === '1') {
                    $('#itemEditForm').submit();
                } else if (result === '2') {
                    sweetAlert('Duplicate Item');
                    return false;
                }
            }
        });
    }

    $(function () {
        $('#itemEditForm').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
            } else {
            }
        })
    });
</script>
