<?php
class ChooseWarehouseForm extends Form
{
	function ChooseWarehouseForm()
	{
		Form::Form('ChooseWarehouseForm');
	}
    function on_submit()
    {
        Url::redirect_current(array('cmd','type','warehouse_id','edit_average_price','move_product'?'move_product':'','purchases_invoice_id'));
    }
	function draw()
	{
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        //$warehouses = DB::select_all('warehouse','portal_id = \''.PORTAL_ID.'\' and structure_id !=\''.ID_ROOT.'\' order by structure_id ');
        $warehouses = get_warehouse(true);
		$this->map['warehouse_id_list'] = String::get_list($warehouses);
		$this->parse_layout('choose_warehouse',$this->map);
	}	
}
?>