<?php 
class RoomAllotment extends Module
{
	function RoomAllotment($row)
	{
		Module::Module($row);
        if(Url::get('status')=='RESTORE'){
            if(Url::get('id')){
                $record = DB::fetch('select * from room_allotment_auto_reset where id='.Url::get('id'));
                DB::update('room_allotment_avail_rate',array('availability'=>$record['avail'],'confirm'=>1),'id='.$record['allotment_avail_rate_id']);
                DB::delete('room_allotment_auto_reset','id='.Url::get('id'));
                echo 'Khôi phục thành công';
            }else{
                echo '';
            }
            exit();
        }
		switch (Url::get('cmd')){
			case 'add':
				require_once 'forms/add.php';
				$this->add_form(new AddRoomAllotmentForm());
				break;
            case 'add_all':
				require_once 'forms/add_all.php';
				$this->add_form(new AddAllRoomAllotmentForm());
				break;
			case 'edit':
				require_once 'forms/edit.php';
				$this->add_form(new EditRoomAllotmentForm());
				break;
			default:
				require_once 'forms/list.php';
				$this->add_form(new ListRoomAllotmentForm());
				break;
		}
	}
}
?>