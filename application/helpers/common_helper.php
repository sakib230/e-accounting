<?php

function getCode($code = null) {
    $CI = & get_instance();
    $CI->load->database();
    $tokenno = "0";
    $query = $CI->db->get_where('code_table', array('element_code' => $code));
    $row_array = $query->row_array();
    $expMonth = date('Y-m', strtotime($row_array['serial_date']));
    $todaysMonth = date('Y-m');
    $todayMonth = strtotime($todaysMonth);

    $expirationMonth = strtotime($expMonth);
    if ($todayMonth > $expirationMonth) {
        $qry = "UPDATE code_table SET serial_date='" . date('Y-m-d') . "',serial_no='0001' WHERE element_code='" . $code . "'";
        $res1 = $CI->db->query($qry);
        $tokenno = '0001';
    } else {
        $qry = "UPDATE code_table SET serial_date='" . date('Y-m-d') . "', serial_no='" . str_pad($row_array['serial_no'] + 1, 4, "0", STR_PAD_LEFT) . "' WHERE element_code='" . $code . "'";
        $res1 = $CI->db->query($qry);
        $tokenno = str_pad($row_array['serial_no'] + 1, 4, "0", STR_PAD_LEFT);
    }
    return date('my') . $tokenno;
}

function reference_no() {
    return uniqid("acc", false);
}
