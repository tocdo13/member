<?php
class MenuFunctionForm extends Form
{
	function MenuFunctionForm()
	{
		Form::Form('MenuFunctionForm');
	}
	function on_submit()
	{
	   echo '<div id="progress" style="position:fixed; top:60px; right:100px;" ><img src="packages/core/skins/default/images/updating.gif" /> Proccessing...</div>';
	}	
	function draw()
	{
		$this->parse_layout('menu');
	}
}
?>