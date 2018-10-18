<?php
class DebtDeduction extends Module
{
    function DebtDeduction($row)
    {
        Module::Module($row);
        if(User::can_add(false,ANY_CATEGORY))
        {
            switch(Url::get('cmd'))
            {
                case 'add':
                    require_once 'forms/edit.php';
    			    $this->add_form(new DebtDeductionForm());
                    Break;
                
                case 'edit';  
                    require_once 'forms/edit.php';
    			    $this->add_form(new DebtDeductionForm());
                    Break;
                
                case 'delete':
                    if(Url::get('id'))
                    {
                         DB::delete('CUSTOMER_REVIEW_DEBT','id='.Url::get('id'));
                    }
                    if(Url::get('item-check-box'))
                    {
                       // system::debug($_REQUEST);die();
                       $ids =Url::get('item-check-box');
                       for($i=0;$i<count($ids);$i++)
                       {
                            DB::delete('CUSTOMER_REVIEW_DEBT','id='.$ids[$i]);
                       }
                    }
                    Url::redirect_current();
                    Break;    
                default:
                     require_once 'forms/list.php';
                     $this -> add_form(new DebtDeductionListForm());
                    Break;     
            }   
            
        }
        else
        {
            Url::access_denied();
        }
    }   
}    
?>