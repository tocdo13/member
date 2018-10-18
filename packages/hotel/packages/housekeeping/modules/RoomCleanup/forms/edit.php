<?php
class EditForm extends Form
{
	function EditForm()
	{
		Form::Form('EditForm');
	}
	function on_submit()
	{
		if(Url::check('update'))
		{
            $record = array(
                            'last_edit_user_id'=>Session::get('user_id'),
                            'note'=>Url::get('note_'.Url::iget('id')),
                            );
            if(Url::check('complete_'.Url::iget('id')))
            {
                $record += array('end_time'=>time(),'status'=>'COMPLETE');
            }
            DB::update_id('room_cleanup',$record,Url::iget('id'));
			Url::redirect_current();
		}
	}	
	function draw()
	{
        $this->map = array();
        if($id = Url::iget('id'))
        {
            $sql='
                SELECT
    					room_cleanup.id,
                        room_cleanup.room_id,
                        room_cleanup.status,
                        room_cleanup.start_time,
                        room_cleanup.end_time,
                        room_cleanup.user_id,
                        room_cleanup.last_edit_user_id,
                        room_cleanup.note,
                        room.name as room_name,
    					ROW_NUMBER() OVER (ORDER BY room_cleanup.id desc) as rownumber
    				FROM
    					room_cleanup
                        inner join room on room_cleanup.room_id = room.id
                    where 
                        room_cleanup.id = '.$id.'
    				ORDER BY
    					room_cleanup.id desc
    			';
            $this->map['item'] = DB::fetch_all($sql);
            //System::debug($this->map['item'] );
        }
		$layout='edit';
		$this->parse_layout($layout,$this->map);
	}
}
?>