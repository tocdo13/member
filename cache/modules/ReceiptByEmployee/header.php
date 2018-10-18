<div class="report-bound" style=" page:land;">
<div >
<link rel="stylesheet" href="skins/default/report.css"/>
<?php 
				if(($this->map['page_no']==1))
				{?>
<?php echo Form::$current->error_messages();?>
<script>full_screen();</script>

				<?php
				}
				?>
<table cellpadding="10" cellspacing="0" width="300px">
<tr>
	<td align="center">
		
		<table cellSpacing=0 width="100%">
			<tr valign="top">
    			<td align="left" width="60%">
        			<strong><?php echo $this->map['hotel_name'];?></strong><br />
        			<?php //echo $this->map['hotel_address'];?>
                </td>
    			<td align="right" nowrap width="40%">
        			<strong><?php echo Portal::language('template_code');?></strong><br />
        			<i><?php echo Portal::language('promulgation');?></i>
                    <br />
                    <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                    <br />
                    <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
    			</td>
			</tr>	
		</table>
		
        <font class="report_title" style="text-transform:uppercase; font-size: 15px;">
            <?php echo Portal::language('receipt_by_employee_report');?>
        </font>
        
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"><?php echo Portal::language('date_from');?>: <?php echo Url::get('from_date') ?> <?php echo Portal::language('date_to');?> <?php echo Url::get('to_date') ?><br />
            <?php
             //echo Url::get('hotel_id');
             if(Url::get('bar_id') != 0)   
             {
                echo Url::get('bar_id')?Portal::language('bar_id').' '.DB::fetch('select name from bar where id=\''.Url::get('bar_id').'\'','name'):'';
             }
             else
             {
                $con_portal = '';
                if(Url::get('hotel_id'))
                {
                    if(Url::get('hotel_id') != 'ALL')
                    {
                        $con_portal = 'and portal_id=\''.Url::get('hotel_id').'\'';
                    }
                }
                $bars = DB::fetch_all('select id,name from bar where 1=1 '.$con_portal.'');
                $str_bars = '';
                foreach($bars as $row )
                {
                   $str_bars .= $row['name'].', ';
                }
                echo Portal::language('bar_id').' '.$str_bars;
               
             }
             ?>
        </div>
        
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"><?php echo Portal::language('time');?> : 
            <?php if( isset( $this->map['start_shift_time'] ) ) echo $this->map['start_shift_time']; ?> - <?php if( isset( $this->map['end_shift_time'] ) ) echo $this->map['end_shift_time']; ?>
        </div>
        <br />        
	