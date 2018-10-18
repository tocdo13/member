<style>

        @media print
{
     .no-display{
        display:none !important; 
        }
}
       
</style>
<div class="report-bound" style=" page:land;">
<div >
<link rel="stylesheet" href="skins/default/report.css"/>
<?php 
				if((($this->map['page_no']==$this->map['start_page']) or ($this->map['page_no']==0)))
				{?>
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>

				<?php
				}
				?>
<?php 
				if((($this->map['page_no']==$this->map['start_page']) or ($this->map['page_no']==0)))
				{?>
<table id="tblExport" cellpadding="10" cellspacing="0" width="100%">
<tr id="header_report">
	<td align="center">
		
		<table cellspacing="0" width="100%">
			<tr valign="top">
			<td align="left" width="65%">
			<strong><?php echo $this->map['hotel_name'];?></strong><br />
			<?php echo $this->map['hotel_address'];?></td>
			<td align="right" nowrap width="35%">
			<?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
			</td>
			</tr>	
		</table>
		<font class="report_title" style="text-transform:uppercase;">
        <?php if(Url::get('revenue')=='min'){
					echo Portal::language('retailer_do_not_offer_bill');			
			}else{
				echo Portal::language('restaurant_order_revenue_report');		
			}
			?>
        </font>
		<?php 
				if((Url::get('product_code')))
				{?><br />
		<span class="notice">Tr&#432;&#7901;ng h&#7907;p t&igrave;m theo m&atilde; s&#7843;n ph&#7849;m th&igrave; t&#7893;ng ti&#7873;n s&#7869; kh&ocirc;ng bao g&#7891;m thu&#7871; v&agrave; ph&iacute; d&#7883;ch v&#7909;</span>
		
				<?php
				}
				?>
		
        <br />
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
        <?php Report::display_date_params();?><br />
        <?php 
				if((!$this->map['check']))
				{?>
		<?php echo URL::get('bar_id')?Portal::language('bar_id').' '.DB::fetch('select name from bar where id=\''.URL::get('bar_id').'\'','name'):'';?>
        
				<?php
				}
				?>
        <?php echo Url::get('bar_name');?>
        </div>
        
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
            <?php if( isset($this->map['start_shift_time']) && Url::get('search_time')) echo Portal::language('time').': '.$this->map['start_shift_time'].' '.$this->map['from_date'].' - '; if( isset( $this->map['end_shift_time'] )  && Url::get('search_time') ) echo $this->map['end_shift_time'].' '.$this->map['to_date']; ?>
        </div>
        <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
            <?php if($this->map['from_bill']!=''){ ?> <?php echo Portal::language('from_bill');?> <?php echo $this->map['from_bill']; } ?> <?php if($this->map['to_bill']!=''){ ?> <?php echo Portal::language('to');?> <?php echo $this->map['to_bill']; } ?> 
        </div>
        <div style="font-family:'Times New Roman', Times, serif;font-size:13px;color:red;" class="no-display">
            <?php if($this->map['check_table']==0)echo 'THỜI ĐIỂM HIỆN TẠI CÓ BÀN CHƯA CHECK OUT.';?>
        </div>
        <br />
        <button id="export"><?php echo Portal::language('export');?></button>
		
        

				<?php
				}
				?>