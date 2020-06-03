<link href="<?php echo base_url() ?>assets/css/listing_datatable.css" rel="stylesheet">
<div class="col-md-12 col-sm-12 col-xs-12 hidden-lg hidden-md hidden-sm">
    <a href="javascript:void(0)" onclick="showCustomizeReportModal()"><i class="fa fa-cogs"></i> Customize Report</a><br><br>
    <div class="btn-group  btn-group-sm">

        <button class="btn btn-default" type="button"><i class="fa fa-print"></i></button>
        <button class="btn btn-default" type="button"><i class="fa fa-file-pdf-o"></i></button>
    </div>
    <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
</div>
<div class="col-md-6 col-sm-6 col-xs-12 hidden-xs">
    <div class="float-left m-t-5">
        <a href="javascript:void(0)" onclick="showCustomizeReportModal()"><i class="fa fa-cogs"></i> Customize Report</a>
    </div>
</div>
<div class="col-md-6 col-sm-6 col-xs-12 hidden-xs">
    <div class="float-right">
        <div class="btn-group  btn-group-sm">
            <button class="btn btn-default" type="button" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="fa fa-print font-15"></i></button>
            <button class="btn btn-default" type="button" data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o font-15"></i></button>
        </div>
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <hr>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="text-center">
                <span class="font-18"><?php echo SHOP_NAME ?></span><br>
                <span class="font-20">Purchase By Vendor</span><br>
                <span class="font-14">From <?php echo $reportFromDate ?> to <?php echo $reportToDate ?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 m-t-30">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th class='td-left'>Vendor Name</th>
                        <th>Vendor No</th>
                        <th>Contact No</th>
                        <th>Bill Count</th>
                        <th class='td-right'>Purchase Amount<br><small><i>BDT</i></small></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalBill = 0;
                    $totalPrice = 0;
                    foreach ($purchaseVendorDetails as $purchaseVendorDetail) {
                        echo "<tr>";
                        echo "<td class='td-left'>$purchaseVendorDetail[vendor_name]</td>";
                        echo "<td>" . $purchaseVendorDetail['vendor_code'] . "</td>";
                        echo "<td>$purchaseVendorDetail[mobile_no]</td>";
                        echo "<td>$purchaseVendorDetail[bill_count]</td>";
                        echo "<td class='td-right'>" . number_format($purchaseVendorDetail['purchase_amount'], 2) . "</td>";
                        //$avaragePrice = $purchaseItemDetail['amount'] / $purchaseItemDetail['quantity_sold'];
                        //echo "<td class='td-right'>" . number_format($avaragePrice, 2) . "</td>";
                        echo "</tr>";
                        $totalBill = $totalBill + $purchaseVendorDetail['bill_count'];
                        $totalPrice = $totalPrice + $purchaseVendorDetail['purchase_amount'];
                    }

                    if (!$purchaseVendorDetails) {
                        echo "<tr>";
                        echo "<td colspan='5'>No record found</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        echo "<td class='td-left'><b>Total</b></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td><b>" . $totalBill . "</b></td>";
                        echo "<td class='td-right'><b>" . number_format($totalPrice, 2) . "</b></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ---------- Customize Report Modal ------------- -->
<div class="modal fade" id="customize-report-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ddd">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel1">Customize Report</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="customizeForm" action="<?php echo base_url() ?>Reports/purchaseByVendor" method="post">
                        <div class="col-md-12 col-sm-12 col-xs-12 m-b-5">
                            <b>Date Range</b>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="reportrange_right" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <span><?php echo $reportDateRange; ?></span> <b class="caret"></b>
                                <input type="hidden" id="reportFromDate" name="fromDate" value="<?php echo $reportFromDate ?>">
                                <input type="hidden" id="reportToDate" name="toDate" value="<?php echo $reportToDate ?>">
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
                            <b>Advance Filters</b>

                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-12 m-t-10">

                            <table id="advanceFilterTable">
                                <tr id="advanceFilterTr1">
                                    <td class="p-b-15">
                                        <select id="filterType1" class="form-control" onchange="setFilterType('1')">
                                            <option value=""></option>
                                            <option value="2">Vendor</option>
                                        </select>
                                    </td>
                                    <td class="p-l-15 p-b-15" id="filterTypeElementTd1">
                                        <input type="text" class="form-control" placeholder="Select Filter Type" readonly="">
                                    </td>
                                    <td class="p-l-15 p-b-15 font-18">
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="advanceFilterCount" id="advanceFilterCount">
                            <br>
                            <input type="hidden" id="filterTypeSerialHidden">
                            <span class="pointer template-green" onclick="showMoreFilter()"><i class="fa fa-plus"></i> Add More</span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" data-dismiss="modal" onclick="runReport()"><i class="fa fa-check"></i> Run Report</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            </div>

        </div>
    </div>
</div>

<!-- ---------- Vendor Modal ------------- -->
<div class="modal fade" id="vendor-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #359db5!important">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel2">Vendor</h4>
            </div>
            <div class="modal-body">
                <div class="table-custom-responsive">
                    <table class="table table-hover custom-table" id="vendor-datatable">
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
    var advanceFilterCount = 2;
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
        var filterTypeSerial = $('#filterTypeSerialHidden').val();
        $('#vendorCode' + filterTypeSerial).val(vendorCode);
        $('#vendorDetails' + filterTypeSerial).val(vendorName + ' (' + vendorCode + ')');
        $('#vendor-modal').modal('hide');
    }


    function showCustomizeReportModal() {
        $("#customize-report-modal").modal('show');
    }

    function showMoreFilter() {
        var moreFilterTr = '<tr class="p-b-10" id="advanceFilterTr' + advanceFilterCount + '">\n\
                                <td class="p-b-15">\n\
                                    <select id="filterType' + advanceFilterCount + '" class="form-control" onchange="setFilterType(' + advanceFilterCount + ')">\n\
                                        <option value=""></option>\n\
                                        <option value="2">Vendor</option>\n\
                                    </select>\n\
                                </td>\n\
                                <td class="p-l-15 p-b-15" id="filterTypeElementTd' + advanceFilterCount + '">\n\
                                    <input type="text" class="form-control" placeholder="Select Filter Type" readonly="">\n\
                                </td>\n\
                                <td class="p-l-15 p-b-15 font-18">\n\
                                    <i class="fa fa-minus-circle text-danger pointer" onclick="removeFilter(' + advanceFilterCount + ')"></i>\n\
                                </td>\n\
                            </tr>';
        $('#advanceFilterTable').append(moreFilterTr);
        advanceFilterCount++;
    }

    function removeFilter(filterTypeSerial) {
        $('#advanceFilterTr' + filterTypeSerial).remove();
    }

    function setFilterType(filterTypeSerial) {
        var filterType = $('#filterType' + filterTypeSerial).val();
        var elementHtml = "";
        if (filterType === '2') {
            elementHtml = '<div class="input-group">\n\
                                <input type="text" id="vendorDetails' + filterTypeSerial + '" placeholder="Vendor" class="form-control" readonly>\n\
                                <input type="hidden" id="vendorCode' + filterTypeSerial + '" name="vendorCode' + filterTypeSerial + '">\n\
                                <span class="input-group-btn">\n\
                                    <button type="button" class="btn btn-warning" onclick="showVendor(' + filterTypeSerial + ')"><i class="fa fa-search"></i></button>\n\
                                </span>\n\
                            </div>';
        } else {
            elementHtml = '<input type="text" class="form-control" placeholder="Select Filter Type" readonly="">';
        }

        $('#filterTypeElementTd' + filterTypeSerial).html(elementHtml);
    }

    function showVendor(filterTypeSerial) {
        $('#filterTypeSerialHidden').val(filterTypeSerial);
        $("#vendor-modal").modal('show');
    }

    function runReport() {
        $('#advanceFilterCount').val(advanceFilterCount);
        $('#customizeForm').submit();
    }
</script>