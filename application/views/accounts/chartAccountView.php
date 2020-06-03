<div class="col-md-12 col-sm-12 col-xs-12 hidden-lg hidden-md hidden-sm">
    <!--<a href="<?php echo base_url() ?>ACcounts/new" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Customer</a>-->
    <a href="#">Page Help</a>
</div>
<div class="col-md-6 col-sm-6 col-xs-12">
    <div class="row">
        <div class="col-md-5 col-sm-6 col-xs-12">
            <select class="form-control">
                <option>ALL</option>
                <option>Active</option>
                <option>In Active</option>
            </select>
        </div>
    </div>
</div>
<div class="col-md-6 col-sm-6 col-xs-12 hidden-xs">
    <div class="float-right">
        <!--<a href="<?php echo base_url() ?>Contacts/newCustomer" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Customer</a>-->
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <div class="table-custom-responsive">
        <table class="table table-hover table-bordered custom-table" id="chart-account-datatable">
            <thead>
                <tr>
                    <th style="width: 30px">#</th>
                    <th>Account Code</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
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
        $('#chart-account-datatable tfoot th').each(function () {
            $(this).html('<input type="text" placeholder="Search" />');
        });
        var chartAccountTable = $('#chart-account-datatable').DataTable({
            "bDestroy": true,
            "ajax": '<?php echo base_url() ?>Accounts/getChartAccountList',
            "deferRender": true,
            "aaSorting": [],
//            "columnDefs": [
//                {
//                    "targets": [6],
//                    "visible": false,
//                    "searchable": false
//                }
//            ],
            "createdRow": function (row, data, index) {
                $(row).addClass('pointer');
            }

        });
        chartAccountTable.columns().every(function () {
            var that = this;
            $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    that.search(this.value).draw();
                }
            });
        });
//        $('#chart-account-datatable tbody').on('click', 'tr', function () {
//            var data = chartAccountTable.row(this).data();
//            showUserDetails(data[1]);
//        });
    });

//    function showUserDetails(customerId) {
//        window.location.href = BASE_URL + "Contacts/showCustomerDetails?customerId=" + customerId;
//    }
</script>

