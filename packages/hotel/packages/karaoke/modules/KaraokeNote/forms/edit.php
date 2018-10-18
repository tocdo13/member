<?php
class EditKaraokeNoteForm extends Form
{
    function EditKaraokeNoteForm()
    {
        Form::Form('EditKaraokeNoteForm');
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
                            'portal_id'=> PORTAL_ID
                            //'confirm_user'=>Url::get('confirm_user'),
                        ); 
            if(Url::get('cmd')=='add')
            {
                $row['user_id'] = Session::get('user_id');
                $row['create_time'] = time();
                $row['create_date'] = Date_Time::to_orc_date(date('d/m/Y'));
                DB::insert('karaoke_note',$row);
            }
            else
            {
                $row['LAST_MODIFY_USER_ID'] = Session::get('user_id');
                $row['LAST_MODIFY_TIME'] = time();
                DB::update_id('karaoke_note',$row,Url::get('id'));
            }
            Url::redirect_current(); 
        }
    }
    
    function draw()
    {
        $this->map = array();
        if(Url::get('cmd')=='edit')
        {
            $row = DB::fetch('Select * from karaoke_note where id = '.Url::get('id'));
            foreach($row as $key=>$value)
                $_REQUEST[$key] = $value;
            $this->map['title'] = Portal::language('edit_note');
        }
        else
        {
            $this->map['title'] = Portal::language('add_note');
        }
        $this->parse_layout('edit',$this->map);
    }
}

?>