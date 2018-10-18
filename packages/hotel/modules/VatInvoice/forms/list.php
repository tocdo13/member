<?php
class VatInvoiceListForm extends Form
{
	function VatInvoiceListForm()
	{
		Form::Form('VatInvoiceListForm');	
	}
    function on_submit()
    {
        $selected_ids = Url::get('selected_ids');
        if(!empty($selected_ids))
        {
			foreach($selected_ids as $id)
			{
                DB::delete_id( 'vat_invoice', $id );
			}  
        } 
    }
	function draw()
	{
        $item_per_page = 30;
		$sql = 'SELECT count(*) AS acount FROM vat_invoice where portal_id = \''.PORTAL_ID.'\' ';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array());
        $sql = '
			SELECT * FROM
			(
				SELECT
					vat_invoice.*,
					ROW_NUMBER() OVER (ORDER BY vat_invoice.id) as rownumber
				FROM
					vat_invoice
                WHERE
                    portal_id = \''.PORTAL_ID.'\'
                    and department = \''.Url::get('department').'\'
				ORDER BY
					vat_invoice.id
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'	
		';
		$items = DB::fetch_all($sql);
        $i = 1;
        foreach($items as $key=>$value)
        {
            $items[$key]['stt'] = $i++;
            $items[$key]['arrival_time'] = date('d/m/Y',strtotime($value['arrival_time']));
            $items[$key]['departure_time'] = date('d/m/Y',strtotime($value['departure_time']));
            $items[$key]['print_time'] = date('d/m/Y H:i',$value['print_time']);
            $items[$key]['last_print_time'] = $value['last_print_time']?date('d/m/Y H:i',$value['last_print_time']):'';
        }
        $this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}
}
?>