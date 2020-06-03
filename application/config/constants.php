<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/* custom constant */
define('PROJECT_NAME', 'E-Accounting');
define('USER_CODE', '01');
define('ACCOUNT_TYPE_CODE', '02');
define('ACCOUNT_CODE', '03');
define('CONTACT_CODE', '04');
define('TAX_CODE', '05');
define('ITEM_CODE', '06');
define('USER_ROLE_CODE', '07');
define('INVOICE_CODE', '08');
define('PAYMENT_RECEIVED_CODE', '09');
define('BILL_CODE', '10');
define('PAYMENT_MADE_CODE', '11');

define('CUSTOMER', 'customer');
define('VENDOR', 'vendor');
define('PRODUCT', 'product');
define('SERVICE', 'service');

define('CREDIT', 'credit');
define('DEBIT', 'dedit');

define('UNPAID', '2');
define('PARTIALLY_PAID', '3');
define('PAID', '4');

define('ACCOUNT_RECEIVABLE', '0305190003');
define('ACCOUNT_PAYABLE', '0305190004');
define('PETTY_CASH', '0305190001');
define('UNDEPOSITED_FUNDS', '0305190010');
define('UNEARNED_REVENUE', '0305190011');
define('PREPAID_EXPENSE', '0305190012');

define('SHOP_ADDRESS', '<strong>Khan Tredars Ltd</strong>
                    <br>795/A Karwanbazar
                    <br>Dhaka-1207, Bangladesh
                    <br>Phone: 01945882352
                    <br>Email: rifatsakib230@gmail.com');
define('SHOP_NAME', 'Khan Traders Ltd');

define('TAX_PAYABLE', '0305190002');
define('GENERAL_INCOME', '0305190006');
define('COST_OF_GOODS_SOLD', '0305190009');
define('OTHER_EXPENSE', '0305190008');

define('INVOICE_CREATE_FOR', 'invoice_create');
define('PAYMENT_RECEIVE_FOR', 'payment_receive');
define('INVOICE_PAYMENT_FOR', 'invoice_payment');
define('BILL_CREATE_FOR', 'bill_create');
define('PAYMENT_MADE_FOR', 'payment_made');
define('BILL_PAYMENT_FOR', 'bill_payment');
