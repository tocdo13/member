<div class="report-bound" style=" page:land;">
<div>
<link rel="stylesheet" href="skins/default/report.css"/>
<style>
/*full m?n h?nh*/
.simple-layout-middle{width:100%;}
</style>

<?php echo Form::$current->error_messages();?>
<script>full_screen();</script>

<table cellpadding="10" cellspacing="0" width="300px">
<tr>
	<td align="center">
    
		<table cellspacing="0" width="300px">
			<tr valign="top">
            <td align="left">
                <strong><?php echo HOTEL_NAME;?></strong><br />
            </td>
                <td align="right">
                <strong><?php echo Portal::language('template_code');?></strong><br />
                <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                <br />
                <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
                </td>
			</tr>	
		</table>
        
        <font class="report_title" style="text-transform:uppercase; font-size: 15px;"><?php echo Portal::language('detailed_daily_report');?></font>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
    		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"><?php echo Portal::language('time');?> : 
                <?php if( isset( $this->map['time_from'] ) ) echo $this->map['time_from']; ?>
                <?php if( isset( $this->map['start_shift_time'] ) ) echo $this->map['start_shift_time']; ?> 
                - 
                <?php if( isset( $this->map['time_to'] ) ) echo $this->map['time_to']; ?>
                <?php if( isset( $this->map['end_shift_time'] ) ) echo $this->map['end_shift_time']; ?>
            </div>
            <br />
            <?php echo Url::get('bar_id')?Portal::language('bar_id').' '.DB::fetch('select name from bar where id=\''.Url::get('bar_id').'\'','name'):'';?>
        </div>

        <div style="font-family:'Times New Roman', Times, serif;font-weight:bold; text-align: left;">
            <?php echo Url::get('user_id')?Portal::language('employee').': '.DB::fetch('select name_1 from party where user_id=\''.Url::get('user_id').'\'','name_1'):'';?>
        </div>

        <br />
        <br />  
        
        