<style>
/*full m�n h�nh*/
.simple-layout-middle{width:100%;}
.th
{
    align:center;
}
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
                        <div style="width:100%; text-align:center; line-height: 40px; ">
                            <font class="report_title specific" >[[.vending_product_revenue_report.]]<br /></font>
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
<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <td>
                                        <strong>[[.from_date.]]</strong><input name="from_date" type="text" id="from_date" value="[[|from_day|]]" onchange="changevalue()" size="8" />
                                        <strong>[[.to_date.]]</strong><input name="to_date" type="text" id="to_date" value="[[|to_day|]]" onchange="changefromday();" size="8" />
                                        <input type="submit" name="do_search" value="[[.report.]]"/>
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

<table  width ="100%" border="1">
    <tr bgcolor="#EFEFEF">
        <th rowspan="2" width="60px">[[.code.]]</th>
        <th rowspan="2" width="290px" >[[.product_name.]]</th>
        <th rowspan="2" width="50px">[[.unit.]]</th>
        <th width="55px">[[.quantity.]]</th>
        <th width="90px">[[.price.]]</th>
        <th width="50px">[[.FOC.]]</th>
        <th width="130px">[[.total_amount.]]</th>
        <th width="90px">[[.agent_discount.]]</th>
        <th width="90px">Promotion</th>
        <th width="130px">Net amount</th>
        <th width="130px">[[.VAT.]](10%)</th>
        <th >[[.net_revenue.]]</th>
    </tr>
    <tr bgcolor="#EFEFEF" >
        <th>a</th>
        <th>b</th>
        <th>c</th>
        <th>d=(a-c)*b</th>
        <th>e</th>
        <th>f</th>
        <th>g=d-e-f</th>
        <th>h=10%*i</th>
        <th>i=g/1.1</th>
    </tr>
    <tr>
        <th align="left" colspan="6" >Alba products</th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['total'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['agent_discount'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['promotion'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['net_amount'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['total_tax'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['net_revenue'])); ?></th>
    </tr>
    <?php foreach($this->map['items'] as $key => $value){ 
        if($value['product_pipeline'] == "ALBA"){
    ?>
        <tr>
            <td align="left" style="padding-left: ;: 5px;" ><?php echo $value['id'] ?></td>
            <td align="left" style="padding-left: 5px;" ><?php echo $value['product_name'] ?></td>
            <td align="left" style="padding-left: 5px;" ><?php echo $value['unit_name'] ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo $value['quantity'] ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo  System::display_number(round($value['price'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo $value['foc'] ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['total'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['agent_discount'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['promotion'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['net_amount'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['total_tax'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['net_revenue'])) ?></td>
        </tr>
    <?php }} ?>
    <tr>
        <th align="left" colspan="6" >Thanh Tan products</th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['thanhtan']['total'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['thanhtan']['agent_discount'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['thanhtan']['promotion'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['thanhtan']['net_amount'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['thanhtan']['total_tax'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['thanhtan']['net_revenue'])); ?></th>
    </tr>
    <?php foreach($this->map['items'] as $key => $value){ 
        if($value['product_pipeline'] != "ALBA"){
    ?>
        <tr>
            <td align="left" style="padding-left: 5px;" ><?php echo $value['id'] ?></td>
            <td align="left" style="padding-left: 5px;" ><?php echo $value['product_name'] ?></td>
            <td align="left" style="padding-left: 5px;" ><?php echo $value['unit_name'] ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo $value['quantity'] ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo  System::display_number(round($value['price'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo $value['foc'] ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['total'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['agent_discount'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['promotion'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['net_amount'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['total_tax'])) ?></td>
            <td align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($value['net_revenue'])) ?></td>
        </tr>
    <?php }} ?>
    <tr>
        <th align="left" colspan="6" >[[.total.]]</th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['total']+$this->map['thanhtan']['total'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['agent_discount']+$this->map['thanhtan']['agent_discount'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['promotion']+$this->map['thanhtan']['promotion'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['net_amount']+$this->map['thanhtan']['net_amount'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['total_tax']+$this->map['thanhtan']['total_tax'])); ?></th>
        <th align="right" style="padding-right: 5px;" ><?php echo System::display_number(round($this->map['alba']['net_revenue']+$this->map['thanhtan']['net_revenue'])); ?></th>
    </tr>
</table>
<br />
<div style=" height: 500px; width: 100%; " id="pie_char"></div>
<br /><br />
<!---------FOOTER----------->
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
<br /><br /><br /><br /><br />
<script type="text/javascript">
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

jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
        
        //revention in date
        var chart_report;
        var data_char = [];
        
        j = 0;
        var tong = 0;
        
        <?php foreach($this->map['items'] as $key => $value){ ?>
        data_char[j] = [];
        data_char[j][0] = '<?php echo $value['product_name'] ?>';
        data_char[j][1] = to_numeric('<?php echo $value['net_revenue'] ?>');
        tong += data_char[j][1];
        j++;
        <?php } ?>
        
        console.log(data_char);
        
        chart_report = new Highcharts.Chart(
        {
            chart:{
                renderTo:'pie_char',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                    text: "[[.Revenue_by_product.]]"
            },
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000',
						formatter: function() {
                            if(this.y>0)
                            {
                                return this.point.name + '(' + roundNumber(this.percentage,1) + ' %) '+ number_format(roundNumber(this.y,0));
                            }
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '9px'
                        }
					},
					showInLegend: true,
				}
			},
            tooltip:{
                formatter: function() {
                    //tong = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,1) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(roundNumber(this.y,0))+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(roundNumber(tong,0))+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: '[[.piechart_room_revenue.]]',
				data:data_char
			}]
        });
    }
);
</script>