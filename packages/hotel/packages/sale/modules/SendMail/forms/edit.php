<?php
class EditSendMail extends Form
{
    function EditSendMail()
    {
       Form::Form();
       $this->link_js('packages/core/includes/js/jquery/datepicker.js');  
       $this->link_js('ckeditor/ckeditor.js');
       $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');  
    }
    
    function draw()
    {
        $this->map = array();
        $list_group_event =DB::fetch_all('SELECT * FROM email_group_event');
        $this->map['group_event_list']=String::get_list($list_group_event);    
        if(Url::get('id'))
        {
            $send_mail_id = Url::get('id');
            $sql ='SELECT  email_send.id,
                           email_send.title,
                           email_send.content,
                           email_send.images,
                           email_send.date_send,
                           email_group_event.code AS code,
                           email_send.email_group_event_id as group_event
                   FROM    email_send
                           LEFT JOIN email_group_event ON email_send.email_group_event_id = email_group_event.id 
                   WHERE   email_send.id='.Url::get('id')
                   ;
            $this->map['items']= DB::fetch($sql);
            $this->map['group_event'] = $this->map['items']['group_event'];   
        }
        $this->parse_layout('edit',$this->map
                                    +array(
                                    'list_group_event' =>$list_group_event
                                    ));                    
    }
    function on_submit()
    {
        if(Url::get('cmd')=="add")
        {
            //system::debug($_REQUEST);die();
            $images ='';
            if($_FILES['file']['name'] != null)
            {
                $file_name = $_FILES['file']['name'];
                $type = $_FILES['file']['type'];
                $tmp_name = $_FILES['file']['tmp_name'];
                $location = 'packages/hotel/includes/Email/images/';
                $ext = substr($file_name,strrpos($file_name,'.')+1);
                if(file_exists($location.'/'.$file_name))
                    $file_name = rand(100000,999999).'.'.$ext;
                $images =$location.$file_name;
                move_uploaded_file($tmp_name,$location.$file_name);
            }
            
            
            
            $sendmail = array(
                           'title'=>Url::get('title'),
                           'date_send'=>Url::get('date_send'),
                           //'content'=>Url::get('content'),
                           'email_group_event_id'=>Url::get('group_event'),
                           'date_send'=>Date_Time::to_orc_date(Url::get('date_send')),
                           'images' =>$images         
                        );           
            $send_mail_id = DB::insert('EMAIL_SEND',$sendmail);
            if(!file_exists('packages/hotel/includes/Email/files'))
            {
                mkdir('packages/hotel/includes/Email/files');
            }
            file_put_contents('packages/hotel/includes/Email/files/text_'.$send_mail_id.'.text',Url::get('content'));
            DB::update('email_send',array('content'=>'packages/hotel/includes/Email/files/text_'.$send_mail_id.'.text'),'id='.$send_mail_id);
            
            
            $list_check_customer = DB::fetch_all("
                                                    SELECT id,customer_id,traveller_id
                                                    FROM email_group_event_customer 
                                                    WHERE email_group_event_customer.email_group_event_id=".Url::get('group_event')
                                                );
            
            $arr_list_check_customer =array();
            $arr_list_check_traveller =array();
            foreach($list_check_customer as $key=>$value)
            {
               $arr_list_check_customer[] =  $value['customer_id'];
               $arr_list_check_traveller[] =  $value['traveller_id']; 
            }
            
            $arr_list_check_customer = array_filter($arr_list_check_customer);
            $arr_list_check_traveller = array_filter($arr_list_check_traveller);
            
            
            foreach($arr_list_check_customer as $k => $v)
            {
                DB::insert('email_list',array('customer_id'=>$v,'email_send_id'=>$send_mail_id));
            }
            foreach($arr_list_check_traveller as $k => $v)
            {
                DB::insert('email_list',array('traveller_id'=>$v,'email_send_id'=>$send_mail_id));
            }
            
            Url::redirect_url('?page=send_mail&cmd=edit&id='.$send_mail_id.'&portal='.PORTAL_ID);
        }
    /* Update Email Send  */
        else  //URL::get('cmd')=='edit';
        {
            //system::debug($_REQUEST);
            if($_FILES['file']['name'] != null)
            {
                $file_name = $_FILES['file']['name'];
                $type = $_FILES['file']['type'];
                $tmp_name = $_FILES['file']['tmp_name'];
                $location = 'packages/hotel/includes/Email/images/';
                $ext = substr($file_name,strrpos($file_name,'.')+1);
                if(file_exists($location.'/'.$file_name))
                {
                    unlink($location.'/'.$file_name);
                }
                $images =$location.$file_name;
                move_uploaded_file($tmp_name,$location.$file_name);
                $row['images']=$location.$file_name;   
            }
            $row['title']=Url::get('title');
            $row['date_send']=Date_Time::to_orc_date(Url::get('date_send'));
            file_put_contents('packages/hotel/includes/Email/files/text_'.Url::get('id').'.text',Url::get('content'));
            DB::update('email_send',array('content'=>'packages/hotel/includes/Email/files/text_'.Url::get('id').'.text'),'id='.Url::get('id'));
            //$row['content']=Url::get('content');
            $row['email_group_event_id']=Url::get('group_event');
            
            DB::update('email_send',$row,'id='.Url::get('id'));
            
            
            
 
        }   
    }
}
?>