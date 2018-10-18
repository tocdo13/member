<?php
class EditSetMenuForm extends Form
{
	function EditSetMenuForm()
	{
		Form::Form('EditSetMenuForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');	
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');	
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->add('menu_code',new UniqueType(true,'duplicate_menu_code','bar_set_menu','code'));
        
	}
    function on_submit(){
        if(isset($_POST['submit'])){
            
            if( (Url::get('cmd')=='edit'))
            {
                $sql = "SELECT * FROM bar_reservation_product WHERE product_id='".mb_strtoupper(trim($_POST['menu_code']),'utf-8')."'";
                $result = DB::fetch($sql);
                
                $sql = "SELECT * FROM mice_restaurant_product WHERE product_id='".mb_strtoupper(trim($_POST['menu_code']),'utf-8')."'";
                $result_mice = DB::fetch($sql);
                
                if(!empty($result) && !empty($result_mice))
                {
                     $this->error("exist","Mã ".$menu_code." đã được đặt! Xin vui lòng không chỉnh sửa!");
                     return; 
                }
            }
            
            require_once 'packages/core/includes/system/config.php';
	        require_once 'packages/core/includes/utils/vn_code.php';
            $_REQUEST['menu_code'] = trim($_POST['menu_code']);
            $_REQUEST['menu_name'] = $_POST['menu_name'];
            $_REQUEST['department'] = $_POST['department']; 
            $_REQUEST['total_hidden'] = $_POST['total_hidden']; 
            $_REQUEST['start_date'] = $_POST['start_date']; 
            $_REQUEST['end_date'] = $_POST['end_date']; 
            $menu_code = mb_strtoupper(trim($_POST['menu_code']),'utf-8');
            $menu_name = $_POST['menu_name'];
            $department = $_POST['department'];
            $total_hidden = System::calculate_number($_POST['total_hidden']);
            $start_date = Date_Time::to_orc_date($_POST['start_date']); 
            $end_date = Date_Time::to_orc_date($_POST['end_date']);
            require_once 'packages/core/includes/system/si_database.php';
            $category_id = DB::fetch("SELECT id FROM product_category WHERE UPPER(code)='SETMENU'");
            if(empty($category_id))
            {
                $category_id_DA = DB::fetch("SELECT id FROM product_category WHERE UPPER(code)='DA'");
                DB::insert('product_category', array('code'=>'SETMENU','name'=>'Set menu','name_en'=>'Set menu','structure_id'=>si_child('product_category',structure_id('product_category',$category_id_DA['id']))));
            }
            
            $unit_id = DB::fetch("SELECT id FROM unit WHERE UPPER(name_1)='SET'");
            if(empty($unit_id))
            {
                DB::insert('unit',array('name_1'=>'set','name_2'=>'set','value'=>1));
            }
            
            $sql = "SELECT id,name_1 as department_name FROM department WHERE code='".$department."'";
            $department_info = DB::fetch($sql);
            
            $sql = "SELECT product.id 
                        FROM product
                        INNER JOIN product_category ON product.category_id = product_category.id
                     WHERE UPPER(product.id)='".$menu_code."' AND UPPER(product_category.code)='SETMENU'";
            $product = DB::fetch($sql);
            //System::debug($sql); exit();
            if(empty($product))
            {
                $category_id = DB::fetch("SELECT id FROM product_category WHERE UPPER(code)='SETMENU'");
                
                $unit_id = DB::fetch("SELECT id FROM unit WHERE UPPER(name_1)='SET'");
                $product_info = array("id"=>$menu_code,
                                      "name_1"=>$menu_name,
                                      "name_2"=>$menu_name,
                                      "category_id"=>$category_id['id'],
                                      "unit_id"=>$unit_id['id'],
                                      "type"=>"PRODUCT",
                                      "status"=>"avaiable"
                                        );                        
               DB::insert("product",$product_info);
            }
            
            if( (Url::get('cmd')=='edit' && !Url::get('id')) || Url::get('copy') ){
                $sql = "SELECT product_price_list.id,
                               product_price_list.price,
                               department.name_1 as department_name
                            FROM product_price_list
                                 INNER JOIN product ON product.id = product_price_list.product_id
                                 INNER JOIN department ON department.code = product_price_list.department_code
                                 WHERE product.id = '".$menu_code."' AND (product_price_list.department_code = '".$department."' OR product_price_list.department_code = 'RES') AND product_price_list.portal_id='".PORTAL_ID."'
                            ";
                $product_price = DB::fetch($sql);  
                //System::debug($product_price); exit();         
                if(empty($product_price))
                {
                    $product_price_info = array("product_id"=>$menu_code,
                                          "price"=>$total_hidden,
                                          "start_date"=>Date_Time::to_orc_date(date("d/m/Y")),
                                          "department_code"=>$department,
                                          "portal_id"=>PORTAL_ID,
                                          "start_date"=>$start_date,
                                          "end_date"=>$end_date
                                            );
                                           
                      DB::insert('product_price_list',$product_price_info);                      
                }
                else{
                    if($product_price['price']!=$total_hidden)
                    {
                        $this->error("duplicate","Mã ".$menu_code." đã được cài đặt giá tại ".$product_price['department_name']." với mức giá là ".System::display_number($total_hidden)." ! Xin vui lòng kiểm tra lại!");
                        return;
                    }
                }
            }
            $check = true;
            if(Url::get('id')){
              $sql = "SELECT id, code FROM bar_set_menu WHERE id=".Url::get('id');
              $check_set_menu_old = DB::fetch($sql);
              if(!empty($check_set_menu_old) && $check_set_menu_old['code']==$menu_code)
              {
                 $check = false;
              }
            }
            $sql = "SELECT id, code FROM bar_set_menu WHERE UPPER(code)='".$menu_code."'";  
            $result = DB::fetch($sql);
            
            
            if($check==true && !empty($result))
            {
                
              $this->error("duplicate","Mã ".$menu_code." đã tồn tại! Xin vui lòng chọn mã khác!");
              return;  
            }
            else{
                if(Url::get('id') && !Url::get('copy')){
                    $sql = "SELECT  bar_set_menu.id,
                                bar_set_menu.code,
                                bar_set_menu.name,
                                bar_set_menu.total,
                                bar_set_menu.department_code
                                FROM bar_set_menu 
                                WHERE bar_set_menu.id!=".Url::get('id')." AND (bar_set_menu.department_code='".$department."' OR bar_set_menu.department_code='RES')";
                }
                else{
                    $sql = "SELECT  bar_set_menu.id,
                                    bar_set_menu.code,
                                    bar_set_menu.name,
                                    bar_set_menu.total,
                                    bar_set_menu.department_code
                                    FROM bar_set_menu 
                                    WHERE (bar_set_menu.department_code='".$department."' OR bar_set_menu.department_code='RES')";
               }
               $bar_set_menu_old = DB::fetch_all($sql);
              // System::debug($bar_set_menu_old);
              // System::debug($_REQUEST['mi_set_menu']);
               foreach($bar_set_menu_old as $key=>$value)
               {
                  $sql = "SELECT * FROM bar_set_menu_product WHERE bar_set_menu_id=".$value['id'];
                  $bar_set_menu_product = DB::fetch_all($sql);
                  
                  //System::debug($bar_set_menu_product);
                  
                  
                  if(count($bar_set_menu_product)==count($_REQUEST['mi_set_menu'])){
                      $count = count($bar_set_menu_product);
                      foreach($bar_set_menu_product as $k=>$v)
                      {
                          $check = true;
                          foreach($_REQUEST['mi_set_menu'] as $k2=>$v2){
                            if(($v2['code']==$v['product_id'] && $v2['quantity']==$v['quantity']))
                            {
                               $check = false;  
                            }
                          }
                          if($check==false)
                          {
                            $count--;
                          }  
                      }
                      //System::debug($count);
                       //continue;
                      if($count==0)
                      {
                        $this->error("duplicate","Mã ".$menu_code." có thành phần định lượng giống hệt với mã ".mb_strtoupper($value['code'],'utf-8')." đã cài đặt trước đó ! Xin vui lòng kiểm tra lại!",false);
                        return;
                      }
                  }   
               }
                //exit();    
            }
            if(!Url::get('copy')){
                    if($this->check()){
                        
                        //$sql = 'SELECT 
//                        product_price_list.id 
//                        FROM 
//                        product_price_list
//                        INNER JOIN product ON product_price_list.product_id = product.id
//                        WHERE 
//                        UPPER(product.id) LIKE \'%'.$menu_code.'%\'
//        				AND (LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower($menu_name,'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower($menu_name,'utf-8')).'%\')';
//                        $result = DB::fetch($sql);
//                        //System::debug($sql); exit();
//                        if(empty($result)){
//                            echo "<script>";
//                            echo "alert('Không tồn tại sản phẩm nào có mã \"".$menu_code."\" và tên \"".$menu_name."\" hoặc chưa được khai báo giá sản phẩm.')";    
//                            echo "</script>";
//                            return;
//                        }
                        
                        $sql = "SELECT id FROM unit WHERE UPPER(name_1)='SET'";
                        $unit_set_id = DB::fetch($sql);
                         
                            if(empty($_POST['bar_set_menu_id'])){
                            $id = DB::insert('bar_set_menu',array('code'=>$menu_code,'name'=>$menu_name,'department_code'=>$department,'total'=>$total_hidden,'portal_id'=>PORTAL_ID));
                            DB::update('product',array('unit_id'=>$unit_set_id['id']),"id='$menu_code'");
                            if(isset($_REQUEST['mi_set_menu'])){
                                foreach($_REQUEST['mi_set_menu'] as $key=>$value){
                                    $product_id = $value['code'];
                                    $product_quantity = $value['quantity'];
                                    DB::insert('bar_set_menu_product',array('product_id'=>$product_id,'bar_set_menu_id'=>$id,'quantity'=>$product_quantity));
                                }
                            } 
                        }
                        else{
                            $id = $_POST['bar_set_menu_id'];
                            $sql = "SELECT id,code,department_code,portal_id FROM bar_set_menu WHERE id=".$id;
                            $current_set_menu = DB::fetch($sql);
                            DB::update('bar_set_menu',array('code'=>$menu_code,'name'=>$menu_name,'department_code'=>$department,'total'=>$total_hidden,'portal_id'=>PORTAL_ID),' id='.$id);
                            DB::update('product',array('unit_id'=>$unit_set_id['id'],'name_1'=>$menu_name,'name_2'=>$menu_name),"id='$menu_code'");
                            DB::update('product_price_list',array('product_id'=>$menu_code,'department_code'=>$department,'portal_id'=>PORTAL_ID,'price'=>$total_hidden,"start_date"=>$start_date,"end_date"=>$end_date)," product_id='".$current_set_menu['code']."' AND department_code='".$current_set_menu['department_code']."' AND portal_id='".$current_set_menu['portal_id']."'");
                            if(isset($_REQUEST['mi_set_menu'])){
                                $id_list = "0";
                                foreach($_REQUEST['mi_set_menu'] as $key=>$value){                       
                                    $product_id = $value['code'];
                                    $product_quantity = $value['quantity'];
                                    if(!empty($value['id']) && DB::exists_id('bar_set_menu_product',$value['id'])){
                                        $id_list.=",".$value['id'];
                                       DB::update('bar_set_menu_product',array('product_id'=>$product_id,'quantity'=>$product_quantity),' id='.$value['id']); 
                                    }
                                    else{
                                        $id_add = DB::insert('bar_set_menu_product',array('product_id'=>$product_id,'bar_set_menu_id'=>$id,'quantity'=>$product_quantity));
                                        $id_list.=",".$id_add; 
                                    }
                                } 
                                DB::delete('bar_set_menu_product',' id NOT IN ('.$id_list.") AND bar_set_menu_id=".$id);                  
                                //DB::update_id('bar_set_menu',array('total'=>$total),$id);
                            } 
                        }                                            
                }
            }
            else{                                                                                                              
                    if(isset($_REQUEST['mi_set_menu'])){
                        foreach($_REQUEST['mi_set_menu'] as $key=>$value){
                            $product_id = $value['code'];
                            $product_quantity = $value['quantity'];
                            $sql = "SELECT 
                                        id 
                                FROM product_price_list 
                                WHERE product_price_list.product_id='".$product_id."' AND product_price_list.department_code='".$department."' AND product_price_list.portal_id='".PORTAL_ID."'";
                            $product_exist = DB::fetch($sql);
                            if(empty($product_exist))
                            {
                               $this->error("duplicate","Mã ".$value['code']." chưa được khai báo giá tại ".$department_info['department_name']."! Xin vui lòng kiểm tra lại!",false);
                               return;                                
                            }                                                                                                              
                        }
                         
                        $id = DB::insert('bar_set_menu',array('code'=>$menu_code,'name'=>$menu_name,'department_code'=>$department,'total'=>$total_hidden,'portal_id'=>PORTAL_ID));
                        foreach($_REQUEST['mi_set_menu'] as $key=>$value){
                            $product_id = $value['code'];
                            $product_quantity = $value['quantity'];
                            DB::insert('bar_set_menu_product',array('product_id'=>$product_id,'bar_set_menu_id'=>$id,'quantity'=>$product_quantity));                            
                        }
                    } 
                    
            }
            require_once("packages/hotel/modules/ProductPrice/db.php");
            ProductPriceDB::export_cache();
            
            Url::redirect('create_set_menu');
        }        
    }
	function draw()
	{
       $sql ='select  
                    product.id || \'_\' || department.code as id,
                    product.id as product_id,
                    product.name_'.Portal::language().' as product_name, 
                    product.type,
                    product_price_list.price,
                    unit.name_'.Portal::language().' as unit,
                    department.code as department_code
				from 	
                    product
                    INNER JOIN product_price_list ON product.id = product_price_list.product_id
                    inner join department ON department.code = product_price_list.department_code
                    LEFT JOIN unit ON product.unit_id = unit.id
				where
                    (product.type =\'PRODUCT\' or product.type =\'GOODS\' or product.type =\'DRINK\')
                    AND (product.id != \'DOUTSIDE\' OR product.id != \'FOUTSIDE\')
                    AND (department.code=\'RES\' or department.parent_id=2)
                    and product.status=\'avaiable\' 
			';
         
        $this->map['products'] = DB::fetch_all($sql);   
        //System::debug($_REQUEST);  
        if(isset($_GET['id'])){
		  $id = $_GET['id'];
          $sql = "SELECT  bar_set_menu.id,
                        bar_set_menu.department_code as department_code,
                        bar_set_menu.code,
                        bar_set_menu.name,
                        bar_set_menu.total,
                        product_price_list.start_date,
                        product_price_list.end_date
          FROM bar_set_menu 
          INNER JOIN product_price_list ON product_price_list.product_id = bar_set_menu.code AND product_price_list.department_code = bar_set_menu.department_code AND product_price_list.portal_id = bar_set_menu.portal_id
          WHERE bar_set_menu.id = ".$id." AND bar_set_menu.portal_id='".PORTAL_ID."'";
          $bar_set_menu = DB::fetch($sql);
          $this->map['department'] = Url::get('department_code')?Url::get('department_code'):$bar_set_menu['department_code'];
          $this->map['menu_code'] = Url::get('code')?Url::get('code'):$bar_set_menu['code'];
          $this->map['menu_name'] = Url::get('name')?Url::get('name'):$bar_set_menu['name'];
          $this->map['start_date'] = Url::get('start_date')?Url::get('start_date'):Date_Time::convert_orc_date_to_date($bar_set_menu['start_date'],"/");
          $this->map['end_date'] = Url::get('end_date')?Url::get('end_date'):Date_Time::convert_orc_date_to_date($bar_set_menu['end_date'],"/");
          $this->map['total'] = Url::get('total_hidden')?System::calculate_number(Url::get('total_hidden')):$bar_set_menu['total'];
          $this->map['total_hidden'] = Url::get('total_hidden')?System::calculate_number(Url::get('total_hidden')):$bar_set_menu['total'];
          if(!isset($_REQUEST['mi_set_menu'])){    
              $sql = "SELECT 
                bar_set_menu_product.id as id,
                bar_set_menu_product.product_id as code,
                bar_set_menu_product.quantity as quantity,
                product.name_1 as product_name, 
                unit.name_".Portal::language()." as unit
                FROM 
                bar_set_menu_product INNER JOIN product ON bar_set_menu_product.product_id = product.id
                INNER JOIN unit ON unit.id=product.unit_id
                WHERE bar_set_menu_product.bar_set_menu_id=".$id;
               $result = DB::fetch_all($sql);
               $i = 1;
               foreach($result as $key=>$value){
                    $result[$key]['stt'] = $i;
                    $i++;
               }
               
               $_REQUEST['mi_set_menu'] = $result; 
            }   
		}
        
        if(isset($_GET['copy']))
        {
            $code_temp = $bar_set_menu['code'];
            if(strpos($bar_set_menu['code'],"-")!=false)
            {
                $code_temp = explode("-",$bar_set_menu['code']);
                $code_temp = $code_temp[0];
            } 
            $sql = "SELECT *
                      FROM (SELECT * FROM bar_set_menu WHERE code LIKE('%".$code_temp."-%') ORDER BY ID DESC )
                     WHERE ROWNUM = 1";
            $max_code = DB::fetch($sql);  
              
            if(empty($max_code))
            {
                $this->map['menu_code'].="-00001";
            }
            else{
                $max_code_id = $max_code['code'];
                $arr_temp = explode("-",$max_code_id);
                $max_code_new = intval($arr_temp[1])+1;
                $max_code_new = str_pad($max_code_new,5,"0",STR_PAD_LEFT);
                $this->map['menu_code'] = $arr_temp[0]."-".$max_code_new;
            }    
        }
        $department = DB::fetch_all(' select 
                                        portal_department.id as portal_department_id,
                                        portal_department.department_code as id,
                                        department.name_'.Portal::language().' as name ,
                                        portal_department.portal_id
                                    from 
                                        portal_department 
                                        inner join department on department.code = portal_department.department_code
                                    where 
                                        portal_department.PORTAL_ID = \''.PORTAL_ID.'\' AND (department.code=\'RES\' or department.parent_id=2)
                                    order by
                                        department.id   
                                '
                                );                    
        $department = String::get_list($department);
        
        foreach($department as $key=>$value){
            if($key!='RES'){
                $value = "--".$value;
                $department[$key] = $value;
            }
        }
        $this->map['department_list'] = $department;
        
        
        
        if(isset($_REQUEST['menu_code'])){
            $this->map['menu_code'] = $_REQUEST['menu_code'];
        }		
        if(isset($_REQUEST['menu_name'])){
            $this->map['menu_name'] = $_REQUEST['menu_name'];
        }
	   //System::debug($this->map);
       $this->parse_layout('edit',$this->map);
	}
}
?>