<script>
    function changeStatus(status) {
        $('#statusHidden').val(status);
        $('#statusForm').submit();
    }
</script>
<div class="col-md-12 col-sm-12 col-xs-12 hidden-lg hidden-md hidden-sm">
    <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
</div>

<div class="col-md-6 col-sm-6 col-xs-12">
    <div class="row">
        <div class="col-md-5 col-sm-6 col-xs-12">
            <select class="form-control" onchange="changeStatus(this.value)"> 
                <?php
                if ($status == '2') {
                    echo '<option value="2">Unpaid</option>';
                } elseif ($status == '3') {
                    echo '<option value="3">Partially Paid</option>';
                } elseif ($status == '4') {
                    echo '<option value="4">Paid</option>';
                }
                ?>
                <option value="">ALL</option>
                <option value="4">Paid</option>
                <option value="2">Unpaid</option>
                <option value="3">Partially Paid</option>
            </select>
            <form class="hidden" id="statusForm" action="<?php echo base_url() ?>Sales/invoice" method="post">
                <input type="hidden" name="status" id="statusHidden">
            </form>
        </div>
    </div>
</div>

<div class="col-md-6 col-sm-6 col-xs-12 hidden-xs">
    <div class="float-right">
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <div class="table-custom-responsive">
        <table class="table table-hover table-bordered custom-table" id="invoice-datatable">
            <thead>
                <tr>
                    <th style="width: 30px">#</th>
                    <th>Date<br><small class="text-muted"><i>yyyy-mm-dd</i></small></th>
                    <th>Invoice</th>
                    <th>Reference No</th>
                    <th>Customer</th>
                    <th>Invoice Status</th>
                    <th>Due Date<br><small class="text-muted"><i>yyyy-mm-dd</i></small></th>
                    <th>Invoice Amount<br><small class="text-muted"><i>BDT</i></small></th>
                    <th>Balance Due<br><small class="text-muted"><i>BDT</i></small></th>
                    <th></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#invoice-datatable tfoot th').each(function () {
            $(this).html('<input type="text" placeholder="Search" />');
        });
        var invoiceTable = $('#invoice-datatable').DataTable({
            "bDestroy": true,
            "ajax": BASE_URL + 'Sales/getInvoiceList?status=<?php echo $status ?>',
            "deferRender": true,
            "aaSorting": [],
            "columnDefs": [
                {
                    "targets": [9],
                    "visible": false,
                    "searchable": false
                }
            ],
            "createdRow": function (row, data, index) {
                $(row).addClass('pointer');
            }

        });
        invoiceTable.columns().every(function () {
            var that = this;
            $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    that.search(this.value).draw();
                }
            });
        });
        $('#invoice-datatable tbody').on('click', 'tr', function () {
            var data = invoiceTable.row(this).data();
            showInvoiceDetails(data[9]);
        });
    });

    function showInvoiceDetails(invoiceCode) {
        window.location.href = BASE_URL + "Sales/showInvoicePaymentDetails?invoice=" + invoiceCode;
    }
</script>
