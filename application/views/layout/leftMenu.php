<div class="col-md-3 col-sm-3 col-xs-3 left_col menu_fixed">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo base_url() ?>" class="site_title"><i class="fa fa-book"></i> <span><?php echo PROJECT_NAME ?></span></a>
        </div>
        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="<?php echo base_url() ?>assets/images/user/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $this->session->userdata('fullName') ?></h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br/>

        <!-- sidebar menu -->

        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <?php
                $userPermittedPageCodeArr = explode(',', $this->session->userdata('permittedPageCode'));
                $menuArr[] = array(
                    'levelOneHeading' => 'User Management',
                    'levelOneIcon' => 'fa fa-users',
                    'levelTwoLi' => array(USER),
                    'levelTwo' => array(
                        array('levelTwoCode' => USER, 'levelTwoHeading' => 'User', 'levelTwoUrl' => 'UserManagement/user')
                    )
                );

                $menuArr[] = array(
                    'levelOneHeading' => 'Contacts',
                    'levelOneIcon' => 'fa fa-user',
                    'levelTwoLi' => array(CUSTOMER_PAGE, VENDOR_PAGE),
                    'levelTwo' => array(
                        array('levelTwoCode' => CUSTOMER_PAGE, 'levelTwoHeading' => 'Customer', 'levelTwoUrl' => 'Contacts/customer'),
                        array('levelTwoCode' => VENDOR_PAGE, 'levelTwoHeading' => 'Vendor', 'levelTwoUrl' => 'Contacts/vendor')
                    )
                );

                $menuArr[] = array(
                    'levelOneHeading' => 'Items',
                    'levelOneIcon' => 'fa fa-cube',
                    'levelTwoLi' => array(ITEM_PAGE),
                    'levelTwo' => array(
                        array('levelTwoCode' => ITEM_PAGE, 'levelTwoHeading' => 'Item', 'levelTwoUrl' => 'Items/item')
                    )
                );


                $menuArr[] = array(
                    'levelOneHeading' => 'Sales',
                    'levelOneIcon' => 'fa fa-shopping-cart',
                    'levelTwoLi' => array(INVOICE),
                    'levelTwo' => array(
                        array('levelTwoCode' => INVOICE, 'levelTwoHeading' => 'Invoice', 'levelTwoUrl' => 'Sales/invoice'),
                        array('levelTwoCode' => PAYMENT_RECEIVED, 'levelTwoHeading' => 'Payment Received', 'levelTwoUrl' => 'Sales/paymentReceived'),
                        array('levelTwoCode' => INVOICE_PAYMENT, 'levelTwoHeading' => 'Invoice Payment', 'levelTwoUrl' => 'Sales/invoicePayment')
                    )
                );

                $menuArr[] = array(
                    'levelOneHeading' => 'Purchase',
                    'levelOneIcon' => 'fa fa-briefcase',
                    'levelTwoLi' => array(BILL_PAGE, PAYMENT_MADE_PAGE, BILL_PAYMENT_PAGE),
                    'levelTwo' => array(
                        array('levelTwoCode' => BILL_PAGE, 'levelTwoHeading' => 'Bill', 'levelTwoUrl' => 'Purchase/bill'),
                        array('levelTwoCode' => PAYMENT_MADE_PAGE, 'levelTwoHeading' => 'Payment Made', 'levelTwoUrl' => 'Purchase/paymentMade'),
                        array('levelTwoCode' => BILL_PAYMENT_PAGE, 'levelTwoHeading' => 'Bill Payment', 'levelTwoUrl' => 'Purchase/billPayment')
                    )
                );

                $menuArr[] = array(
                    'levelOneHeading' => 'Accounts',
                    'levelOneIcon' => 'fa fa-list',
                    'levelTwoLi' => array(CHART_ACCOUNT_PAGE, TAX_PAGE),
                    'levelTwo' => array(
                        array('levelTwoCode' => CHART_ACCOUNT_PAGE, 'levelTwoHeading' => 'Chart of Account', 'levelTwoUrl' => 'Accounts/chartAccount'),
                        array('levelTwoCode' => TAX_PAGE, 'levelTwoHeading' => 'Tax', 'levelTwoUrl' => 'Accounts/tax')
                    )
                );

                $menuArr[] = array(
                    'levelOneHeading' => 'Reports',
                    'levelOneIcon' => 'fa fa-file-text-o',
                    'levelTwoLi' => array(REPORT_SALE_BY_ITEM_PAGE, REPORT_SALE_BY_CUSTOMER_PAGE, REPORT_INVOICE_DETAILS_PAGE),
                    'levelTwo' => array(
                        array('levelTwoCode' => REPORT_GENERAL_LEDGER_PAGE, 'levelTwoHeading' => 'General Ledger', 'levelTwoUrl' => 'Reports/generalLedger'),
                        array('levelTwoCode' => REPORT_JOURNAL_PAGE, 'levelTwoHeading' => 'Journal', 'levelTwoUrl' => 'Reports/journal'),
                        array('levelTwoCode' => REPORT_TRAIL_BALANCE_PAGE, 'levelTwoHeading' => 'Trail Balance', 'levelTwoUrl' => 'Reports/trailBalance'),
                        array('levelTwoCode' => REPORT_SALE_BY_ITEM_PAGE, 'levelTwoHeading' => 'Sale By Item', 'levelTwoUrl' => 'Reports/salesByItem'),
                        array('levelTwoCode' => REPORT_SALE_BY_CUSTOMER_PAGE, 'levelTwoHeading' => 'Sale By Customer', 'levelTwoUrl' => 'Reports/salesByCustomer'),
                        array('levelTwoCode' => REPORT_INVOICE_DETAILS_PAGE, 'levelTwoHeading' => 'Invoice Details', 'levelTwoUrl' => 'Reports/invoiceDetails'),
                        array('levelTwoCode' => REPORT_PURCHASE_BY_ITEM_PAGE, 'levelTwoHeading' => 'Purchase By Item', 'levelTwoUrl' => 'Reports/purchaseByItem'),
                        array('levelTwoCode' => REPORT_PURCHASE_BY_VENDOR_PAGE, 'levelTwoHeading' => 'Purchase By Vendor', 'levelTwoUrl' => 'Reports/purchaseByVendor'),
                        array('levelTwoCode' => REPORT_BILL_DETAILS_PAGE, 'levelTwoHeading' => 'Bill Details', 'levelTwoUrl' => 'Reports/billDetails')
                    )
                );
                ?>

                <ul class="nav side-menu">
                    <?php
                    foreach ($menuArr as $menu => $eachMenu) {
                        $levelOneIsactive = '';
                        $childMenuIsBlock = '';
                        $levelTwoStr = '';
                        $childMenuBlockFlag = 0;
                        foreach ($eachMenu['levelTwo'] as $levelTwo => $levelTwoElement) {
                            $currentPage = '';

                            if (in_array($levelTwoElement['levelTwoCode'], $userPermittedPageCodeArr)) {
                                if ($currentPageCode == $levelTwoElement['levelTwoCode']) {
                                    $currentPage = 'current-page';
                                    $childMenuBlockFlag = 1;
                                }
                                $levelTwoStr .= '<li class="' . $currentPage . '"><a href="' . base_url() . $levelTwoElement['levelTwoUrl'] . '">' . $levelTwoElement['levelTwoHeading'] . '</a></li>';
                            }
                        }

                        if ($levelTwoStr) {
                            if ($childMenuBlockFlag) {
                                $levelOneIsactive = 'active';
                                $childMenuIsBlock = 'style="display:block"';
                            }
                            echo '<li class="' . $levelOneIsactive . '"><a><i class="' . $eachMenu['levelOneIcon'] . '"></i> ' . $eachMenu['levelOneHeading'] . ' <span class="fa fa-chevron-down"></span></a>';
                            echo '<ul class="nav child_menu" ' . $childMenuIsBlock . ' >';
                            echo $levelTwoStr;
                            echo '</ul>';
                            echo '</li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>