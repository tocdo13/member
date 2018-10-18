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
            <td style="text-align: right; font-weight: bold;">[[.template_code.]]<br />[[.date.]]: <?php echo date('H:i d/m/Y'); ?><br />[[.user.]]: <?php echo User::id(); ?></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><p style="font-size: 20px; font-weight: bold; text-transform: uppercase;">[[.breakfast_report.]]</p><p>[[.from_date.]]: [[|from_date|]] [[.to_date.]]: [[|to_date|]] </p><p>[[.breakfast_from_time.]]: <?php echo BREAKFAST_FROM_TIME; ?> [[.breakfast_to_time.]]: <?php echo BREAKFAST_TO_TIME; ?></p></td>
        </tr>
    </table>
</div><!-- end header -->

<div id="search" style="width: 98%; margin: 0px auto;">
    <form name="BreakfastReportForm" method="post">
        <fieldset style="border: 1px solid #00b2f9;">
            <legend  style="border: 1px solid #00b2f9; background: #ffffff; border-left: 3px solid #00b2f9; border-right: 3px solid #00b2f9; padding: 5px 20px;">[[.option.]]</legend>
            <table style="width: 100%;">
                <tr>
                    <td>
                        [[.hotel.]]: <select name="portal_id" id="portal_id" style="width: 120px; text-align: center;"></select>
                    </td>
                    <td>
                        [[.from_date.]]: <input name="from_time" type="text" id="from_time" style="width: 35px; text-align: center; display: none;" />
                        <input name="from_date" type="text" id="from_date" style="width: 80px; text-align: center;" />
                    </td>
                    <td>
                        [[.to_date.]]: <input name="to_time" type="text" id="to_time" style="width: 35px; text-align: center; display: none;" />
                        <input name="to_date" type="text" id="to_date" style="width: 80px; text-align: center;" />
                    </td>
                    <!--trung add search customer-->
                    <td>
                        [[.customer_name.]] : <input name="customer_name" type="text" id="customer_name" onkeypress="Autocomplete();"  autocomplete="off"/> 
                        <input name="customer_id" type="text" id="customer_id" class="hidden" />
                    </td>
                    <!-- ednd :trung add search customer-->
                    <td>
                        [[.line_per_page.]]: <input name="line_per_page" type="text" id="line_per_page" style="width: 20px; text-align: center;" />
                    </td>
                    <td>
                        [[.no_of_page.]]: <input name="no_of_page" type="text" id="no_of_page" style="width: 20px; text-align: center;" />
                    </td>
                    <td>
                        [[.start_page.]]: <input name="start_page" type="text" id="start_page" style="width: 20px; text-align: center;" />
                    </td>
                    <td>
                        <input name="search" value="[[.view_report.]]" type="submit" style="border: 1px solid #00b2f9; background: #ffffff; padding: 5px; font-weight: bold;" />
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div><!-- end search -->

<div id="report" style="width: 98%; margin: 0px auto;">
    <table style="width: 100%; margin: 10px auto; border-spacing: inherit; border-collapse: 1px;" cellpadding="5" cellspacing="0" border="1" bordercolor="#5d5d5d">
        <tr style="background: #eeeeee;">
            <th rowspan="2" style="text-align: center; width: 40px;">[[.stt.]]</th>
            <th rowspan="2" style="text-align: center; width: 50px;">[[.recode.]]</th>
            <th rowspan="2" style="text-align: center; width: 15%;">[[.customer1.]]</th>
            <th rowspan="2" style="text-align: center;">[[.traveller_name.]]</th>
            <th rowspan="2" style="text-align: center;">[[.nationality.]]</th>
            <th rowspan="2" style="text-align: center;">[[.room_name.]]</th>
            <th colspan="3" style="height: 25px; text-align: center;">[[.people.]]</th>
            <th rowspan="2" style="text-align: center; width: 5%;">[[.breakfast.]]</th>
            <th rowspan="2" style="text-align: center; width: 110px;">[[.arrival_time.]]</th>
            <th rowspan="2" style="text-align: center; width: 110px;">[[.departure_time.]]</th>
            <th rowspan="2" style="text-align: center;">[[.note.]]</th>
        </tr>
        <tr style="background: #eeeeee;">
            <th style="height: 25px; text-align: center;width:70px ;">[[.adult.]]</th>
            <th style="text-align: center;width:70px ;">[[.child.]]</th>
            <th style="text-align: center;width:70px ;">[[.child_5.]]</th><!--them cot tr.e<5t-->            
        </tr>
        <!--LIST:items-->
        <?php $chil = ''; ?>
        <tr>
            <td rowspan="[[|items.count_child|]]">[[|items.stt|]]</td>
            <td rowspan="[[|items.count_child|]]"><a href="?page=reservation&cmd=edit&id=[[|items.reservation_id|]]&r_r_id=[[|items.id|]]">[[|items.reservation_id|]]</a></td>
            <td rowspan="[[|items.count_child|]]">[[|items.customer_name_1|]]</td>   
                <!--LIST:items.child_traveller-->
                <?php $chil = [[=items.child_traveller.id=]]; ?>
                <td><?php echo  mb_convert_case([[=items.child_traveller.traveller_name=]], MB_CASE_TITLE, 'UTF-8');  ?></td>
                <td>[[|items.child_traveller.code_name|]]</td>
                <?php break; ?>
                <!--/LIST:items.child_traveller-->
            
            <td rowspan="[[|items.count_child|]]">[[|items.room_name|]]</td>
            <td rowspan="[[|items.count_child|]]">[[|items.adult|]]</td>
            <td rowspan="[[|items.count_child|]]">[[|items.child|]]</td>
            <td rowspan="[[|items.count_child|]]">[[|items.child_5|]]</td>                        
            <td rowspan="[[|items.count_child|]]"><?php if([[=items.breakfast=]]==1){ echo "Yes"; }else{ echo "No"; } ?></td>
            <td rowspan="[[|items.count_child|]]">[[|items.time_in|]]</td>
            <td rowspan="[[|items.count_child|]]">[[|items.time_out|]]</td>
            <td rowspan="[[|items.count_child|]]">[[|items.note|]]</td>
        </tr>
        <?php if([[=items.count_child=]]>1){ ?>
            <!--LIST:items.child_traveller-->
                <?php if($chil!=[[=items.child_traveller.id=]]){ ?>
                <tr>
                    <td> <?php echo ucwords(mb_strtolower([[=items.child_traveller.traveller_name=]], 'UTF-8'))  ?></td>
                    <td>[[|items.child_traveller.code_name|]]</td>
                </tr>
                <?php } ?>
            <!--/LIST:items.child_traveller-->
        <?php } ?>
        <!--/LIST:items-->
        <tr>
            <td colspan="5">[[.total.]]:</td>
            <td>[[|total_room|]]</td>
            <td>[[|total_adult|]]</td>
            <td>[[|total_child|]]</td>
            <td >[[|total_child_5|]]</td>
            <td colspan="4"></td>
        </tr>
    </table>
    
    <table style="width: 30%; margin: 10px auto; border-spacing: inherit; border-collapse: 1px;" cellpadding="5" cellspacing="0" border="1" bordercolor="#5d5d5d">
    <tr>
        <td colspan="3"><div style="text-align: center;font-weight:bold;"> Thống kê tổng số quốc tịch</div></td>
    </tr>
    
    <?php 
        $stt=0;
        foreach([[=nationality=]] as $k=>$val){
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