<div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-6">
    <div class="float-right">
        <a href="<?php echo base_url() ?>Items/showEditItem?itemCode=<?php echo $itemDetail[0]['item_code'] ?>"><i class="fa fa-edit"></i> Edit Info</a>
        <a href="<?php echo base_url() ?>Items/newItem" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Item</a>
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Item Name<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $itemDetail[0]['title']; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Item Tye<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo ucfirst($itemDetail[0]['item_type']);
//            if ($itemDetail[0]['item_type'] == PRODUCT) {
//                echo 'Product';
//            } else {
//                echo 'Service';
//            }
            ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Unit<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<?php echo $itemDetail[0]['unit_name']; ?>
        </div>
    </div> 
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Rate<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<?php echo $itemDetail[0]['sale_rate'] . " BDT"; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Account<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<?php echo $itemDetail[0]['sale_account_name']; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Description<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<?php echo $itemDetail[0]['sale_description']; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Tax<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<?php echo $itemDetail[0]['sale_tax_name']; ?>
        </div>
    </div>

    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Rate<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<?php echo $itemDetail[0]['purchase_rate'] . " BDT"; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Account<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<?php echo $itemDetail[0]['purchase_account_name']; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Description<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<?php echo $itemDetail[0]['purchase_description']; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Tax<span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
<?php echo $itemDetail[0]['purchase_tax_name']; ?>
        </div>
    </div>
</div>
