<?php 
    function get_karaokes()
   	{
        //cond
  		$cond = '1=1';
        if($_REQUEST['portal_id'])
            $cond .= " and portal_id = '#".$_REQUEST['portal_id']."'";
        //connect
        $conn = oci_connect('nhap', 'hotel2014', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        //excute
        $stid = oci_parse($conn, 'select id,name from karaoke where '.$cond);
        oci_execute($stid);
                
        $karaoke_lists = array();
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $karaoke_lists[$row['ID']] = $row['NAME'];
        }
            
        return $karaoke_lists;
   	}
    
    function get_category()
   	{
   	    //connect
        $conn = oci_connect('nhap', 'hotel2014', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $str_karaoke_ids=$_REQUEST['str_karaokes'];
        /***get categories***/
        //get cond department
        $cond_karaoke_d = $cond_karaoke_d?" and id in (".$str_karaoke_ids.")":'';
        $cond_portal_d = $_REQUEST['portal_id']?" and portal_id = '#".$_REQUEST['portal_id']."'":"";
        //excute
        $stid = oci_parse($conn, "select department_id as id from karaoke where 1=1 ".$cond_karaoke_d.$cond_portal_d);
        oci_execute($stid);
                
        $dp_code = array();
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $dp_code[$row['ID']] = $row['ID'];
        }
        
        $str_dp_codes='';
        foreach($dp_code as $key=>$value)
        {
            $str_dp_codes.=",'".$key."'";
        }
                
        $cond_dep_cate = $str_dp_codes?" and product_price_list.department_code in (".substr($str_dp_codes,1,strlen($str_dp_codes)-1).")":"";
        $cond_portal_cate = $_REQUEST['portal_id']?" and product_price_list.portal_id = '#".$_REQUEST['portal_id']."'":"";
        //get cond structure_id
        require_once 'D:/wamp/www/nhap/packages/core/includes/system/id_structure.php';
        $ROOT_ID = "1000000000000000000";
        //su ham function direct_child_cond($structure_id, $child_level=1,$extra = '') trong class IDStructure
        $cond_struct_cate = "(".IDStructure::direct_child_cond($ROOT_ID,1,"product_category.").")";
        //categories level = DA
        //excute
        $stid = oci_parse($conn, "SELECT
        					product_category.id
        					,product_category.name
        				FROM
        					product_category
        					INNER JOIN product ON product_category.id = product.category_id
        					INNER JOIN product_price_list ON product_price_list.product_id = product.id
        					INNER JOIN unit ON unit.id = product.unit_id
        				WHERE ".$cond_struct_cate.$cond_dep_cate.$cond_portal_cate."
        				ORDER BY product_category.position");
        oci_execute($stid);
                
        $categories = array();
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $categories[$row['ID']]=$row;
        }
        
        return array('categories'=>$categories);
   	}
        
    switch($_REQUEST['cmd'])
    {
        case "get_karaokes":
        {
            echo json_encode(get_karaokes()); break;
        }
        case "get_categorys":
        {
            echo json_encode(get_category()); break;
        }
        default: echo '';break;
    }
?>