<?php 
				if((($this->map['page_no']==$this->map['start_page']) or $this->map['page_no']==0))
				{?>
<div class="report-bound" style=" page:land;">
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%">
			<tr valign="top">
                <td align="left" width="65%">
                    <strong><?php echo HOTEL_NAME;?></strong>
                    <br />
                    <?php echo HOTEL_ADDRESS;?>
                </td>
                <td align="right" nowrap width="35%">
                    <?php echo Portal::language('template_code');?>
                    <br />
                    <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                    <br />
                    <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
                </td>
			</tr>	
		</table>
		<h2 style="text-transform:uppercase;font-weight:bold;"><?php echo Portal::language('product_remain_report');?></h2>
        
        <!---------SEARCH----------->
        <table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
            <tr><td>
            	<link rel="stylesheet" href="skins/default/report.css"/>
            	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
                	<div>
                    	<table width="100%">
                            <tr><td >
                                <form name="SearchForm" method="post">
                                    <table style="margin: 0 auto;">
                                        <tr>
                                        	<td><?php echo Portal::language('line_per_page');?></td>
                                        	<td style="text-align: right;"><input name="line_per_page" type="text" id="line_per_page" value="<?php echo $this->map['line_per_page'];?>" size="4" maxlength="2" style="text-align:right"/></td>
                                            
                                            <td style="text-align: left;"><?php echo Portal::language('date');?></td>
                                        	<td style="text-align: right;"><input  name="date" id="date" class="date-input" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>"></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo Portal::language('no_of_page');?></td>
                                        	<td style="text-align: right;"><input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                        	
                                            <td style="text-align: left;"><?php echo Portal::language('to_date');?></td>
                                        	<td style="text-align: right;"><input  name="to_date" id="to_date" class="date-input" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo Portal::language('from_page');?></td>
                                        	<td style="text-align: right;"><input name="start_page" type="text" id="start_page" value="<?php echo $this->map['start_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                        	<td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            
                                        </tr>
                                    </table>
                                    
                                    <table style="margin: 0 auto;">
                            <tr>
                                <td style="text-align: left;"><?php echo Portal::language('hotel');?></td>
                                <td style="text-align: right;"><select  name="portal_id" id="portal_id" onchange="get_bar();"><?php
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
	</select></td>
                            </tr>
                            <tr>
                         		<td align="right"> <input  name="checked_all" id="checked_all" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('checked_all'));?>"></td> 
                                <td align="left"><b><label for="checked_all"><?php echo Portal::language('select_all_bar');?></label></b></td>
                         </tr>
                            <?php if(isset($this->map['bars']) and is_array($this->map['bars'])){ foreach($this->map['bars'] as $key1=>&$item1){if($key1!='current'){$this->map['bars']['current'] = &$item1;?>
                             <tr >
                             	<?php 
				if((Session::is_set('bar_id') and Session::get('bar_id')==$this->map['bars']['current']['id']))
				{?>
                                	<td align="right"> <input  name="bar_id_<?php echo $this->map['bars']['current']['id'];?>" value="<?php echo String::html_normalize(URL::get('bar_id_'.$this->map['bars']['current']['id'],$this->map['bars']['current']['id']));?>" id="bar_id_<?php echo $this->map['bars']['current']['id'];?>" class="check_box" checked="checked"  / type ="checkbox"></td>
                                 <?php }else{ ?>
                                  <td align="right"><input  name="bar_id_<?php echo $this->map['bars']['current']['id'];?>"  type="checkbox" value="<?php echo $this->map['bars']['current']['id'];?>" id="bar_id_<?php echo $this->map['bars']['current']['id'];?>" <?php if(isset($_REQUEST['bar_id_'.$this->map['bars']['current']['id']])) echo 'checked="checked"' ?> class="check_box"  /></td>
                                
				<?php
				}
				?>
                                  <td><label for="bar_id_<?php echo $this->map['bars']['current']['id'];?>"><?php echo $this->map['bars']['current']['name'];?></label></td>
                    		 </tr>
                             <?php }}unset($this->map['bars']['current']);} ?>
                            
                            <tr>
                                <td colspan="2" style="text-align: right;"><input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/></td>
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
    </td>
</tr>
</table>
</div>

				<?php
				}
				?>     
<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>
<script>
    jQuery('#status').val('<?php echo Url::get('status'); ?>');
    jQuery('.date-input').datepicker(); 
    function get_bar()
    {
        //SearchForm.submit(); 
    }

    function changevalue()
    {
        var myfromdate = $('date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date").val(jQuery("#to_date").val());
        }
    }
    
    jQuery("#checked_all").click(function (){
       // console.log("!!");
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
    });
</script>
