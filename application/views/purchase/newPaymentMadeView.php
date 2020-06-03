<link href="<?php echo base_url() ?>assets/css/listing_datatable.css" rel="stylesheet">
<script>
    function showVendor() {
        $("#vendor-modal").modal('show');
    }

    function newPayment() {
        var vendor = $('#vendorCode').val();
        var amount = $.trim($('#amount').val());
        var paymentDate = $.trim($('#paymentDate').val());
        if (vendor === "" || amount === "" || paymentDate === "") {
            sweetAlert('Fileds are required');
            return false;
        }
        $('#paymentMadeForm').submit();
    }
</script>
<div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-6">
    <div class="float-right">
        <a href="<?php echo base_url() ?>Purchase/addNewPaymentMade" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Payment</a>
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 p-t-20">
    <form id="paymentMadeForm" class="form-horizontal" action="<?php echo base_url() ?>Purchase/addNewPaymentMade" method="post">
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" >Vendor <span class="danger">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="input-group">
                        <input type="text" id="vendorDetails" class="form-control" readonly>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-warning" onclick="showVendor()"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" >Amount <span class="danger">*</span></label>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <input type="number" min="0" name="amount" id="amount" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" >Payment Date <span class="danger">*</span></label>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="input-group dateTxt">
                        <input type="text" name="paymentDate" id="paymentDate" value="<?php echo date('Y-m-d') ?>" class="form-control">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" >Payment Mode <span class="danger">*</span></label>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <select class="form-control" name="paymentMode"> 
                        <?php
                        foreach ($paymentModes as $paymentMode) {
                            echo "<option value='" . $paymentMode['payment_mode_code'] . "'>" . $paymentMode['payment_mode'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" >Paid Through <span class="danger">*</span></label>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <select class="form-control" name="paidThrough"> 
                        <option value="<?php echo PETTY_CASH ?>">Petty Cash</option>
                        <option value="<?php echo UNDEPOSITED_FUNDS ?>">Undeposited Funds</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" >Reference No</label>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <input type="text" name="referenceNo" class="form-control">
                </div>
            </div>
            <input type="hidden" id="vendorCode" name="vendorCode">
        </div>
        <div class="row">
            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="button" class="btn btn-success btn-sm" onclick="newPayment()"><i class="fa fa-check"></i> Save</button>
                    <a href="<?php echo base_url() ?>Purchase/newPaymentMade" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ---------- Vendor Modal ------------- -->
<div class="modal fade" id="vendor-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ddd">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Vendor</h4>
            </div>
            <div class="modal-body">
                <div class="table-custom-responsive">
                    <table class="table table-hover table-striped custom-table" id="vendor-datatable">
                        <thead class="hidden">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- ---------- end Vendor Modal ------------- -->

<script>
    $(document).ready(function () {
        //---------- vendor modal ------------------//
        var vendorTable = $('#vendor-datatable').DataTable({
            "bDestroy": true,
            "ajax": '<?php echo base_url() ?>Purchase/getVendor',
            "deferRender": true,
            "paging": true,
            "dom": "<'row'<'col-sm-12 col-md-12 col-xs-12'l><'col-sm-12 col-md-12 col-xs-12'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12'i><'col-sm-12'p>>",
            "aaSorting": [],
            "iDisplayLength": 5,
            "bLengthChange": false,
            "columnDefs": [
                {
                    "targets": [0],
                    "visible": true,
                    "searchable": false
                },
                {
                    "targets": [1],
                    "visible": false,
                    "searchable": true
                },
                {
                    "targets": [2],
                    "visible": false,
                    "searchable": true
                },
                {
                    "targets": [3],
                    "visible": false,
                    "searchable": true
                }
            ],
            "createdRow": function (row, data, index) {
                $(row).addClass('pointer');
            }

        });
        $('#vendor-datatable tbody').on('click', 'tr', function () {
            var data = vendorTable.row(this).data();
            setVendor(data[1], data[2]);
        });
    });

    function setVendor(vendorCode, vendorName) {
        $('#vendorCode').val(vendorCode);
        $('#vendorDetails').val(vendorName + ' (' + vendorCode + ')');
        $('#vendor-modal').modal('hide');
    }
</script>