<?php
class DataImportForm extends Form
{
	function DataImportForm()
	{
		Form::Form('DataImportForm');
	}
	function on_submit()
	{
		if(Url::get('import')){
			$this->import(Url::get('type'));
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$this->map['type_list'] = array(
			'CUSTOMER' => Portal::language('customer'),
			'PRODUCT' => Portal::language('product'),
		);
		$this->parse_layout('list',$this->map);
	}
	function import($type='PRODUCT',$sheet=0){
		require_once 'packages/core/includes/utils/library_excel.php';
		if(isset($_FILES['file'])){
			$file = $_FILES['file']['tmp_name'];
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('UTF-8');
			$data->read($file);
			if($type=='PRODUCT'){
				$products =  $data->sheets[$sheet]['cells'];
				$i = 0;
				foreach($products as $value){
					if($i>0 and isset($value[1]) and isset($value[2]) and $value[1] and $value[2]){
						$product_id = strtoupper($value[1]);				
						if(!DB::exists('select id from wh_product where id=\''.$product_id.'\'')){
							$unit_name = isset($value['3'])?$value[3]:'';
							$unit_id = 0;
							$product_name = $value[2];
							if($unit_name){
								if($unit = DB::fetch('select id,name_1 from unit where upper(name_1) like \''.strtoupper($unit_name).'\'')){
									$unit_id = $unit['id'];
								}else{
									//$unit_id = DB::insert('unit',array('name_1'=>$unit_name,'name_2'=>$unit_name,'normal_unit'=>1));
								}
							}
							//DB::insert('wh_product',array('id'=>$product_id,'name_1'=>$product_name,'name_2'=>$product_name,'unit_id'=>$unit_id,'type'=>'GOODS'));
						}
					}
					$i++;
				}
			}elseif($type=='CUSTOMER'){
				$items =  $data->sheets[1]['cells'];
				$i = 0;
				foreach($items as $value){
					if($i>0 and isset($value[2]) and isset($value[3]) and isset($value[4]) and isset($value[5]) and $value[2] and $value[3] and $value[4] and $value[5]){
						$code = strtoupper($value[2]);				
						$name = $value[3];
						$address = $value[4];
						$tax_code = $value[5];
						if(!DB::exists('select id,code from customer where code=\''.$code.'\'')){
							DB::insert('customer',array('code'=>$code,'name'=>$name,'address'=>$address,'tax_code'=>$tax_code,'group_id'=>'TOURISM'));
						}
					}
					$i++;
				}
			}
		}
	}
}
?>