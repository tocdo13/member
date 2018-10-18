<?php
class PurchaseHistoryForm extends Form
{
	function PurchaseHistoryForm()
	{
		Form::Form('PurchaseHistoryForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
	}
	function draw()
	{
	    $this->map['from_date'] = isset($_REQUEST['from_date'])?$_REQUEST['from_date'] = $_REQUEST['from_date']:$_REQUEST['from_date'] = date('d/m/Y', time());
        $this->map['to_date'] = isset($_REQUEST['to_date'])?$_REQUEST['to_date'] = $_REQUEST['to_date']:$_REQUEST['to_date'] = date('d/m/Y', time());
        $from_date = Date_Time::to_time($_REQUEST['from_date']);
        $to_date = $_REQUEST['to_date']?Date_Time::to_time($_REQUEST['to_date']) + 86399:'';
        
        $cond = '1=1';
        $from_date?$cond .= ' AND pc_order.last_edit_time >=\''.$from_date.'\'':'';
        $to_date?$cond .= ' AND pc_order.last_edit_time <=\''.$to_date.'\'':'';
        
        //trung add: $this->map['suppliers']                                                                 //System::debug($this->map['supplier_id_list']);
        $this->map['suppliers'] = DB::fetch_all('
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
		');
        
        if(Url::get('product_id'))
        {
            $cond .= ' AND pc_order_detail.product_id =\''.Url::get('product_id').'\'';
        }
        if(Url::get('supplier_id'))
        {
            if(Url::get('supplier_name') != '' )
            {
                $cond .= ' AND pc_order.pc_supplier_id =\''.Url::get('supplier_id').'\'';
            }
        }
        //System::debug($cond);
        
        // Lay ra cac san pham da duoc xac nhan mua hang
        $item_per_page =100;
        DB::query("
    			SELECT 
    				count(pc_order_detail.id) as acount
                FROM 
                    pc_order_detail
                    INNER JOIN pc_order ON pc_order_detail.pc_order_id = pc_order.id
                    LEFT JOIN supplier ON supplier.id = pc_order.pc_supplier_id
                    INNER JOIN party ON party.user_id = pc_order.last_edit_user
                    INNER JOIN product ON pc_order_detail.product_id = product.id 
                WHERE
                    ".$cond."
                    and pc_order.status = 4 
		");
        $count = DB::fetch();
        require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('from_date','to_date','product_id','supplier_id'));
        
        $detail = DB::fetch_all("
                        SELECT 
                            *
                        FROM(
                            SELECT
                                pc_order_detail.id,
                                pc_order.last_edit_time as time,
                                pc_order.code,
                                pc_order.id as pc_order_id,
                                party.full_name,
                                supplier.code as supplier_code,
                                supplier.name as supplier_name,
                                pc_order_detail.product_id,
                                product.name_".Portal::language()." as product_name,
                                pc_order_detail.quantity,
                                pc_order_detail.price,
                                pc_order_detail.tax_percent,
                                row_number() over (".(URL::get('order_by')?"order by ".URL::get('order_by').(URL::get('order_dir')?" ".URL::get('order_dir'):""):"order by pc_order.last_edit_time DESC").") as rownumber
                            FROM
                                pc_order_detail
                                INNER JOIN pc_order ON pc_order_detail.pc_order_id = pc_order.id
                                LEFT JOIN supplier ON supplier.id = pc_order.pc_supplier_id
                                INNER JOIN party ON party.user_id = pc_order.last_edit_user
                                INNER JOIN product ON pc_order_detail.product_id = product.id 
                            WHERE
                                ".$cond."
                                and pc_order.status = 4 
                            ORDER BY
                                pc_order.last_edit_time DESC
                      )
                    WHERE
                        rownumber > ".(page_no()-1)*$item_per_page." and rownumber<=".(page_no()*$item_per_page)."              
        ");
        foreach($detail as $key => $value)
        {
            $detail[$key]['total_amount'] = $value['price'] *(1+($value['tax_percent']/100))*$value['quantity'];
        }
        //System::debug($detail);
        // Lay ra cac san pham
        $product = DB::fetch_all('SELECT id, name_'.Portal::language().' as name FROM product ORDER BY name_'.Portal::language().' ASC');
        $this->map['product_id_list'] =array('ALL'=>Portal::language('all')) + String::get_list($product);
        
        // Lay ra nha cc
        //$supplier = DB::fetch_all('SELECT id, name FROM supplier ORDER BY name ASC');
        //$this->map['supplier_id_list'] =array('ALL'=>Portal::language('all')) + String::get_list($supplier);
        
        $this->map['items'] = $detail;
        $this->map['paging'] = $paging;
        $this->parse_layout('report', $this->map);
	}	
}
?>