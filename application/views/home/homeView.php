<script src="<?php echo base_url(); ?>assets/js/highChart.js"></script>
<style>
    .highcharts-credits{
        display:none;
    }
</style>
<script>
    $(document).ready(function () {
        Highcharts.chart('salePurchaseGraphnMonth', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Amount (BDT)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} BDT</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: <?php echo $salePurchaseGraphValue ?>
        });

    });
</script>
<div class="col-md-12 col-sm-12 col-xs-12" style="background-color: #f7f7f7;">
    <div class="row tile_count">
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count col-md-offset-1">
            <span class="count_top"><i class="fa fa-users"></i> Total Customers</span>
            <div class="count"><?php echo $countValues['total_customer'] ?></div>
            <span class="count_bottom"><i class="green"><b><?php echo $countValues['customer_percentage']; ?>% </b></i> In This Month</span>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-users"></i> Total Vendors</span>
            <div class="count"><?php echo $countValues['total_vendor'] ?></div>
            <span class="count_bottom"><i class="green"><b><?php echo $countValues['vendor_percentage']; ?>% </b></i> In This Month</span>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-product-hunt"></i> Total Items</span>
            <div class="count"><?php echo $countValues['total_product'] + $countValues['total_service'] ?></div>
            <span class="count_bottom"><i class="green"><b><?php echo $countValues['total_product'] ?></b></i> Product(s), <i class="green"><b><?php echo $countValues['total_service'] ?></b> </i> Service(s)</span>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-shopping-cart"></i> Total Sales Invoice</span>
            <div class="count"><?php echo $countValues['invoice_count'] ?></div>
            <span class="count_bottom">In This Month</span>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-file-text-o"></i> Total Purchase Bill</span>
            <div class="count"><?php echo $countValues['bill_count'] ?></div>
            <span class="count_bottom">In This Month</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="m-t-15 font-14">
            <b>Total Receivables</b>
            <div style="border:1px solid #ddd;margin-top:15px;padding: 20px">
                Total Unpaid Sales Invoice <b class="font-15">BDT <?php echo number_format(($receivable['overDue'] + $receivable['due']), 2) ?></b>

                <div class="progress m-t-20">
                    <?php
                    $totalReceivable = $receivable['overDue'] + $receivable['due'];
                    $progressBar = ($receivable['due'] / $totalReceivable) * 100;
                    if ($totalReceivable == 0) {
                        $progressBar = "100";
                    }
                    ?>
                    <div class="progress-bar" style="width:<?php echo $progressBar ?>%; background:#1abb9c;">
                        <!--<div class="progress-value">70%</div>-->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="template-green float-left" style="width: 50%"> <b>Current Due: </b> <span class="font-19">BDT <?php echo number_format($receivable['due'], 2) ?></span></div>
                        <div class="text-danger float-right" style="width: 50%"> <b>Overdue: </b> <span class="font-19">BDT <?php echo number_format($receivable['overDue'], 2) ?></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="m-t-15 font-14">
            <b>Total Payables</b>
            <div style="border:1px solid #ddd;margin-top:15px;padding: 20px">
                Total Unpaid Purchase Bill <b class="font-15">BDT <?php echo number_format(($payable['overDue'] + $payable['due']), 2) ?></b>

                <div class="progress m-t-20">
                    <?php
                    $totalPayable = $payable['overDue'] + $payable['due'];
                    $progressBar = ($payable['due'] / $totalPayable) * 100;
                    if ($totalPayable == 0) {
                        $progressBar = "100";
                    }
                    ?>
                    <div class="progress-bar" style="width:<?php echo $progressBar ?>%; background:#1abb9c;">
                        <!--<div class="progress-value">70%</div>-->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="template-green float-left" style="width: 50%"> <b>Current Due: </b> <span class="font-19">BDT <?php echo number_format($payable['due'], 2) ?></span></div>
                        <div class="text-danger float-right" style="width: 50%"> <b>Overdue: </b> <span class="font-19">BDT <?php echo number_format($payable['overDue'], 2) ?></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="m-t-15">
            <b>Sales And Purchase</b>
            <div style="border:1px solid #ddd;margin-top:15px;padding: 20px">
                <div id="salePurchaseGraphnMonth" >
                </div>
            </div>

        </div>

    </div>
</div>