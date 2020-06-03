<div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-6">
    <div class="float-right">
        <a href="<?php echo base_url() ?>Accounts/showEditTax?taxCode=<?php echo $taxDetail[0]['tax_code']?>"><i class="fa fa-edit"></i> Edit Info</a>
        <a href="<?php echo base_url() ?>Accounts/newTax" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Tax</a>
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Name <span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $taxDetail[0]['title']; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Rate(%) <span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $taxDetail[0]['rate']; ?>
        </div>
    </div>
</div>
