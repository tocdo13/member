<?php
/**
 * Copy Right by TCV.JSC
 * Written by Kid 1412
**/ 
class PredictMaterial extends Module
{
	function PredictMaterial($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY))
        {
			require_once 'forms/edit.php';
			$this->add_form(new PredictMaterialForm());
		}
        else
        {
			URL::access_denied();
		}
	}	
}
?>