<!------------------------------ HEADER ---------------------------------->
<script src="packages/core/includes/js/jquery/datepicker.js"></script>
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
                    
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title email_report">[[.email_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                
                            </span> 
                        </div>
                    </td>
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
td.status_error a {border: none; text-decoration: none; }
.email_report {font-size: 19px !important;}
td {
    height:24px !important;
}
@media print {
   #email_report {
    display: none;
   } 
}
.toggle_button{
    margin:0 !important;padding: 0 !important; color:red; border:none !important; height: 24px !important; width: 60px !important; background: #4d90fe; color:#fff; cursor: pointer;
}
.folio_pointer:hover {
    cursor: pointer;
}
/*.status_error:hover .toggle_button{display: block;}
.status_error:hover .toggle_span{display: none;} */
</style>

<!------------------------------ SEARCH ---------------------------------->
<form method="POST" name="email_report" id="email_report">
    <div id="search_email" style="width: 100%; margin: 0 auto;">
        <label>[[.from_date.]]</label><input name="date_from" type="text" id="date_from" onchange="changevalue();" />
        <label>[[.to_date.]]</label><input name="date_to" type="text" id="date_to" onchange="changefromday();" />
        <label>[[.type_email.]]</label><select name="type_mail" id="type_mail"></select>
        <label>[[.email_status.]]</label><select name="email_status" id="email_status"></select>
        <label>[[.portal.]]</label><select name="portal_id" id="portal_id"></select>
        <input name="search" type="submit" id="search" value="search" />
    </div>

<!------------------------------ REPORT ---------------------------------->
<table style="width: 100%; margin: 0 auto;"  cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" class="table-bound">
     <tr bgcolor="#EFEFEF">
        <td rowspan="2">[[.STT.]]</td>
        <td colspan="2">[[.type_folio.]]</td>
        <td rowspan="2">[[.email.]]</td>
        <td rowspan="2">[[.traveller_name.]]</td>
        <td rowspan="2">[[.number_phone.]]</td>
        <td rowspan="2">[[.create_date_invoice.]]</td>
        <td rowspan="2" style="width: 10%;">[[.status.]]</td>
     </tr>
     <tr bgcolor="#EFEFEF">
        <td>[[.folio.]]</td>
        <td>[[.folio_group.]]</td>
     </tr>
     <?php $k=1 ?>     
<!--LIST:folio-->     
     <tr>
        <td><?php echo $k++ ?></td>
        
        <td class="folio_pointer"  onclick="Openwindownfolio(<?php echo [[=folio.traveller_id=]];?>,<?php if([[=folio.type_folio=]]==0) echo  [[=folio.id=]];?>)"><?php if([[=folio.type_folio=]]==0) echo  [[=folio.id=]]; ?></td>
        <td class="folio_pointer" <?php if([[=folio.type_folio=]]==1){ ?> onclick="OpenWindownGroupfolio(<?php echo [[=folio.customer_id=]];?>,<?php echo [[=folio.reservation_id=]];?>,<?php echo [[=folio.id=]]; ?>);"  <?php } ?>><?php if([[=folio.type_folio=]]==1) echo  [[=folio.id=]];?></td>
        <td id="email_<?php echo [[=folio.id=]] ?>">[[|folio.email|]]</td>
        <td><a target="_blank" href="?page=traveller&cmd=edit&id=<?php echo [[=folio.travellerid=]];?>">[[|folio.fullname|]]</a></td>
        <td>[[|folio.phone|]]</td>
        <td><?php echo date('d/m/Y H:i',[[=folio.create_time_2=]]);?></td>
        
        <?php if([[=folio.check_send_mail=]]==0){ ?>
            <td class="status_pending"><?php  echo Portal::language('pending') ?></td>
            <?php } if([[=folio.check_send_mail=]]==1){ ?>
            <td class="status_sent"><?php  echo Portal::language('sent') ?></td>
            <?php } if([[=folio.check_send_mail=]]==2){ ?>
            <td class="status_error" align="center">
                <span id="error_<?php echo [[=folio.id=]] ?>" class="toggle_span" style="color: red;"><?php  echo Portal::language('error') ?></span>
                <a class="status_a" style="display: ;" id="status_<?php echo [[=folio.id=]]; ?>"
                    <?php if([[=folio.type_folio=]]!=1)
                          {
                    ?>
                        href="?page=report_email&cmd=send_mail_invoice&folio_id=<?php echo [[=folio.id=]];?>&traveller_id=<?php echo [[=folio.traveller_id=]];?>&email=<?php echo [[=folio.email=]]; ?>"
                    <?php            
                          }
                          else
                          {
                    ?>  
                        href="?page=report_email&cmd=send_mail_invoice&folio_id=<?php echo [[=folio.id=]];?>&reservation_id=<?php echo [[=folio.reservation_id=]];?>&email=<?php echo [[=folio.email=]]; ?>"      
                    <?php        
                          }  
                     ?>
                        href="?page=report_email&cmd=send_mail_invoice&folio_id=<?php echo [[=folio.id=]];?>&traveller_id=<?php echo [[=folio.traveller_id=]];?>&email=<?php echo [[=folio.email=]]; ?>">
                     <input class="toggle_button" type="button" name="invoice_send" value="[[.resent.]]" onclick="validate_check_email(email='<?php echo [[=folio.email=]]; ?>')"/>
                       
                </a>
                <span style="display: none;" class="error">error</span>
            </td>
        <?php } ?>
     </tr>
<!--/LIST:folio-->  

</table>
</form>
<script type="text/javascript">
    var str = window.location.href;
    var result = str.match(/send_mail_invoice/);
    if(result != null)
    {
        window.location.assign('?page=report_email');
    }
    function validate_check_email(email)
    {
        var traveller_email = email;
        if(traveller_email=='')
        {
           alert('[[.email_not_null.]]');
        }
        else
        {
            
        }
               
    }
    function Openwindownfolio(traveller_id,folio_id)
    {
         openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo 431;?>&traveller_id='+traveller_id+'&folio_id='+folio_id,Array('','[[.folio.]]','80','210','950','500'));     
    }
    
    function OpenWindownGroupfolio(customer_id,reservation_id,folio_id)
    {
         openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo 431;?>&cmd=group_invoice&customer_id='+customer_id+'&reservation_id='+reservation_id+'&folio_id='+folio_id,Array('','[[.group_folio.]]','80','210','950','500'));     
    }
    
        
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
    
	jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();

    var folio_js =<?php echo String::array2js([[=folio=]]); ?>; 
    for(var folio_item in folio_js)
    {
        if(jQuery('#email_'+folio_item).html()=='')
        {
            jQuery('#status_'+folio_item+' .toggle_button').css('display','none');   
        }
    }
</script>
<style>
    .status_error:hover .toggle_span { display: none;}
</style>



