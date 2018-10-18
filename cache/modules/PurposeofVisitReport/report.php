<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}

</style>
<!---------HEADER----------->
<!--IF:first_page($this->map['real_page_no']==1)-->
<div class="report-bound"> 
    <link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/global.css" media="print">
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <td >
        		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
        			<tr style="font-size:11px; font-weight:normal">
                        <td align="left" width="70%">
                            <img src="<?php echo HOTEL_LOGO;?>" style="width: 150px;height: auto;"/><br />
                            <?php echo HOTEL_NAME;?>
                            <br />
                            <?php echo HOTEL_ADDRESS;?>
                        </td>
                        <td align="right" style="padding-right:10px;" >
                            <strong><?php echo Portal::language('template_code');?></strong><br /> <?php echo Portal::language('print_by');?>: <?php echo Session::get('user_id');?> <br /> <?php echo Portal::language('print_time');?> : <?php echo date('H:i d/m/Y');?>
                        </td>
                    </tr>
                    <tr>
        				<td colspan="2"> 
                            <div style="width:100%; text-align:center;">
                                <font class="report_title specific" ><?php echo Portal::language('purpose_of_visit_report');?><br /></font>
                            </div>
                        </td>
                        
        			</tr>	
        		</table>
            </td>
        </tr>
    </table>		
</div>

<style type="text/css">
.specific {font-size: 19px !important;}
</style>


<!---------SEARCH----------->
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <td><?php echo Portal::language('from_date');?></td>
                                	<td><input  name="from_date" id="from_date" autocomplete="off" onchange="changevalue()"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                    <td><?php echo Portal::language('to_date');?></td>
                                	<td><input  name="to_date" id="to_date" autocomplete="off" onchange="changevalue()"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                                    <td><?php echo Portal::language('gender');?></td>
                                	<td><select  name="gender_id" id="gender_id"><?php
					if(isset($this->map['gender_id_list']))
					{
						foreach($this->map['gender_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('gender_id',isset($this->map['gender_id'])?$this->map['gender_id']:''))
                    echo "<script>$('gender_id').value = \"".addslashes(URL::get('gender_id',isset($this->map['gender_id'])?$this->map['gender_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><?php echo Portal::language('nationality');?></td>
                                    <td><input  name="nationality_name" id="nationality_name" autocomplete="off" onfocus="Autocomplete();"/ type ="text" value="<?php echo String::html_normalize(URL::get('nationality_name'));?>"> <input  name="nationality_id" id="nationality_id"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('nationality_id'));?>"></td>
                                    <td><?php echo Portal::language('Segment');?></td>
                                    <td><select  name="reservation_types_id" id="reservation_types_id"><?php
					if(isset($this->map['reservation_types_id_list']))
					{
						foreach($this->map['reservation_types_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('reservation_types_id',isset($this->map['reservation_types_id'])?$this->map['reservation_types_id']:''))
                    echo "<script>$('reservation_types_id').value = \"".addslashes(URL::get('reservation_types_id',isset($this->map['reservation_types_id'])?$this->map['reservation_types_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/></td>
                                </tr>
                            </table>
                        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>

<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
    }
);
</script>

<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" style="width: 1%;"><?php echo Portal::language('stt');?></th>
        <th class="report_table_header" style="width: 10%;"><?php echo Portal::language('guest_name');?></th>
        <th class="report_table_header" style="width: 10%;"><?php echo Portal::language('guest_level');?></th>
        <th class="report_table_header" style="width: 10%;"><?php echo Portal::language('nationality');?></th>
        <th class="report_table_header" style="width: 10%;"><?php echo Portal::language('arrival_date');?></th>
        <th class="report_table_header" style="width: 10%;"><?php echo Portal::language('departure_date');?></th>
        <th class="report_table_header" style="width: 10%;"><?php echo Portal::language('Purpose_of_visit');?></th>  
        <th class="report_table_header" style="width: 10%;"><?php echo Portal::language('Segment');?></th> 
        <th class="report_table_header" style="width: 10%;"><?php echo Portal::language('age');?></th>
        <th class="report_table_header" style="width: 9%;"><?php echo Portal::language('gender');?></th>
        <th class="report_table_header" style="width: 10%;"><?php echo Portal::language('expense');?></th>
        <th class="report_table_header" style="width: 10%;"><?php echo Portal::language('address');?></th>
    </tr>
    <?php $stt=1;?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr>
        <td><?php echo $stt++;?></td>
        <td style="text-align: left;"><span onclick="GetLinkedit(<?php echo $this->map['items']['current']['traveller_id'];?>)"><a><?php echo $this->map['items']['current']['full_name'];?></a></span></td>
        <td style="text-align: left;"><?php echo $this->map['items']['current']['guest_level'];?></td>
        <td style="text-align: left;"><?php echo $this->map['items']['current']['name_1'];?></td>
        <td><?php echo $this->map['items']['current']['arrival_date'];?></td>
        <td><?php echo $this->map['items']['current']['departure_date'];?></td>
        <td style="text-align: left;"><?php echo $this->map['items']['current']['target_of_entry'];?></td>
        <td style="text-align: left;"><?php echo $this->map['items']['current']['name'];?></td>
        <td><?php echo $this->map['items']['current']['age'];?></td>
        <td style="text-align: left;"><?php echo $this->map['items']['current']['gender'];?></td>
        <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['amount']);?></td>
        <td style="text-align: left;"><?php echo $this->map['items']['current']['address'];?></td>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
    <tr>
        <td colspan="12">&nbsp;</td>
    </tr>
</table>
<script>
    $('from_date').value='<?php if(Url::get('from_date')){echo Url::get('from_date');}else{ echo ('1/'.date('m').'/'.date('Y'));}?>';
    function changevalue(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('to_date').value =$('from_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('to_date').value =$('from_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('to_date').value =$('from_date').value;
                }
            }
        }
    }
</script>
<script>
    function Autocomplete()
    {
        jQuery("#nationality_name").autocomplete({
             url: 'r_get_countries_son.php?',
             onItemSelect: function(item){
                console.log(item.data[0]);
                document.getElementById('nationality_id').value = item.data[0];
            }
        }) ;
    }
    function GetLinkedit(id){
    url = '?page=traveller&cmd=edit&id='+id;
    window.open(url);
}
</script>