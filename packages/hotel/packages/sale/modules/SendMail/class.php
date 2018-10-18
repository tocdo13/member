<?php  
class SendMail extends Module
{
    function SendMail($row)
    {
        //require_once "send_mail_marketing.php";
        //require_once "send_mail_invoice.php";
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        { 
            switch(Url::get('cmd'))
            {
                case 'add':
                    require_once('forms/edit.php');
                    $this -> add_form(new EditSendMail());
                    break;
                case 'edit':
                    require_once('forms/edit.php');
                    $this -> add_form(new EditSendMail());
                    break;
                    
                case 'delete':
                    if(Url::get('id'))
                    {
                         $send_mail_id = Url::get('id');
                         $send_mail =DB::fetch('SELECT email_send.images,email_send.content FROM email_send WHERE id='.$send_mail_id);
                         unlink($send_mail['images']);
                         unlink($send_mail['content']);
                         DB::delete('email_send','id='.$send_mail_id);
                         DB::delete('email_list','email_send_id='.$send_mail_id);
                    }
                    if(Url::get('item-check-box'))
                    { 
                       $send_mail =Url::get('item-check-box');
                       for($i=0;$i<count($send_mail);$i++)
                       {
                            $send_mail_images = DB::fetch('SELECT email_send.id,email_send.images,email_send.content FROM email_send WHERE id='.$send_mail[$i]);
                            unlink($send_mail_images['images']);
                            unlink($send_mail_images['content']);
                            DB::delete('email_send','id='.$send_mail[$i]);
                            DB::delete('email_list','email_send_id='.$send_mail[$i]);
                       }
                    }
                    Url::redirect_current();
                    break;
                case 'list_customer':
                    require_once('forms/choose_customer.php');
                    $this ->add_form(new ChooseCustomer());
                    break;
                default:
                    require_once('forms/list.php');
                    $this ->add_form(new SendMailForm());
                    break;
            }
         }
         else
            URL::access_denied();
         
   }
    
}
?>