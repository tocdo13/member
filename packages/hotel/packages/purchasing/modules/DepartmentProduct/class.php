<?php 
class DepartmentProduct extends Module
{
	function DepartmentProduct($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            switch(Url::get('cmd'))
            {
                case 'import':
                {
                    require_once 'forms/import.php';
   					$this->add_form(new ImportSupplierPriceForm());
                    break;
                }
                case 'edit':
                {
                    require_once 'forms/add.php';
                    $this->add_form(new EditDepartmentProductForm()); 
                    break;
                }
                case 'delete':
                {
                    $this->delete_department_product(Url::get('ids'));
                    Url::redirect_current();
                    break;
                }
                default:
                {
                    require_once 'forms/list.php';
                    $this->add_form(new ListDepartmentProductForm());  
                    break;
                }
            }
        }
        else
            Url::access_denied();
	}
    
    function delete_department_product($ids)
    {
        DB::delete('pc_department_product','id in ('.$ids.')');
    }
}
?>