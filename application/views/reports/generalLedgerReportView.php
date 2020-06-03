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
                <span class="font-20">General Ledger</span><br>
                <span class="font-12">Basis: Accrual</span><br>
                <span class="font-14">From <?php echo $reportFromDate ?> to <?php echo $reportToDate ?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 m-t-30">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th class='td-left'>Account</th>
                        <th class='td-right'>Debit<br><small><i>BDT</i></small></th>
                        <th class='td-right'>Credit<br><small><i>BDT</i></small></th>
                        <th class='td-right'>Balance<br><small><i>BDT</i></small></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($generalLedgers as $generalLedger) {
                        echo "<tr>";
                        echo "<td class='td-left'>$generalLedger[account_title]</td>";
                        echo "<td class='td-right'>" . number_format($generalLedger['debit'], 2) . "</td>";
                        echo "<td class='td-right'>" . number_format($generalLedger['credit'], 2) . "</td>";
                        $balance = $generalLedger['debit'] - $generalLedger['credit'];
                        if ($balance < 0) {
                            $balance = '(' . number_format(-1 * $balance, 2) . ')';
                        } else {
                            $balance = number_format($balance, 2);
                        }
                        echo "<td class='td-right'>$balance</td>";
                        echo "</tr>";
                    }

                    if (!$generalLedgers) {
                        echo "<tr>";
                        echo "<td colspan='4'>No record found</td>";
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
                    <form id="customizeForm" action="<?php echo base_url() ?>Reports/generalLedger" method="post">
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


<script>
    function showCustomizeReportModal() {
        $("#customize-report-modal").modal('show');
    }
    function runReport() {
        $('#customizeForm').submit();
    }

</script>