<!--Bao cao ton tong hop-->
<style>
.scroll{
    display:block;
    width:100%;
    overflow-x:auto;
}
</style>
<link rel="stylesheet" href="skins/default/report.css"/>
<link rel="stylesheet" href="packages/hotel/packages/warehousing/skins/default/css/style.css"/>
<div>
	<div style="width:100%;padding:10px;text-align:center;font-size:14px;">	
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left">
					<strong><?php echo HOTEL_NAME;?></strong><br />
					Địa chỉ: <?php echo HOTEL_ADDRESS;?><br/>
					Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
					Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
				</td>
                
                <td align="right">
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
                </td>
			</tr>
		</table>
	</div>
    <table width="100%" id="export">
        <tr>
            <td>
    <div style="padding:10px; ">
        <div style="text-align:center;">
        	<!--div class="report_title">[[.warehouse_remain_synthesis_report.]]</div-->
        	<div class="report_title">[[.warehouse_remain_synthesis_report.]]</div>

        	<br/><strong>[[.date.]] [[|date|]]</strong><br/><br />
        </div>
        <div style="text-align: center;"><p><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]"  /></p></div>
        <div id="tbl" style="width: 100%; overflow: auto; height: 500px;  position: relative;">
        	<table id="tblExport" cellpadding="2" cellspacing="0" bordercolor="#000000" border="1" width="100%">
                <thead>
                    <tr valign="middle" align="center" style="background-color: #D8D8D8;">
                        <th style="width:10px;background-color: #D8D8D8;" class="report_table_header">[[.stt.]]</th>
                    	<th style="width:70px;background-color: #D8D8D8;" class="report_table_header">[[.id.]]</th>
                        <th style="width:200px;background-color: #D8D8D8;;" class="report_table_header">[[.product_name.]]</th>
                        <th style="width:70px;background-color: #D8D8D8;" class="report_table_header">[[.unit.]]</th>
                        <!--LIST:group_wh-->
                        <th style="background-color: #D8D8D8;" class="report_table_header">[[|group_wh.name|]]</th>
                        <!--/LIST:group_wh-->
                        <th style="width:80px;background-color: #D8D8D8;" class="report_table_header">[[.remain1.]]</th>
                    </tr>
                </thead>
                <?php
                //System::debug($this->map['product_remain']);
                foreach($this->map['product_remain'] as $key=>$value)
                {
                    echo '<tr>';
                    echo '<td>'.$value['stt'].'</td>';
                    echo '<td><strong>'.$value['id'].'</strong></td>';
                    echo '<td style="text-align:left;">'.$value['product_name'].'</td>';
                    echo '<td>'.$value['unit_name'].'</td>';
                    
                    $total = 0;
                    foreach($this->map['group_wh'] as $k=>$v)
                    {
                        echo '<td>'.($value['remain_number_'.$k]?$value['remain_number_'.$k]:'').'</td>';
                        $total+=$value['remain_number_'.$k];
                    }
                    echo '<td>'.$total.'</td>';
                    echo '</tr>';
                }
                
                
                ?>
                
        	</table>
            
            <table cellspacing="0" cellpadding="2" style="background: white; position: absolute; top:0px; z-index:99999; width: 100%; table-layout: fixed; display:none;" border="1" id="hidTable">
                <thead style="width: 100%;"> 
                    <tr valign="middle" align="center"  style="width: 100%;">
                        <th style="width:10px;background-color: #D8D8D8;" class="report_table_header">[[.stt.]]</th>
                    	<th style="width:70px;background-color: #D8D8D8;" class="report_table_header">[[.id.]]</th>
                        <th style="width:200px;background-color: #D8D8D8;" class="report_table_header">[[.product_name.]]</th>
                        <th style="width:70px;background-color: #D8D8D8;" class="report_table_header">[[.unit.]]</th>
                        <!--LIST:group_wh-->
                        <th class="report_table_header" style="background-color: #D8D8D8;">[[|group_wh.name|]]</th>
                        <!--/LIST:group_wh-->
                        <th style="width:80px;background-color: #D8D8D8;" class="report_table_header">[[.remain1.]]</th>
                    </tr>
                </thead> 
            </table>
            
            <table cellpadding="2" cellspacing="0" bordercolor="#000000" border="1" style="display:none;background: white; position: absolute; top:0px; z-index:9999; width: 256px; table-layout: fixed;" id="hiddenTable" >
                <thead> 
                     <tr valign="middle" align="center">
                        <th style="width:10px;background-color: #D8D8D8;" class="report_table_header">[[.stt.]]</th>
                    	<th style="width:70px;background-color: #D8D8D8;" class="report_table_header">[[.id.]]</th>
                        <th style="width:200px;background-color: #D8D8D8;" class="report_table_header">[[.product_name.]]</th>
                        <th style="width:70px;background-color: #D8D8D8;" class="report_table_header">[[.unit.]]</th>                        
                    </tr>
                </thead>     
                    <?php
                        //System::debug($this->map['product_remain']);
                        foreach($this->map['product_remain'] as $key=>$value)
                        {
                            echo '<tr>';
                            echo '<td>'.$value['stt'].'</td>';
                            echo '<td><strong>'.$value['id'].'</strong></td>';
                            echo '<td style="text-align:left;">'.$value['product_name'].'</td>';
                            echo '<td>'.$value['unit_name'].'</td>';
                            echo '</tr>';
                        }
                    ?> 
                
            </table>
            
        </div>
        <div>
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td colspan="5" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center" width="45%">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right" width="45%"><em>[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?> </em></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center"><strong>[[.creater.]]</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="center"><strong>[[.warehouseman.]]</strong> </td>
                </tr>
            </table>
        </div>
    </div>	
     </td>
        </tr>
    </table>
</div>
<script>
 jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html()));
        });
        //jQuery('.class_none').remove();
        jQuery('#export_repost').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
    
    
  jQuery(document).ready(
        function()
        {   
            jQuery("div#tbl").width(jQuery(window).width()-20);
            //jQuery("table#tblExport tbody:first-child td").attr("colspan",jQuery("table#tblExport thead tr tr").length);
        
            jQuery("table#hidTable").width(jQuery("table#tblExport").outerWidth()+"px");
            //jQuery("table#hidTable thead").width(jQuery("table#tblExport").outerWidth()+"px");
            //jQuery("table#hidTable thead tr").width(jQuery("table#tblExport").outerWidth()+"px");
            //jQuery("table#hidTable").css("left",jQuery("table#tblExport").offset().left+"px");
            
            jQuery("table#hidTable thead tr th").each(function(){
                
                var index = jQuery("table#hidTable thead tr th").index(jQuery(this));
                var element  = jQuery("table#tblExport thead tr th").get(index);
                var width = to_numeric(jQuery(element).outerWidth());
                console.log(width);
                jQuery(this).width(width+"px");
            });
            
            jQuery("table#hiddenTable thead tr th").each(function(){
                
                var index = jQuery("table#hiddenTable thead tr th").index(jQuery(this));
                var element  = jQuery("table#tblExport thead tr th").get(index);
                var width = to_numeric(jQuery(element).outerWidth());
                var height = to_numeric(jQuery(element).outerHeight());
                //console.log(width);
                jQuery(this).width(width+"px");
                jQuery(this).height(height+"px");
            });
            
            jQuery("div#tbl").scroll(function() {
                var documentScrollLeft = jQuery("div#tbl").scrollLeft();
                var documentScrollTop = jQuery("div#tbl").scrollTop();          
                if(documentScrollLeft>200){
                    jQuery("table#hiddenTable").css("left",documentScrollLeft);
                    jQuery("table#hiddenTable").show("fast");
                }
                else{
                   jQuery("table#hiddenTable").hide("fast"); 
                }
                //console.log(documentScrollTop);
                if(documentScrollTop>100){
                   jQuery("table#hidTable").css({"top":documentScrollTop+"px","display":"","table-layout": "fixed"}); // Bi thay doi boi "display":"in-line"
                }
                else{
                   jQuery("table#hidTable").css("display","none");  
                }
                
            });
            
        });  
</script>
