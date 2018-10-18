<?php
class AddShiftKaraokeForm extends Form{
	function AddShiftKaraokeForm()
    {
		Form::Form('AddShiftKaraokeForm');
 		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
		$this->add('shift.name',new TextType(true,'name',0,255));
        $this->add('shift.start_time',new TextType(true,'start_time',0,5));
        $this->add('shift.end_time',new TextType(true,'end_time',0,5));
	}
	function on_submit()
    {
        if(Url::get('save'))//Khi an nut save moi luu, khi submit qua onchange thi khong luu
        {
            if($this->check())
            {
                if(URL::get('deleted_ids'))
                {
        			$ids = explode(',',URL::get('deleted_ids'));
        			foreach($ids as $id)
                    {
        				DB::delete('karaoke_shift','id=\''.$id.'\'');
        			}
        		}
                $karaokes = DB::fetch_all('Select * from karaoke where portal_id = \''.PORTAL_ID.'\' ');
    
                if(isset($_REQUEST['mi_shift']))
                {
                    foreach($_REQUEST['mi_shift'] as $key=>$record)
                    {
                        unset($record['no']);
                        $record['brief_start_time'] = $record['start_time'];
                        $record['brief_end_time'] = $record['end_time'];
                        $record['start_time'] = $this->calc_time($record['start_time']);
                        $record['end_time'] = $this->calc_time($record['end_time']);
                        
                        if($record['id'] and DB::exists_id('karaoke_shift',$record['id']))
                        {
                            DB::update_id('karaoke_shift',$record,$record['id']);
                        }
                        else
                        {
                            unset($record['id']);
                            if($row = DB::select_id('karaoke',Url::get('karaoke_id')))
                            {
                                $record['karaoke_id'] = $row['id'];
                                $record['karaoke_code'] = $row['code'];
                                DB::insert('karaoke_shift',$record);
                            }
                            else
                            {
                                foreach($karaokes as $k=>$v)
                                {
                                    $record['karaoke_id'] = $v['id'];
                                    $record['karaoke_code'] = $v['code'];
                                    DB::insert('karaoke_shift',$record);
                                }
                            }
                        }
                    }
                    Url::redirect_current();
        		}
            }
        }
        else
            return false;
	}	
	function draw()
    {
        $this->map = array();
        $this->map['karaoke_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::fetch_all('Select * from karaoke where portal_id = \''.PORTAL_ID.'\' '));
		if($row = DB::select_id('karaoke',Url::get('karaoke_id')))
        {
            $cond = ' 1>0 ';
			$sql = '
				SELECT
					karaoke_shift.*
				FROM
					karaoke_shift 
                WHERE
                    karaoke_id='.$row['id'].' 
                ORDER BY
                    id
                    ';
			$shift = DB::fetch_all($sql);
			//System::Debug($shift);
			$i=1;
			foreach($shift as $key => $value)
            {
				$shift[$key]['no'] = $i;
                $shift[$key]['start_time'] = $this->format_time($shift[$key]['start_time']);
                $shift[$key]['end_time'] = $this->format_time($shift[$key]['end_time']);
                $i++;
			}
			$_REQUEST['mi_shift'] = $shift;
        }
        $this->parse_layout('add_shift',$this->map);
	}
    
    function format_time($time)
    {
        $h = floor($time/3600);
        $m = floor(($time%3600)/60);      
        $h = strlen($h)<2?('0'.($h)):$h;
        $m = strlen($m)<2?('0'.($m)):$m;
        return $h.$m; 
    }
    
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>