
<?php
  define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    require_once 'packages/core/includes/system/config.php';
     
    if(Url::get('q'))
    {
        //lay ra danh sach nhung package trong khoang thoi gian 
        if(Url::get('arrival_time'))
        {
            $arrival_time = explode("/",Url::get('arrival_time'));
            $time = mktime(0,0,0,$arrival_time[1],$arrival_time[0],$arrival_time[2]);
            $time +=86400;
            $sql ="select 
                            id,
                            name
                        from
                            package_sale
                        where
                            UPPER(name) LIKE '".strtoupper(Url::sget('q'))."%'
                            AND ((DATE_TO_UNIX(start_date)<=$time AND DATE_TO_UNIX(end_date)>=$time
                             AND start_date is not null AND end_date is not null) OR start_date is null OR end_date is null)
                            
                        order by
                            name";
        }
        else
        {
            $sql ="select 
                        id,
                        name
                    from
                        package_sale
                    where
                        UPPER(name) LIKE '".strtoupper(Url::sget('q'))."%'
                    order by
                        name";   
        }
        $items = DB::fetch_all($sql);
        //$items = String::get_list($items);
        foreach($items as $key=>$value){
            echo $value['name'].'|'.$value['id']."\n";
        }
        
        DB::close();
    }
?>
