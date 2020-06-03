<div class="col-md-12 col-sm-12 col-xs-12 hidden-lg hidden-md hidden-sm">
    <div class="btn-group  btn-group-sm">
        <a href="<?php echo base_url() ?>Sales/showEditPayment?paymentCode=<?php echo $paymentDetails[0]['payment_receive_code'] ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
        <button class="btn btn-default" type="button"><i class="fa fa-print"></i></button>
        <button class="btn btn-default" type="button"><i class="fa fa-file-pdf-o"></i></button>
    </div>
    <a href="<?php echo base_url() ?>Sales/newInvoice" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Invoice</a>
    <a href="#">Page Help</a>
</div>
<div class="col-md-6 col-sm-6 col-xs-12 hidden-xs col-md-offset-6 col-sm-offset-6">
    <div class="float-right">
        <div class="btn-group  btn-group-sm">
            <a href="<?php echo base_url() ?>Sales/showEditPayment?paymentCode=<?php echo $paymentDetails[0]['payment_receive_code'] ?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil font-15"></i></a>
            <button class="btn btn-default" type="button" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="fa fa-print font-15"></i></button>
            <button class="btn btn-default" type="button" data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o font-15"></i></button>
        </div>

        <a href="<?php echo base_url() ?>Sales/newPaymentReceived" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Payment</a>
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <section class="content invoice">
        <div class="row">
            <div class="col-xs-12 invoice-header">
                <h1>
                    <i class="fa fa-globe"></i> Payment Receipt 
                    <small class="pull-right">Date: <?php echo $paymentDetails[0]['payment_date'] ?></small>
                </h1>
            </div>
        </div>

        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <address>
                    <?php
                    echo SHOP_ADDRESS;
                    ?>
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
                Bill To
                <address>
                    <strong><?php echo $paymentDetails[0]['contact_name'] ?></strong>
                    <?php echo ($paymentDetails[0]['company_name'] != "") ? '<br>' . $paymentDetails[0]['company_name'] : "" ?>
                    <?php echo ($paymentDetails[0]['address'] != "") ? '<br>' . $paymentDetails[0]['address'] : "" ?>
                    <br>Phone: <?php echo $paymentDetails[0]['mobile_no'] ?> 
                    <?php echo ($paymentDetails[0]['email'] != "") ? '<br>Email: ' . $paymentDetails[0]['email'] : "" ?>
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Payment Receipt <?php echo $paymentDetails[0]['payment_receive_code'] ?></b>
            </div>
        </div>

        <div class="row">
            <hr>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <span class="font-15">Reference No </span>
                <span class="font-15 float-right"><?php echo $paymentDetails[0]['dis_reference_no'] ?></span>
                <hr>
                <span class="font-15">Payment Mode </span>
                <span class="font-15 float-right"><?php echo $paymentDetails[0]['payment_mode_title'] ?></span>
                <hr>
                <span class="font-15">Payment Date </span>
                <span class="font-15 float-right"><?php echo $paymentDetails[0]['payment_date'] ?></span>
                <hr>
                <span class="font-15">Amount Received<small>(BDT)</small></span>
                <span class="font-15 float-right"><b><?php echo $paymentDetails[0]['amount'] ?></b></span>
            </div>
        </div>
        <div class="row m-t-50">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <span style="border-bottom:1px solid #ddd;width: 200px;float: right"></span>
                <br>
                <span class="font-15 float-right">Signature </span>
            </div>

        </div>
    </section>
</div>