<link href="<?php echo base_url() ?>assets/css/listing_datatable.css" rel="stylesheet">
<script>
    function showVendor() {
        $("#vendor-modal").modal('show');
    }
</script>
<div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-6">
    <div class="float-right">
        <a href="<?php echo base_url() ?>Purchase/newBill" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New Bill</a>
        <a href="#"><i class="fa fa-info-circle"></i> Page Help</a>
    </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 p-t-20">
    <form id="billForm" class="form-horizontal" action="<?php echo base_url() ?>Purchase/addNewBill" method="post">
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
                <label class="control-label col-md-2 col-sm-3 col-xs-12" >Reference No</label>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <input type="text" name="referenceNo" id="referenceNo" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" >Bill Date <span class="danger">*</span></label>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="input-group dateTxt">
                        <input type="text" name="billDate" id="billDate" class="form-control">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" >Due Date <span class="danger">*</span></label>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="input-group dateTxt">
                        <input type="text" name="dueDate" id="dueDate" class="form-control">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <div class="row">
            <div class="table-custom-responsive">
                <table id="itemDetailsTable" class="table table-bordered custom-table">
                    <tr class="bg-gray">
                        <th style="width:40%!important">Item Details</th>
                        <th style="width:10%!important">Quantity</th>
                        <th style="width:10%!important">Unit</th>
                        <th style="width:10%!important">Rate (BDT)</th>
                        <th style="width:15%!important">Tax (%)</th>
                        <th style="width:15%!important">Amount (BDT)</th>
                    </tr>
                    <tr id="noItemTr">
                        <td colspan="6">No Item Has Been Taken</td>
                    </tr>
                </table>
                <span class="pointer template-green" onclick="showItemModal()"><i class="fa fa-plus"></i> Add Items</span>
                <!--<a href="">asdfg</a>-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 col-sm-5 col-xs-12 col-md-offset-7 col-sm-offset-7">
                <span><b>Sub Total (BDT)</b></span>
                <span class="float-right" id="subTotal"></span>
                <hr>
                <span><b>Adjustment (BDT)</b></span>
                <span class="float-right m-t--10"><input type="text" class="form-control text-right" onchange="allCalculation()" onkeyup="allCalculation()" id="adjust" name="adjust"></span>
                <hr>
                <span><b>Total (BDT)</b></span>
                <span class="float-right" id="total"></span>
            </div>
        </div>
        <div class="row">
            <hr>
        </div>
        <div class="row">
            <div class="" >
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="control-label"> Vendor Notes</label>
                        <textarea class="form-control" name="vendorNotes" rows="5" style="resize: none"></textarea>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="control-label"> Terms & Condition</label>
                        <textarea class="form-control" rows="5" name="termsCondition" style="resize: none"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="button" class="btn btn-success btn-sm" onclick="newBill('1')"><i class="fa fa-check"></i> Save</button>
                    <button type="button" class="btn btn-info btn-sm" onclick="newBill('2')"><i class="fa fa-check-square"></i> Save & Make Payment</button>
                    <a href="<?php echo base_url() ?>Purchase/newBill" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Cancel</a>
                </div>
            </div>
        </div>
        <input type="hidden" id="vendorCode" name="vendorCode">
        <input type="hidden" id="applyItemCount" name="applyItemCount">
        <input type="hidden" id="billFlag" name="billFlag">
        <input type="hidden" id="billTotalAmount">
    </form>
</div>

<!-- ---------- Item Modal ------------- -->
<div class="modal fade" id="ietm-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ddd">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Items</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12" style="border-right: 1px solid #ddd">
                        <div class="table-custom-responsive">
                            <table class="table table-hover table-striped custom-table" id="item-datatable">
                                <thead class="hidden">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
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
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h3>Selected Items</h3>
                        <div class="boder-bottom"></div>

                        <table id="selectedItemTable" class="table custom-table selected-item-table" style="width:100%">
<!--                            <tr>
                                <td class="td-left" style="width:55%!important"><span class="template-green"><b>Hand Hand Bag Hand Bag</b></span></td>
                                <td style="width:35%!important">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-sm btn-number" disabled="disabled" data-type="minus" data-field="quantity">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </span>
                                        <input type="text" name="quantity" class="form-control input-number input-sm" value="1" min="0" max="9999999">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-sm btn-number" data-type="plus" data-field="quantity">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td style="width:10%!important">
                                    <i class="fa fa-remove text-danger pointer"></i>
                                </td>
                            </tr>-->
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" onclick="applyItems()"><i class="fa fa-check"></i> Apply</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- ---------- end Item Modal ------------- -->

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
    var selectedItemCount = 1;
    var applyItemCount = 1;
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
        //--------- item modal -------------------//
        var itemTable = $('#item-datatable').DataTable({
            "bDestroy": true,
            "ajax": '<?php echo base_url() ?>Purchase/getItem',
            "deferRender": true,
            "paging": true,
            "dom": "<'row'<'col-sm-12 col-md-12 col-xs-12'l><'col-sm-12 col-md-12 col-xs-12'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12'i><'col-sm-12'p>>",
            "aaSorting": [],
            "iDisplayLength": 10,
            "bLengthChange": false,
            "columnDefs": [
                {
                    "targets": [0], // title and rate
                    "visible": true,
                    "searchable": false
                },
                {
                    "targets": [1], // item code
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [2], // item title
                    "visible": false,
                    "searchable": true
                },
                {
                    "targets": [3], //unit_name
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [4], // sale_rate
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [5], // sale_tax
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [6], // tax_title
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [7], // tax_rate
                    "visible": false,
                    "searchable": false
                }

            ],
            "createdRow": function (row, data, index) {
                $(row).addClass('pointer');
            }

        });
        $('#item-datatable tbody').on('click', 'tr', function () {
            var data = itemTable.row(this).data();
            showSelectedItem(data);
        });
    });
    
    function showItemModal() {
        if (applyItemCount === 1) {
            $("#ietm-modal").modal('show');
            return false;
        }
        var selectedItemCode = "";
        for (var i = 1; i <= selectedItemCount; i++) {
            selectedItemCode = $('#selectedItemCode' + i).val();
            if (typeof selectedItemCode !== "undefined") {
                $('#selectedItemTr' + i).remove();
            }
        }
        selectedItemCount = 1;
        var itemCode = "";
//        var itemDetails = new Array();
        for (var i = 1; i <= applyItemCount; i++) {
            itemCode = $('#itemCode' + i).val();
            if (typeof itemCode !== "undefined") {
                var itemDetails = new Array();
                itemDetails.push('');
                itemDetails.push(itemCode); // 1
                itemDetails.push($('#itemTitle' + i).val()); // 2
                itemDetails.push($('#itemUnitName' + i).val()); //3
                itemDetails.push($('#itemRate' + i).val()); // 4
                itemDetails.push($('#itemTaxCode' + i).val()); // 5
                itemDetails.push($('#itemTaxTitle' + i).val()); // 6
                itemDetails.push($('#itemTaxRate' + i).val()); // 7
                itemDetails.push($('#itemQuantity' + i).val()); // 8
                setSelectedItem(itemDetails);
                selectedItemCount++;
            }
        }
        $("#ietm-modal").modal('show');
    }

    function applyItems() {
        var itemDetailsStr = "";
        var selectedItemTitle = "";
        var selectedItemCode = "";
        var selectedItemRate = "";
        var selectedItemTaxTitle = "";
        var selectedItemTaxRate = "";
        var selectedItemTaxCode = "";
        var selectedItemUnitName = "";
        var selectedItemQuantity = "";
        var itemCode = "";
        for (var i = 1; i <= applyItemCount; i++) {
            itemCode = $('#itemCode' + i).val();
            if (typeof itemCode !== "undefined") {
                $('#itemDetailsTr' + i).remove();
            }
        }
        $('#noItemTr').remove();
        applyItemCount = 1;
        for (var i = 1; i <= selectedItemCount; i++) {
            selectedItemCode = $('#selectedItemCode' + i).val();
            if (typeof selectedItemCode !== "undefined") {
                selectedItemTitle = $('#selectedItemTitle' + i).val();
                selectedItemCode = $('#selectedItemCode' + i).val();
                selectedItemRate = $('#selectedItemRate' + i).val();
                selectedItemTaxTitle = $('#selectedItemTaxTitle' + i).val();
                selectedItemTaxRate = $('#selectedItemTaxRate' + i).val();
                selectedItemTaxCode = $('#selectedItemTaxCode' + i).val();
                selectedItemUnitName = $('#selectedItemUnitName' + i).val();
                selectedItemQuantity = $('#selectedItemQuantity' + i).val();
                $('#selectedItemCode' + i).val();
                itemDetailsStr += '<tr id="itemDetailsTr' + applyItemCount + '">\n\
                                    <td class="td-left">' + selectedItemTitle + '</td>\n\
                                    <td><input class="form-control form-control-td-center" onchange="allCalculation()" onkeyup="allCalculation()" id="itemQuantity' + applyItemCount + '" name="itemQuantity' + applyItemCount + '"  type="number" min="0" value="' + selectedItemQuantity + '"></td>\n\
                                    <td>' + selectedItemUnitName + '</td>\n\
                                    <td><input class="form-control form-control-td-right" onchange="allCalculation()" onkeyup="allCalculation()" id="itemRate' + applyItemCount + '" name="itemRate' + applyItemCount + '" type="number" value="' + selectedItemRate + '"></td>\n\
                                    <td>' + selectedItemTaxTitle + '</td>\n\
                                    <td id="amountTd' + applyItemCount + '"></td>\n\
                                    <input type="hidden" id="itemCode' + applyItemCount + '" name="itemCode' + applyItemCount + '"  value="' + selectedItemCode + '">\n\
                                    <input type="hidden" id="itemTaxRate' + applyItemCount + '" name="itemTaxRate' + applyItemCount + '"  value="' + selectedItemTaxRate + '">\n\
                                    <input type="hidden" id="itemTaxCode' + applyItemCount + '" name="itemTaxCode' + applyItemCount + '"  value="' + selectedItemTaxCode + '">\n\
                                    <input type="hidden" id="itemTaxTitle' + applyItemCount + '" name="itemTaxTitle' + applyItemCount + '"  value="' + selectedItemTaxTitle + '">\n\
                                    <input type="hidden" id="itemTitle' + applyItemCount + '" name="itemTitle' + applyItemCount + '"  value="' + selectedItemTitle + '">\n\
                                    <input type="hidden" id="itemUnitName' + applyItemCount + '" name="itemUnitName' + applyItemCount + '"  value="' + selectedItemUnitName + '">\n\\n\
                                  </tr>';
                applyItemCount++;
            }
        }

        if (itemDetailsStr === "") {
            itemDetailsStr = "<tr id='noItemTr'><td colspan='6'>No Item Has Been Taken</td></tr>";
        }
        $('#itemDetailsTable').append(itemDetailsStr);
        allCalculation();
        $('#ietm-modal').modal('hide');
    }

    function allCalculation() {
        var itemCode = "";
        var itemQuantity = "";
        var itemRate = "";
        var amount = "";
        var tax = "";
        var subTotal = 0;
        for (var i = 1; i <= applyItemCount; i++) {
            itemCode = $('#itemCode' + i).val();
            if (typeof itemCode !== "undefined") {
                itemQuantity = $.trim($('#itemQuantity' + i).val());
                itemRate = $.trim($('#itemRate' + i).val());
                tax = $.trim($('#itemTaxRate' + i).val());
                //--------- quantity check ------------//
                if (!$.isNumeric(itemQuantity)) {
                    itemQuantity = 0;
                    $('#itemQuantity' + i).val('');
                }
                itemQuantity = parseFloat(itemQuantity);
                if (itemQuantity < 0) {
                    itemQuantity = 0;
                    $('#itemQuantity' + i).val('');
                }

                //--------- item rate check ------------//
                if (!$.isNumeric(itemRate)) {
                    itemRate = 0;
                    $('#itemRate' + i).val('');
                }
                itemRate = parseFloat(itemRate);
                if (itemRate < 0) {
                    itemRate = 0;
                    $('#itemRate' + i).val('');
                }

                //--------- tax check ------------//
                if (!$.isNumeric(tax)) {
                    tax = 0;
                    $('#itemTaxRate' + i).val('');
                }
                tax = parseFloat(tax);
                if (tax < 0) {
                    tax = 0;
                    $('#itemTaxRate' + i).val('');
                }

                amount = itemQuantity * itemRate;
                if (amount !== '0.00' && tax !== 0) {
                    amount = amount + ((amount * tax) / 100);
                }
                subTotal = subTotal + amount;
                $('#amountTd' + i).text(amount.toFixed(2));
            }
        }
        var adjustInput = $('#adjust').val();
        var adjust = parseFloat(adjustInput);
        if (!(adjustInput === '-' || adjustInput === '+')) {
            if (!$.isNumeric(adjustInput)) {
                $('#adjust').val('');
                adjust = 0;
            }
        } else {
            adjust = 0;
        }
        var total = subTotal + adjust;
        $('#subTotal').text(subTotal.toFixed(2));
        $('#total').text(total.toFixed(2));
        $('#billTotalAmount').val(total);
    }

    function  showSelectedItem(itemDetails) {
        //selectedItemCount = 1;
        var selectedItemCode = "";
        for (var i = 1; i <= selectedItemCount; i++) {
            selectedItemCode = $('#selectedItemCode' + i).val();
            if (typeof selectedItemCode !== "undefined") {
                if (itemDetails[1] === selectedItemCode) {
                    $('#selectedItemQuantity' + i).focus();
                    return false;
                }
            }
        }
        itemDetails.push('1');
        setSelectedItem(itemDetails);
        selectedItemCount++;
    }

    function setSelectedItem(itemDetails) {
        var itemStr = '<tr id="selectedItemTr' + selectedItemCount + '">\n\
            <td class="td-left" style="width:55%!important"><span class="template-green"><b>' + itemDetails[2] + '</b></span></td>\n\
            <td style="width:35%!important">\n\
                <div class="input-group">\n\
                    <span class="input-group-btn">\n\
                        <button type="button" class="btn btn-default btn-sm btn-number" data-type="minus" data-field="selectedItemQuantity' + selectedItemCount + '">\n\
                            <i class="fa fa-minus"></i>\n\
                        </button>\n\
                    </span>\n\
                    <input type="text" name="selectedItemQuantity' + selectedItemCount + '" id="selectedItemQuantity' + selectedItemCount + '" class="form-control input-number input-sm" value="' + itemDetails[8] + '" min="0" max="9999999">\n\
                    <span class="input-group-btn">\n\
                        <button type="button" class="btn btn-default btn-sm btn-number" data-type="plus" data-field="selectedItemQuantity' + selectedItemCount + '">\n\
                            <i class="fa fa-plus"></i>\n\
                        </button>\n\
                    </span>\n\
                </div>\n\
            </td>\n\
            <td style="width:10%!important">\n\
                <i class="fa fa-remove text-danger pointer" onclick="removeSelectedItem(' + selectedItemCount + ')"></i>\n\
            </td>\n\
            <input type="hidden" id="selectedItemCode' + selectedItemCount + '" value="' + itemDetails[1] + '">\n\
            <input type="hidden" id="selectedItemRate' + selectedItemCount + '" value="' + itemDetails[4] + '">\n\
            <input type="hidden" id="selectedItemUnitName' + selectedItemCount + '" value="' + itemDetails[3] + '">\n\
            <input type="hidden" id="selectedItemTaxTitle' + selectedItemCount + '" value="' + itemDetails[6] + '">\n\
            <input type="hidden" id="selectedItemTaxRate' + selectedItemCount + '" value="' + itemDetails[7] + '">\n\
            <input type="hidden" id="selectedItemTaxCode' + selectedItemCount + '" value="' + itemDetails[5] + '">\n\
            <input type="hidden" id="selectedItemTitle' + selectedItemCount + '" value="' + itemDetails[2] + '">\n\
        </tr>';
        $('#selectedItemTable').append(itemStr);
    }

    function removeSelectedItem(selectedItemSl) {
        $('#selectedItemTr' + selectedItemSl).remove();
    }

    function setVendor(vendorCode, vendorName) {
        $('#vendorCode').val(vendorCode);
        $('#vendorDetails').val(vendorName + ' (' + vendorCode + ')');
        $('#vendor-modal').modal('hide');
    }

    function newBill(billFlag) {
        var vendorCode = $.trim($('#vendorCode').val());
        var billDate = $.trim($('#billDate').val());
        var dueDate = $.trim($('#dueDate').val());

        if (vendorCode === "" || billDate === "" || dueDate === "") {
            sweetAlert('Vendor, Bill Date and Due Date are required');
            return false;
        }
        
        var itemCode = "";
        var flag = 0;
        var itemQuantity = "";
        var itemRate = "";
        for (var i = 1; i <= applyItemCount; i++) {
            itemCode = $('#itemCode' + i).val();
            if (typeof itemCode !== "undefined") {
                itemQuantity = $.trim($('#itemQuantity' + i).val());
                itemRate = $.trim($('#itemRate' + i).val());
                if (itemQuantity === "" || itemRate === "") {
                    sweetAlert('Please enter valid item details');
                    return false;
                }
                itemQuantity = parseFloat(itemQuantity);
                itemRate = parseFloat(itemRate);
                if (itemQuantity <= 0 || itemRate <= 0) {
                    sweetAlert('Please enter valid item details');
                    return false;
                }
                flag = 1;
            }
        }

        if (flag === 0) {
            sweetAlert('Please take at least one item');
            return false;
        }
        var title = "";
        if (billFlag === '1') {
            title = "Do you want to create a new bill?";
        } else {
            title = "Do you want to create and payment this bill?";
        }

        swal({
            title: title,
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: true,
            confirmButtonText: "Yes, I Do !",
            confirmButtonColor: "#ec6c62"
        }, function () {
            $('#applyItemCount').val(applyItemCount);
            $('#billFlag').val(billFlag);

            if (billFlag === '1') {
                $('#billForm').submit();
            } else {
                showLoader();
                $.ajax({
                    type: 'POST',
                    data: {billAmount: $('#billTotalAmount').val(), vendor: $('#vendorCode').val()},
                    url: '<?php echo base_url() ?>Purchase/checkBillPaymentWhenCreate',
                    success: function (result) {
                        hideLoader();
                        if (result === '1') {
                            $('#billForm').submit();
                        } else if (result === '3') {
                            window.location.href = BASE_URL + "Purchase/newBill";
                        } else {
                            var resultArr = result.split('|');
                            var exceedAmount = resultArr[1];
                            swal({
                                title: "Payment",
                                text: "You do not have enough available balance to paid this bill. You need more BDT " + exceedAmount + " to payment this bill. If you want to continue this, a payment receipt will be generated against this amount ( " + exceedAmount + " )",
                                type: "info",
                                closeOnConfirm: true,
                                showCancelButton: true,
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#A5DC86"
                            }, function () {
                                $('#billForm').submit();
                            });
                        }
                    }
                });
            }
        });
    }
</script>

<style>
    .selected-item-table tbody tr td{
        border-bottom:1px solid white!important;
        /*text-align: left!important;*/
    }
    .input-number{
        text-align: center;
    }
    .width-40{width: 40%!important}
    .width-20{width: 20%!important}
</style>
