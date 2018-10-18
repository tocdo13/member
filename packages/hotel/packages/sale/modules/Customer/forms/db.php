<?php
function get_city()
   	{
   	    //echo "exit ";
        if($_REQUEST['country'])
        $id_country = substr($_REQUEST['country'],0,3);
        //echo $_REQUEST['country']."--";
        //echo $id_country ;
        //connect
        $conn = oci_connect('develop', 'hotel2014', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        //excute
        $stid = oci_parse($conn, 'select zone.structure_id as id, zone.name_1 as name from zone ORDER BY zone.name_1 DESC');
        oci_execute($stid);
                
        $bar_lists = array();
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            if((substr($row['ID'],3,2)!="00")and(substr($row['ID'],5,2)=="00")and(substr($row['ID'],0,3))==$id_country){
                $bar_lists[$row['ID']] = $row['NAME'];
            }
        }  
        return $bar_lists;
   	}
function get_district()
    {
        //echo "exit ";
        if($_REQUEST['city'])
        $id_city = substr($_REQUEST['city'],0,5);
        //echo $_REQUEST['city']."--";
        //echo $id_city ;
        //connect
        $conn = oci_connect('develop', 'hotel2014', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        //excute
        $stid = oci_parse($conn, 'select zone.structure_id as id, zone.name_1 as name from zone ORDER BY zone.name_1 DESC');
        oci_execute($stid);
                
        $distric_list = array();
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            if((substr($row['ID'],5,2)!="00")and(substr($row['ID'],7,2)=="00")and(substr($row['ID'],0,5))==$id_city){
                
                $distric_list[$row['ID']] = $row['NAME'];
            }
        }  
        return $distric_list;
        
    }
// ############################################### //
    //echo 'hello';
    //exit();
    switch($_REQUEST['data'])
    {
        case "get_city":
        {
            echo json_encode(get_city()); break;
        }
        case "get_district":
        {
            echo json_encode(get_district()); break;
        }
        default: echo '';break;
    }
?>
