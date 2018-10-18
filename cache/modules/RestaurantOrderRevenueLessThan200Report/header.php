<div class="report-bound" style=" page:land;">
<div >
<link rel="stylesheet" href="skins/default/report.css">
<?php 
				if(($this->map['page_no']==Url::get('start_page') OR $this->map['page_no']==0))
				{?>
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>

				<?php
				}
				?>
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<?php 
				if(($this->map['page_no']==Url::get('start_page') OR $this->map['page_no']==0))
				{?>
		<table cellspacing="0" width="100%" style="font-size:11px;">
			<tr valign="top">
			<td align="left" width="60%">
			<strong><?php echo $this->map['hotel_name'];?></strong><br />
			<?php echo $this->map['hotel_address'];?></td>
            <td align="right">
            <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
            </td>

			</tr>	
		</table>
		<font class="report_title" style="font-size:16px;">
        <?php echo Portal::language('restaurant_order_revenue_less_than_200_report'); ?>
        </font>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
       <?php echo Portal::language('date_from');?>: <?php echo Url::get('date_from') ?> <?php echo Portal::language('date_to');?>: <?php echo Url::get('date_to') ?> <br />
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
            <?php if( isset( $this->map['start_shift_time'] ) ) echo Portal::language('time').': '.$this->map['start_shift_time'].' - '; if( isset( $this->map['end_shift_time'] ) ) echo $this->map['end_shift_time']; ?>
        </div>
        <br />
        
		<table cellspacing="3px" cellpadding="3px" width="100%" border="0" style="float: left; font-size:11px;">
			<tr valign="top">
                <td align="left">
    			<strong><?php echo Portal::language('hotel_name');?></strong> :<?php echo $this->map['hotel_name'];?> 
                </td>
    		
            </tr>
			<tr valign="top">
                <td align="left">
    			<strong><?php echo Portal::language('hotel_address');?></strong> :<?php echo HOTEL_ADDRESS ;?> 
                </td>
    			
            </tr>
			<tr valign="top">
                <td align="left">
    			<strong><?php echo Portal::language('tax_code');?></strong> :<?php echo HOTEL_TAXCODE;?> 
                </td>
    			
            </tr>
		</table>
		
				<?php
				}
				?>

<?php

if($this->map['total_page']==0)
{

?>
    <div style="padding:20px;">
    	<h3><?php echo Portal::language('no_result_matchs');?></h3>
    	<a href="<?php echo Url::build_current();?>"><?php echo Portal::language('back');?></a>
    </div>
<?php        
    }
?>