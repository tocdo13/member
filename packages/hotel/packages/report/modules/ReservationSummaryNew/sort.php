<?php
    function option_sort($option_sort = array(),$define_sorf = array(),$cond = array(),$from_name){
        $sort = '';
        $num_sort = 0;
        if($option_sort AND is_array($option_sort)){
            $num_sort += sizeof($option_sort);
        }
        if($define_sorf AND is_array($define_sorf)){
            $num_sort += sizeof($define_sorf);
        }
        if($from_name AND $from_name!=''){
            $sort .= '
                <form name="'.$from_name.'" method="post">
            ';
        }
        if($cond AND is_array($cond)){
            //System::debug($cond);
            $sort .= '
                <div style="display:none;">
            ';
            foreach($cond as $id=>$content){
                $sort .= '<input name="'.$id.'" id="'.$id.'" type="text" value="'.$content.'" />';
            }
            
            $sort .= '</div>';
        }
        $sort .= '
            <div>
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: right;"><input type="button" name="button_sort" onclick="sort_report();" value="option sort" style="width: 100px; height: 30px; background: #fff; border: 1px solid #eeeeee; box-shadow: 0px 0px 3px #555555; border-radius: 10px;" />
                        <input type="text" name="sort" id="sort" value="'.$num_sort.'" style="display:none;">
                        </td>
                    </tr>
                </table>
            </div>
            <div id="container_sort" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; z-index: 99; background-color: rgba(0, 0, 0, 0.9); display: none;">
                <div style="width: 500px; height: auto; margin: 15px auto; padding: 5px; border: 2px double #00b2f9; background: #ffffff; ">
                    <div style="margin: 0px auto; width: 100%;">
                        <div id="close_sort" style="width: 25px; height: 25px; text-align: center; line-height: 25px; border-radius: 50%; border: 2px solid #00b2f9; color: #555555; float: right; margin-top: -15px; margin-right: -15px; background: #ffffff; cursor: pointer;" onclick="close_sort();">X</div>
                    </div>
                    <div id="content_sort" style="margin: 25px auto;">
                        <table style="width: 90%; margin: 5% auto;" id="table_sort" >
                            <tr style="line-height: 25px;">
                                <th style="width: 5%; background: #00b2f9; border: none;"> </th>
                                <th style="width: 45%; font-size: 13px;">NAME SORT</th>
                                <th style="width: 45%; font-size: 13px;">TYPE SORT</th>
                                <th style="width: 5%; background: #00b2f9; border: none;"></th>
                            </tr>
        ';
        //echo $sort;
        if($define_sorf AND is_array($define_sorf)){
            //System::debug($define_sorf);
            $stt = 0;
            foreach($define_sorf as $key=>$value){
            $stt ++;
            //echo $key.'--'.$value;
                    $sort .= '
                            <tr id="tr_'.$stt.'">
                                <th style="width: 5%; background: #00b2f9; border: none;"><input type="checkbox" name="check_sort_'.$stt.'" id="check_sort_'.$stt.'" checked=checked style="display:none;" /></th>
                                <th style="width: 45%;"><select id="name_sort_'.$stt.'" name="name_sort_'.$stt.'"><option value="'.$key.'" >'.$value.'</option></select></th>
                                <th style="width: 45%;"><select id="type_sort_'.$stt.'" name="type_sort_'.$stt.'"><option value="ASC">ASC</option><option value="DESC">DESC</option></select></th>
                                <th style="width: 5%; background: #00b2f9; border: none; color: #fff;"></th>
                            </tr>
                    ';
            }
            $sort .= '<input type="text" name="stt_sort" id="stt_sort" value="'.$stt.'" style="display:none;" />';
        }
        
                    $sort .= '</table>';
        if($from_name AND $from_name!=''){
            $sort .= '
                    <table style="width: 90%; margin: 5% auto; border: none;" >
                    <tr>
                        <td><input type="button" id="add" value="ADD" onclick="add_sort();" style="width: 50px; height: 25px; border: 1px solid #eeeeee; border-radius: 5px;" /></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><input type="submit" value="SORT" style="width: 200px; height: 30px; background: #00b2f9; border: 1px solid #eeeeee; border-radius: 5px;" /></td>
                    </tr>
                    </table>
                </form>
            ';
        }
        $sort .= '
                        
                    </div>
                </div>
            </div>
        ';
        if($option_sort AND is_array($option_sort)){
            $sort .= '
                <div id="option_sort" style="display:none;">
            ';
                foreach($option_sort as $sort_id=>$sort_value){
                    $sort .= '
                        <option value="'.$sort_id.'">'.$sort_value.'</option>
                    ';
                }
                
            $sort .= '
                </div>
            ';
        }
        return $sort;
    }
?>
<script type="text/javascript">
    function sort_report(){
        jQuery("#container_sort").css('display','block');
    }
    function close_sort(){
        jQuery("#container_sort").css('display','none');
    }
    function add_sort(){
        
        var option = jQuery("#option_sort").html();
        console.log(option);
        if(option){
        var stt = Number(jQuery("#stt_sort").val());
        stt += 1;
        jQuery("#stt_sort").val(stt);
        var add = '<tr id="tr_'+stt+'">';
        add += '<th style="width: 5%; background: #00b2f9; border: none;"><input type="checkbox" name="check_sort_'+stt+'" id="check_sort_'+stt+'" checked=checked style="display:none;" /></th>';
        add += '<th style="width: 45%;"><select id="name_sort_'+stt+'" name="name_sort_'+stt+'">'+option+'</select></th>';
        add += '<th style="width: 45%;"><select id="type_sort_'+stt+'" name="type_sort_'+stt+'"><option value="ASC">ASC</option><option value="DESC">DESC</option></select></th>';
        add += '<th style="width: 5%; background: #00b2f9; border: none; color: #fff;"><span style="border-radius:2px; padding: 5px; background:#ffffff; text-align: center; line-hieght:25px; cursor: pointer;" id="'+stt+'" onclick="delete_sort(this);">X</span></th>';
        add += '</tr>';
        //console.log(option);
        //add += '</tr>';
        jQuery("#table_sort").append(add);
        }else{
            alert("ĐÃ HẾT TRƯỜNG ĐỂ SẮP XẾP");
        }
    }
    function delete_sort(obj){
        var id=obj.id;
        document.getElementById("check_sort_"+id).checked=false;
        jQuery("#tr_"+id).css('display','none');
    }
</script>
<style>
table#table_sort tr th{
    transition: all .5s ease-out;
    background: #ffffff;
    border: 1px solid #eeeeee;
}
table#table_sort tr th:hover{
    background: #eeeeee;
    border: 1px solid #dddddd;
}
#add{
    transition: all .5s ease-out;
    background: #ffffff;
}
#add:hover{
    background: #00b2f9;
}
</style>