<?php
class Footer extends Module
{
	function Footer($row)
	{
		Module::Module($row);
		require_once 'db.php';
		require_once 'forms/footer.php';		
		$this->add_form(new FooterForm());
	}
}
?>
