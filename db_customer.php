<?php
define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
set_include_path(ROOT_PATH);
require_once 'packages/core/includes/system/config.php';

function get_city()
   	{
        if($_REQUEST['country'])
        $id_country = substr($_REQUEST['country'],0,3);
        $sql = "select zone.structure_id as id, zone.name_1 as name from zone ORDER BY zone.name_1 DESC";
        $rows = DB::fetch_all($sql);
        $bar_lists = array();
        foreach($rows as $key=>$row)
        {
            if((substr($row['id'],3,2)!="00")and(substr($row['id'],5,2)=="00")and(substr($row['id'],0,3))==$id_country)
            {
                $bar_lists[$row['id']] = $row['name'];
            }
        }  
        return $bar_lists;
   	}
function get_district()
    {
        if($_REQUEST['city'])
        $id_city = substr($_REQUEST['city'],0,5);
        $sql = "select zone.structure_id as ID, zone.name_1 as NAME from zone ORDER BY zone.name_1 DESC";
        $rows = DB::fetch_all($sql); 
        $distric_list = array();
        foreach($rows as $key=>$row)
        {
            if((substr($row['id'],5,2)!="00")and(substr($row['id'],7,2)=="00")and(substr($row['id'],0,5))==$id_city){
                
                $distric_list[$row['id']] = $row['name'];
            }
        }  
        return $distric_list;
        
    }
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
