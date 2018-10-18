<?php
class ListWarehouseInvoiceForm extends Form
{
	function ListWarehouseInvoiceForm()
	{
		Form::Form('ListWarehouseInvoiceForm');
		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/invoice.css');
		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        require_once 'packages/core/includes/utils/vn_code.php';
		$this->map = array();
        
        $warehouses = get_warehouse(true);
        $this->map['warehouse_id_list'] = array(''=>Portal::language('All'))+String::get_list($warehouses);
        
        $suppliers = DB::select_all('supplier');
		$this->map['supplier_id_list'] = array(''=>Portal::language('All'))+String::get_list($suppliers);
		$item_per_page = 30;
        $_REQUEST['create_date_from'] = Url::get('create_date_from')?Url::get('create_date_from'):date('d/m/Y');
        $_REQUEST['create_date_to'] = Url::get('create_date_to')?Url::get('create_date_to'):date('d/m/Y');
		$cond = '1=1 and wh_invoice.portal_id = \''.PORTAL_ID.'\' 
			'.(Url::get('type')?' AND wh_invoice.type = \''.Url::sget('type').'\'':'').'
			'.(Url::get('bill_number')?' AND UPPER(wh_invoice.bill_number) LIKE \'%'.strtoupper(Url::sget('bill_number')).'%\'':'').'
			'.(Url::get('note')?' AND (LOWER(FN_CONVERT_TO_VN(wh_invoice.note))) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('note'),'utf-8')).'%\'':'').'
			'.(Url::get('receiver_name')?' AND wh_invoice.receiver_name LIKE \'%'.Url::sget('receiver_name').'%\'':'').'
			'.(Url::get('create_date_from')?' AND wh_invoice.create_date >= \''.Date_Time::to_orc_date(Url::sget('create_date_from')).'\'':'').'
			'.(Url::get('create_date_to')?' AND wh_invoice.create_date <= \''.Date_Time::to_orc_date(Url::sget('create_date_to')).'\'':'').'
			'.(Url::get('warehouse_id')?' AND wh_invoice.warehouse_id = '.Url::iget('warehouse_id').'':'').'
			'.(Url::get('supplier_id')?' AND wh_invoice.supplier_id = '.Url::iget('supplier_id').'':'').'
            '.(Url::get('invoice_number')?' AND UPPER(wh_invoice.invoice_number) LIKE \'%'.strtoupper(Url::sget('invoice_number')).'%\'':'').'
			';
        
        //Show ra cac san pham trong kho ma ho duoc phan quyen
        $cond .=' and wh_invoice.warehouse_id in (';    
        foreach($warehouses as $k=>$v)
        {
            $cond.=$k.',';
        }
        $cond = trim($cond,',');
        $cond.= ')';  
         // loc ra cac phieu xuat tam
        if (Url::get('type') == 'EXPORT')
        {
            $temp_sql = 'select 
                            wh_invoice_detail.invoice_id as id 
                         from wh_invoice_detail
                         inner join wh_invoice on wh_invoice_detail.invoice_id = wh_invoice.id
                         where
                         '.$cond.'
                         and wh_invoice_detail.tmp = 1 
                        ';
            $temp = DB::fetch_all($temp_sql);
            $tmp_id = ' AND ';
            if (!empty($temp))
            {
                if (Url::get('borrow') == 1)
                {
                	$tmp_id .= 'wh_invoice.id in (select invoice_id as id from wh_invoice_detail where tmp = 1)';
                }
                else 
                {
                	$tmp_id .= 'wh_invoice.id not in (select invoice_id as id from wh_invoice_detail where tmp = 1)';
                }
            }
            else
            {
                //start:KID cmt (1) => if else de hien thi dung danh sach xuat và xuat tam
                //$tmp_id .= '1 = 1)';(1)
                if (Url::get('borrow') == 1)
                {
                    $tmp_id .= 'wh_invoice.id is null  ';   
                }
                else
                {
                    $tmp_id .= '1 = 1';
                }
                //end:KID cmt (1) => if else de hien thi dung danh sach xuat và xuat tam
            }
            $cond .= $tmp_id;
            if (User::is_admin())
            {
            	//System::debug($cond);
            }
        }
        if(Url::get('type')=='IMPORT')
        {
            $this->map['title']=Portal::language('import_bill_list');
        }
        else
        {
            $this->map['title']=Portal::language('export_bill_list');
            if(Url::get('borrow')==1)
            {
                $this->map['title']=Portal::language('export_bill_list_1');
            }
        }
        //$this->map['title'] = (Url::get('type')=='IMPORT')?Portal::language('import_bill_list'):Portal::language('export_bill_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				wh_invoice
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array('type','borrow','create_date_from','create_date_to','warehouse_id','supplier_id'));

        $sql = '
			SELECT * FROM
			(
				SELECT wh_invoice.*,
					
					ROW_NUMBER() OVER (ORDER BY wh_invoice.id Desc) as rownumber
				FROM
					wh_invoice
				WHERE
					'.$cond.'
				ORDER BY
					wh_invoice.id DESC
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'	
		';
        
        //System::debug($sql);
		$items = DB::fetch_all($sql);
        //echo 2;exit();
        $to_wh_arr = array();
        if(Url::get('type')=='EXPORT')
        {
            $to_wh_arr=DB::fetch_all('Select
                    wh_invoice_detail.invoice_id as id,
                    warehouse.name as warehouse_name,
                    warehouse.id as warehouse_id
                from
                    wh_invoice_detail
                    inner join wh_invoice on wh_invoice_detail.invoice_id = wh_invoice.id
					INNER JOIN warehouse ON warehouse.id = wh_invoice_detail.to_warehouse_id and wh_invoice_detail.to_warehouse_id is not null
                where
                    '.$cond.'
            ');   
        }
        //echo 1;exit();
		$i = 1;
		foreach($items as $key=>$value)
        {
            
			$items[$key]['i'] = $i++;
			$items[$key]['create_date'] = Date_Time::convert_orc_date_to_date($value['create_date'],'/');
			if(isset($suppliers[$value['supplier_id']]))
            {
				$items[$key]['supplier_name'] = $suppliers[$value['supplier_id']]['name'];
			}
            else
            {
				$items[$key]['supplier_name'] = '';
			}
			if(isset($warehouses[$value['warehouse_id']])){
				$items[$key]['warehouse_name'] = $warehouses[$value['warehouse_id']]['name'];
			}else{
				$items[$key]['warehouse_name'] = '';
			}
            if(isset($to_wh_arr[$key]))
            {
				$items[$key]['to_warehouse_name'] = $to_wh_arr[$key]['warehouse_name'];
                $items[$key]['to_warehouse_id'] = $to_wh_arr[$key]['warehouse_id'];
			}
            else
            {
				$items[$key]['to_warehouse_name'] = '';
                $items[$key]['to_warehouse_id'] = '';
			}
		}
		$this->map['items'] = $items;
        //System::debug($items);
		$this->parse_layout('list',$this->map);
	}	
}
?>
