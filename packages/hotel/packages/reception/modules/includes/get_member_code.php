<?php
function get_member_code(){
    $max_member_code = DB::fetch(" SELECT max(traveller.member_code) as max_code FROM traveller");
    $date = getdate(); $year = $date['year'];
    $code = $year."000001";
    if($max_member_code['max_code']==0){
        return $code;
    }else{
        $year_code = substr($max_member_code['max_code'],0,4);
        if($year_code==$year){
            $code++;
            return $code;
        }
    }
    return $code;
}
?>