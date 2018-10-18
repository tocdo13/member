<?php 
    function printed_tmp_bill()
    {
        $conn = oci_connect('standard', 'hotel2014', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            return 0;
        }
        //excute
        $stid = oci_parse($conn, 'UPDATE bar_reservation
                        SET printed_tmp_bill = 1
                        WHERE id = '.$_REQUEST['bar_reservation_id']);
        oci_execute($stid);
        oci_commit($conn);
        return 1;
    }

    switch($_REQUEST['cmd'])
    {
        case "printed_tmp_bill":
        {
            echo printed_tmp_bill(); break;
        }
        default: echo '';break;
    }
?>