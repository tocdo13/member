<?php
    date_default_timezone_set('Asia/Saigon');
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    $sql = '
            select 
                room_allotment_avail_rate.id,
                room_allotment_avail_rate.in_date,
                room_allotment_avail_rate.availability
            from
                room_allotment_avail_rate
                inner join room_allotment on room_allotment.id=room_allotment_avail_rate.room_allotment_id
            where
                (room_allotment_avail_rate.confirm=0 or room_allotment_avail_rate.confirm is null)
                and room_allotment.auto_reset_avail=1
                and room_allotment_avail_rate.availability!=0 and room_allotment_avail_rate.availability is not null
                and room_allotment_avail_rate.in_date-room_allotment.day_reset_avail=\''.Date_Time::to_orc_date(date('d/m/Y')).'\'
            ';
    $items = DB::fetch_all($sql);
    //System::debug($items); die;
    foreach($items as $key=>$value){
        DB::update('room_allotment_avail_rate',array('availability'=>0),'id='.$value['id']);
        DB::insert('room_allotment_auto_reset',array('time'=>time(),'allotment_avail_rate_id'=>$value['id'],'avail'=>$value['availability'],'in_date'=>$value['in_date']));
    }
 ?>


