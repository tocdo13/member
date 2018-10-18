<?php
class CheckOutForm extends Form
{
	function CheckOutForm()
	{
		Form::Form('CheckOutForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
        $this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
	   echo '<div id="progress" style="position:fixed; top:60px; right:'.(Url::get('width')/2 - 64).'px;" ><img src="packages/core/skins/default/images/updating.gif" /> Proccessing...</div>';
	}
	
	function draw()
	{
        require_once 'packages/hotel/packages/reception/modules/ManagerKeyHune/db.php';
        if(isset($_REQUEST['delete']))
        {
            $this->list_checkout_detail();
        }
        else
        {
            $this->loadData();
        }
	}
    function list_checkout_detail()
    {
        $this->map = array();
        //hien thi danh sach voi cac keys va phong duoc checkout 

        $encoder =  $_REQUEST['reception_id'];
        $str_encoder = explode("_",$encoder);
        $row = array();
        
        $row['reception_id'] = $str_encoder[0];
        $row['number_keys'] = $_REQUEST['number_keys'];
        $this->map['row'] = $row;
        
        $this->parse_layout('checkout_detail_room',$this->map);
    }

    function loadData($result='')
    {
        $this->map = array();
        $this->map['result'] = $result;
        //lay ra thong tin encoder 
        $db_items = DB::fetch_all("select 
                                         id || '_' || ip as id, 
                                        reception as name
                                    from manage_ipsever 
                                    order by reception desc");
		$reception_id_options = '';
		foreach($db_items as $item)
		{
			$reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        
        $this->map['reception_id'] = $reception_id_options;
        //end lay ra thong tin encoder
        
        
        $this->parse_layout('checkout',$this->map);
    }
    
}
?>