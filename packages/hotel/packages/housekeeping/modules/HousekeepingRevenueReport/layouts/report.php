<form name="HousekeepingRevenueReportFrom" method="post"><div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]"  /></div>
</form>
<p style="margin-left: 15px;">[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></p>

<table cellpadding="5" cellspacing="0" width="98%" border="1" bordercolor="#CCCCCC" style="text-align: center; margin: 5px auto;" id="export">
    <tr style="font-weight: bold; background: #eeeeee;">
        <th>[[.stt.]]</th>
        
        <th>[[.product_code.]]</th>
        <th>[[.product_name.]]</th>
        <th>[[.unit.]]</th>
        <th>[[.original_price.]]</th>
        <th>[[.price.]]</th>
        <th>[[.discount.]](%)</th>
        <th>[[.quantity.]]</th>
        <th>[[.promotion.]]</th>
        <th>[[.original_amount.]]</th>   
        <th>[[.amount.]]</th>
        <th>[[.interest_rate.]]</th>
    </tr>
    <?php 
    $i = 1;
    $product_id = '';
    $total_before_tax ='';
    $discount ='';
    //System::debug($this ->map['count'])
    ?>
    <!--LIST:categories-->
    <tr>
        <td colspan="12" style="text-transform: uppercase; font-weight: bold;">[[|categories.name|]]</td>
    </tr>
    <?php $total1=0;$total2=0;$total3=0; ?>
    <!--LIST:categories.items-->
    <tr>
    <?php 
    if([[=categories.items.product_id=]] != $product_id)
    {
        $total_before_tax ='';
        $discount ='';
        $product_id = [[=categories.items.product_id=]];
        $rowspan = $this -> map['count'][$product_id]['count'];
    ?>
        <td rowspan="<?php echo $rowspan ?>"><?php echo $i++ ?></td>
        <td rowspan="<?php echo $rowspan ?>">[[|categories.items.product_id|]]</td>
        <td rowspan="<?php echo $rowspan ?>">[[|categories.items.product_name|]]</td>   
        <td rowspan="<?php echo $rowspan ?>">[[|categories.items.unit|]]</td>
    <?php
    }
     if([[=categories.items.product_id=]].'_'.[[=categories.items.total_before_tax=]].'_'.[[=categories.items.discount=]] != $product_id.'_'.$total_before_tax.'_'.$discount)
     {
        $total_before_tax = [[=categories.items.total_before_tax=]];
        $discount = [[=categories.items.discount=]];
        $rowspan_child =$this->map['count'][$product_id][$total_before_tax][$discount];
     ?>
        <td rowspan="<?php echo $rowspan_child ?>" class="change_numTr"><?php echo System::display_number(round([[=categories.items.original_total_before_tax=]],2));?></td>
        <td rowspan="<?php echo $rowspan_child ?>" class="change_numTr"><?php echo System::display_number(round([[=categories.items.total_before_tax=]]));?></td>
        <td rowspan="<?php echo $rowspan_child ?>">[[|categories.items.discount|]](%)</td>
        <td rowspan="<?php echo $rowspan_child ?>"><?php echo $this->map['count'][$product_id][$total_before_tax][$discount."_quantity"];  ?></td>
        <td rowspan="<?php echo $rowspan_child ?>">[[|categories.items.promotion|]]</td>
        <td rowspan="<?php echo $rowspan_child ?>" style="text-align: right;" class="change_numTr"><?php echo System::display_number(round($this->map['count'][$product_id][$total_before_tax][$discount."_original_total_before"])); $total1+=$this->map['count'][$product_id][$total_before_tax][$discount."_original_total_before"];  ?></td>
        <td rowspan="<?php echo $rowspan_child ?>" style="text-align: right;" class="change_numTr"><?php echo System::display_number(round($this->map['count'][$product_id][$total_before_tax][$discount."_total_before"])); $total2+=$this->map['count'][$product_id][$total_before_tax][$discount."_total_before"];  ?></td>
        <td rowspan="<?php echo $rowspan_child ?>" style="text-align: right;" class="change_numTr"><?php echo System::display_number(round($this->map['count'][$product_id][$total_before_tax][$discount."_total_before"]-$this->map['count'][$product_id][$total_before_tax][$discount."_original_total_before"])); $total3+=$this->map['count'][$product_id][$total_before_tax][$discount."_total_before"]-$this->map['count'][$product_id][$total_before_tax][$discount."_original_total_before"];  ?></td>
      <?php
      }
      ?>
      
    <!--/LIST:categories.items-->
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="9">[[.total_revenue.]] [[.of.]] [[|categories.name|]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round($total1)); ?></td>
        <td class="change_numTr"><?php echo System::display_number(round($total2)); ?></td>
        <td class="change_numTr"><?php echo System::display_number(round($total3)); ?></td>
      </tr>  
    </tr>
    <!--/LIST:categories-->
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="9">[[.total_revenue.]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round([[=original_total_befor_tax=]])); ?></td>
        <td class="change_numTr"><?php echo System::display_number(round([[=total_befor_tax=]])); ?></td>
        <td class="change_numTr"><?php echo System::display_number(round([[=interest_rate_total=]])); ?></td>
    </tr>
    <?php
        if([[=type=]]==2)
        {
    ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="10">[[.total_express_rate.]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round([[=total_express_rate=]])); ?></td>
        <td></td>
    </tr>
    <?php } ?>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="10">[[.total_fee.]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round([[=total_fee_rate=]])); ?></td>
        <td></td>
    </tr>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="10">[[.total_tax.]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round([[=total_tax_rate=]])); ?></td>
        <td></td>
    </tr>
    <tr style="text-align: right; font-weight: bold;">
        <td colspan="10">[[.summary.]]:</td>
        <td class="change_numTr"><?php echo System::display_number(round([[=total_amount=]])); ?></td>
        <td></td>
    </tr>
</table>
<script>
jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>