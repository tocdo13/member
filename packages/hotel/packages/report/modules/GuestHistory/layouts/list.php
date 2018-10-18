<script>
	traveller_array_items = {
		'length':'<?php echo sizeof([[=items=]]);?>'
<!--LIST:items-->
,'[[|items.i|]]':'[[|items.id|]]'
<!--/LIST:items-->
	}
</script>
<style>
	.link_url{
		color:#03F;	
	}
    #hover:hover{
        background: #cccccc;
    }
</style>
<table cellpadding="15" cellspacing="0" width="100%" border="0" class="table-bound">
		<tr>
        	<td width="90%" class="form-title">[[.guest_history.]]</td>
        </tr>
</table> 
<table bgcolor="#FFFFFF" cellspacing="0" width="100%">
<tr>
	<td width="100%" align="center" > 
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			<form name="SearchGuestHistoryForm" method="post">
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
                    <td>
                        [[.full_name.]]:<input name="traveller_name" type="text" id="traveller_name" style="width:150px" />
                        [[.birthday.]]:<input name="birthday" type="text" id="birthday" style="width:70px" />
                        [[.gender.]]:<select name="gender" id="gender" style="width:80px"></select>
                        [[.nationality.]]:<select name="nationality_id" id="nationality_id" style="width:100px"></select>
                        [[.company_name.]]:<input name="customer_name" type="text" id="customer_name" onfocus="customerAutocomplete();" style="width:205px" />
                        [[.booking_code.]]:<input name="booking_code" type="text" id="booking_code" style="width:60px" />
                    </td> 
                    
				</tr>
                <tr>
                    <td >
                        <!--Start Luu Nguyen Giap add portal -->
                        <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                        [[.hotel.]]
                        <select name="portal_id" id="portal_id" onchange="SearchGuestHistoryForm.submit()"></select>
                        <?php //}?>
                        <!--End Luu Nguyen Giap add portal -->
                        [[.from_day.]]:<input name="arrival_date" type="text" id="arrival_date" onchange="changevalue();" style="width:70px" />
                        [[.to_day.]]:<input name="departure_date" type="text" id="departure_date" onchange="changefromday();" style="width:70px" onchange="check_validate_time();" />
                        [[.passport.]]:<input name="passport" type="text" id="passport" style="width:100px" />
                        [[.room.]]:<select name="room_id" id="room_id" style="width:100px"></select>
                        [[.status.]]:<select name="status" id="status" style="width:100px;"></select>
                        [[.RE_code.]]:<input name="code" type="text" id="code" style="width:30px" />
                        <label>[[.number_of_guests_at_the_hotel.]] </label><input name="count_traveller" type="text" style="width:40px;text-align:center;"/>
                        <input type="submit" value="[[.search.]]" class="button-medium search" style="float:right;" />                                                                        
                    </td>                                    
				</tr>                        
			</table>            
		</form>
        <input name="export_excel" id="export_excel" type="button" id="export_excel" value="[[.export_excel.]]" style="width: 100px; height: 23px;"/>
		</fieldset><br />        
		<div class="content">        
		<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC">
		<tr class="table-header">
         <th width="1%" >[[.re_code.]]</th>
				<th align="left"  align="right" style="width: 230px; text-align:center"><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.birth_date' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.birth_date'));?>" title="[[.sort.]]">
                  <?php if(URL::get('order_by')=='traveller.birth_date') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				  [[.TA_company.]]</a></th>
                <th align="left" style="width: 200px; text-align:center">[[.email.]] - [[.phone.]] - [[.address.]]</th>
				<th align="left" style="width: 120px; text-align:center">[[.nationality.]]</th>
				<th align="left" style="width: 90px; text-align:center"><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.passport' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.passport'));?>" title="[[.sort.]]">
                  <?php if(URL::get('order_by')=='traveller.passport') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				  [[.passport.]]</a></th>
				<th align="left" style="width: 75px; text-align:center"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='time_in' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_in'));?>" title="[[.sort.]]">
                  <?php if(URL::get('order_by')=='time_in') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				  [[.date_in.]]</a></th>
				<th align="left" style="width: 75px; text-align:center">
					<a href="<?php echo URL::build_current(((URL::get('order_by')=='time_out' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_out'));?>" title="[[.sort.]]">
					<?php if(URL::get('order_by')=='time_out') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.date_out.]]</a></th>
				<th align="left" style="width: 150px; text-align:center">[[.room.]]</th>
                <th align="left" style="width: 50px; text-align:center">[[.created_person.]]</th>
                <th align="left" style="width: 70px; text-align:center">[[.to_judge.]]</th>
				<th align="left" style="width: 150px; text-align:center">[[.note.]]</th>
			</tr>
			<?php $temp = '';$i=1;?>
            <!--LIST:items-->
            <?php if($temp!=[[=items.full_name=]]){$temp == [[=items.full_name=]];?>
            <?php }?>
			<tr style="background-color: whitesmoke;">
             <th width="1%" ></th>
				<td><a href="#" class="link_url" onClick="window.open('?page=traveller&id=[[|items.id|]]');">
				<strong><?php echo $i;$i++;?>. </strong>[[|items.gender|]]. <strong>[[|items.full_name|]] (<?php echo count([[=items.vn_time=]]); ?>)</strong>
                <?php if([[=items.birth_date=]]!=null)echo '<br/>'.[[=items.birth_date=]];?></a>
                </td>
                <td align="left" style="text-align:left">
                    <!--IF:cond([[=items.traveller_email=]] != '')-->[[|items.traveller_email|]]<br /><!--/IF:cond-->
                    <!--IF:cond([[=items.traveller_phone=]] != '')--><em>phone: </em>[[|items.traveller_phone|]]<br /><!--/IF:cond-->
                    [[|items.address|]]
                </td>
				<td align="left" style="text-align:center">[[|items.nationality|]]</td>
				<td align="left" style="text-align:center"> [[|items.passport|]]</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <!--LIST:items.vn_time-->
                <tr id="hover">
                    <td width="1%" align="center" nowrap="nowrap"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.vn_time.reservation_id=]]));?>">[[|items.vn_time.reservation_id|]]</a></td>
                    <td>
                        <!--<i>[[.stay_time.]]:</i>[[|items.vn_time.arrival_time|]] - [[|items.vn_time.departure_time|]]-->
                        <?php if([[=items.vn_time.customer_name=]]!=null) echo '<strong>'.[[=items.vn_time.customer_name=]].'</strong>';?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>[[|items.vn_time.arrival_time|]]</td>
                    <td>[[|items.vn_time.departure_time|]]</td>
                    <td>
                        <?php echo '<strong> '.[[=items.vn_time.room_name=]].'</strong>('.[[=items.vn_time.status=]].') - '.System::display_number([[=items.vn_time.price=]]);?>
                    </td>
                    <td>[[|items.vn_time.user_id|]]</td>
                    <td>
                        <?php if([[=items.vn_time.to_judge=]]>0)
                            {
                                for($i=1;$i<=[[=items.vn_time.to_judge=]];$i++)
                                {    
                        ?>
                                <img style="height: 13px; width: auto; float: left;" src="resources\default\sao.png" />
                        <?php }}?>
                        <!--[[|items.vn_time.to_judge|]]<img style="height: 13px; width: auto; float: left;" src="resources\default\sao.png" />-->
                    </td>
                    <td>[[|items.vn_time.note|]]</td>
                </tr>
            <!--/LIST:items.vn_time-->
				
			<!--/LIST:items-->
	  </table>
      
      <!--Export excel -->
        <table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC" id="Export" style="display: none;">
    		<tr class="table-header">
             <th width="1%" >[[.re_code.]]</th>
    				<th align="left"  align="right" style="width: 230px; text-align:center"><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.birth_date' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.birth_date'));?>" title="[[.sort.]]">
                      <?php if(URL::get('order_by')=='traveller.birth_date') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
    				  [[.TA_company.]]</a></th>
                    <th align="left" style="width: 200px; text-align:center">[[.email.]] - [[.phone.]] - [[.address.]]</th>
    				<th align="left" style="width: 120px; text-align:center">[[.nationality.]]</th>
    				<th align="left" style="width: 90px; text-align:center"><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.passport' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.passport'));?>" title="[[.sort.]]">
                      <?php if(URL::get('order_by')=='traveller.passport') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
    				  [[.passport.]]</a></th>
    				<th align="left" style="width: 75px; text-align:center"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='time_in' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_in'));?>" title="[[.sort.]]">
                      <?php if(URL::get('order_by')=='time_in') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
    				  [[.date_in.]]</a></th>
    				<th align="left" style="width: 75px; text-align:center">
    					<a href="<?php echo URL::build_current(((URL::get('order_by')=='time_out' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_out'));?>" title="[[.sort.]]">
    					<?php if(URL::get('order_by')=='time_out') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.date_out.]]</a></th>
    				<th align="left" style="width: 150px; text-align:center">[[.room.]]</th>
                    <th align="left" style="width: 50px; text-align:center">[[.created_person.]]</th>                    
    				<th align="left" style="width: 150px; text-align:center">[[.note.]]</th>
    			</tr>
    			<?php $temp = '';$i=1;?>
                <!--LIST:items-->
                <?php if($temp!=[[=items.full_name=]]){$temp == [[=items.full_name=]];?>
                <?php }?>
    			<tr style="background-color: whitesmoke;">
                 <th width="1%" ></th>
    				<td><a href="#" class="link_url" onClick="window.open('?page=traveller&id=[[|items.id|]]');">
    				<strong><?php echo $i;$i++;?>. </strong>[[|items.gender|]]. <strong>[[|items.full_name|]] (<?php echo count([[=items.vn_time=]]); ?>)</strong>
                    <?php if([[=items.birth_date=]]!=null)echo '<br/>'.[[=items.birth_date=]];?></a>
                    </td>
                    <td align="left" style="text-align:left">
                        <!--IF:cond([[=items.traveller_email=]] != '')-->[[|items.traveller_email|]]<br /><!--/IF:cond-->
                        <!--IF:cond([[=items.traveller_phone=]] != '')--><em>phone: </em>[[|items.traveller_phone|]]<br /><!--/IF:cond-->
                        [[|items.address|]]
                    </td>
    				<td align="left" style="text-align:center">[[|items.nationality|]]</td>
    				<td align="left" style="text-align:center"> [[|items.passport|]]</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!--LIST:items.vn_time-->
                    <tr id="hover">
                        <td width="1%" align="center" nowrap="nowrap"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.vn_time.reservation_id=]]));?>">[[|items.vn_time.reservation_id|]]</a></td>
                        <td>
                            <!--<i>[[.stay_time.]]:</i>[[|items.vn_time.arrival_time|]] - [[|items.vn_time.departure_time|]]-->
                            <?php if([[=items.vn_time.customer_name=]]!=null) echo '<strong>'.[[=items.vn_time.customer_name=]].'</strong>';?>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>[[|items.vn_time.arrival_time|]]</td>
                        <td>[[|items.vn_time.departure_time|]]</td>
                        <td>
                            <?php echo '<strong> '.[[=items.vn_time.room_name=]].'</strong>('.[[=items.vn_time.status=]].') - '.System::display_number([[=items.vn_time.price=]]);?>
                        </td>
                        <td>[[|items.vn_time.user_id|]]</td>                                                
                        <td>[[|items.vn_time.note|]]</td>
                    </tr>
                <!--/LIST:items.vn_time-->    				
    			<!--/LIST:items-->
    	  </table>
      <!--Export excel -->
	  <input name="cmd" type="hidden" id="cmd" value="">
	  </form>
	  </div>
	</td>
</tr>
</table>
<div class="paging">[[|paging|]]</div>
<script>
        function customerAutocomplete()
    {
    	jQuery("#customer_name").autocomplete({
             url: 'get_customer_search.php?customer=1',
        onItemSelect: function(item) {
    			//getCustomerFromCode(jQuery("#customer_id").val());
                console.log('test');
                //document.waitingbooklist.submit();
    		}
        }) 
    }
	jQuery("#arrival_date").datepicker();
	jQuery("#departure_date").datepicker();
    jQuery("#birthday").datepicker();
	jQuery('#company_name').autocomplete({
	url:'r_get_customer.php?name=1',
			onItemSelect: function(item) {
		},
		formatItem: function(row, i, max) {
			return row.id + ' [<span style="color:#993300"> ' + row.name + '</span> ]';
		},
		formatMatch: function(row, i, max) {
			return row.id + ' ' + row.name;
		},
		formatResult: function(row) {			
			return row.id;
		}	
});
    
    function changevalue()
    {
        var myfromdate = $('arrival_date').value.split("/");
        var mytodate = $('departure_date').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#departure_date").val(jQuery("#arrival_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('arrival_date').value.split("/");
        var mytodate = $('departure_date').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#arrival_date").val(jQuery("#departure_date").val());
        }
    }
    jQuery('#export_excel').click(function(){
        jQuery("#Export").battatech_excelexport({
            containerid: "Export"
           , datatype: 'table'
           , fileName: '[[.guest_history.]]'
        });
    });    
</script>
