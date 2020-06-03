<div class="col-md-12 col-sm-12 col-xs-12 hidden-lg hidden-md hidden-sm">
    <a href="<?php echo base_url() ?>UserManagement/newUser" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New User</a>
    <a href="#">Page Help</a>
</div>

<script>
    $(document).ready(function () {
       
        var empTable = $('#empTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?php echo base_url() ?>UserManagement/getDataTableData'
            },
            'columns': [
                {data: 'id'},
                {data: 'card_id'},
                {data: 'card_number'}
            ],
            "createdRow": function (row, data, index) {
                $(row).addClass('pointer');
            }
        });
       
        $('#empTable tbody').on('click', 'tr', function () {
            var data = empTable.row(this).data();
            showUserDetails(data.id);
        });
    });

    function showUserDetails(userId) {
        window.location.href = BASE_URL + "UserManagement/showUserDetails?userId=" + userId;
    }
</script>
<div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
    <div class="table-custom-responsive">
        <table id='empTable' class='table table-hover table-bordered custom-table'>

            <thead>
                <tr>
                    <th>Id</th>
                    <th>Card Id</th>
                    <th>Card Number</th>
                </tr>
            </thead>

        </table>
    </div>
</div>