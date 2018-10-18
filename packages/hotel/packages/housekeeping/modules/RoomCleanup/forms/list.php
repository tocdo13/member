<?php
class ListForm extends Form
{
    function ListForm()
    {
        Form::Form('ListForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    
    function on_submit()
    {
        $selected_ids = Url::get('selected_ids');
        if(!empty($selected_ids))
        {
			foreach($selected_ids as $id)
			{
                DB::delete_id( 'room_cleanup', $id );
			}  
        } 
    }
    function draw()
    {
        
        $date_from = Url::get('from_date')?Date_Time::to_orc_date(Url::get('from_date')):$this->get_beginning_date_of_week();
		$date_to = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):$this->get_end_date_of_week();
		$this->map['from_date'] = Date_Time::convert_orc_date_to_date($date_from,'/');
		$this->map['to_date'] = Date_Time::convert_orc_date_to_date($date_to,'/');
        $_REQUEST['from_date'] = $this->map['from_date'];
        $_REQUEST['to_date'] = $this->map['to_date'];
		$time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,'/'));
		$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);
        
        $cond = ' room_cleanup.portal_id = \''.PORTAL_ID.'\' and room_cleanup.start_time <= '.$time_to.' and room_cleanup.start_time >= '.$time_from.' ';
        $item_per_page = 50;
		$sql = 'SELECT count(*) AS acount FROM room_cleanup where '.$cond.' ';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array());

        $sql = '
			SELECT * FROM
			(
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
                    '.$cond.'
				ORDER BY
					room_cleanup.id desc
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'	
		';
        //System::debug($sql);
		$items = DB::fetch_all($sql);
        
        $i = ((page_no()-1)*$item_per_page)+1;
        foreach($items as $k => $v)
        {
            $items[$k]['stt'] = $i++;
            $items[$k]['start_time'] = $items[$k]['start_time']?date('H\h:i d/m/Y',$items[$k]['start_time'] ):'';
            $items[$k]['end_time'] = $items[$k]['end_time']?date('H\h:i d/m/Y',$items[$k]['end_time'] ):'';
        }
        //System::debug($items);
        $this->map['items'] = $items;
        $this->map['title'] = Portal::language('list_room_cleanup');
        $this->parse_layout('list',$this->map);
    }
    
    function get_beginning_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$day_begin_of_week = $time_today  - (24 * 3600 * $day_of_week);
		return (Date_Time::to_orc_date(date('d/m/Y',$day_begin_of_week)));
	}
	function get_end_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$end_of_week = $time_today + (24 * 3600 * (6 - $day_of_week));
		return (Date_Time::to_orc_date(date('d/m/Y',$end_of_week)));
	}
}

?>