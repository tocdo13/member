<?php
class ChattingForm extends Form
{
	function ChattingForm()
	{
		Form::Form('ChattingForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');		
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	
	}

	function draw()
	{
	   $this->map = array();
       $sql = "SELECT id, name_1 FROM party WHERE user_id='".User::id()."'";
       $result = DB::fetch($sql);
       $this->map['nickname'] = $result['name_1'];
       $this->parse_layout('list', $this->map);
	}
}
?>