<?php
class EditRecommendationForm extends Form
{
    function EditRecommendationForm()
    {
        Form::Form('EditRecommendationForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
        
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    function on_submit()
    {

        if(isset($_REQUEST['deleted_ids']) && $_REQUEST['deleted_ids']!='')
        {
            DB::delete('pc_recommend_detail','id in('.$_REQUEST['deleted_ids'].')');
        } 
        if(isset($_REQUEST['delete']))
        {
            if(Url::get('id'))
                Url::redirect_current(array('cmd'=>Url::get('cmd'),'id'=>Url::get('id')));
            else
                Url::redirect_current(array('cmd'=>Url::get('cmd')));
        }else if(Url::get('save'))
        {
            /** Kiem tra san pham co ton tai trong khai bao khong **/
            $department_product = DB::fetch_all('
                select 
                    pc_department_product.product_id as id,
                    product.name_'.Portal::language().' as name,
                    pc_department_product.id as department_product_id,
                    unit.name_'.Portal::language().' as unit_name
                from
                    pc_department_product
                    INNER JOIN product ON product.id=pc_department_product.product_id
                    INNER JOIN unit ON unit.id = product.unit_id
                where
                    pc_department_product.portal_department_id='.Url::get('department_id')
            );
            $check = false;
            foreach($_REQUEST['products'] as $key=>$value)
            {
                if(!isset($department_product[$value['product_id']]))
                {
                    $check = true;
                    $this->error('_'.$key,Portal::language('product').' "'.$value['product_name'].'" '.Portal::language('not_exits'),false);
                }
            }
            if($check)
    		{
    			return false;
    		}
            /** end Kiem tra san pham co ton tai trong khai bao khong **/
            //1. thuc hien update pc_recommendation
            $row_recommend = array();
            $row_recommend['portal_department_id'] = Url::get('department_id');
            $row_recommend['user_id'] = User::id();
            $row_recommend['description'] = Url::get('description');
            $d = Date_Time::to_time(Url::get('recommend_date'));
            $date = Date_Time::convert_time_to_ora_date($d);
            if(Url::get('recommend_time'))
            {
                $time = explode(":",Url::get('recommend_time'));
                $d +=60*60*$time[0] + 60*$time[1];
            }
            $row_recommend['recommend_date'] = $date;
            
            $row_recommend['recommend_time'] = $d;
            
            $row_recommend['recommend_person'] = Url::get('person_recommend');
            if(isset($_REQUEST['move']))
            {
                $row_recommend['status'] ='MOVE';
            }
            $row_recommend['delivery_date'] = Url::get('delivery_date');
            if(Url::get('cmd')=='edit')
            {
                if(isset($_REQUEST['confirm']))
                {
                    $row_recommend['confirm'] = 'HeadOfDepartment';
                }
                if(isset($_REQUEST['person_edit']))
                {
                    $row_recommend['last_edit_user'] = $_REQUEST['person_edit'];
                    $row_recommend['last_edit_time'] = time();                    
                }
                DB::update('pc_recommendation',$row_recommend,'id='.Url::get('id'));
                //2. thuc hien update pc_recommend_detail
               
                foreach($_REQUEST['products'] as $key=>$value)
                {
                    $row_detail = array();
                    $row_detail['recommend_id'] = Url::get('id');
                    $row_detail['product_id'] = $value['product_id'];
                    $row_detail['quantity'] = System::calculate_number($value['quantity']);

                    $row_detail['note'] = $value['note'];
                    if($value['delivery_date']=='')
                    {
                        $row_detail['delivery_date'] = time();
                    }
                    else
                    {
                        $row_detail['delivery_date'] = Date_Time::to_time($value['delivery_date']);   
                    }
                    
                    if(isset($value['id']) && $value['id']!="")
                    {
                        //update
                        DB::update('pc_recommend_detail',$row_detail,'id='.$value['id']); 
                    }
                    else
                    {
                        //insert 
                        DB::insert('pc_recommend_detail',$row_detail);
                    }
                }
            }
            else
            {
                $recommend_id = DB::insert('pc_recommendation',$row_recommend);
                foreach($_REQUEST['products'] as $key=>$value)
                {
                    $row_detail = array();
                    $row_detail['recommend_id'] = $recommend_id;
                    $row_detail['product_id'] = $value['product_id'];
                    $row_detail['quantity'] = System::calculate_number($value['quantity']);

                    $row_detail['note'] = $value['note'];
                    if($value['delivery_date']=='')
                    {
                        $row_detail['delivery_date'] = time();
                    }
                    else
                    {
                        $row_detail['delivery_date'] = Date_Time::to_time($value['delivery_date']);   
                    }
                    
                    DB::insert('pc_recommend_detail',$row_detail);
                } 
            }
            if(isset($_REQUEST['move']))
            {
                 Url::redirect_current(array('cmd'=>'list_move'));
            }
            else
                Url::redirect_current();
        }
        
    }
    function draw()
    {
        $this->map = array();
        $this->map['upload_file'] = '';
        if(Url::get('cmd')=='edit')
            $this->map['title'] = "Sửa phiếu đề xuất";
        else
            $this->map['title'] = "Thêm phiếu đề xuất";    
        $sql="Select 
                portal_department.id, 
                portal_department.department_code as code, 
                '--' || department.name_1 as name,
                department.parent_id,
                department.id as department_id,
                portal_department.warehouse_pc_id,
                warehouse.name as warehouse_name-- trung:lay ten kho
            from 
                portal_department
                inner join department on department.code  = portal_department.department_code and portal_department.portal_id = '".PORTAL_ID."'
                left join warehouse on portal_department.warehouse_pc_id = warehouse.id
            where 
                department.parent_id=0
            order by 
                portal_department.id ";
        //System::debug($sql);
        $departments = DB::fetch_all($sql);
        //System::debug($departments);
               
        $default = array('id'=>0,'code'=>'CHOOSE_DEPARTMENT','name'=>Portal::language('select_department'));
        $result = array();
        /** Daund viet lai phan chon bo phan de toi uu toc do load*/
        array_push($result,$default);
        $department_list = '';
        $child_department = DB::fetch_all('
                                SELECT 
                                    portal_department.id,
                                    portal_department.department_code as code,
                                    \'----\' || department.name_1 as name,
                                    department.parent_id,
                                    department.id as department_id,
                                    portal_department.warehouse_pc_id,
                                    warehouse.name as warehouse_name --trung:lay ten kho
                                FROM 
                                    portal_department
                                    left join department on department.code  = portal_department.department_code
                                    left join warehouse on portal_department.warehouse_pc_id = warehouse.id
                                WHERE 
                                    portal_department.portal_id=\''.PORTAL_ID.'\'                        
        ');
        foreach($departments as $key => $value)
        {
            array_push($result,$departments[$key]);
            foreach($child_department as $k=>$v)
            {
                if($v['parent_id'] == $value['department_id'])
                {
                    array_push($result,$child_department[$k]);
                }
            }         
        }
        foreach($result as $id => $value)
        {
            $department_list .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        /** Daund viet lai phan chon bo phan de toi uu toc do load*/
        /*array_push($result,$default);
        $parent_id = false;
        foreach($departments as $key=>$row)
        {
             array_push($result,$departments[$key]);
             //2. tim nhung dong child $row['department_id']
             $sql = "select portal_department.id,
                        portal_department.department_code as code,
                        '----' || department.name_1 as name,
                        department.parent_id,
                        department.id as department_id,
                        portal_department.warehouse_pc_id,
                        warehouse.name as warehouse_name --trung:lay ten kho
                        from portal_department
                        inner join department on department.code  = portal_department.department_code
                        left join warehouse on portal_department.warehouse_pc_id = warehouse.id
                    where department.parent_id=".$row['department_id']." AND portal_department.portal_id='".PORTAL_ID."'";
             $items = DB::fetch_all($sql);
            
             foreach($items as $k=>$v)
             {
                array_push($result,$items[$k]);
             }
        }
               
        $department_list = '';
		foreach($result as $id => $value)
        {
            $department_list .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }*/
        $this->map['get_warehouse_list']= String::array2js($result);// trung lay ra mang 
        //System::debug($this->map['get_warehouse_list']);
        $this->map['department_list'] = $department_list;
        //3. hien thi cac thong tin chung 
        if(Url::get('cmd')=='edit' || Url::get('cmd')=='copy')
        {
            $recommend = DB::fetch('SELECT * FROM pc_recommendation WHERE id='.Url::get('id'));
            require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
            
            //System::debug($recommend);
            
            if(empty($recommend)==false)
            {
                if(Url::get('cmd')=='copy')
                {
                    $this->map['recommend_date'] = date('d/m/Y',time());
                    $this->map['recommend_time'] = date('H:i',time());
                    $this->map['confirm'] = '';
                    $sql ="SELECT account.id,
                        account.portal_department_id,
                        party.name_1 as full_name
                    FROM account
                    INNER JOIN party ON account.id=party.user_id 
                    WHERE account.id='".User::id()."'";
                    $account = DB::fetch($sql);
            
                    $this->map['person_recommend'] =$account['full_name'];
                }
                else
                {
                    $this->map['recommend_date'] = date('d/m/Y',$recommend['recommend_time']);
                    $this->map['recommend_time'] = date('H:i',$recommend['recommend_time']);
                    $this->map['confirm'] = $recommend['confirm'];
                    $this->map['person_recommend'] = $recommend['recommend_person'];
                    $_REQUEST['delivery_date'] = $recommend['delivery_date'];
                }
                
                
                $this->map['description'] = $recommend['description'];
                $this->map['department_id'] = $recommend['portal_department_id'];

                //3. lay ra danh sach cac san pham 
                $sql = "SELECT pc_recommend_detail.id,
                                pc_recommend_detail.quantity,
                                pc_recommend_detail.recommend_id,
                                pc_recommend_detail.product_id,
                                product.name_1 as product_name,
                                unit.name_1 as unit,
                                pc_recommend_detail.note,
                                pc_recommend_detail.delivery_date
                            FROM pc_recommend_detail
                            INNER JOIN product ON product.id=pc_recommend_detail.product_id
                            INNER JOIN unit ON unit.id=product.unit_id
                            WHERE pc_recommend_detail.recommend_id=".$recommend['id']."
                            ORDER BY pc_recommend_detail.id";
                $items = DB::fetch_all($sql);
                $i = 1;
                //lay ra warehouse_id(kho) cho bo phan(portal_department_id)
                $sql="SELECT * FROM portal_department WHERE id=".$recommend['portal_department_id'];
                $portal_department = DB::fetch($sql);
                $warehouse_id = $portal_department['warehouse_pc_id'];
                /** Daund viet lai phan lay ra so luong ton kho va ton kho khac */
                $product_remain_warehouse = get_remain_products($warehouse_id,false,false,false,$items);
                
                $product_remain_total = get_remain_products('',false,false,false,false,$items);
                //System::debug($items);
                //System::debug($product_remain_total);
                foreach($items as $key=>$value)
                {
                    //lay ra so luong ton cho san pham do o kho hien tai
                    //$product_remain_warehouse = get_remain_products($warehouse_id,false,$value['product_id']);
                    //lay ra ton kho tong cong cua san pham do 
                    //$product_remain_total = get_remain_products('',false,$value['product_id']);
                    /*if(empty($product_remain_warehouse))
                    {
                         $items[$key]['remain_product'] = 0;
                    }
                    else
                    {
                        //neu kho hien tai con ton thi lay ra so luong ton
                        foreach($product_remain_warehouse as $k=>$v)
                        {
                            $items[$key]['remain_product'] = $v['remain_number'];
                            break;
                        }
                    }

                    if(empty($product_remain_total))
                    {
                         $items[$key]['remain_total'] = 0;
                    }
                    else
                    {
                        //neu co ton kho tong thi lay ra tong so luong ton
                        foreach($product_remain_total as $k=>$v)
                        {
                            $items[$key]['remain_total'] = $v['remain_number'] - $items[$key]['remain_product'];
                            break;
                        }
                    }
                    /** lay ra so luong ton cho san pham do o kho hien tai */
                    if(isset($product_remain_warehouse[$value['product_id']]))
                    {
                        $items[$key]['remain_product'] = $product_remain_warehouse[$value['product_id']]['remain_number'];                  
                    }else
                    {
                        $items[$key]['remain_product'] = 0;                        
                    }
                    /** lay ra ton kho tong cong cua san pham do*/ 
                    if(isset($product_remain_total[$value['product_id']]))
                    {
                        $items[$key]['remain_total'] = $product_remain_total[$value['product_id']]['remain_number'] - $items[$key]['remain_product'];                      
                    }else
                    {
                        $items[$key]['remain_total'] = 0;
                    }
                    /** Daund viet lai phan lay ra so luong ton kho va ton kho khac */
                    
                    $items[$key]['index'] = $i++;
                    $items[$key]['delivery_date'] = date('d/m/Y',$value['delivery_date']);
                    if($items[$key]['quantity']>0 AND $items[$key]['quantity']<1)
                    {
                        $items[$key]['quantity'] = '0'.$value['quantity'];
                    }
                }
                //$product_remain_warehouse_1 = get_remain_products($warehouse_id,false,false,false,$items);
                //System::debug($product_remain_warehouse_1);
                if($items)
                {
                    $_REQUEST['products'] = $items;
                }
            }
            $this->map['upload_file'] = 1;
        }else
        {
            $this->map['department_id'] =0;
            $this->map['recommend_date'] = date('d/m/Y');
            $this->map['recommend_time'] = date('H:i');
            $this->map['description'] ='';
            $sql ="SELECT account.id,
                        account.portal_department_id,
                        party.name_1 as full_name
                    FROM account
                    INNER JOIN party ON account.id=party.user_id 
                    WHERE account.id='".User::id()."'";
            $account = DB::fetch($sql);
            
            $this->map['person_recommend'] =$account['full_name'];
            if(isset($account['portal_department_id']) && $account['portal_department_id']!='' && $account['portal_department_id']!=1001 && $account['portal_department_id']!=1002)
                $this->map['department_id'] = $account['portal_department_id'];
            else
                $this->map['department_id'] = 0;
            
        }
        //4. lay ra ten tat ca san pham cua tat ca cac bo phan 
        $sql  = "SELECT 
                    pc_department_product.id,
                    pc_department_product.product_id,
                    product.name_1 as product_name,
                    unit.name_1 as unit
                FROM pc_department_product
                INNER JOIN product ON pc_department_product.product_id=product.id 
                INNER JOIN unit ON unit.id=product.unit_id
                ORDER BY pc_department_product.id
                ";
                
              
        $elements  = DB::fetch_all($sql);
        $this->map['elements'] = $elements;
        //System::debug($items);
        $user_data = Session::get('user_data');
        $this->map['person_edit'] = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id');
        /** Daund: Thêm nhập dữ liệu từ file excel */
        if(Url::get('check_file') && $_FILES['data']['tmp_name'])
        {
            require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
            $_REQUEST['department_id'] = $department_id = Url::get('department_id');
            $sql="SELECT * FROM portal_department WHERE id=".$department_id;
            $portal_department = DB::fetch($sql);
            $warehouse_id = $portal_department['warehouse_pc_id'];
            //System::debug($_FILES);
            $file = $_FILES['data']['tmp_name'];
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
			$objReader = new PHPExcel_Reader_Excel2007();
			$objPHPExcel = $objReader->load($file);			
			$data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            if(!empty($data))
            {
                unset($data[1]);
                $this->result = $this->parse_sheet($data, $department_id);
                if($this->result['error'] != '')
                {
                    $this->error('', $this->result['error']);
                }
                $items = $this->result['content'];
                foreach($items as $key=>$value)
                {
                    //lay ra so luong ton cho san pham do o kho hien tai
                    $product_remain_warehouse = get_remain_products($warehouse_id,false,$value['product_id']);
                    //lay ra ton kho tong cong cua san pham do 
                    $product_remain_total = get_remain_products('',false,$value['product_id']);
                    if(empty($product_remain_warehouse))
                    {
                         $items[$key]['remain_product'] = 0;
                    }
                    else
                    {
                        //neu kho hien tai con ton thi lay ra so luong ton
                        foreach($product_remain_warehouse as $k=>$v)
                        {
                            $items[$key]['remain_product'] = $v['remain_number'];
                            break;
                        }
                    }

                    if(empty($product_remain_total))
                    {
                         $items[$key]['remain_total'] = 0;
                    }
                    else
                    {
                        //neu co ton kho tong thi lay ra tong so luong ton
                        foreach($product_remain_total as $k=>$v)
                        {
                            $items[$key]['remain_total'] = $v['remain_number'] - $items[$key]['remain_product'];
                            break;
                        }
                    }
                    if($items[$key]['quantity']>0 AND $items[$key]['quantity']<1)
                    {
                        $items[$key]['quantity'] = '0'.$value['quantity'];
                    }
                } 
                $_REQUEST['products'] = $items; 
                @unlink($file);
            } 
            $this->map['upload_file'] = 1;        
        }
        $this->parse_layout('edit',$this->map);
    }
    
    function parse_sheet($data, $department_id)
	{
        require_once 'packages/core/includes/utils/vn_code.php';
        
        $content = array();
        $preview = array();
        $error = '';
        $i = 1;
        //System::debug($data);
        $check_passport = array();
        foreach($data as $key => $value)
        {
            if($value['A'] == '')
            {
                unset($data[$key]);
            }else
            {
                /** Convert data sang array*/
                if($value['A'] != '')
                {
                    $product = DB::fetch('SELECT product.*,unit.name_'.portal::language().' as unit_name FROM product INNER JOIN unit on product.unit_id=unit.id WHERE product.id =\''.$value['A'].'\' ');
                    //System::debug($product);
                }
                $row_content = array(
                                    //Dữ liệu insert bảng hrm_staff
                                    'product_id'=>mb_strtoupper($value['A'], 'utf-8'),
                                    'product_name'=>($product['name_'.portal::language()])?$product['name_'.portal::language()]:'',
                                    'unit' => ($product['unit_name'])?$product['unit_name']:'',
                                    'quantity' => $value['C'],
                                    'note' => '',
                                    'delivery_date' => '',
                                    'index' => $i,
                );
                /** Convert data sang array*/
                /** Check lỗi */
                $check = true;
                if($value['A'] == '')
                {
                    $error .= '<span style="color: red;display:block">- Chưa nhập mã sản phẩm.</span>'; 
                    $check = false;              
                }
                if(!DB::exists('SELECT * FROM pc_department_product INNER JOIN product ON product.id=pc_department_product.product_id WHERE pc_department_product.portal_department_id =\''.$department_id.'\' AND product.id = \''.$value['A'].'\''))
                {
                    $error .= '<span style="color: red;display:block">- Sản phẩm '.$value['B'].' chưa được khai báo cho bộ phận này.</span>'; 
                    $check = false;      
                }
                /** Check lỗi */
                if($check)
                {
                    $content[$i] = $row_content;
                    $i++;                    
                }
            }
        }
        
        $result = array('content'=>$content, 'error'=>$error);
        
        return $result;
    }
}
?>
