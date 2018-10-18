<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table border="0" cellSpacing=0 cellpadding="5" width="100%" style="font-size:11px;">
			<tr valign="middle">
			<td width="100"><img src="<?php echo HOTEL_LOGO;?>" width="100" /></td>
			  <td align="left"><br />
			  	<strong><?php echo HOTEL_NAME;?></strong><br />
				[[.address.]]: <?php echo HOTEL_ADDRESS;?><BR>
				Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
				Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
			  </td>
			  <td>
				  &nbsp;
			  </td>
			</tr>	
			<tr>
				<td colspan="3">
					<center>
					  <h3 class="report_title">Báo cáo chi tiết công nợ</h3>
				  </center>
				</td>
			</tr>
            <tr>
                <td colspan="3" style="text-align: center;">
                    <span style="font-weight:bold;">[[.customer.]]: [[|customer_name|]]</span><br />
				    <span>[[.date_from.]]: <?php echo Url::get('date_from');?>[[.date_to.]]: <?php echo Url::get('date_to');?></span>
                </td>
            </tr>
		</table>
		
        <div style="text-align: right; font-weight: bold;">Số dư nợ đầu kì  : <br /><span id="base_line_vn"><?php echo System::display_number([[=items_baseline_vnd=]]); ?> VND <br /></span><span id="base_line_usd"><?php echo System::display_number([[=items_baseline_usd=]]); ?> USD</span></div><br />
        <table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:12px; border-collapse:collapse;">
            <tr class="header-table">
                <td rowspan="2" align="center">[[.stt.]]</td>
                <td rowspan="2"  align="center">[[.date.]]</td>
                <td rowspan="2"  align="center">[[.description.]]</td>
                <td rowspan="2"  align="center">[[.recode.]]/[[.bar_invoice.]]</td>
                <td rowspan="2"  align="center">Folio</td>
                <td colspan="2" align="center">PS tăng</td>
                <td colspan="2" align="center">PS giảm</td>
                <td colspan="2" align="center">Dư Nợ</td>
            </tr>
            <tr>
                <td align="center">VND</td>
                <td align="center">USD</td>
                <td align="center">VND</td>
                <td align="center">USD</td>
                <td align="center">VND</td>
                <td align="center">USD</td>
            </tr>
            <?php $i = 1 ?>
           
            <?php foreach([[=items=]] as $k => $v) {?>
            <tr>
                <td align="center"><?php echo $i ?></td>
                <td><?php echo $v['time']; ?></td>
                <td><?php echo $v['description'] ?></td>
                <?php if(isset($v['type']) and $v['type']=='RESERVATION'){ ?>
                <td align="center"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$v['recode']));?>"><?php echo $v['recode'] ?></a></td>
                <?Php }elseif(isset($v['type']) and $v['type']=='BAR'){ ?>
                <td align="center"><a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>$v['recode'],'bar_id'));  ?>"><?php echo $v['code'] ?></a></td>
                <?php }else{?>
                    <td>&nbsp;</td>
               <?php } ?>
                <td align="center"><!--Oanh comment<?php //echo $v['folio_number'] ?>></a>-->
                   <a target="_blank" href="<?php echo ($v['folio_number'] !=''?Url::build('view_traveller_folio',array('cmd'=>'group_invoice','folio_id'=>$v['folio_number'],'id'=>$v['folio_number'])):Url::build('view_traveller_folio',array('folio_id'=>$v['folio_number'])));?>">
                        <?php echo $v['folio_number'] ?>
                     </a>
                </td>
                <?php 
                if(isset($v['debt']))
                { 
                ?>  <td align="right" class="item_asc_vnd"><?php if($v['currency_id']=='VND'){echo System::display_number($v['price']);}else{echo '';} ?></td>
                    <td align="right" class="item_asc_usd"><?php if($v['currency_id']=='USD'){echo System::display_number($v['price']);}else{echo '';} ?></td>
                    <td class="item_desc_vnd"></td>
                    <td class="item_desc_usd"></td>
                <?php 
                }
                else
                { 
                ?>
                    <td class="item_asc_vnd"></td>
                    <td class="item_asc_usd"></td>
                    <td align="right" class="item_desc_vnd"><?php if($v['currency_id']=='VND'){echo System::display_number($v['price']);}else{echo '';} ?></td>
                    <td align="right" class="item_desc_usd"><?php if($v['currency_id']=='USD'){echo System::display_number($v['price']);}else{echo '';} ?></td>
                <?php 
                } 
                ?> 
                <td align="right" class="items_debt_vnd"><?php echo System::display_number($v['debit_vnd']); ?></td>
                <td align="right" class="items_debt_usd"><?php echo System::display_number($v['debit_usd']); ?></td>
            </tr>
            <?php $i++ ?>
            <?php } ?>
            
            <tr>
                <td align="right" colspan="5">Tổng cộng</td>
                <td align="right" id="total_asc_vnd">[[|total_debit_asc_vnd|]]</td>
                <td align="right" id="total_asc_usd">[[|total_debit_asc_usd|]]</td>
                <td align="right" id="total_des_vnd">[[|total_debit_desc_vnd|]]</td>
                <td align="right" id="total_des_usd">[[|total_debit_desc_usd|]]</td>
                <td align="right" id="total_debt_vnd">[[|total_final_debit_vnd|]]</td>
                <td align="right" id="total_debt_usd">[[|total_final_debit_usd|]]</td>
            </tr>
        </table><br />
        <div style="text-align: right; font-weight: bold;">Số dư nợ cuối kì :<br /><span id="totals">[[|total_final_debit_vnd|]] VND<br /></span><span id="totals">[[|total_final_debit_usd|]] USD</span></div>
<script>
// Lấy phát sinh tăng và giảm    
    var item_asc_vnd = jQuery('.item_asc');
    var total_asc_v = 0;
    for(var i=0; i < item_asc.length ; i++ )
    {
        total_asc += parseInt((item_asc[i].innerText),10);   
        
    }
    jQuery('#total_asc').text(total_asc);
    var item_des = jQuery('.item_des');
    var total_des = 0;
    for(var i=0; i < item_des.length ; i++ )
    {
        total_des += parseInt((item_des[i].innerText),10); 
    }
    jQuery('#total_des').text(total_des); 
     
// Lấy dư nợ của mỗi đợt    
    var base_line_vnd = parseInt(jQuery('#base_line_vnd').html(),10);// số dư đầu kì

    if (isNaN(base_line_vnd)) base_line_vnd=0;
    var item_debt_vnd = jQuery('td .items_debt_vnd');
    var item_asc_vnd = jQuery('td .item_asc_vnd');
    var item_desc_vnd = jQuery('td .item_desc_vnd');
    for ( var i=0; i< item_debt_vnd.length; i++ )
    {
        var items_des = to_numeric(jQuery(item_desc_vnd[i]).text());//mỗi thằng tăng
        var items_asc = to_numeric(jQuery(item_asc_vnd[i]).text());// mỗi thằng giảm
        if(isNaN(items_des)) items_des=0;
        if(isNaN(items_asc)) items_asc=0;
        base_line_vnd += items_asc - items_des;
        jQuery(item_debt_vnd[i]).text(base_line_vnd);            
    }
     var base_line_usd = parseInt(jQuery('#base_line_usd').html(),10);// số dư đầu kì

    if (isNaN(base_line_usd)) base_line_usd=0;
    var item_debt_usd = jQuery('td .items_debt_usd');
    var item_asc_usd = jQuery('td .item_asc_usd');
    var item_desc_usd = jQuery('td .item_desc_usd');
    for ( var i=0; i< item_debt_usd.length; i++ )
    {
        var items_des = to_numeric(jQuery(item_desc_usd[i]).text());//mỗi thằng tăng
        var items_asc = to_numeric(jQuery(item_asc_usd[i]).text());// mỗi thằng giảm
        if(isNaN(items_des)) items_des=0;
        if(isNaN(items_asc)) items_asc=0;
        base_line_usd += items_asc - items_des;
        jQuery(item_debt_usd[i]).text(base_line_usd);            
    }
    
// Lấy dư nợ cuối kì
    var base_line = parseInt(jQuery('#base_line').html(),10);// số dư đầu kì
    if (isNaN(base_line)) base_line=0;    
    var total_asc = parseInt(jQuery('#total_asc').html(),10);
    var total_des = parseInt(jQuery('#total_des').html(),10);
    jQuery('#totals').text( PriceFormatCurrency(base_line - total_des + total_asc)) ;    
    
// Format dang Price currentcy
    var base_line = jQuery('#base_line').text();
    var total_asc = jQuery('#total_asc').text();
    var total_des = jQuery('#total_des').text();
    if(base_line!='')
    {
        base_line = PriceFormatCurrency(base_line);
        jQuery('#base_line').text(base_line);
    }
    if(total_asc!='')
    {
        total_asc = PriceFormatCurrency(total_asc);
        jQuery('#total_asc').text(total_asc);
    }
    if(total_des!='')
    {
        total_des = PriceFormatCurrency(total_des);
        jQuery('#total_des').text(total_des);
    }
    
    
    var item_debt = jQuery('td .items_debt');
    for ( var i=0; i< item_debt.length; i++ )
    {
        var items_des = jQuery(item_debt[i]).prev().text();//mỗi thằng tăng
        var items_asc = jQuery(item_debt[i]).prev().prev().text();// mỗi thằng giảm
        if(!isNaN(items_des))
        {
            items_des = PriceFormatCurrency(items_des);
            jQuery(item_debt[i]).prev().text(items_des);
        }     
        if(!isNaN(items_asc))
        {
            items_asc = PriceFormatCurrency(items_asc);
            jQuery(item_debt[i]).prev().prev().text(items_asc);
        } 
        var item_debit = jQuery(item_debt[i]).text();
        item_debit = PriceFormatCurrency(item_debit);
        jQuery(item_debt[i]).text(item_debit);
        
                     
    }
    
    function PriceFormatCurrency(input_number)
    {
        if(input_number)
        {
            input_number += '';
            x = input_number.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }
        
    }
    
    
</script>