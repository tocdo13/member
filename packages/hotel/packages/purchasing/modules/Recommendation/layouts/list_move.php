<style>
.center{text-align:center;}
</style>
<div class="room-type-supplier-bound">
<form name="ListMoveForm" method="post">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td width="100%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.require_transfer_invoice.]]</td>
            <td></td>
        </tr>
    </table>
            
    <div class="content">
        <table width="80%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
            <tr class="w3-light-gray" style="text-transform: uppercase; height: 24px;">
              <th width="30" class="center">[[.stt.]]</th>
              <th width="100" align="center">[[.recomment_date.]]</th>
                <th width="150" align="center">[[.person_recomment.]]</th>
                <th width="150" align="center">[[.department.]]</th>
                <th width="400" align="center">[[.description.]]</th>
                <th width="40" align="center">[[.view.]]</th>
          </tr>
          <!--LIST:items-->
          <?php
          if([[=items.index=]]%2==0)
          {
            $style='style="background-color: #E8F3FF"';
          } 
          else
            $style ="";
          ?>
            <tr <?php echo $style;?> style="height: 24px;">
              <td align="center">[[|items.index|]]</td>
                <td align="center">[[|items.recommend_date|]]</td>
                <td>[[|items.recommend_person|]]</td>
                <td>[[|items.department|]]</td>
                <td>[[|items.description|]]</td>
                <td align="center"><?php if(User::can_view(false,ANY_CATEGORY)){?><a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'view_move','id'=>[[=items.id=]]));?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a><?php }?></td>
            </tr>
            <!--/LIST:items-->      
        </table>
<br />
        
    </div>
    <input name="cmd" type="hidden" value="">
</form> 
</div>


<script type="text/javascript">
</script>