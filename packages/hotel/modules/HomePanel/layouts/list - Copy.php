<table style="margin:0 auto;">
<tr>
	<td>
    	<div style="width:258px; float:left;border-top:1px dotted #CCC; border-right:1px dotted #CCC;">
       <!--IF:cond(User::can_view($this->get_module_id('RoomMap'),ANY_CATEGORY))-->
            <div style="float:left; width:258px; padding:0px; margin-bottom:0px" onclick="window.location='<?php echo Url::build('room_map');?>'">
                <div class="bod" style="float:left; border-top:2px solid #FF3300;">
                      <div class="icon-home">
                            <img src="packages/hotel/skins/default/images/icons/icon_home/fontdesk.png" class="img-icon-home" />
                      </div><div class="clearm-all"></div>
                      <div class="icon-name" style="border:none;">[[.reception.]]</div>
                  </div>
                  <div style="width:130px; float:right; height:100%"></div>
             </div>
       <!--/IF:cond-->
        <!--IF:cond(User::can_view($this->get_module_id('RoomMap'),ANY_CATEGORY))-->
             <div style="float:left; width:258px; padding-top:8px;" onclick="window.location='<?php echo Url::build('room_map');?>'">
                <div class="bod" style="float:left; border-bottom:2px solid #FF3300;">
                    <div class="icon-home"> 
                        <img src="packages/hotel/skins/default/images/icons/icon_home/hskp.png" class="img-icon-home" />
                    </div>
                    <div class="clearm-all"></div>
                    <div class="icon-name">[[.housekeeping.]]</div>
                 </div>
                 <div style="width:135px; border-bottom:1px dotted #CCC; float:right;"></div>
              </div>
       <!--/IF:cond-->
        <!--IF:cond(User::can_view($this->get_module_id('TableMap'),ANY_CATEGORY))-->
              <div style="float:left; width:258px; padding-top:5px; padding-bottom:0px; margin-bottom:0px" id="restaurant" onclick="window.location='<?php echo Url::build('table_map');?>'">
                <div class="bod" style="float:left; border-bottom:2px solid #FF3300;">
                    <div class="icon-home">
                        <img src="packages/hotel/skins/default/images/icons/icon_home/Menu.png" class="img-icon-home" />
                    </div>
                    <div class="icon-name" id="restaurant">[[.restaurant.]]</div>
                </div>
                 <div style="width:135px; border-bottom:1px dotted #CCC; float:right;"></div>
             </div>
      <!--/IF:cond-->
      <!--IF:cond(User::can_view($this->get_module_id('MassageDailySummary'),ANY_CATEGORY))-->
             <div style="float:left; width:258px; padding-top:5px;" id="massage" onclick="window.location='<?php echo Url::build('massage_daily_summary');?>'">
                 <div class="bod" style="float:left; border-bottom:2px solid #FF3300;">
                     <div class="icon-home">
                         <img src="packages/hotel/skins/default/images/icons/icon_home/masage.png" class="img-icon-home" />
                     </div>
                      <div class="icon-name">[[.massage.]]</div>  
                 </div>
                 <div style="width:135px; border-bottom:1px dotted #CCC; float:right;"></div>
            </div>
      <!--/IF:cond-->
      <!--IF:cond(User::can_view($this->get_module_id('TennisDailySummary'),ANY_CATEGORY))-->        
            <div style="float:left; width:258px; padding-top:5px;" id="tennis" onclick="window.location='<?php echo Url::build('tennis_daily_summary');?>'">
             <div class="bod" style="float:left; border-bottom:2px solid #FF3300;">
                 <div class="icon-home">
                    <img src="packages/hotel/skins/default/images/icons/icon_home/tennis.png" class="img-icon-home" />
                 </div>
                 <div class="icon-name">[[.tennis.]]</div>
              </div>
              <div style="width:135px; border-bottom:1px dotted #CCC; float:right; height:100%;"></div>
            </div>        
        </div>
         <!--/IF:cond-->  
     </div>
    </td>
    <td valign="middle">
       		<div class="root-hms">HMS</div>
            
    </td>
    <td>
    		 <div style="width:250px; float:right; padding:0;  border-left:1px dotted #CCC; margin-left:-3px;">
          <!--IF:cond(User::can_view($this->get_module_id('OverviewReport'),ANY_CATEGORY))--> 
     		<div style="float:right; width:250px;" onclick="window.location='<?php echo Url::build('overview_report');?>'">
        	<div style="width:123px; border-bottom:1px dotted #CCC; float:left; height:100%;"></div>       
             <div class="bod" style="float:right; border-top:2px solid #FF3300; padding-top:5px;">
                 <div class="icon-home">
                     <img src="packages/hotel/skins/default/images/icons/icon_home/director.png" class="img-icon-home" />
                 </div>
                <div class="icon-name">[[.director.]]</div>
             </div>
        </div>
		<!--/IF:cond-->
     <!--IF:cond(User::can_view($this->get_module_id('MonthlyRoomReport'),ANY_CATEGORY))-->        
     	<div style="float:right; width:250px; padding-top:5px; margin:0px; border-bottom:1px dotted #CCC;" onclick="window.location='<?php echo Url::build('monthly_room_report&manager');?>'">
            <div style=" float:left; width:119px;"></div>
             <div class="bod" style="float:right; border-bottom:2px solid #FF3300;">
                <div class="icon-home">
                <img src="packages/hotel/skins/default/images/icons/icon_home/car-rantal.png" class="img-icon-home" />
                </div>
              <div class="icon-name">[[.sales.]]</div>
             </div>
        </div>
     <!--/IF:cond-->
      <!--IF:cond(User::can_view($this->get_module_id('DetailDebitReport'),ANY_CATEGORY))-->        
     	<div style="float:right; width:250px; padding-top:5px; margin:0px; border-bottom:1px dotted #CCC;" onclick="window.location='<?php echo Url::build('detail_debit_report');?>'">      
            <div style="width:119px; float:left; height:100%;"></div>
             <div class="bod" style="float:right; border-bottom:2px solid #FF3300;">  
                <div class="icon-home">
                <img src="packages/hotel/skins/default/images/icons/icon_home/cashier.png" class="img-icon-home" />
                 </div>
               <div class="icon-name">[[.accounting_report.]]</div>
              </div>
        </div>
           <!--/IF:cond-->
             <!--IF:cond(User::can_view($this->get_module_id('WarehouseReport'),ANY_CATEGORY))-->        
    	<div style="float:right; width:250px; padding-top:5px; border-bottom:1px dotted #CCC;" id="warehouse" onclick="window.location='<?php echo Url::build('warehouse_report');?>'"> 
            <div style="width:128px;float:left; height:100%;"></div>
             <div class="bod" style="float:right; border-bottom:2px solid #FF3300;">
                <div class="icon-home">
                	<img src="packages/hotel/skins/default/images/icons/icon_home/kho.png" class="img-icon-home" />
                </div>
                <div class="icon-name">[[.warehouse.]]</div>
              </div>
        </div>       
           <!--/IF:cond-->
           <!--IF:cond(User::can_view($this->get_module_id('SwimmingPoolDailySummary'),ANY_CATEGORY))-->        
            <div style="float:right; width:258px; padding-top:5px;  border-bottom:1px dotted #CCC" id="swimming" onclick="window.location='<?php echo Url::build('swimming_pool_daily_summary');?>'"> 
                <div style="width:130px; float:left; height:100%;"></div>
                 <div class="bod" style="float:right; border-bottom:2px solid #FF3300;">
                   <div class="icon-home">
                   <img src="packages/hotel/skins/default/images/icons/icon_home/swimming.png" class="img-icon-home" />
                   </div>
                  <div class="icon-name">[[.swimming.]]</div>
                 </div>
            </div>
           <!--/IF:cond-->
         </div>
    </td>
</tr>
</table>
<script>
  var bar = <?php echo(HAVE_RESTAURANT); ?>;
	 if(bar == 0){
		 jQuery('#restaurant').css('display','none');
	 }
	var masage = <?php echo(HAVE_MASSAGE); ?>;
	if(masage == 0){
		 jQuery('#massage').css('display','none');
	}
	var tennis =<?php echo(HAVE_TENNIS); ?>;
	if(tennis == 0){
		 jQuery('#tennis').css('display','none');
	}
	var swimming = <?php echo(HAVE_SWIMMING);?>;
	if(swimming==0){
		 jQuery('#swimming').css('display','none');
	}
	var warehouse = <?php echo(HAVE_WAREHOUSE);?>;
	if(warehouse == 0){
		 jQuery('#warehouse').css('display','none');
	}
</script>


