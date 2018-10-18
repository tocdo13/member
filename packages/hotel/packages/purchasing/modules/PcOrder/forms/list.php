<?php
class ListPcOrderForm extends Form
{
    function ListPcOrderForm()
    {
        Form::Form('ListPcOrderForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        
    }
    function draw()
    {
       // System::debug($_REQUEST);
       $this->map = array();
       if(Url::get('status')!='')
       {
           $cond = ' pc_order.status = '.Url::get('status');
           if(Url::get('status')==4)
           {
                $cond.=' and pc_order.id in (select pc_order_detail.pc_order_id from pc_order_detail where nvl(pc_order_detail.quantity_import,0) < pc_order_detail.quantity)';
           }
           $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):'';
           $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):'';
           $_REQUEST['from_date'] = $this->map['from_date']; $_REQUEST['to_date'] = $this->map['to_date'];
           if($this->map['from_date']!='' AND $this->map['to_date']!='')
           {
                $cond .= ' AND pc_order.create_time >= '.Date_Time::to_time($this->map['from_date']).' AND pc_order.create_time <= '.(Date_Time::to_time($this->map['to_date'])+24*60*60);
           }
           
           $this->map['order_code'] = Url::get('order_code')?Url::get('order_code'):'';
           if(Url::get('order_code')!='')
           {
                $cond .= ' AND pc_order.code like \'%'.Url::get('order_code').'%\'';
                $_REQUEST['order_code'] = $this->map['order_code'];
           }
           
           $this->map['supplier_id'] = Url::get('supplier_id')?Url::get('supplier_id'):'';
           if(Url::get('supplier_name')!='' && Url::get('supplier_id')!='')
           {
                $cond .= ' AND pc_order.pc_supplier_id = '.Url::get('supplier_id');
                $_REQUEST['supplier_id'] = $this->map['supplier_id'];
                
           }
           $supplier = DB::fetch_all("SELECT * FROM supplier");
           $this->map['suppliers_arr'] = DB::fetch_all('
			SELECT
				supplier.code as id,
                supplier.id as supplier_id,
                supplier.name,
                supplier.address,
                supplier.tax_code
			FROM
				supplier
			ORDER BY
				supplier.code
		');// trung add lay mang nay ra ben layout 
           $this->map['supplier_id_list'] = array(""=>"-----".Portal::language('select_supplier')."-----");
           foreach($supplier as $id_sup=>$value_sup)
           {
                $this->map['supplier_id_list'] += array($value_sup['id']=>$value_sup['name']);
           }
           //System::debug($this->map['supplier_id']);
           /** sql **/
           /** Daund them phan trang de giam toc do load du lieu */
           $item_per_page = 100;
           DB::query("
            		SELECT 
            			count(pc_order.id) as acount
                    FROM 
                        pc_order
                        inner join supplier on supplier.id=pc_order.pc_supplier_id
                        inner join party on party.user_id=pc_order.creater
                        left join payment_type on payment_type.def_code=pc_order.payment_type_id
                    WHERE
                        ".$cond."
           ");
           $count = DB::fetch();
           require_once 'packages/core/includes/utils/paging.php';
           $paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('cmd','invoice_code','invoice_name','create_user','status','from_date','to_date','supplier_name'));
           $sql = "
                                SELECT
                                    ID,
                                    CREATE_TIME,
                                    CODE,
                                    NAME,
                                    DESCRIPTION,
                                    STATUS,
                                    PC_SUPPLIER_ID,
                                    LAST_EDIT_TIME,
                                    LAST_EDIT_USER,
                                    TOTAL,
                                    NUMBER_CONTRACT,
                                    FILE_CONTRACT,
                                    PAYMENT_TYPE_ID,
                                    IMPORT_STATUS,
                                    CANCEL_USER,
                                    TIME_CANCEL,
                                    NOTE_CANCEL,
                                    PERSON_CONFIRM,
                                    PERSON_CONFIRM_1,
                                    RECEIVER,
                                    PLACE_OF_RECEIPT,
                                    TEL_OF_RECEIPT,
                                    DESCRIPTION_PRODUCT,
                                    pc_supplier_name,
                                    creater,
                                    payment_type_name
                                FROM
                                (
                                    SELECT
                                        PC_ORDER.ID
                                        ,PC_ORDER.CREATE_TIME
                                        ,PC_ORDER.CODE
                                        ,PC_ORDER.NAME
                                        ,PC_ORDER.DESCRIPTION
                                        ,PC_ORDER.STATUS
                                        ,PC_ORDER.PC_SUPPLIER_ID
                                        ,PC_ORDER.LAST_EDIT_TIME
                                        ,PC_ORDER.LAST_EDIT_USER
                                        ,PC_ORDER.TOTAL
                                        ,PC_ORDER.NUMBER_CONTRACT
                                        ,PC_ORDER.FILE_CONTRACT
                                        ,PC_ORDER.PAYMENT_TYPE_ID
                                        ,PC_ORDER.IMPORT_STATUS
                                        ,PC_ORDER.CANCEL_USER
                                        ,PC_ORDER.TIME_CANCEL
                                        ,PC_ORDER.NOTE_CANCEL
                                        ,PC_ORDER.PERSON_CONFIRM
                                        ,PC_ORDER.PERSON_CONFIRM_1
                                        ,PC_ORDER.RECEIVER
                                        ,PC_ORDER.PLACE_OF_RECEIPT
                                        ,PC_ORDER.TEL_OF_RECEIPT
                                        ,PC_ORDER.DESCRIPTION_PRODUCT
                                        ,supplier.name as pc_supplier_name
                                        ,party.full_name as creater
                                        ,payment_type.name_1 as payment_type_name
                                        ,row_number() over (".(URL::get('order_by')?"order by ".URL::get('order_by').(URL::get('order_dir')?" ".URL::get('order_dir'):""):"order by PC_ORDER.create_time DESC").") as rownumber
                                    FROM
                                        pc_order
                                        inner join supplier on supplier.id=pc_order.pc_supplier_id
                                        inner join party on party.user_id=pc_order.creater
                                        left join payment_type on payment_type.def_code=pc_order.payment_type_id
                                    WHERE
                                        ".$cond."
                                    ORDER BY
                                        pc_order.create_time DESC 
                               )
                            WHERE
                                rownumber > ".(page_no()-1)*$item_per_page." and rownumber<=".(page_no()*$item_per_page)."
            ";
           $items = DB::fetch_all($sql);
           //System::debug($items);
            foreach($items as $key=>$value)
            {
                $items[$key]['create_time'] = Date('H:i d/m/Y',$value['create_time']);
                if($value['status'] == 0 )
                    $items[$key]['status'] = portal::language('order_not_confirm'); // hoa don khong duoc duyet
                elseif($value['status'] == 1)
                    $items[$key]['status'] = portal::language('order_confirming'); // hoa don cho duyet
                elseif($value['status'] == 2)
                    $items[$key]['status'] = portal::language('order_chief_accountant_confirm'); // hoa don cho duyet
                elseif($value['status'] == 3)
                    $items[$key]['status'] = portal::language('order_manager_confirm'); // hoa don cho duyet
                elseif($value['status'] == 4)
                    $items[$key]['status'] = portal::language('order_finish'); // hoa don cho duyet
                
                if(!$this->check_product($value['id']))
                {
                    //unset($items[$key]);
                }  
            }
            
            if(sizeof($items)>0)
            {
                $this->map['items'] = $items;  
            }
            else
                $this->map['no_data'] = portal::language('no_record');
       }
       else
       {
            $this->map['no_data'] = portal::language('no_record');
       }
       
       $this->map['paging'] = $paging;
       $layout = 'list';
       $this->parse_layout($layout,$this->map);
    }
    
    function check_product($order_id)
    {
        $check=false;
        /**giap.ln comment 
        $detail = DB::fetch_all("SELECT id,status FROM pc_order_detail WHERE pc_order_id=".$order_id);
        foreach($detail as $key=>$value)
        {
            if($value['status']=='' OR $value['status']==0)
            {
                $check=true;
            }
        }**/
        $order = DB::fetch("SELECT * FROM pc_order WHERE id=".$order_id);
        if($order['import_status']!='COMPLETE' && $order['import_status']!='CLOSE' && $order['import_status']!='APART_CLOSE')
        {
            $check = true;
        }
        return $check;
    }   
}
?>
