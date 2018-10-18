<style>
/*full m�n h�nh*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="right" style="padding-right:10px;" >
                        <strong><?php echo Portal::language('template_code');?></strong>
                        
                        <br />
                        <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('statisfy_guest_nationality');?><br /></font>
                            <label style="font-size: 14px;">(<?php echo Portal::language('attach_form');?>)<br /></label>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                <?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['from_date'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['to_date'];?>
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
                                   
                                    <td><?php echo Portal::language('hotel');?>: <select  name="portal_id" id="portal_id"><?php
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
                                    <td>|</td>
                                    <td><?php echo Portal::language('from');?></td>
                                	<td><input  name="from_date" id="from_date" style="width: 80px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                    <td><?php echo Portal::language('to');?></td>
                                	<td><input  name="to_date" id="to_date" style="width: 80px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
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
        jQuery("#from_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>,1 - 1, 1)});
    	jQuery("#to_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1)});
    }
);
</script>



<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">

	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="250px" rowspan="2"><?php echo Portal::language('nationality');?></th>
        <th class="report_table_header" width="250px" rowspan="2"><?php echo Portal::language('total_of_guest');?><br />(<?php echo Portal::language('count');?>)</th>
        <th class="report_table_header" width="250px" rowspan="2"><?php echo Portal::language('male');?><br />(M)</th>
        <th class="report_table_header" width="250px" rowspan="2"><?php echo Portal::language('female');?><br />(F)</th>
        <th class="report_table_header" width="250px" colspan="3"><?php echo Portal::language('in_which');?></th>
    </tr>
    <tr>
        <th class="report_table_header" width="250px"><?php echo Portal::language('foreign_visitor');?></th>
        <th class="report_table_header" width="250px"><?php echo Portal::language('foreign_vietnamese');?></th>
        <th class="report_table_header" width="250px"><?php echo Portal::language('vietnamese_1');?></th>
    </tr>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr>
        <td><?php if($this->map['items']['current']['country_name']!='country_other'){?><?php echo $this->map['items']['current']['country_name'];?><?php }else{?> <?php echo Portal::language('country_other');?> <?php } ?></td>
        <td><?php echo $this->map['items']['current']['count_traveller'];?></td>
        <td><?php echo $this->map['items']['current']['count_male'];?></td>
        <td><?php echo $this->map['items']['current']['count_famale'];?></td>
        <td><?php echo $this->map['items']['current']['count_visitor'];?></td>
        <td><?php echo $this->map['items']['current']['count_visitor_vietnam'];?></td>
        <td><?php echo $this->map['items']['current']['count_vietnam'];?></td>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
    <tr>
        <th><?php echo Portal::language('total');?></th>
        <th><?php echo $this->map['total_traveller'];?></th>
        <th><?php echo $this->map['total_male'];?></th>
        <th><?php echo $this->map['total_famale'];?></th>
        <th><?php echo $this->map['total_visitor'];?></th>
        <th><?php echo $this->map['total_visitor_vietnam'];?></th>
        <th><?php echo $this->map['total_vietnam'];?></th>
    </tr>
<!---------FOOTER----------->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td ></td>
	<td align="center" > <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ></td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center"><strong><?php echo Portal::language('representative');?></strong><br /><em>(<?php echo Portal::language('signature_stamped');?>)</em></td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>