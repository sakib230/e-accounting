<div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-6">
    <div class="float-right">
        <a href="<?php echo base_url() ?>Contacts/showEditCustomer?customerId=<?php echo $customerDetail[0]['contact_code']?>"><i class="fa fa-edit"></i> Edit Info</a>
        <a href="<?php echo base_url() ?>Contacts/newCustomer" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Customer</a>
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Full Name <span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $customerDetail[0]['contact_name']; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Mobile No <span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $customerDetail[0]['mobile_no']; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Email <span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $customerDetail[0]['email']; ?>
        </div>
    </div> 
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Address <span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $customerDetail[0]['address']; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Company Name <span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $customerDetail[0]['company_name']; ?>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Opening Balance <span class="pull-right">:</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $customerDetail[0]['opening_balance']; ?>
        </div>
    </div>
</div>
