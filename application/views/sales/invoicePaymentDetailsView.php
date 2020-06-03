<link href="<?php echo base_url() ?>assets/css/listing_datatable.css" rel="stylesheet">
<style>
    .td-f-l{
        width:50%!important
    }
    .td-f-r{
        width:50%!important
    }
</style>
<div class="col-md-12 col-sm-12 col-xs-12 hidden-lg hidden-md hidden-sm">
    <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
</div>
<div class="col-md-6 col-sm-6 col-xs-12 hidden-xs col-md-offset-6 col-sm-offset-6">
    <div class="float-right">
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>
<div class="col-md-5 col-sm-6 col-xs-12 m-t-20" style="border-right:2px solid #ddd; height: 100vh;padding-right: 20px">
    <div class="row">
        <h4><b>All Invoices of <span class="template-green"><i><?php echo $invoiceInfo[0]['contact_name'] ?><small> (<?php echo $invoiceInfo[0]['customer'] ?>)</small></i></span> </b></h4>
        <hr>
        <div class="table-custom-responsive">
            <table class="table table-hover custom-table" id="invoice-datatable">
                <thead class="hidden">
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
        <div class="row m-t-20">
            <div class="col-md-9 col-sm-9 col-xs-12" style="line-height:35px">
                <span class="font-12"><b>Total Balance <small>(BDT)</small></b></span>
                <span class="float-right"><?php echo $invoiceInfo[0]['total_balance'] ?></span>
                <br>
                <span class="font-12"><b>Used Balance <small>(BDT)</small></b></span>
                <span class="float-right"><?php echo $invoiceInfo[0]['used_balance'] ?></span>
                <br>
                <span class="font-15"><b>Available Balance <small>(BDT)</small></b></span>
                <span class="font-15 float-right template-green"><b><?php echo number_format(($invoiceInfo[0]['total_balance'] - $invoiceInfo[0]['used_balance']), 2) ?></b></span>
            </div>
        </div>
    </div>
</div>
<div class="col-md-7 col-sm-6 col-xs-12 m-t-20 p-l-10">
    <section class="content ainvoice" style="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 invoice-header font-18">
                <span class="pull-left">Invoice: <b class="template-green"><?php echo $invoiceInfo[0]['invoice_code'] ?></b></span>
                <span class="pull-right">Date: <?php echo $invoiceInfo[0]['invoice_date'] ?></span>
            </div>

        </div>
        <br>

        <div class="row">
            <div class="col-md-7 col-sm-7 col-xs-12" style="line-height:35px">
                <span class="font-12 text-danger"><b>Due Date <small>(yyyy-mm-dd)</small></b></span>
                <span class="float-right text-danger"><?php echo $invoiceInfo[0]['due_date'] ?></span>
                <br>
                <span class="font-12"><b>Sub Total <small>(BDT)</small></b></span>
                <span class="float-right"><?php echo $invoiceInfo[0]['sub_total'] ?></span>
                <br>
                <span class="font-12"><b>Adjustment <small>(BDT)</small></b></span>
                <span class="float-right"><?php echo $invoiceInfo[0]['adjustment'] ?></span>
                <br>
                <span class="font-12"><b>Total <small>(BDT)</small></b></span>
                <span class="float-right"><b><?php echo $invoiceInfo[0]['total'] ?></b></span>
                <br>
                <span class="font-12 text-danger"><b>Balance Due <small>(BDT)</small></b></span>
                <span class="float-right text-danger"> <?php echo number_format(($invoiceInfo[0]['total'] - $invoiceInfo[0]['paid_amount']), 2) ?></span>
                <hr>
            </div>
        </div>
        <div class="row">
            <form id="invoicePaymentForm" action="<?php echo base_url() ?>Sales/makePayment" class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" >Payment</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input type="number" min="0" name="paymentAmount"  id="paymentAmount" value="<?php echo $invoiceInfo[0]['paid_amount'] ?>" class="form-control text-right p-r-10">
                    </div>
                </div>
                <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoiceInfo[0]['invoice_code'] ?>">
                <input type="hidden" id="invoiceTotal" value="<?php echo $invoiceInfo[0]['total'] ?>">
            </form>
        </div>
        <div class="row">
            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="button" class="btn btn-success btn-sm" onclick="makePayment()"><i class="fa fa-check"></i> Save</button>
                    <a href="<?php echo base_url() ?>Sales/invoicePayment" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Cancel</a>
                </div>
            </div>
        </div>


        <div class="row m-t-30">
            <div class="col-md-12 col-sm-12 col-xs-12 table">
                <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            <h5 class="panel-title"><b>Invoice Details</b></h5>
                        </a>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
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
                                        foreach ($invoiceItemDetails as $invoiceItemDetail) {
                                            echo "<tr>";
                                            echo "<td>" . $i . "</td>";
                                            echo "<td class='td-left'>" . $invoiceItemDetail['item_title'] . "</td>";
                                            echo "<td>" . $invoiceItemDetail['quantity'] . " " . $invoiceItemDetail['unit'] . "</td>";
                                            echo "<td>" . $invoiceItemDetail['rate'] . "</td>";
                                            echo "<td>" . $invoiceItemDetail['tax_title'] . "</td>";
                                            echo "<td class='td-right'>" . $invoiceItemDetail['amount'] . "</td>";
                                            echo "<tr>";
                                            $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php echo $invoiceInfo[0]['customer_notes'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        $('#invoice-datatable tfoot th').each(function () {
            $(this).html('<input type="text" placeholder="Search" />');
        });
        var invoiceTable = $('#invoice-datatable').DataTable({
            "bDestroy": true,
            "ajax": BASE_URL + 'Sales/getCustomerInvoiceList?customer=<?php echo $invoiceInfo[0]['customer'] ?>',
            "deferRender": true,
            "paging": true,
            "dom": "<'row'<'col-sm-12 col-md-12 col-xs-12'l><'col-sm-12 col-md-12 col-xs-12'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12'i><'col-sm-12'p>>",
            "aaSorting": [],
            "iDisplayLength": 5,
            "bLengthChange": false,
            "columnDefs": [
                {
                    "targets": [1],
                    "visible": false,
                    "searchable": true
                }
            ],
            "createdRow": function (row, data, index) {
                $(row).addClass('pointer');
            }

        });
        invoiceTable.columns().every(function () {
            var that = this;
            $('#invoice-datatable input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    that.search(this.value).draw();
                }
            });
        });
        $('#invoice-datatable tbody').on('click', 'tr', function () {
            var data = invoiceTable.row(this).data();
            var arr = data[1].split(" ");
            showInvoicePaymentDetails(arr[0]);
        });
    });

    function showInvoicePaymentDetails(invoiceCode) {
        window.location.href = BASE_URL + "Sales/showInvoicePaymentDetails?invoice=" + invoiceCode;
    }

    function makePayment() {
        var invoiceCode = $('#invoice').val();
        var paymentAmount = $.trim($('#paymentAmount').val());
        if (paymentAmount === "") {
            sweetAlert('Payment amount is required');
            return false;
        }
        if (!$.isNumeric(paymentAmount)) {
            $('#paymentAmount').val('');
            return false;
        }
        paymentAmount = parseFloat(paymentAmount);
        if (paymentAmount < 0) {
            $('#paymentAmount').val('');
            sweetAlert('Payment amount can not be less than zero');
            return false;
        }

        var invoiceTotal = parseFloat($('#invoiceTotal').val());

        if (paymentAmount > invoiceTotal) {
            sweetAlert('Payment amount can not exceed Total amount of invoice');
            return false;
        }

        showLoader();

        $.ajax({
            type: 'POST',
            data: {invoiceCode: invoiceCode, paymentAmount: paymentAmount},
            url: '<?php echo base_url() ?>Sales/checkInvoicePayment',
            success: function (result) {
                hideLoader();
                if (result === '1') {
                    $('#invoicePaymentForm').submit();
                } else if (result === '3') {
                    window.location.href = BASE_URL + "Sales/invoicePayment";
                } else {
                    var resultArr = result.split('|');
                    var exceedAmount = resultArr[1];
                    swal({
                        title: "Payment",
                        text: "You do not have enough available balance to paid this invoice. You need more BDT " + exceedAmount + " to payment this invoice. If you want to continue this, a payment receipt will be generated against this amount ( " + exceedAmount + " )",
                        type: "info",
                        closeOnConfirm: true,
                        showCancelButton: true,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"
                    }, function () {
                        $('#invoicePaymentForm').submit();
                    });
                }

            }
        });

    }
</script>