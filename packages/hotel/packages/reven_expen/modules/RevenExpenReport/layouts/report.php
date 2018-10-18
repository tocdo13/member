<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="left" width="80%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.cashbook.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[.from_date.]] [[|from_day|]] [[.to_date.]] [[|to_day|]]
                            </span> 
                        </div>
                    </td>
                 </tr>	
    		</table>
        </td></tr>
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
                                    <td>
                                        <strong>[[.line_per_page.]]</strong><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right"/>
                                        <strong>[[.no_of_page.]]</strong><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/>
                                        <strong>[[.from_page.]]</strong><input name="from_page" type="text" id="start_page" value="[[|from_page|]]" size="4" maxlength="4" style="text-align:right"/>
                                        <strong>[[.from_date.]]</strong><input name="from_date" type="text" id="from_date" onchange="changevalue();" value="[[|from_day|]]" size="8" />
                                        <strong>[[.to_date.]]</strong><input name="to_date" type="text" id="to_date" value="[[|to_day|]]" onchange="changefromday();" size="8" />
                                        <strong>[[.portal.]]</strong><select name="portal_id" id="portal_id" ></select>
                                        <input type="submit" name="do_search" value="[[.report.]]"/>
                                        <script>
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
                                            function changefromday(){
                                                var myfromdate = $('from_date').value.split("/");
                                                var mytodate = $('to_date').value.split("/");
                                                if(myfromdate[2] > mytodate[2]){
                                                    $('from_date').value= $('to_date').value;
                                                }else{
                                                    if(myfromdate[1] > mytodate[1]){
                                                        $('from_date').value = $('to_date').value;
                                                    }else{
                                                        if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                                            $('from_date').value =$('to_date').value;
                                                        }
                                                    }
                                                }
                                            }
                                        </script>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>

<!---------REPORT----------->
<!--LIST:pages-->
    <table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" >
        <tr bgcolor="#EFEFEF" >
            <th rowspan="2" width="30px"  >[[.stt.]]</th>
            <th rowspan="2" width="80px" >[[.time.]]</th>
            <th colspan="<?php echo count($this->map['currencys']) ?>" >[[.revenue.]]</th>
            <th colspan="<?php echo count($this->map['currencys']) ?>" >[[.expen.]]</th>
            <th colspan="<?php echo count($this->map['currencys']) ?>" >[[.balance.]]</th>
            <th rowspan="2" width="210px" >[[.item.]]</th>
            <th rowspan="2" width="440px" >[[.note.]]</th>
            <th rowspan="2" >[[.member.]]</th>
        </tr>
        <tr bgcolor="#EFEFEF" >
            <!--LIST:currencys-->
                <th >[[|currencys.name|]]</th>
            <!--/LIST:currencys-->
            <!--LIST:currencys-->
                <th >[[|currencys.name|]]</th>
            <!--/LIST:currencys-->
            <!--LIST:currencys-->
                <th>[[|currencys.name|]]</th>
            <!--/LIST:currencys-->
        </tr>
    
        <?php if([[=pages.page=]] > [[=from_page=]]) {?>
        <tr >
            <th colspan="2" >[[.last_page_summary.]]</th>
            <!--LIST:currencys-->
                <th align="right" style="padding-right: 5px;" ><?php echo System::display_number($this->map['pages']['current']['start']['thu_'.[[=currencys.name=]]]); ?></th>
            <!--/LIST:currencys-->
            <!--LIST:currencys-->
                <th align="right" style="padding-right: 5px;" ><?php echo System::display_number($this->map['pages']['current']['start']['chi_'.[[=currencys.name=]]]); ?></th>
            <!--/LIST:currencys-->
            <!--LIST:currencys-->
                <th align="right" style="padding-right: 5px;" ><?php echo System::display_number($this->map['pages']['current']['start']['balance_'.[[=currencys.name=]]]); ?></th>
            <!--/LIST:currencys-->
            <th colspan="3" >&nbsp;</th>
        </tr>
        <?php } else{?>
        <tr>
            <th colspan="<?php echo count($this->map['currencys'])*2+2 ?>" width="170px" >Tồn đầu kỳ</th>
            <!--LIST:currencys-->
                <th width="85px" align="right" style="padding-right: 5px;" >
                    <?php echo System::display_number($this->map['begin_balance']['balance_'.[[=currencys.name=]]]); ?>
                </th>
            <!--/LIST:currencys-->
            <th colspan="3" >&nbsp;</th>
        </tr>
        <?php }?>
        <!--LIST:pages.items-->
            <tr>
                <td>[[|pages.items.stt|]]</td>
                <td>[[|pages.items.date_cf|]]</td>
                <!--LIST:currencys-->
                <td align="right" style="padding-right: 5px;" ><?php echo System::display_number($this->map['pages']['current']['items']['current']['thu_'.[[=currencys.name=]]]); ?></td>
                <!--/LIST:currencys-->
                <!--LIST:currencys-->
                <td align="right" style="padding-right: 5px;" ><?php echo System::display_number($this->map['pages']['current']['items']['current']['chi_'.[[=currencys.name=]]]); ?></td>
                <!--/LIST:currencys-->
                <!--LIST:currencys-->
                <td align="right" style="padding-right: 5px;" ><?php echo System::display_number($this->map['pages']['current']['items']['current']['balance_'.[[=currencys.name=]]]); ?></td>
                <!--/LIST:currencys-->
                <td align="left" style="padding-left: 10px;" >[[|pages.items.item_name|]]</td>
                <td align="left" style="padding-left: 10px;" >[[|pages.items.note|]]</td>
                <td align="left" style="padding-left: 5px;" >[[|pages.items.member_name|]]</td>
            </tr>
        <!--/LIST:pages.items-->
        
        <tr bgcolor="#EFEFEF" >
            <th colspan="2" ><?php if([[=pages.page=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></th>
            <!--LIST:currencys-->
            <th align="right" style="padding-right: 5px;" ><?php echo System::display_number($this->map['pages']['current']['end']['thu_'.[[=currencys.name=]]]); ?></th>
            <!--/LIST:currencys-->
            <!--LIST:currencys-->
            <th align="right" style="padding-right: 5px;" ><?php echo System::display_number($this->map['pages']['current']['end']['chi_'.[[=currencys.name=]]]); ?></th>
            <!--/LIST:currencys-->
            <!--LIST:currencys-->
            <th align="right" style="padding-right: 5px;" ><?php echo System::display_number($this->map['pages']['current']['end']['balance_'.[[=currencys.name=]]]); ?></th>
            <!--/LIST:currencys-->
            <th colspan="3" >&nbsp;</th>
        </tr>
    </table>
    <br />
    <table width ="100%" >
        <tr height="60px">
            <td colspan="<?php echo count($this->map['currencys'])*3+5;?>" valign="top" align="center" >
                [[.page.]] [[|pages.page|]]/[[|total_page|]]
            </td>
        </tr>
    </table>
<!--/LIST:pages-->

<!---------HEADER----------->
<table width="100%" style="font-family:'Times New Roman', Times, serif">
    <tr>
        <td></td>
        <td></td>
        <td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
    </tr>
    <tr valign="top">
        <td width="33%" align="center">[[.creator.]]</td>
        <td width="33%" align="center">[[.general_accountant.]]</td>
        <td width="33%" align="center">[[.director.]]</td>
    </tr>
</table>
<br /><br /><br /><br />
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