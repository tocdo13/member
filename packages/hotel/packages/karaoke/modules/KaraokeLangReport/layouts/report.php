<style>
    #timehidden
    {
        display:none;	
    }
	@media print
    {
		#hidden
        {
			display:none;
		}
		#timehidden
        {
			display:block;	
		}
	}
</style>
<table  bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.restaurant_total_report.]]</b></font>
        
        <br/><font class="report_title" style="font-size:16px; text-align:center;"><b><?php echo Portal::language([[=portal_name=]]); ?></b></font>
        
		<br/><label id="timehidden">[[.from_date.]]: <?php echo $_REQUEST['from_date']; ?>  [[.to_date.]]: <?php echo $_REQUEST['to_date']; ?></label><br />
		<form name="WeeklyRevenueForm" method="post">
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b>[[.select_option.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td>[[.select_hotel.]]: <select name="hotel_id" id="hotel_id"></select></td>
            	<td>[[.from_date.]]:&nbsp;&nbsp;
            	<input name="from_date" type="text" id="from_date" onchange="changevalue();" class="date-input"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>[[.to_date.]]:&nbsp;&nbsp;
                <input name="to_date" type="text" id="to_date" onchange="changefromday();" class="date-input"/></td>
                <td><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
			</tr>
		  </table>
          
		  </fieldset>
		  </td>
    </tr>
    </table>
			</form>
	</td></tr></table>
</td>
</tr>
</table>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" >
    <tr valign="middle" bgcolor="silver" align="center" >
        <th rowspan="2" ></th>
        <th colspan="2">[[.fact.]]</th>
        <th colspan="2">[[.profit_/holes.]]</th>
        <th colspan="2">[[.compared_to_budget.]]</th>
        <th colspan="2">[[.increase_/decrease.]]</th>
        <th colspan="2">[[.Thực hiện kế hoạch.]]</th>
    </tr>
    <tr align="center" bgcolor="silver">
        <th>[[.revenue.]]</th>
        <th>[[.capital.]]</th>
        <th>[[.VND.]]</th>
        <th>%</th>
        <th>[[.Previous_period.]]</th>
        <th>%</th>
        <th>[[.last_time_revenue.]]</th>
        <th>%</th>
        <th>[[.plan_revenue.]]</th>
        <th>%</th>
    </tr>
    <?php 
        $alba = '';
        $alba_queen = '';
        foreach([[=karaoke_revenue=]] as $key => $value)
        {
    ?>
        <tr>
            <td style="text-align: left;" ><strong><?php echo Portal::language($key);?></strong></td>
            <td style="text-align: right;"><strong><?php echo System::display_number($value['real_price']);?></strong></td>
            <td style="text-align: right;"><strong><?php echo System::display_number($value['original_price']);?></strong></td>
            <td style="text-align: right;"><strong><?php echo System::display_number($value['real_price']-$value['original_price']);?></strong></td>
            <td style="text-align: right;"><strong><?php echo $value['original_price'] != 0?System::display_number(round(100*($value['real_price'] - $value['original_price'])/$value['real_price']),2):100;?></strong></td>
            <td style="text-align: right;"><strong><?php echo System::display_number($value['decrese_real_price']);?></strong></td>
            <td style="text-align: right;"><strong><?php echo $value['decrese_real_price'] != 0?System::display_number(round(100*($value['real_price'])/$value['decrese_real_price']),2):100;?></strong></td>
            <td style="text-align: right;"><strong><?php echo System::display_number(0);?></strong></td>
            <td style="text-align: right;"><strong><?php echo System::display_number(100);?></strong></td>
            <td></td>
            <td></td>
        </tr>
    <?php 
            foreach($value as $k => $v)
            {
                if(is_array($v))
                {
    ?>
        <tr>
            <td style="text-align: left;">&nbsp;&nbsp;<?php echo Portal::language($k); ?></td>
            <td style="text-align: right;"><?php echo System::display_number($v['real_price']);?></td>
            <td style="text-align: right;"><?php echo System::display_number($v['original_price']);?></td>
            <td style="text-align: right;"><?php echo System::display_number($v['real_price'] - $v['original_price']);?></td>
            <td style="text-align: right;"><?php echo $v['original_price'] != 0?System::display_number(round(100*($v['real_price'] - $v['original_price'])/$v['real_price']),2):100;?></td>
            <td style="text-align: right;"><?php echo System::display_number($v['decrese_real_price']);?></td>
            <td style="text-align: right;"><?php echo $v['decrese_real_price'] != 0?System::display_number(round(100*($v['real_price'])/$v['decrese_real_price']),2):100;?></td>
            <td style="text-align: right;"><?php echo System::display_number(0);?></td>
            <td style="text-align: right;"><?php echo System::display_number(100);?></td>
            <td></td>
            <td></td>
        </tr>
    <?php
                }
            }
        }
    ?>
    <tr bgcolor="silver" >
        <td style="text-align: left;"><strong>[[.total.]] <?php echo Portal::language([[=resort=]]['portal_id']); ?> (1)</strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=resort=]]['real_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=resort=]]['original_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=resort=]]['real_price']-[[=resort=]]['original_price']);?></strong></td>
        <td style="text-align: right;"><?php echo [[=resort=]]['original_price'] != 0?System::display_number(round(100*([[=resort=]]['real_price']-[[=resort=]]['original_price'])/[[=resort=]]['real_price']),2):100;?></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=resort=]]['decrese_real_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo [[=resort=]]['decrese_real_price'] != 0?System::display_number(round(100*([[=resort=]]['real_price'])/[[=resort=]]['decrese_real_price']),2):100;?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number(0);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number(100);?></strong></td>
        <td></td>
        <td></td>
    </tr>
    <?php 
        foreach([[=resort=]] as $key => $value)
        {
            if(is_array($value))
            {
                //System::debug($value);
    ?>    
    <?php
            //foreach($value as $k => $v)
            //{
    ?>
                <tr>
                    <td style="text-align: left;">&nbsp;&nbsp;<?php echo Portal::language($key); ?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['real_price']);?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['original_price']);?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['real_price'] - $value['original_price']);?></td>
                    <td style="text-align: right;"><?php echo $value['original_price'] != 0?System::display_number(round(100*($value['real_price'] - $value['original_price'])/$value['real_price']),2):100;?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['decrese_real_price']);?></td>
                    <td style="text-align: right;"><?php echo $value['decrese_real_price'] != 0?System::display_number(round(100*($value['real_price'])/$value['decrese_real_price']),2):100;?></td>
                    <td style="text-align: right;"><?php echo System::display_number(0);?></td>
                    <td style="text-align: right;"><?php echo System::display_number(100);?></td>
                    <td></td>
                    <td></td>
                </tr>
    <?php
            //}
            }
        }
    ?>
    <?php 
    if(isset([[=alba_queen=]]))
    {
    ?>
    <!--Alba queen-->
    <tr bgcolor="silver" >
        <td style="text-align: left;"><strong>[[.total.]] <?php $alba_queen = 2; echo Portal::language([[=alba_queen=]]['portal_id']).' (2)'; ?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=alba_queen=]]['real_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=alba_queen=]]['original_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=alba_queen=]]['real_price']-[[=alba_queen=]]['original_price']);?></strong></td>
        <td style="text-align: right;"><?php echo [[=alba_queen=]]['original_price'] != 0?System::display_number(round(100*([[=alba_queen=]]['real_price']-[[=alba_queen=]]['original_price'])/[[=alba_queen=]]['real_price']),2):100;?></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=alba_queen=]]['decrese_real_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo [[=alba_queen=]]['decrese_real_price'] != 0?System::display_number(round(100*([[=alba_queen=]]['real_price'])/[[=alba_queen=]]['decrese_real_price']),2):100;?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number(0);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number(100);?></strong></td>
        <td></td>
        <td></td>
    </tr>
    <?php 
        foreach([[=alba_queen=]] as $key => $value)
        {
            if(is_array($value))
            {
                //System::debug($value);
    ?>    
    <?php
            //foreach($value as $k => $v)
            //{
    ?>
                <tr>
                    <td style="text-align: left;">&nbsp;&nbsp;<?php echo Portal::language($key); ?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['real_price']);?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['original_price']);?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['real_price'] - $value['original_price']);?></td>
                    <td style="text-align: right;"><?php echo $value['original_price'] != 0?System::display_number(round(100*($value['real_price'] - $value['original_price'])/$value['real_price']),2):100;?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['decrese_real_price']);?></td>
                    <td style="text-align: right;"><?php echo $value['decrese_real_price'] != 0?System::display_number(round(100*($value['real_price'])/$value['decrese_real_price']),2):100;?></td>
                    <td style="text-align: right;"><?php echo System::display_number(0);?></td>
                    <td style="text-align: right;"><?php echo System::display_number(100);?></td>
                    <td></td>
                    <td></td>
                </tr>
    <?php
            //}
            }
        }
    ?>
    <?php
    }
    ?>
    <!--Alba-->
    <?php 
    if(isset([[=alba=]]))
    {
    ?>
    <!--Alba-->
    <tr bgcolor="silver" >
        <td style="text-align: left;"><strong>[[.total.]] <?php $alba = 3; echo Portal::language([[=alba=]]['portal_id']).' (3)'; ?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=alba=]]['real_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=alba=]]['original_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=alba=]]['real_price']-[[=alba=]]['original_price']);?></strong></td>
        <td style="text-align: right;"><?php echo [[=alba=]]['original_price'] != 0?System::display_number(round(100*([[=alba=]]['real_price']-[[=alba=]]['original_price'])/[[=alba=]]['real_price']),2):100;?></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=alba=]]['decrese_real_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo [[=alba=]]['decrese_real_price'] != 0?System::display_number(round(100*([[=alba=]]['real_price'])/[[=alba=]]['decrese_real_price']),2):100;?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number(0);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number(100);?></strong></td>
        <td></td>
        <td></td>
    </tr>
    <?php 
        foreach([[=alba=]] as $key => $value)
        {
            if(is_array($value))
            {
                //System::debug($value);
    ?>    
    <?php
            //foreach($value as $k => $v)
            //{
    ?>
                <tr>
                    <td style="text-align: left;">&nbsp;&nbsp;<?php echo Portal::language($key); ?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['real_price']);?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['original_price']);?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['real_price'] - $value['original_price']);?></td>
                    <td style="text-align: right;"><?php echo $value['original_price'] != 0?System::display_number(round(100*($value['real_price'] - $value['original_price'])/$value['real_price']),2):100;?></td>
                    <td style="text-align: right;"><?php echo System::display_number($value['decrese_real_price']);?></td>
                    <td style="text-align: right;"><?php echo $value['decrese_real_price'] != 0?System::display_number(round(100*($value['real_price'])/$value['decrese_real_price']),2):100;?></td>
                    <td style="text-align: right;"><?php echo System::display_number(0);?></td>
                    <td style="text-align: right;"><?php echo System::display_number(100);?></td>
                    <td></td>
                    <td></td>
                </tr>
    <?php
            //}
            }
        }
    ?>
    <?php
    }
    ?>
    <tr bgcolor="silver" >
        <td style="text-align: left;"><strong>[[.grand_total.]] (1) <?php echo $alba != ''?'+ (2)':''; echo $alba_queen != ''?'+ (3)':''; ?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=summary=]]['real_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=summary=]]['original_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=summary=]]['real_price']-[[=summary=]]['original_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo [[=summary=]]['original_price'] != 0?System::display_number(round(100*([[=summary=]]['real_price']-[[=summary=]]['original_price'])/[[=summary=]]['real_price']),2):100;?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number([[=summary=]]['decrese_real_price']);?></strong></td>
        <td style="text-align: right;"><strong><?php echo [[=summary=]]['decrese_real_price'] != 0?System::display_number(round(100*([[=summary=]]['real_price'])/[[=summary=]]['decrese_real_price']),2):100;?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number(0);?></strong></td>
        <td style="text-align: right;"><strong><?php echo System::display_number(100);?></strong></td>
        <td></td>
        <td></td>
    </tr>
    <?php 
        foreach([[=summary=]] as $key => $value)
        {
            if(is_array($value))
            {
                //System::debug($value);
    ?>    
    <?php
            //foreach($value as $k => $v)
            //{
    ?>
                <tr style="background-color: rgba(150, 152, 158, 0.44);">
                    <td style="text-align: left;">&nbsp;&nbsp;<strong><?php echo Portal::language($key); ?></strong></td>
                    <td style="text-align: right;"><strong><?php echo System::display_number($value['real_price']);?></strong></td>
                    <td style="text-align: right;"><strong><?php echo System::display_number($value['original_price']);?></strong></td>
                    <td style="text-align: right;"><strong><?php echo System::display_number($value['real_price'] - $value['original_price']);?></strong></td>
                    <td style="text-align: right;"><strong><?php echo $value['original_price'] != 0?System::display_number(round(100*($value['real_price'] - $value['original_price'])/$value['real_price']),2):100;?></strong></td>
                    <td style="text-align: right;"><strong><?php echo System::display_number($value['decrese_real_price']);?></td>
                    <td style="text-align: right;"><strong><?php echo $value['decrese_real_price'] != 0?System::display_number(round(100*($value['real_price'])/$value['decrese_real_price']),2):100;?></strong></td>
                    <td style="text-align: right;"><strong><?php echo System::display_number(0);?></strong></td>
                    <td style="text-align: right;"><strong><?php echo System::display_number(100);?></strong></td>
                    <td></td>
                    <td></td>
                </tr>
    <?php
            //}
            }
        }
    ?>
</table>
<br />
<script>
		jQuery("#from_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR,1 - 1, 1)});
		jQuery("#to_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
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