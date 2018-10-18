<?php
class ListHKWarehouseInvoiceForm extends Form
{
	function ListHKWarehouseInvoiceForm()
	{
		Form::Form('ListHKWarehouseInvoiceForm');
		$this->link_css(Portal::template('warehousing').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 50;
		$cond = '1=1
			'.(Url::get('type')?' AND hk_wh_invoice.type = \''.Url::sget('type').'\'':'').'
			'.(Url::get('note')?' AND UPPER(hk_wh_invoice.note) LIKE \'%'.strtoupper(Url::sget('note')).'%\'':'').'
			'.(Url::get('receiver_name')?' AND hk_wh_invoice.receiver_name LIKE \'%'.Url::sget('receiver_name').'%\'':'').'
			'.(Url::get('create_date')?' AND hk_wh_invoice.create_date = \''.Date_Time::to_orc_date(Url::sget('create_date')).'\'':'').'
			'.(Url::get('warehouse_id')?' AND hk_wh_invoice.warehouse_id = '.Url::iget('warehouse_id').'':'').'
			'.(Url::get('supplier_id')?' AND hk_wh_invoice.supplier_id = '.Url::iget('supplier_id').'':'').'
			';
		$this->map['title'] = (Url::get('type')=='IMPORT')?Portal::language('import_bill_list'):Portal::language('export_bill_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				hk_wh_invoice
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10);
		$sql = '
			SELECT * FROM
			(
				SELECT
					hk_wh_invoice.*,
					ROWNUM as rownumber
				FROM
					hk_wh_invoice
				WHERE
					'.$cond.'
				ORDER BY
					'.(Url::get('order_by')?Url::get('order_by'):'hk_wh_invoice.id').' '.(Url::get('dir')?Url::get('dir'):'ASC').' 
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'	
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		$suppliers = DB::select_all('customer','group_id=\'SUPPLIER\'');
		$customers = DB::select_all('warehouse',IDStructure::child_cond(ID_ROOT,1));
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['create_date'] = Date_Time::convert_orc_date_to_date($value['create_date'],'/');
			if(isset($suppliers[$value['supplier_id']])){
				$items[$key]['supplier_name'] = $suppliers[$value['supplier_id']]['name'];
			}else{
				$items[$key]['supplier_name'] = '';
			}
			if(isset($customers[$value['warehouse_id']])){
				$items[$key]['warehouse_name'] = $customers[$value['warehouse_id']]['name'];
			}else{
				$items[$key]['warehouse_name'] = '';
			}
		}
		$this->map['items'] = $items;
		$this->map['supplier_id_list'] = array(''=>Portal::language('no_supplier'))+String::get_list($suppliers);
		$this->map['warehouse_id_list'] = array(''=>Portal::language('select_warehouse'))+String::get_list($customers);
		$this->parse_layout('list',$this->map);
	}	
}
?>