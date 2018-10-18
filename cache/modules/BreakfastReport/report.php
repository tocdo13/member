<style>
	 @media print {
	   fieldset {
	       display: none;
	   }
  }	
</style>
<div id="header">
    <table style="width: 98%; margin: 0px auto;">
        <tr>
            <td style="font-weight: bold;"><?php echo HOTEL_NAME; ?><br /><?php echo HOTEL_ADDRESS; ?></td>
            <td style="text-align: right; font-weight: bold;"><?php echo Portal::language('template_code');?><br /><?php echo Portal::language('date');?>: <?php echo date('H:i d/m/Y'); ?><br /><?php echo Portal::language('user');?>: <?php echo User::id(); ?></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><p style="font-size: 20px; font-weight: bold; text-transform: uppercase;"><?php echo Portal::language('breakfast_report');?></p><p><?php echo Portal::language('from_date');?>: <?php echo $this->map['from_date'];?> <?php echo Portal::language('to_date');?>: <?php echo $this->map['to_date'];?> </p><p><?php echo Portal::language('breakfast_from_time');?>: <?php echo BREAKFAST_FROM_TIME; ?> <?php echo Portal::language('breakfast_to_time');?>: <?php echo BREAKFAST_TO_TIME; ?></p></td>
        </tr>
    </table>
</div><!-- end header -->

<div id="search" style="width: 98%; margin: 0px auto;">
    <form name="BreakfastReportForm" method="post">
        <fieldset style="border: 1px solid #00b2f9;">
            <legend  style="border: 1px solid #00b2f9; background: #ffffff; border-left: 3px solid #00b2f9; border-right: 3px solid #00b2f9; padding: 5px 20px;"><?php echo Portal::language('option');?></legend>
            <table style="width: 100%;">
                <tr>
                    <td>
                        <?php echo Portal::language('hotel');?>: <select  name="portal_id" id="portal_id" style="width: 120px; text-align: center;"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select>
                    </td>
                    <td>
                        <?php echo Portal::language('from_date');?>: <input  name="from_time" id="from_time" style="width: 35px; text-align: center; display: none;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>">
                        <input  name="from_date" id="from_date" style="width: 80px; text-align: center;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                    </td>
                    <td>
                        <?php echo Portal::language('to_date');?>: <input  name="to_time" id="to_time" style="width: 35px; text-align: center; display: none;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>">
                        <input  name="to_date" id="to_date" style="width: 80px; text-align: center;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
                    </td>
                    <!--trung add search customer-->
                    <td>
                        <?php echo Portal::language('customer_name');?> : <input  name="customer_name" id="customer_name" onkeypress="Autocomplete();"  autocomplete="off"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"> 
                        <input  name="customer_id" id="customer_id" class="hidden" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
                    </td>
                    <!-- ednd :trung add search customer-->
                    <td>
                        <?php echo Portal::language('line_per_page');?>: <input  name="line_per_page" id="line_per_page" style="width: 20px; text-align: center;" / type ="text" value="<?php echo String::html_normalize(URL::get('line_per_page'));?>">
                    </td>
                    <td>
                        <?php echo Portal::language('no_of_page');?>: <input  name="no_of_page" id="no_of_page" style="width: 20px; text-align: center;" / type ="text" value="<?php echo String::html_normalize(URL::get('no_of_page'));?>">
                    </td>
                    <td>
                        <?php echo Portal::language('start_page');?>: <input  name="start_page" id="start_page" style="width: 20px; text-align: center;" / type ="text" value="<?php echo String::html_normalize(URL::get('start_page'));?>">
                    </td>
                    <td>
                        <input name="search" value="<?php echo Portal::language('view_report');?>" type="submit" style="border: 1px solid #00b2f9; background: #ffffff; padding: 5px; font-weight: bold;" />
                    </td>
                </tr>
            </table>
        </fieldset>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div><!-- end search -->

<div id="report" style="width: 98%; margin: 0px auto;">
    <table style="width: 100%; margin: 10px auto; border-spacing: inherit; border-collapse: 1px;" cellpadding="5" cellspacing="0" border="1" bordercolor="#5d5d5d">
        <tr style="background: #eeeeee;">
            <th rowspan="2" style="text-align: center; width: 40px;"><?php echo Portal::language('stt');?></th>
            <th rowspan="2" style="text-align: center; width: 50px;"><?php echo Portal::language('recode');?></th>
            <th rowspan="2" style="text-align: center; width: 15%;"><?php echo Portal::language('customer1');?></th>
            <th rowspan="2" style="text-align: center;"><?php echo Portal::language('traveller_name');?></th>
            <th rowspan="2" style="text-align: center;"><?php echo Portal::language('nationality');?></th>
            <th rowspan="2" style="text-align: center;"><?php echo Portal::language('room_name');?></th>
            <th colspan="3" style="height: 25px; text-align: center;"><?php echo Portal::language('people');?></th>
            <th rowspan="2" style="text-align: center; width: 5%;"><?php echo Portal::language('breakfast');?></th>
            <th rowspan="2" style="text-align: center; width: 110px;"><?php echo Portal::language('arrival_time');?></th>
            <th rowspan="2" style="text-align: center; width: 110px;"><?php echo Portal::language('departure_time');?></th>
            <th rowspan="2" style="text-align: center;"><?php echo Portal::language('note');?></th>
        </tr>
        <tr style="background: #eeeeee;">
            <th style="height: 25px; text-align: center;width:70px ;"><?php echo Portal::language('adult');?></th>
            <th style="text-align: center;width:70px ;"><?php echo Portal::language('child');?></th>
            <th style="text-align: center;width:70px ;"><?php echo Portal::language('child_5');?></th><!--them cot tr.e<5t-->            
        </tr>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <?php $chil = ''; ?>
        <tr>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['stt'];?></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><a href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['reservation_id'];?>&r_r_id=<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['reservation_id'];?></a></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['customer_name_1'];?></td>   
                <?php if(isset($this->map['items']['current']['child_traveller']) and is_array($this->map['items']['current']['child_traveller'])){ foreach($this->map['items']['current']['child_traveller'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['child_traveller']['current'] = &$item2;?>
                <?php $chil = $this->map['items']['current']['child_traveller']['current']['id']; ?>
                <td><?php echo  mb_convert_case($this->map['items']['current']['child_traveller']['current']['traveller_name'], MB_CASE_TITLE, 'UTF-8');  ?></td>
                <td><?php echo $this->map['items']['current']['child_traveller']['current']['code_name'];?></td>
                <?php break; ?>
                <?php }}unset($this->map['items']['current']['child_traveller']['current']);} ?>
            
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['room_name'];?></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['adult'];?></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child'];?></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child_5'];?></td>                        
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php if($this->map['items']['current']['breakfast']==1){ echo "Yes"; }else{ echo "No"; } ?></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['time_in'];?></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['time_out'];?></td>
            <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['note'];?></td>
        </tr>
        <?php if($this->map['items']['current']['count_child']>1){ ?>
            <?php if(isset($this->map['items']['current']['child_traveller']) and is_array($this->map['items']['current']['child_traveller'])){ foreach($this->map['items']['current']['child_traveller'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['child_traveller']['current'] = &$item3;?>
                <?php if($chil!=$this->map['items']['current']['child_traveller']['current']['id']){ ?>
                <tr>
                    <td> <?php echo ucwords(mb_strtolower($this->map['items']['current']['child_traveller']['current']['traveller_name'], 'UTF-8'))  ?></td>
                    <td><?php echo $this->map['items']['current']['child_traveller']['current']['code_name'];?></td>
                </tr>
                <?php } ?>
            <?php }}unset($this->map['items']['current']['child_traveller']['current']);} ?>
        <?php } ?>
        <?php }}unset($this->map['items']['current']);} ?>
        <tr>
            <td colspan="5"><?php echo Portal::language('total');?>:</td>
            <td><?php echo $this->map['total_room'];?></td>
            <td><?php echo $this->map['total_adult'];?></td>
            <td><?php echo $this->map['total_child'];?></td>
            <td ><?php echo $this->map['total_child_5'];?></td>
            <td colspan="4"></td>
        </tr>
    </table>
    
    <table style="width: 30%; margin: 10px auto; border-spacing: inherit; border-collapse: 1px;" cellpadding="5" cellspacing="0" border="1" bordercolor="#5d5d5d">
    <tr>
        <td colspan="3"><div style="text-align: center;font-weight:bold;"> Thống kê tổng số quốc tịch</div></td>
    </tr>
    
    <?php 
        $stt=0;
        foreach($this->map['nationality'] as $k=>$val){
            echo '
                <tr>
                <td>'.++$stt.'</td>
                <td>'.$k.'</td>
                <td>'.$val.'</td>
                </tr>
            ';
        }
    ?>
   
    </table>
</div><!-- end report -->

<div id="footer">

</div><!-- end footer -->
<script>
    function Autocomplete()
{
    jQuery("#customer_name").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item) {
             //console.log(item);
            document.getElementById('customer_id').value = item.data[0];
           // console.log(document.getElementById('customer_id').value);
        }
    }) ;
}


    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>