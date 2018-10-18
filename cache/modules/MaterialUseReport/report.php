<style>
.simple-layout-middle{
		width:100%;	
	}
@media print
{
    .search{display: none;}
}
</style>
<?php 
				if((($this->map['page_no']==$this->map['start_page']) or $this->map['page_no']==0))
				{?>
<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr>
                    <td align="left" width="35%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
    				<td></td>
                    <td align="right" style="padding-right:10px;" >
                        <strong><?php echo Portal::language('template_code');?></strong>
                        
                        <br />
                        <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
                    </td>
    			</tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('material_used_report');?><br /><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                <?php echo Portal::language('from_date');?>&nbsp;<?php echo $this->map['from_date'];?> &nbsp;<?php echo Portal::language('to_date');?>&nbsp;<?php echo $this->map['to_date'];?>
                            </span> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;"><div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="<?php echo Portal::language('export');?>" style="width: 50px;"  /></div></td>
                </tr>	
    		</table>
        </td></tr>
    </table>		
</div>




<!---------SEARCH----------->
<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search" class="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td><?php echo Portal::language('line_per_page');?></td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="<?php echo $this->map['line_per_page'];?>" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td><?php echo Portal::language('no_of_page');?></td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td><?php echo Portal::language('from_page');?></td>
                                	<td><input name="start_page" type="text" id="start_page" value="<?php echo $this->map['start_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>|</td>
                                    <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td><?php echo Portal::language('hotel');?></td>
                                	<td><select  name="portal_id" id="portal_id" onchange="SearchForm.submit();"><?php
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
                                    <?php //}?>
                                    <td><?php echo Portal::language('category');?></td>
                                    <td><select  name="category_id" id="category_id" style="width: 100px;"><?php
					if(isset($this->map['category_id_list']))
					{
						foreach($this->map['category_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('category_id',isset($this->map['category_id'])?$this->map['category_id']:''))
                    echo "<script>$('category_id').value = \"".addslashes(URL::get('category_id',isset($this->map['category_id'])?$this->map['category_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    
                                    <td><?php echo Portal::language('from_date');?></td>
                                	<td><input  name="from_date" id="from_date" onchange="changevalue();" class="date"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                    <td><input  name="from_time" id="from_time" style="width: 50px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>"></td>
                                    <td><?php echo Portal::language('to_date');?></td>
                                    <td><input  name="to_time" id="to_time" style="width: 50px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>"></td>
                                	<td><input  name="to_date" id="to_date" onchange="changefromday();" class="date"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                                    <td><input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  " onclick="return check_bar()"/></td>
                                </tr>
                            </table>
                            <table style="margin: 0 auto;" >
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
                    		  </table>
                        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>


				<?php
				}
				?>

<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
.date{width: 70px!important;}
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
        jQuery('#from_time').mask("99:99");
        jQuery('#to_time').mask("99:99");
        
    }
);

jQuery("#checked_all").click(function ()
{
    var check  = this.checked;
    jQuery(".check_box").each(function()
    {
        this.checked = check;
    });
});
</script>



<?php 
				if((isset($this->map['items'])))
				{?>

<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" id="export">
    <tr valign="middle" bgcolor="#EFEFEF">
        <th class="report_table_header" style="width: 10px;"><?php echo Portal::language('stt');?></th>
        <th class="report_table_header" style="width: 50px;"><?php echo Portal::language('product_id');?></th>
        <th class="report_table_header" style="width: 80px;"><?php echo Portal::language('product_name');?></th>
        <th class="report_table_header" style="width: 50px;"><?php echo Portal::language('quantity_used');?></th>
        <th class="report_table_header" style="width: 80px;"><?php echo Portal::language('unit');?></th>
        <!--<th class="report_table_header" style="width: 80px;"><?php echo Portal::language('type');?></th>-->
    </tr>
    <?php $stt_product = 1; ?>
    <?php if(isset($this->map['category']) and is_array($this->map['category'])){ foreach($this->map['category'] as $key2=>&$item2){if($key2!='current'){$this->map['category']['current'] = &$item2;?>
        <tr style="background: #dddddd;">
            <td colspan="5" style="font-size: 15px; font-weight: bold;"><?php echo Portal::language('category');?>: <?php echo $this->map['category']['current']['name'];?></td>
        </tr>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current'] = &$item3;?>
            <?php if($this->map['category']['current']['id']==$this->map['items']['current']['category_id']){ ?>
    	<tr bgcolor="white">
    		<td nowrap="nowrap" valign="top" align="center" class="report_table_column"><?php echo $stt_product++; ?></td>
            <td nowrap align="center" class="report_table_column" ><?php echo $this->map['items']['current']['product_id'];?></td>
            <td nowrap align="left" class="report_table_column" ><?php echo $this->map['items']['current']['product_name'];?></td>
            <td nowrap align="center" class="report_table_column" ><?php echo $this->map['items']['current']['quantity'];?></td>
            <td nowrap align="center" class="report_table_column" ><?php echo $this->map['items']['current']['unit_name'];?></td>
            <!--<td nowrap align="center" class="report_table_column" ><?php echo $this->map['items']['current']['type'];?></td>-->
    	</tr>
            <?php } ?>
    	<?php }}unset($this->map['items']['current']);} ?>
    <?php }}unset($this->map['category']['current']);} ?>
</table>
<br/>
<center><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></center>
<br/>

 <?php }else{ ?>
<strong><?php echo Portal::language('no_data');?></strong>


				<?php
				}
				?>

<!---------FOOTER----------->


<?php 
				if(($this->map['real_page_no']==$this->map['real_total_page']))
				{?>

<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td ></td>
	<td > <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ><?php echo Portal::language('creator');?></td>
	<td width="33%" ><?php echo Portal::language('general_accountant');?></td>
	<td width="33%" ><?php echo Portal::language('director');?></td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>

				<?php
				}
				?>
<div style="page-break-before:always;page-break-after:always;"></div>
<script>
    //start:KID 
    function test_checked()
    {
        var check  = false;
        jQuery(".check_box").each(function (){

            if(this.checked)
                check = true;
        });
        return check;
    }
    
    function check_bar()
    {
        //start:KID them doan nay vao check valid time
        var hour_from = (jQuery("#from_time").val().split(':'));
        var hour_to = (jQuery("#to_time").val().split(':'));
        var date_from_arr = jQuery("#from_date").val();
        var date_to_arr = jQuery("#to_date").val();
        //end:KID them doan nay vao check valid time
        var validate = test_checked();
        if( validate)
        {
            //start:KID them doan nay vao check valid time
            if((date_from_arr == date_to_arr) && (to_numeric(hour_from[0]) > to_numeric(hour_to[0])))
            {
                alert('<?php echo Portal::language('start_time_longer_than_end_time_try_again');?>');
                return false;
            }
            else
            {
                if(to_numeric(hour_from[0]) >23 || to_numeric(hour_to[0]) >23 || to_numeric(hour_from[1]) >59 || to_numeric(hour_to[1]) >59)
                {
                    alert('<?php echo Portal::language('the_max_time_is_2359_try_again');?>');
                    return false;
                }
                else
                {  
                    return true;
                }
            }
            //end:KID them doan nay vao check valid time
        }
        else
        {
            alert('<?php echo Portal::language('you_must_choose_bar');?>');
            return false;

        }
    }
    //end:KID   
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
    jQuery('#export_repost').click(function(){
        
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>
