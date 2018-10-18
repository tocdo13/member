<div class="report-bound" style=" page:land;">
<div >
<link rel="stylesheet" href="skins/default/report.css">
<!--IF:first_page([[=page_no=]]==1)-->
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>
<!--/IF:first_page-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellspacing="0" width="100%">
			<tr valign="top">
			<td align="left" width="65%">
			<strong><?php echo [[=hotel_name=]];?></strong><br />
			<?php echo [[=hotel_address=]];?></td>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
			</tr>	
		</table>
		<font class="report_title" style="text-transform:uppercase;">
        <?php if(Url::get('revenue')=='min'){
					echo Portal::language('retailer_do_not_offer_bill');			
			}else{
				echo Portal::language('karaoke_order_revenue_report');		
			}
			?>
        </font>
		<!--IF:cond(Url::get('product_code'))--><br />
		<span class="notice">Tr&#432;&#7901;ng h&#7907;p t&igrave;m theo m&atilde; s&#7843;n ph&#7849;m th&igrave; t&#7893;ng ti&#7873;n s&#7869; kh&ocirc;ng bao g&#7891;m thu&#7871; v&agrave; ph&iacute; d&#7883;ch v&#7909;</span>
		<!--/IF:cond-->
		
        <br />
		
        <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"><span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
					[[.from.]]&nbsp;[[|from_date|]]&nbsp;[[.to.]]&nbsp;[[|to_date|]]
		  		<br>
			      </span>
        <!--IF:cond(![[=check=]])-->
		<?php echo URL::get('karaoke_id')?Portal::language('karaoke_id').' '.DB::fetch('select name from karaoke where id=\''.URL::get('karaoke_id').'\'','name'):'';?>
        <!--/IF:cond-->
        <?php echo Url::get('karaoke_name');?>
        </div>
        
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
            <?php if( isset( [[=start_shift_time=]] ) ) echo Portal::language('time').': '.[[=start_shift_time=]].' - '; if( isset( [[=end_shift_time=]] ) ) echo [[=end_shift_time=]]; ?>
        </div>
        <br />
		<!--/IF:first_page-->