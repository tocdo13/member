<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}

</style>
<!---------HEADER----------->
<!--IF:first_page([[=real_page_no=]]==1)-->
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
                            <strong>[[.template_code.]]</strong><br /> [[.print_by.]]: <?php echo Session::get('user_id');?> <br /> [[.print_time.]] : <?php echo date('H:i d/m/Y');?>
                        </td>
                    </tr>
                    <tr>
        				<td colspan="2"> 
                            <div style="width:100%; text-align:center;">
                                <font class="report_title specific" >[[.purpose_of_visit_report.]]<br /></font>
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
                                    <td>[[.from_date.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" autocomplete="off" onchange="changevalue()"/></td>
                                    <td>[[.to_date.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" autocomplete="off" onchange="changevalue()"/></td>
                                    <td>[[.gender.]]</td>
                                	<td><select name="gender_id" id="gender_id"></select></td>
                                    <td>[[.nationality.]]</td>
                                    <td><input name="nationality_name" type="text" id="nationality_name" autocomplete="off" onfocus="Autocomplete();"/> <input name="nationality_id" type="hidden" id="nationality_id"/></td>
                                    <td>[[.Segment.]]</td>
                                    <td><select name="reservation_types_id" id="reservation_types_id"></select></td>
                                    <td><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
                                </tr>
                            </table>
                        </form>
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
        <th class="report_table_header" style="width: 1%;">[[.stt.]]</th>
        <th class="report_table_header" style="width: 10%;">[[.guest_name.]]</th>
        <th class="report_table_header" style="width: 10%;">[[.guest_level.]]</th>
        <th class="report_table_header" style="width: 10%;">[[.nationality.]]</th>
        <th class="report_table_header" style="width: 10%;">[[.arrival_date.]]</th>
        <th class="report_table_header" style="width: 10%;">[[.departure_date.]]</th>
        <th class="report_table_header" style="width: 10%;">[[.Purpose_of_visit.]]</th>  
        <th class="report_table_header" style="width: 10%;">[[.Segment.]]</th> 
        <th class="report_table_header" style="width: 10%;">[[.age.]]</th>
        <th class="report_table_header" style="width: 9%;">[[.gender.]]</th>
        <th class="report_table_header" style="width: 10%;">[[.expense.]]</th>
        <th class="report_table_header" style="width: 10%;">[[.address.]]</th>
    </tr>
    <?php $stt=1;?>
    <!--LIST:items-->
    <tr>
        <td><?php echo $stt++;?></td>
        <td style="text-align: left;"><span onclick="GetLinkedit([[|items.traveller_id|]])"><a>[[|items.full_name|]]</a></span></td>
        <td style="text-align: left;">[[|items.guest_level|]]</td>
        <td style="text-align: left;">[[|items.name_1|]]</td>
        <td>[[|items.arrival_date|]]</td>
        <td>[[|items.departure_date|]]</td>
        <td style="text-align: left;">[[|items.target_of_entry|]]</td>
        <td style="text-align: left;">[[|items.name|]]</td>
        <td>[[|items.age|]]</td>
        <td style="text-align: left;">[[|items.gender|]]</td>
        <td style="text-align: right;"><?php echo System::display_number([[=items.amount=]]);?></td>
        <td style="text-align: left;">[[|items.address|]]</td>
    </tr>
    <!--/LIST:items-->
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