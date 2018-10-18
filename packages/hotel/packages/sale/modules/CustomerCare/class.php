<?php
    class CustomerCare extends Module
    {
        function CustomerCare($row)
        {
            Module::Module($row);
            switch(Url::get('cmd'))
            {
                 case 'edit':
                     if(User::can_edit(false,ANY_CATEGORY))
                     {
                        require_once 'forms/edit.php';
                        $this->add_form(new EditCustomerCareForm());
                     }
                     else
                     {
                        Url::access_denied();
                     } 
                     break;
                 case 'delete':
                    $id = Url::iget('id_customer_care');
                    $customer_id = Url::iget('customer_id');
                    DB::delete('customer_care','id='.$id);
                    Url::redirect_current(array('id'=>$customer_id));
                    break;
                 default:
                     if(User::can_view(false,ANY_CATEGORY))
                     {
                        require_once 'forms/edit.php';
                        $this->add_form(new EditCustomerCareForm());
                     }
                     else
                     {
                        Url::access_denied();
                     } 
                     break;
            }
            
             
        }
    }
?>
