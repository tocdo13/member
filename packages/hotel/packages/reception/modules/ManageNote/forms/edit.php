<?php
class EditNoteForm extends Form
{
    function EditNoteForm()
    {
        Form::Form('EditNoteForm');
		$this->add('note',new TextType(true,'note_is_required',0,4000));
        //if(Url::get('confirm'))
            //$this->add('confirm_user',new TextType(true,'confirm_user_is_required',0,255));
    }
    
    function on_submit()
    {
        if($this->check())
        {
            /*
            if(Url::get('confirm'))
            {
                if(!Url::get('confirm_user'))
                {
                    $this->error('confirm_user_is_required','confirm_user_is_required');
                    return false;
                }   
            }
            */
            $row = array(
                            'note' => Url::get('note'),
                            'portal_id'=> PORTAL_ID,
                            'confirm'=>Url::get('confirm'),
                            //'confirm_user'=>Url::get('confirm_user'),
                        );
            if(Url::get('confirm_user'))
            {
                $row['confirm'] = 1;
            }   
            if(Url::get('cmd')=='add')
            {
                $row['user_id'] = Session::get('user_id');
                $row['create_time'] = time();
                $row['create_date'] = Date_Time::to_orc_date(date('d/m/Y'));
                DB::insert('reservation_note',$row);
            }
            else
            {
                $row['LAST_MODIFY_USER_ID'] = Session::get('user_id');
                $row['LAST_MODIFY_TIME'] = time();
                DB::update_id('reservation_note',$row,Url::get('id'));
            }
            Url::redirect_current(); 
        }
    }
    
    function draw()
    {
        $this->map = array();
        if(Url::get('cmd')=='edit')
        {
            $row = DB::fetch('Select * from reservation_note where id = '.Url::get('id'));
            foreach($row as $key=>$value)
                $_REQUEST[$key] = $value;
            $this->map['title'] = Portal::language('edit_note');
        }
        else
        {
            $this->map['title'] = Portal::language('add_note');
        }
        /*
        $this->map['confirm_user_list'] = DB::fetch_all('select 
                    										account.id
                    										,party.full_name 
                    									from 
                                                            account 
                                                            INNER JOIN party on party.user_id = account.id 
                                                            AND party.type=\'USER\' 
                                                        WHERE 
                                                            account.type=\'USER\' 
                                                            AND party.description_1=\'Lễ tân\' 
                                                        ORDER BY account.id');
                                                        
        $this->map['confirm_user_list'] = array(''=>Portal::language('confirm_user'))+String::get_list($this->map['confirm_user_list']);
        */
        $this->parse_layout('edit',$this->map);
    }
}

?>