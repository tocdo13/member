<div class="report-bound" style=" page:land;">
<div >
<?php 
				if(($this->map['page_no']==$this->map['start_page'] or $this->map['page_no'] == 0 ))
				{?>
<link rel="stylesheet" href="skins/default/report.css"/>
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page($this->map['page_no']==1)-->
		<table cellSpacing=0 width="100%" style="font-size:12px;">
			<tr>
            	<td align="left" width="60%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
            
            <td align="right">
            <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
            </td>
            </tr> 
		</table>
		<font class="report_title"><?php echo Portal::language('product_revenue_report');?></font>
        <!--Luu Nguyen Giap add search nha hang end-->
        <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
        <?php echo Url::get('bar_names');?>
        </div>
        <!--Luu Nguyen Giap add search nha hang end-->
        <?php 
        if(Url::get('search_time')){
         ?>
           	<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"> <?php echo Portal::language('from_b');?>: <?php 
echo (Url::get('from_time')).'-'.(Url::get('from_date_tan'));?>&nbsp;&nbsp;<?php echo Portal::language('to_b');?>:<?php echo (Url::get('to_time')).'-'.(Url::get('to_date_tan'));?><br />
		      
        <?php    
        }
        ?>
        <?php
         if(Url::get('search_invoice')){ ?>
        <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
            <?php if($this->map['from_bill']!=''){ ?> <?php echo Portal::language('from_bill');?> <?php echo $this->map['from_bill']; } ?> <?php if($this->map['to_bill']!=''){ ?> <?php echo Portal::language('to_bill');?> <?php echo $this->map['to_bill']; } ?> 
        </div>
        <?php } ?>
        <?php 
				if((Url::get('customer_name')))
				{?><?php echo Portal::language('customer');?>: <?php echo Url::sget('customer_name');?>
				<?php
				}
				?>
		
				<?php
				}
				?>