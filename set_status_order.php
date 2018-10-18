<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
    if(isset($_POST['type'])){
        $id = $_POST['id'];
        $status = $_POST['status'];
        $quantity = $_POST['quantity'];
        $type = $_POST['type'];
           $sql = "SELECT * FROM bar_reservation_product WHERE id=$id";
           $temp = DB::fetch($sql);
           $current_quantity = $temp['quantity'];
           $current_complete = $temp['complete'];
        if($type=='total'){
           if($status == '1'){
                if($current_complete==0){
                    DB::update('bar_reservation_product',array('complete'=>$quantity)," id=$id");
                }
                else{
                    DB::update('bar_reservation_product',array('complete'=>$quantity+$current_complete)," id=$id");
                }
           } 
           else{
                if($current_complete==$quantity){
                    DB::update('bar_reservation_product',array('complete'=>0)," id=$id");
                }
                else{
                    DB::update('bar_reservation_product',array('complete'=>$current_complete-$quantity)," id=$id");
                }
           }
        }
        else{
            if($status == '1'){
                DB::update('bar_reservation_product',array('complete'=>$current_complete+$quantity)," id=$id"); 
            }
            else{
                DB::update('bar_reservation_product',array('complete'=>$current_complete-$quantity)," id=$id"); 
            }
        }    
    }
    else{
      $id = $_POST['id'];
      $quantity = $_POST['quantity'];
      $sql = "SELECT * FROM bar_reservation_product WHERE id=$id";
      $temp = DB::fetch($sql);
      $current_quantity = $temp['quantity'];
      if($quantity == $current_quantity){
        DB::delete_id('bar_reservation_product',$id);
      }
      else{
         DB::update('bar_reservation_product', array('quantity'=>$current_quantity-$quantity)," id=$id");
      }  
    }
    
?>