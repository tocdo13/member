<?php
class AddShopForm extends Form
{
	function AddShopForm()
	{
		Form::Form('AddShopForm');
		$this->add('shop.code',new UniqueType(true,'invalid_code','shop','code')); 
		$this->add('shop.name',new TextType(true,'invalid_name',0,255));
	}
	function on_submit()
	{
		if($this->check())
		{
			if(isset($_REQUEST['mi_shop']))
		{
			foreach($_REQUEST['mi_shop'] as $key=>$record)
			{
				unset($record['id']);
				DB::insert('shop',$record); // Huan sua? them $ids 
			}
		}
			if (Url::get('href'))
			{
				echo '<script>
						opener.document.forms[0].submit();
						window.close();
					</script>';
			}
			else
			{
				Url::redirect_current(array('selected_ids')+array(
				'shop_code', 'shop_name', 
				));
			}
		}
	}
	function draw()
	{
		$this->parse_layout('add',
			array(
			)
		);
	}
}
?>