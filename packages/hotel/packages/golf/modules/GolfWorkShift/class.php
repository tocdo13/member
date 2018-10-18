<?php 
class GolfWorkShift extends Module
{
	function GolfWorkShift($row)
	{
		Module::Module($row);
        if(Url::get('status')=='GETINFO'){
            $time = Url::get('time');
            $list_time = explode(',',$time);
            $data = array();
            for($i=0;$i<sizeof($list_time);$i++){
                $in_date = date('d/m/Y',$list_time[$i]);
                $record = DB::fetch('
                                    SELECT
                                        GOLF_WORKSHIFT.*,
                                        TO_CHAR(GOLF_WORKSHIFT.in_date,\'DD/MM/YYYY\') as in_date
                                    FROM
                                        GOLF_WORKSHIFT
                                    WHERE
                                        GOLF_WORKSHIFT.in_date=\''.Date_Time::to_sql_date($in_date).'\'
                                        and GOLF_WORKSHIFT.portal_id=\''.PORTAL_ID.'\'
                                    ');
                if($record)
                {
                    $data[$list_time[$i]]['status'] = 1;
                    $data[$list_time[$i]]['in_date'] = date('d/m/Y',$list_time[$i]);
                    $data[$list_time[$i]]['data'] = $record;
                    $data[$list_time[$i]]['data']['start_time'] = date('d/m/Y',$record['start_time']);
                    $data[$list_time[$i]]['data']['end_time'] = date('d/m/Y',$record['end_time']);
                }else{
                    $data[$list_time[$i]]['status'] = 0;
                    $data[$list_time[$i]]['in_date'] = date('d/m/Y',$list_time[$i]);
                    $data[$list_time[$i]]['data'] = array();
                }
            }
            echo json_encode($data);
            exit();
        }
		if(User::can_view(false,ANY_CATEGORY))
        {
			require_once 'forms/edit.php';
			$this->add_form(new EditgolfWorkShiftForm());
		}
        else
        {
			Url::access_denied();
		}
	}	
}
?>