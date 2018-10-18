<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<style>
    * {
        margin: 0px;
        padding: 0px;
        font-size: 12px;
    }
    #chang_language {
        opacity: 0;
        transition: all 0.3s ease-out;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        -o-transition: all 0.3s ease-out;
    }
    #chang_language:hover {
        opacity: 1;
    }
    #button_print {
        width: 50px;
        height: 50px;
        position: fixed;
        top: 100px;
        right: 20px;
        background: #00B2F9;
        cursor: pointer;
        overflow: hidden;
        border: 5px solid #FFFFFF;
        border-radius: 50%;
        transition: all 0.5s ease-out;
        opacity: 1;
        -moz-transition:box-shadow .7s;
        -o-transition:box-shadow 2s;
        -webkit-transition:box-shadow 2s;
        transition:box-shadow 2s;
    }
    #button_print:hover {
        box-shadow:0 0 10px rgba(0,0,0,0.7) inset;
    }
    #button_print img {
        width:35px;
        height:35px;
        -moz-transition:-moz-transform 5s ease;
        -webkit-transition:-webkit-transform 5s ease;
        -o-transition:-o-transform 5s ease;
        transition:transform 5s ease;
        position:absolute;
        z-index:-1;
        top:7.5px;
        left:7.5px;
    }
    #button_print:hover img {
        -moz-transform:scale(1.1) rotate(10deg);
        -webkit-transform:scale(1.1) rotate(10deg);
        -o-transform:scale(1.1) rotate(10deg);
        transform:scale(1.1) rotate(10deg);
    }
    #search {
        width: 99%;
        margin: 5px auto;
        transition: all 0.5s ease-out;
    }
    #search form fieldset {
        border: 1px dashed #555555;
        border-radius: 10px;
    }
    #search form fieldset legend {
        color: #171717;
        line-height: 20px;
        font-weight: normal;
        text-transform: uppercase;
        padding: 2px;
        border: 1px dashed #555555;
        border-radius: 10px;
    }
</style>
<!--IF:first_page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->
<link rel="stylesheet" href="skins/default/report.css"/>

<!--/IF:first_page-->
<div id="print">
    <!--IF:first_page([[=page_no=]]==[[=start_page=]])-->
    <div id="button_print" onclick="jQuery('#popup_confirm').css('top','0%');">
        <img src="packages/core/skins/default/images/printer.png" title="In" />
    </div><!-- end button_print -->
    
    <div id="header">
        <table style="width: 99%; margin: 0px auto;">
            <tr>
                <td style="text-align: left;">
                    <h3><?php echo HOTEL_NAME; ?></h3>
                    <p><?php echo HOTEL_ADDRESS; ?></p>
                </td>
                <td style="text-align: right;">
                    <p>[[.print_date.]]: <?php echo date('H:i d/m/Y'); ?></p>
                    <p>[[.print_user.]]: <?php echo User::id(); ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <h1 style="font-size: 20px; line-height: 30px; font-weight: normal; text-transform: uppercase;">[[.in_house_guest_list.]]</h1>
                    <p><?php if(isset([[=from_date=]]) AND isset([[=to_date=]])){ ?>[[.from_date.]]: [[|from_date|]] [[.to_date.]] [[|to_date|]] <?php } ?></p>
                    <p><?php if((isset([[=status=]]))){ ?> [[.status.]]: [[|status|]] <?php } ?></p>
                </td>
            </tr>
        </table>
    </div><!-- end header -->
    
    <div id="search">
        <form name="InHouseGuestListForm" method="post">
            <fieldset>
                <legend>[[.option_search.]]</legend>
                <table style="width: 100%;">
                    <tr>
                        <td>[[.hotel.]]: <select name="portal_id" id="portal_id" style="width: 80px; height: 20px;"></select></td>
                        <td>[[.from_date.]]: <input name="from_date" type="text" id="from_date" style="width: 80px; height: 20px;" onchange="fun_check_date();" /></td>
                        <td id="td_to_date">[[.to_date.]]: <input name="to_date" type="text" id="to_date" style="width: 80px; height: 20px;" onchange="fun_check_date();" /></td>
                        <td>[[.status.]]: <select name="status" style="width: 180px; height: 20px;" id="status" onchange="fun_check_date();"></select></td>
                        <td>[[.line_per_page.]]: <input name="line_per_page" type="text" id="line_per_page" style="width: 20px; height: 20px;" /></td>
                        <td>[[.no_of_page.]]: <input name="no_of_page" type="text" id="no_of_page" style="width: 20px; height: 20px;" /></td>
                        <td>[[.start_page.]]: <input name="start_page" type="text" id="start_page" style="width: 20px; height: 20px;" /></td>
                        <td><input name="do_search" type="submit" value="[[.search.]]" style="padding: 5px; border: 1px solid #CCCCCC; background: #FFFFFF;" /></td>
                        <td><button id="export" style="padding: 5px; border: 1px solid #CCCCCC; background: #FFFFFF;">[[.export.]]</button></td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div><!-- end search -->
    <!--/IF:first_page-->
    <?php if(!isset([[=no_recode=]])){ ?>
    <table id="tblExport" cellpadding="2" cellspacing="0" width="99%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse; margin: 0px auto;">
    	<tr valign="middle" bgcolor="#EFEFEF">
    		<th width="20px" align="center" class="report_table_header">[[.stt.]]</th>
    		<th class="report_table_header">[[.company_name.]]</th>
            <th class="report_table_header" width="15px">[[.booking_code.]]</th>
            <th class="report_table_header" width="15px">[[.re_code.]]</th>
    		<th class="report_table_header">[[.traveller_name.]]</th>
    		<th class="report_table_header">[[.status.]]</th>
    		<th class="report_table_header">[[.gender.]]</th>
    		<th class="report_table_header">[[.birth_date.]]</th>
    		<th class="report_table_header">[[.nationality.]]</th>
    		<th class="report_table_header">[[.passport.]]</th>
    		<th class="report_table_header">[[.room.]]</th>
            <th class="report_table_header">[[.num_traveller.]]</th>
        <th class="report_table_header price">[[.room_price.]]</th>
            <th class="report_table_header">[[.extra_bed.]]</th>
    		<!--IF:status(!URL::get('status'))-->
    		<th class="report_table_header">[[.status.]]</th>
    		<!--/IF:status-->
    		<!--IF:price([[=price=]]==1)-->
    	  <th class="report_table_header">[[.price.]]<br/></th>
    		<!--/IF:price-->
            <th class="report_table_header">[[.hour_in.]]</th>
    		<th class="report_table_header">[[.arrival_date.]]</th>
            <th class="report_table_header">[[.hour_out.]]</th>
    		<th class="report_table_header">[[.departure_date.]]</th>
            <th class="report_table_header note" style="width: 250px;">[[.note.]]</th>
        </tr>
    <!--start: KID  1
    <!--IF:first_pages([[=page_no=]]!=1)-->
    <!---------LAST GROUP VALUE----------->	        
        <tr>
            <td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
    		<td align="center" class="report_table_column" width="130"><strong><?php echo System::display_number([[=last_group_function_params=]]['guest_count']);?></strong></td>
    		<td colspan="5" align="center" class="report_table_column" width="30">&nbsp;</td>
    		<td align="center" class="report_table_column" width="1%"><strong><?php echo System::display_number([[=last_group_function_params=]]['room_count']);?></strong></td>
            <td colspan="2" align="center" class="report_table_column" width="30">&nbsp;</td>
            <td></td>
    		<!--IF:status(!URL::get('status'))-->
    		<td>&nbsp;</td>
    		<!--/IF:status-->
    		<!--IF:price([[=price=]]==1)-->
    		<td align="center" class="report_table_column">
    			<strong><?php echo System::display_number([[=last_group_function_params=]]['total_price']);?></strong>		</td>
    		<!--/IF:price-->		
		<td colspan="4"></td>
            
        </tr>
    <!--/IF:first_pages-->
    <!--end:KID--> 
        <?php 
            $r_id = '';
            $rr_id = '';
            $i = 1;
            $total_traveller=0;
         ?>
    	<!--LIST:items-->
    	<tr bgcolor="white">
            
            <?php if($r_id!=[[=items.reservation_id=]])
            { 
                $r_id=[[=items.reservation_id=]]; 
                $rowspan = $this->map['count'][$r_id]['count'];
                //echo $rowspan;
            ?>
    		<td align="center" valign="middle" class="report_table_column" rowspan="<?php echo $rowspan; ?>"><?php echo $i++;?></td>
    		<td align="left" valign="middle" class="report_table_column" rowspan="<?php echo $rowspan; ?>"><div style="float:left;width:80px;">[[|items.customer_name|]]</div></td>
            <td align="left" class="report_table_column" style="width:15px !important;" rowspan="<?php echo $rowspan; ?>">[[|items.booking_code|]]</td>
            <td align="left" class="report_table_column" style="width:15px !important;" rowspan="<?php echo $rowspan; ?>"><a href="?page=reservation&cmd=edit&id=[[|items.reservation_id|]]&r_r_id=[[|items.reservation_room_id|]]" target="_blank">[[|items.reservation_id|]]</a></td>
            <?php } ?>
            <td align="left" class="report_table_column" width="130">
    			[[|items.first_name|]]
    		[[|items.last_name|]]</td>
    		<td align="center" class="report_table_column" width="30">[[|items.status|]] </td>
    		<td align="center" class="report_table_column" width="30">
    			[[|items.gender|]]		</td>
    		<td  align="center" class="report_table_column">[[|items.birth_date|]]</td>
    		<td align="center" class="report_table_column" title="[[|items.nationality_name|]]">
    			[[|items.nationality_code|]]</td>
    		<td align="center" class="report_table_column">[[|items.passport|]]</td>
    		<?php 
            if([[=items.reservation_id=]].'-'.[[=items.reservation_room_id=]]!=$r_id.'-'.$rr_id)
            {
                $rr_id = [[=items.reservation_room_id=]];
                $rowspan_1 = $this->map['count'][$r_id][$rr_id];
            ?>
            <td align="center" class="report_table_column" rowspan="<?php echo $rowspan_1; ?>">[[|items.room_name|]]</td>
            <td align="right" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>"><?php echo System::display_number([[=items.num_traveller=]]);$total_traveller+=[[=items.num_traveller=]]; ?></td>
            <td align="right" class="report_table_column price"rowspan="<?php echo $rowspan_1; ?>"><?php echo System::display_number([[=items.room_price=]]);?></td>
    		<td align="right" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">[[|items.extra_bed|]]</td>
            <!--IF:status(!URL::get('status'))-->
    		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">[[|items.status|]]</td>
    		<!--/IF:status-->
    		<!--IF:price([[=price=]]==1)-->
    		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
    		<!--IF:cond([[=items.price=]])-->
    			[[|items.price|]] 
    		<!--/IF:cond-->		</td>
    		<!--/IF:price-->	
            <td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
    			<?php echo date('H:i',[[=items.time_in=]]);?></td>	
    		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
    			<?php echo date('d/m/Y',[[=items.time_in=]]);?></td>
            <td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
    			<?php echo date('H:i',[[=items.time_out=]]);?></td>    
    		<td align="center" class="report_table_column"rowspan="<?php echo $rowspan_1; ?>">
    			<?php echo date('d/m/Y',[[=items.time_out=]]);?></td>
            <td align="center" class="report_table_column note" rowspan="<?php echo $rowspan_1; ?>">[[|items.note|]]</td>
       <?php }?>
        </tr>
    	<!--/LIST:items-->
    	<tr bgcolor="white">
    		<td colspan="4" align="center" valign="middle" class="report_table_column"><strong><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
    		<td align="center" class="report_table_column" width="130"><strong><?php echo System::display_number([[=group_function_params=]]['guest_count']);?></strong></td>
    		<td colspan="5" align="center" class="report_table_column" width="30">&nbsp;</td>
    		<td align="center" class="report_table_column" width="1%"><strong><?php echo System::display_number([[=group_function_params=]]['room_count']);?></strong></td>
            <td align="right" class="report_table_column" width="30"><?php echo $total_traveller;?></td>
            <td align="center" class="report_table_column price">&nbsp;</td>
            <td align="center" class="report_table_column">&nbsp;</td>
            <td></td>
    		<!--IF:status(!URL::get('status'))-->
    		<td>&nbsp;</td>
    		<!--/IF:status-->
    		<!--IF:price([[=price=]]==1)-->
    		<td align="center" class="report_table_column">
    			<strong><?php echo System::display_number([[=group_function_params=]]['total_price']);?></strong>		</td>
    		<!--/IF:price-->		
    		<td colspan="3"></td>
            <td class="note"></td>
    	</tr>
    </table>
    
    <!--IF:page_no([[=page_no=]])--><p style="margin: 5px auto;">[[.page.]] [[|page_no|]]/[[|total_page|]]</p><!--/IF:page_no--><br/>
    <?php } else { ?>
        <div id="no_recode" style="width: 300px; text-align: center; line-height: 30px; font-size: 20px; border: 1px dashed #171717; margin: 20px auto;">
            [[.no_recode.]]
        </div>
    <?php } ?>
    <!--IF:first_page([[=real_page_no=]]==[[=real_total_page=]])-->
    <div id="footer">
    <table style="width: 99%; margin: 0px auto;">
        <tr>
            <td style="width: 33%;"></td>
            <td></td>
            <td style="width: 30%; text-align: center;">
                [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?> <br />
                [[.creator.]]
                <br />
                <br />
                <br />
            </td>
        </tr>
    </table>
    </div><!-- end footer -->
    <!--/IF:first_page-->
</div>
<div id="popup_confirm" style="width: 100%; height: 100%; background: rgba(0,0,0,0.7); position: fixed; top: -100%; left: 0px; transition: all 0.5s ease-out;">
    <div style="background: #ffffff; box-shadow: 0px 0px 5px #000000; width: 320px; height: 200px; margin: 100px auto; border-radius: 5px; position: relative;">
        <div style="position: absolute; top: -20px; right: -20px; width: 30px; height: 30px; text-align: center; line-height: 30px; font-size: 30px; border-radius: 50%; background: #000000; color: #ffffff; border: 2px solid red; cursor: pointer;" onclick="jQuery('#popup_confirm').css('top','-100%');">X</div>
        <p>[[.are_you_print_price_or_note.]]</p>
        <br />
        <p>
            <input type="checkbox" name="print_price" id="print_price" />[[.confirm_price.]]
        </p>
        <p>
            <input type="checkbox" name="print_note" id="print_note" />[[.confirm_note.]]
        </p>
        <p>
            <input type="button" value="print" onclick="fun_print_report()" />
        </p>
    </div>
</div>
<script>
    jQuery(document).ready(
        function()
        {
            jQuery("#export").click(function () {
                jQuery("#tblExport").battatech_excelexport({
                    containerid: "tblExport"
                   , datatype: 'table'
                });
            });
        }
    );
    function fun_print_report()
    {
        if(document.getElementById("print_price").checked==false)
        {
            jQuery(".price").css('display','none');
        }
        if(document.getElementById("print_note").checked==false)
        {
            jQuery(".note").css('display','none');
        }
        jQuery("#search").css('display','none');
        jQuery("#button_print").css('display','none');
        jQuery('#popup_confirm').css('top','-100%');
        var user ='<?php echo User::id(); ?>';
        printWebPart('printer',user);
        location.reload();
    }
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    fun_check_date();    
    function fun_check_date()
    {
        if(jQuery("#status").val()=='IN_HOUSE')
        {
            jQuery("#td_to_date").css('opacity','0');
        }
        else
        {
            jQuery("#td_to_date").css('opacity','1');
            var date_from = jQuery("#from_date").val().split("/"); 
            var date_to = jQuery("#to_date").val().split("/");
            arr_date = new Date(date_from[2],date_from[1],date_from[0]);
            dep_date = new Date(date_to[2],date_to[1],date_to[0]);
            if(arr_date>dep_date)
            {
                alert("ngày đi phải nhỏ hơn ngày đến");
                jQuery("#to_date").val(jQuery("#from_date").val());
            }
        }
    }
    //setInterval(function(){ jQuery("#button_print").css("transform","rotate(360deg)"); }, 3000);
</script>