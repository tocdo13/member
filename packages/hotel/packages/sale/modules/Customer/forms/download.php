<?php
class DownloadCustomerForm extends Form
{
	function DownloadCustomerForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
        $this->add('group_id',new TextType(true,'miss_group_id',0,255));
		
		$this->add('name',new TextType(true,'miss_name',0,255));

	}
	function on_submit(){
        $upload_dir = "http://".$_SERVER['HTTP_HOST']."/".Url::$root."packages/hotel/packages/sale/modules/Customer/file/";
         
        $filename = Url::get('file_name');
        $fp = fopen($upload_dir.$filename, "rb");
        header('Content-type: application/octet-stream');
        header('Content-disposition: attachment; filename="'.$filename.'"');
        header('Content-length: ' . filesize($upload_dir.$filename));
        fpassthru($fp);
        fclose($fp);
	}
	function draw(){
	   require_once 'packages/core/includes/utils/vn_code.php';
	   $this->map=array();
	   if(Url::get('id')){
	       $id = Url::get('id');
           $file = DB::fetch("SELECT * FROM FILE_CUSTOMER WHERE id=".$id);
           if(sizeof($file)>0){
            $this->map['file'] = $file;
           }
	   }
       if(Url::get('customer_code')){
            $customer_code = Url::get('customer_code');
            $customer = DB::fetch("SELECT customer.id, customer.name, customer_group.name as g_name FROM customer inner join customer_group on customer_group.id = customer.group_id WHERE customer.code='$customer_code'");
            $this->map['customer'] = $customer;                
       }
	   $this->parse_layout('download',$this->map);
    }
}

?>
