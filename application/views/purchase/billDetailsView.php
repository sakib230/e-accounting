<div class="col-md-12 col-sm-12 col-xs-12 hidden-lg hidden-md hidden-sm">
    <div class="btn-group  btn-group-sm">
        <a href="<?php echo base_url() ?>Purchase/showEditBill?bill=<?php echo $billInfo[0]['bill_code'] ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
        <button class="btn btn-default" type="button"><i class="fa fa-print"></i></button>
        <button class="btn btn-default" type="button"><i class="fa fa-file-pdf-o"></i></button>
    </div>
    <a href="<?php echo base_url() ?>Purchase/newBill" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Bill</a>
    <a href="#">Page Help</a>
</div>
<div class="col-md-6 col-sm-6 col-xs-12 hidden-xs col-md-offset-6 col-sm-offset-6">
    <div class="float-right">
        <div class="btn-group  btn-group-sm">
            <a href="<?php echo base_url() ?>Purchase/showEditBill?bill=<?php echo $billInfo[0]['bill_code'] ?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil font-15"></i></a>
            <button class="btn btn-default" type="button" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="fa fa-print font-15"></i></button>
            <button class="btn btn-default" type="button" data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o font-15"></i></button>
        </div>

        <a href="<?php echo base_url() ?>Purchase/newBill" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Bill</a>
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <section class="content invoice" style="">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12 bill-header">
                <h1>
                    <i class="fa fa-globe"></i> Bill 
                    <small class="pull-right">Date: <?php echo $billInfo[0]['bill_date'] ?></small>
                </h1>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong><?php echo $billInfo[0]['contact_name'] ?></strong>
                    <?php echo ($billInfo[0]['company_name'] != "") ? '<br>' . $billInfo[0]['company_name'] : "" ?>
                    <?php echo ($billInfo[0]['address'] != "") ? '<br>' . $billInfo[0]['address'] : "" ?>
                    <br>Phone: <?php echo $billInfo[0]['mobile_no'] ?> 
                    <?php echo ($billInfo[0]['email'] != "") ? '<br>Email: ' . $billInfo[0]['email'] : "" ?>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <?php
                    echo SHOP_ADDRESS;
                    ?>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Bill <?php echo $billInfo[0]['bill_code'] ?></b>
                <br>
                <br>
                <b>Due Date:</b>  <?php echo $billInfo[0]['due_date'] ?>
                <br>
                <b>Balance Due: </b> BDT <?php echo number_format($billInfo[0]['total'] - $billInfo[0]['paid_amount'], 2) ?>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 table">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 40%" >Description</th>
                            <th>Qty</th>
                            <th>Rate <small>(BDT)</small></th>
                            <th>Tax</th>
                            <th class='td-right'>Amount <small>(BDT)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($billItemDetails as $billItemDetail) {
                            echo "<tr>";
                            echo "<td>" . $i . "</td>";
                            echo "<td class='td-left'>" . $billItemDetail['item_title'] . "</td>";
                            echo "<td>" . $billItemDetail['quantity'] . " " . $billItemDetail['unit'] . "</td>";
                            echo "<td>" . $billItemDetail['rate'] . "</td>";
                            echo "<td>" . $billItemDetail['tax_title'] . "</td>";
                            echo "<td class='td-right'>" . $billItemDetail['amount'] . "</td>";
                            echo "<tr>";
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-9 col-sm-offset-9">
                <span class="font-12"><b>Sub Total <small>(BDT)</small></b></span>
                <span class="float-right"><?php echo $billInfo[0]['sub_total'] ?></span>
                <br>
                <span class="font-12"><b>Adjustment <small>(BDT)</small></b></span>
                <span class="float-right"><?php echo $billInfo[0]['adjustment'] ?></span>
                <br>
                <span class="font-12"><b>Total <small>(BDT)</small></b></span>
                <span class="float-right"><?php echo $billInfo[0]['total'] ?></span>
                <br>
                <span class="font-12"><b>Payment Made <small>(BDT)</small></b></span>
                <span class="float-right text-danger">(-) <?php echo $billInfo[0]['paid_amount'] ?></span>
                <hr>
                <span class="font-12"><b>Balance Due <small>(BDT)</small></b></span>
                <span class="float-right"> <?php echo number_format(($billInfo[0]['total'] - $billInfo[0]['paid_amount']), 2) ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php echo $billInfo[0]['vendor_notes'] ?>
            </div>
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <!--        <div class="row no-print">
                    <div class="col-xs-12">
                        <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                        
                    </div>
                </div>-->
    </section>
</div>