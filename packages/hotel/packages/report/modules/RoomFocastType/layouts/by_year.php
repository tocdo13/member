<script>
jQuery(document).ready(function(){
	jQuery('#from_date').datepicker();
	jQuery('#to_date').datepicker();
 });
</script>
<div style="width:100%;overflow:auto">
<table cellSpacing=0 width="100%">
<tr>
<td>
<p>
<form name="OccupancyHodingReport" method="post">
<table cellSpacing=0 width="100%">
	<tr valign="top">
	  <td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />
		[[.address.]]: <?php echo HOTEL_ADDRESS;?><br />
          </td>
		<td align="right" nowrap width="15%" style="padding-right:20px;">
		<strong>[[.template_code.]]</strong><br />
		<i>[[.promulgation.]]</i>		</td>
	</tr>	
	<tr>
	<td align="center" colspan="2" style="font-size:18px;"><strong>[[.room_focast_type.]]</strong></td>
    </tr>
    <tr>
        <td>
            <span class="by-year">[[.by_year.]]</span>
            <select name="year" id="year" style="width:60px;" class="by-year">
            <?php
            	for($i=date('Y')+5;$i>=BEGINNING_YEAR;$i--)
            	{
            		echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')) + 1)?' selected':'').'>'.$i.'</option>';
            	}
            ?>
            </select>
            <input name="view_result_2"  value="[[.view.]]" type="submit" id="view_result_2"/>
        </td>
    </tr>
</table>
</form>
</p>
<?php $current_year = date('y');?>
<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="black" style="border-collapse:collapse" style="font-size:12px;">
    <tr>
        <th align="center" style="background-color:#CCCCCC; font-size:18px;">[[.room_type.]]</th>
        <th>[[.sum.]]</th>
        <th>[[.prev_year.]]</th>
        <th>[[.current_year.]]</th>
        <th>[[.next_year.]]</th>
    </tr>
    <?php $total_room = 0;?>
    <!--LIST:months-->
        <tr>
        <td style="text-align: left;">[[|months.name|]]</td>
        <td>[[|months.acount|]]</td>
        <?php $total_room += [[=months.acount=]];?>
        <!--LIST:months.days-->
            <td align="center">
        	<?php 
        	    if([[=months.days.resold=]]>[[=months.days.total=]])
                {
                    echo [[=months.days.total=]].'/0';
                }
                else
                {
                    echo ([[=months.days.resold=]]).'/'.([[=months.days.total=]]-[[=months.days.resold=]]);
                }
        	?>
        	</td>
        <!--/LIST:months.days-->
        </tr>
    <!--/LIST:months-->
    <tr>
    	<td align="right"><strong>[[.Sum.]]</strong></td>
    	<td><strong><?php echo $total_room;?></strong></td>
    	<!--LIST:days-->
        	<td align="center"><strong>
        		<?php echo ([[=days.total=]].'/'.($total_room-[[=days.total=]])).'<br>('.(round([[=days.total=]]/$total_room,4)*100).'%)';?></strong>
        	</td>
    	<!--/LIST:days-->
    </tr>   
</table>
</td>
</tr>
</table>
<br />
</div>