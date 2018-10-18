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
                            <font class="report_title email_report"><?php echo Portal::language('email_report');?><br /></font>
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
        <label><?php echo Portal::language('from_date');?></label><input  name="date_from" id="date_from" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>">
        <label><?php echo Portal::language('to_date');?></label><input  name="date_to" id="date_to" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>">
        <label><?php echo Portal::language('type_email');?></label><select  name="type_mail" id="type_mail"><?php
					if(isset($this->map['type_mail_list']))
					{
						foreach($this->map['type_mail_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('type_mail',isset($this->map['type_mail'])?$this->map['type_mail']:''))
                    echo "<script>$('type_mail').value = \"".addslashes(URL::get('type_mail',isset($this->map['type_mail'])?$this->map['type_mail']:''))."\";</script>";
                    ?>
	</select>
        <label><?php echo Portal::language('email_status');?></label><select  name="email_status" id="email_status"><?php
					if(isset($this->map['email_status_list']))
					{
						foreach($this->map['email_status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('email_status',isset($this->map['email_status'])?$this->map['email_status']:''))
                    echo "<script>$('email_status').value = \"".addslashes(URL::get('email_status',isset($this->map['email_status'])?$this->map['email_status']:''))."\";</script>";
                    ?>
	</select>
        <label><?php echo Portal::language('portal');?></label><select  name="portal_id" id="portal_id"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select>
        <input  name="search" id="search" value="search" / type ="submit" value="<?php echo String::html_normalize(URL::get('search'));?>">
    </div>

<!------------------------------ REPORT ---------------------------------->
<table style="width: 100%; margin: 0 auto;"  cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" class="table-bound">
     <tr bgcolor="#EFEFEF">
        <td rowspan="2"><?php echo Portal::language('STT');?></td>
        <td colspan="2"><?php echo Portal::language('type_folio');?></td>
        <td rowspan="2"><?php echo Portal::language('email');?></td>
        <td rowspan="2"><?php echo Portal::language('traveller_name');?></td>
        <td rowspan="2"><?php echo Portal::language('number_phone');?></td>
        <td rowspan="2"><?php echo Portal::language('create_date_invoice');?></td>
        <td rowspan="2" style="width: 10%;"><?php echo Portal::language('status');?></td>
     </tr>
     <tr bgcolor="#EFEFEF">
        <td><?php echo Portal::language('folio');?></td>
        <td><?php echo Portal::language('folio_group');?></td>
     </tr>
     <?php $k=1 ?>     
<?php if(isset($this->map['folio']) and is_array($this->map['folio'])){ foreach($this->map['folio'] as $key1=>&$item1){if($key1!='current'){$this->map['folio']['current'] = &$item1;?>     
     <tr>
        <td><?php echo $k++ ?></td>
        
        <td class="folio_pointer"  onclick="Openwindownfolio(<?php echo $this->map['folio']['current']['traveller_id'];?>,<?php if($this->map['folio']['current']['type_folio']==0) echo  $this->map['folio']['current']['id'];?>)"><?php if($this->map['folio']['current']['type_folio']==0) echo  $this->map['folio']['current']['id']; ?></td>
        <td class="folio_pointer" <?php if($this->map['folio']['current']['type_folio']==1){ ?> onclick="OpenWindownGroupfolio(<?php echo $this->map['folio']['current']['customer_id'];?>,<?php echo $this->map['folio']['current']['reservation_id'];?>,<?php echo $this->map['folio']['current']['id']; ?>);"  <?php } ?>><?php if($this->map['folio']['current']['type_folio']==1) echo  $this->map['folio']['current']['id'];?></td>
        <td id="email_<?php echo $this->map['folio']['current']['id'] ?>"><?php echo $this->map['folio']['current']['email'];?></td>
        <td><a target="_blank" href="?page=traveller&cmd=edit&id=<?php echo $this->map['folio']['current']['travellerid'];?>"><?php echo $this->map['folio']['current']['fullname'];?></a></td>
        <td><?php echo $this->map['folio']['current']['phone'];?></td>
        <td><?php echo date('d/m/Y H:i',$this->map['folio']['current']['create_time_2']);?></td>
        
        <?php if($this->map['folio']['current']['check_send_mail']==0){ ?>
            <td class="status_pending"><?php  echo Portal::language('pending') ?></td>
            <?php } if($this->map['folio']['current']['check_send_mail']==1){ ?>
            <td class="status_sent"><?php  echo Portal::language('sent') ?></td>
            <?php } if($this->map['folio']['current']['check_send_mail']==2){ ?>
            <td class="status_error" align="center">
                <span id="error_<?php echo $this->map['folio']['current']['id'] ?>" class="toggle_span" style="color: red;"><?php  echo Portal::language('error') ?></span>
                <a class="status_a" style="display: ;" id="status_<?php echo $this->map['folio']['current']['id']; ?>"
                    <?php if($this->map['folio']['current']['type_folio']!=1)
                          {
                    ?>
                        href="?page=report_email&cmd=send_mail_invoice&folio_id=<?php echo $this->map['folio']['current']['id'];?>&traveller_id=<?php echo $this->map['folio']['current']['traveller_id'];?>&email=<?php echo $this->map['folio']['current']['email']; ?>"
                    <?php            
                          }
                          else
                          {
                    ?>  
                        href="?page=report_email&cmd=send_mail_invoice&folio_id=<?php echo $this->map['folio']['current']['id'];?>&reservation_id=<?php echo $this->map['folio']['current']['reservation_id'];?>&email=<?php echo $this->map['folio']['current']['email']; ?>"      
                    <?php        
                          }  
                     ?>
                        href="?page=report_email&cmd=send_mail_invoice&folio_id=<?php echo $this->map['folio']['current']['id'];?>&traveller_id=<?php echo $this->map['folio']['current']['traveller_id'];?>&email=<?php echo $this->map['folio']['current']['email']; ?>">
                     <input class="toggle_button" type="button" name="invoice_send" value="<?php echo Portal::language('resent');?>" onclick="validate_check_email(email='<?php echo $this->map['folio']['current']['email']; ?>')"/>
                       
                </a>
                <span style="display: none;" class="error">error</span>
            </td>
        <?php } ?>
     </tr>
<?php }}unset($this->map['folio']['current']);} ?>  

</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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
           alert('<?php echo Portal::language('email_not_null');?>');
        }
        else
        {
            
        }
               
    }
    function Openwindownfolio(traveller_id,folio_id)
    {
         openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo 431;?>&traveller_id='+traveller_id+'&folio_id='+folio_id,Array('','<?php echo Portal::language('folio');?>','80','210','950','500'));     
    }
    
    function OpenWindownGroupfolio(customer_id,reservation_id,folio_id)
    {
         openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=<?php echo 431;?>&cmd=group_invoice&customer_id='+customer_id+'&reservation_id='+reservation_id+'&folio_id='+folio_id,Array('','<?php echo Portal::language('group_folio');?>','80','210','950','500'));     
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

    var folio_js =<?php echo String::array2js($this->map['folio']); ?>; 
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



