<style>
    .clear{
        clear:both;
    }
     th.x1:after{
    content:"";
    position:absolute;
    border-top:2px solid #ddd;
      width:97px;
      transform: rotate(35deg);
      transform-origin: 0% 0%;
    }
    .checkbox label{
        margin-left : 10px;
    }
    .checkbox label span{
        display:inline-block;
        padding-top: 3px;
    }
    #loopInfo{
        display:none;
    }
    div.indate label{
        width: 100%; float:left;
    }
    div.indate span.spFrom{
        display: block; float:left; padding:10px 5px 0 0;
    }
    div.indate span.spTo{
        display: block; float:left; display: block; float:left; padding:10px;
    }
    .icon{
 
    }
    div.indate button{
        margin: 6px 5px 0 10px;

    }
    div.inline label{
        float: left; margin-right: 15px; padding-top:10px;
    }
    .clear{
        clear: both;
    }
    #submit{
        display: none;
    }
    #next_content{
        display:none;
    }
    table.table-bordered td, table.table-bordered th{
        text-align: center;
    }
    table.table-bordered th{
    }
    .input_number{
        text-align:right;
    }
    #threadSchedule>div{
        color:maroon;
    }
    .Schedule{
        height:40px;
        line-height:40px;
    }
    .Schedule>div{
        float:left;
        border: black thin solid;
        box-sizing: border-box;
        border-right:none;
        padding-left:5px;
    }
    .Schedule>div:first-child{
        width:2%;
        height:inherit;
        line-height:inherit;
        border:none !important;
        padding-left: 0px;
    }
    .Schedule>div:nth-child(2),.Schedule>div:nth-child(3),.Schedule>div:nth-child(6){
        width:9%;
        height:inherit;
        line-height:inherit;
    }
    .Schedule>div:nth-child(5){
        width:14%;
        height:inherit;
        line-height:inherit;
    }
    .Schedule>div:nth-child(4){
        width:38%;
        height:inherit;
        line-height:inherit;
    }
    .Schedule>div:nth-child(9),.Schedule>div:nth-child(7),.Schedule>div:nth-child(8){
        width:5%;
        height:inherit;
        line-height:inherit;
        
    }
    .Schedule>div:nth-child(9){
        border-right:black thin solid;
    }
    div#myModal table td{
        padding:0px !important;
    }
    div#myModal table td label{
        padding:10px !important;
        margin-bottom:0px !important;
    }
    div.Schedule div.loopInfo{
        cursor:pointer;
    }
    div.Schedule div.loopInfo:hover{
        background:#FFFFB0;
        
    }
    #notice{
        position:fixed;
        left:77%;
        top: 550px;
        width:180px;
        height:35px;
        line-height:35px;
        background: #B16565;
        color: white;
        border-radius: 10px;
        display:none;
        text-align: center;
        font-size: 14px;
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="container-fuild">
        <form name="frm" method="POST" onsubmit="return checkAll();"> 
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo Portal::language('rate_code_infomation');?></h4>
            </div>
            <div class="panel-body">
                <div class="col-md-2 col-md-offset-10">
                    <input style="margin-right: 20px;" class="btn btn-info" type="submit" value="<?php echo Portal::language('save');?>"/>
                    <button class="btn btn-danger" type="button" onclick="if(confirm('<?php echo Portal::language('are_you_sure');?>')) window.close();"><?php echo Portal::language('close');?></button>
                </div>
                <div class="col-md-12">
                        <div>
                            <div class="form-group" style="width:10%; float:left; margin-right:90px; margin-left:90px">
                                <label><?php echo Portal::language('code');?> (*) :</label>
                                <input required="" class="form-control" name="code" <?php if(isset($this->map['schedule_list'])){ echo "readonly=''";}?> value="<?php echo $this->map['rate_code_code'];?>" />
                            </div>
                            <div class="form-group" style="width:20%; float:left; ">
                                <label><?php echo Portal::language('name');?> (*) :</label>
                                <input required="" class="form-control" name="name" value="<?php echo $this->map['rate_code_name'];?>" />
                            </div>
                            <div class="col-md-1 col-md-offset2" style="float: left; margin-top: 21px; margin-left: 50px;">
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#setTime" id="setSchedule"><?php echo Portal::language('create_schedule');?></button>
                                <div style="overflow-y: auto !important;" class="modal fade" id="setTime" tabindex="-1" role="dialog" aria-labelledby="setTimeLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="setTimeLabel"><?php echo Portal::language('create_schedule');?></h4>
                                      </div>
                                      <div class="modal-body">
                                           <div id="define_content">
                                                <div class="form-group inline">
                                                    <label style="width: 10%;"><?php echo Portal::language('loop');?></label>
                                                    <select onchange="stringDate();" id="loop" name="loop" class="form-control" style="width: 30%;">
                                                        <option value="d"><?php echo Portal::language('every_day');?></option>
                                                        <option value="w"><?php echo Portal::language('every_week');?></option>
                                                    </select>
                                                </div>
                                                <div id="loopInfo" class="form-group">
                                                    <label style="width: 15%;"><?php echo Portal::language('loop_in');?> : </label>
                                                    <div class="checkbox" style="margin-bottom: 20px;">
                                                        <label>
                                                            <input id="in_week" onclick="checkInWeek();" type="checkbox"  /><span><?php echo Portal::language('weekdays');?></span>
                                                        </label>
                                                        <label>
                                                            <input id="out_week" onclick="checkInWeek();" type="checkbox"  /><span><?php echo Portal::language('weekend');?></span>
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input onclick="stringDate();" name="check_1" type="checkbox" value="1" fName="chủ nhật" /> <span>CN</span>
                                                        </label>
                                                        <label>
                                                            <input onclick="stringDate();" name="check_2" type="checkbox" value="2" fName="thứ hai" /> <span>T2</span>
                                                        </label>
                                                        <label>
                                                            <input onclick="stringDate();" name="check_3" type="checkbox" value="3" fName="thứ ba"/> <span>T3</span>
                                                        </label>
                                                        <label>
                                                            <input onclick="stringDate();" name="check_4" type="checkbox" value="4" fName="thứ tư"/> <span>T4</span>
                                                        </label>
                                                        <label>
                                                            <input onclick="stringDate();" name="check_5" type="checkbox" value="5" fName="thứ năm"/> <span>T5</span>
                                                        </label>
                                                        <label>
                                                            <input onclick="stringDate();" name="check_6" type="checkbox" value="6" fName="thứ sáu"/> <span>T6</span>
                                                        </label>
                                                        <label>
                                                            <input onclick="stringDate();" name="check_7" type="checkbox" value="7" fName="thứ bảy"/> <span>T7</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="clear"></div>
                                                <div id="inputDate">
                                                    <div class="form-group indate" id="add_1">
                                                        <label><?php echo Portal::language('time_slot');?>:</label>
                                                            <span class="spFrom"><?php echo Portal::language('from');?> :</span> <input time="ok" type="date" name="from_date" id="from_date" class="form-control" style="width: 35%; float:left;"/>
                                                            <span class="spTo"><?php echo Portal::language('to');?> :</span> <input time="ok" type="date" name="to_date" id="to_date" class="form-control" style="width: 35%; float: left;"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="clear"></div>
                                                <div class="form-group" style="margin-top: 20px;" >
                                                    <label><?php echo Portal::language('brief');?>:</label>
                                                    <p id="content" style="color: maroon;"><?php echo Portal::language('every_day');?></p>
                                                    <input id="strHidden" name="strHidden" type="hidden" value="" />
                                                    <input id="sttHour" name="sttHour" type="hidden" value="1" />
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo Portal::language('choose_priority');?> :  </label>
                                                    <div class="radio">
                                                        <label><input  type="radio" class="radio" name="special" value="1" /> <span style="padding-top:4px; display:block;"><?php echo Portal::language('level1');?></span></label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input checked="" type="radio" class="radio" name="special" value="2" /> <span style="padding-top:4px; display:block;"><?php echo Portal::language('level2');?></span></label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input checked="" type="radio" class="radio" name="special" value="3" /> <span style="padding-top:4px; display:block;"><?php echo Portal::language('level3');?></span></label>
                                                    </div>
                                                </div>
                                            </div> 
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="saveBtn"><?php echo Portal::language('save');?></button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Portal::language('close');?></button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-md-offset2" style="margin-top: 20px; margin-bottom:20px; margin-left: 50px;">
                                <button data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-primary"><?php echo Portal::language('customer_group_list');?></button>
                                <div style="overflow-y: auto !important;" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel"><?php echo Portal::language('customer_group_list');?></h4>
                                      </div>
                                      <div class="modal-body">
                                            <table id="customer_info" class="table table-bordered table-hover">  
                                                <thead>
                                                    <tr>
                                                        <th><input id="check_all" type="checkbox" onclick="check_group_list();" /></th>
                                                        <th style="color: maroon;"><?php echo Portal::language('code');?></th>
                                                        <th style="color: maroon;"><?php echo Portal::language('name');?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(isset($this->map['group_list']) and is_array($this->map['group_list'])){ foreach($this->map['group_list'] as $key1=>&$item1){if($key1!='current'){$this->map['group_list']['current'] = &$item1;?>
                                                            <tr class="customer_group">
                                                                <td><input id="<?php echo $this->map['group_list']['current']['id'];?>" type="checkbox" onclick="checkAllCustomer(this);"/></td>
                                                                <td onclick="open_customer(this);"><label style="width: 100%; height:inherit; color:blue;"><?php echo $this->map['group_list']['current']['id'];?></label></td>
                                                                <td onclick="open_customer(this);"><label style="width: 90%; height:inherit; float:left; color:blue;"><?php echo $this->map['group_list']['current']['name'];?></label> <span style="display: block; float:left; margin-top:12px;" class="glyphicon glyphicon-chevron-down"></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3" style="display:none;">
                                                                <?php if(isset($this->map['group_list']['current']['items']) and is_array($this->map['group_list']['current']['items'])){ foreach($this->map['group_list']['current']['items'] as $key2=>&$item2){if($key2!='current'){$this->map['group_list']['current']['items']['current'] = &$item2;?>
                                                                    <div style="float: left; width:50%; padding:5px 0px;">
                                                                        <input name="group_list[]" type="checkbox" id="group_list[]" check_group_list="" onclick="tick_items(this);" value="<?php echo $this->map['group_list']['current']['items']['current']['id'];?>" />
                                                                        <label onclick="click_item(this);" style=" color:green;"><?php echo $this->map['group_list']['current']['items']['current']['name'];?></label>
                                                                    </div>   
                                                                <?php }}unset($this->map['group_list']['current']['items']['current']);} ?>
                                                                </td>
                                                            </tr>     
                                                    <?php }}unset($this->map['group_list']['current']);} ?>
                                                </tbody>
                                            </table>
                                            
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Portal::language('save');?></button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Portal::language('close');?></button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <br />
                            <br />
                            
                            <div>
                                <div style="overflow-y: auto;" class="modal fade" id="setPrice" tabindex="-1" role="dialog" aria-labelledby="setPriceLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="setPriceLabel"><?php echo Portal::language('price_list');?></h4>
                                      </div>
                                      <div class="modal-body">
                                         <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><?php echo Portal::language('room_level_name');?></th>
                                                    <th><?php echo Portal::language('price');?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($this->map['room_level_list']) and is_array($this->map['room_level_list'])){ foreach($this->map['room_level_list'] as $key3=>&$item3){if($key3!='current'){$this->map['room_level_list']['current'] = &$item3;?>
                                                    <tr>
                                                        <td><?php echo $this->map['room_level_list']['current']['name'];?></td>
                                                        <td><input r_id="<?php echo $this->map['room_level_list']['current']['id'];?>" type="text" class="input_number" style="text-align: right;" oninput="this.value = number_format(to_numeric(this.value));" /></td>
                                                    </tr>
                                                <?php }}unset($this->map['room_level_list']['current']);} ?>
                                            </tbody>
                                         </table>   
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="setPriceValue();"><?php echo Portal::language('save');?></button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Portal::language('close');?></button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div id="ScheduleWrapper" <?php if(!isset($this->map['schedule_list'])) echo "style='display: none;'" ?> >
                                <div id="threadSchedule" class="Schedule">
                                    <div></div>
                                    <div><?php echo Portal::language('start_date');?></div>
                                    <div><?php echo Portal::language('end_date');?></div>
                                    <div><?php echo Portal::language('loop_in');?></div>
                                    <div><?php echo Portal::language('priority');?></div>
                                    <div><?php echo Portal::language('price_list');?><button type="button" onclick="quickSetup()" title="<?php echo Portal::language('quick_setup');?>" class="pull-right btn btn-success btn-xs" style="margin: 11px 5px 0px 0px;"><span class="glyphicon glyphicon-import"></span></button></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <?php
                                    if(isset($this->map['schedule_list'])){
                                       $k = 0; 
                                 ?>
                                    <?php if(isset($this->map['schedule_list']) and is_array($this->map['schedule_list'])){ foreach($this->map['schedule_list'] as $key4=>&$item4){if($key4!='current'){$this->map['schedule_list']['current'] = &$item4;?>
                                        <div id="bodySchedule_<?php echo $k; ?>" class="Schedule">
                                            <div id='check_<?php echo $k; ?>'><span class="glyphicon glyphicon-ok" style="color: red; font-size:17px; padding-top:6px; display:none;"></span></div>
                                            <div class='loopInfo' id='from_date_info' onclick='viewEditModal(<?php echo $k; ?>);'><?php echo date("d/m/Y", strtotime($this->map['schedule_list']['current']['start_date'])); ?></div>
                                            <div class='loopInfo' id='to_date_info' onclick='viewEditModal(<?php echo $k; ?>);'><?php echo date("d/m/Y", strtotime($this->map['schedule_list']['current']['end_date'])); ?></div>
                                            <div class='loopInfo' id='brief_info' onclick='viewEditModal(<?php echo $k; ?>);'><?php echo $this->map['schedule_list']['current']['brief'];?></div>
                                            <div class='loopInfo' id='priority_info' onclick='viewEditModal(<?php echo $k; ?>);'><?php echo $this->map['schedule_list']['current']['strPriority'];?></div>
                                            <div style="text-align:center; padding-top:9px;"><button  onclick='setPriceValueId(<?php echo $k; ?>)' type='button' class='btn btn-xs btn-success' data-toggle='modal' data-target='#setPrice'><?php echo Portal::language('price_list');?></button></div>
                                            <div style="text-align:center; padding-top:9px;"><button onclick='copyPrice(<?php echo $k; ?>);' type='button' class='btn btn-sm btn-primary' title="<?php echo Portal::language('copy');?>"><span style="color:white;" class="glyphicon glyphicon glyphicon-copy"></span></button></div>
                                            <div style="text-align:center; padding-top:9px;"><button onclick='pastePrice(<?php echo $k; ?>);' type='button' class='btn btn-sm btn-info' title="<?php echo Portal::language('paste');?>"><span style="color:white;" class="glyphicon glyphicon glyphicon-paste"></span></button></div>
                                            <div style="text-align:center; padding-top:9px;"><button onclick='deletePrice(<?php echo $k; ?>);' type='button' class='btn btn-sm btn-danger'><span style="color:white;" class="glyphicon glyphicon-remove"></span></button></div>
                                            <?php if(isset($this->map['schedule_list']['current']['schedule']) and is_array($this->map['schedule_list']['current']['schedule'])){ foreach($this->map['schedule_list']['current']['schedule'] as $key5=>&$item5){if($key5!='current'){$this->map['schedule_list']['current']['schedule']['current'] = &$item5;?>
                                            <input type="hidden" name="price_<?php echo $k; ?>_<?php echo $this->map['schedule_list']['current']['schedule']['current']['id'];?>" value="<?php echo $this->map['schedule_list']['current']['schedule']['current']['price'];?>" />
                                            <?php }}unset($this->map['schedule_list']['current']['schedule']['current']);} ?> 
                                            <input type='hidden' name='start_date_<?php echo $k; ?>' value='<?php echo date("d/m/Y", strtotime($this->map['schedule_list']['current']['start_date'])); ?>' />
                                            <input type='hidden' name='end_date_<?php echo $k; ?>' value='<?php echo date("d/m/Y", strtotime($this->map['schedule_list']['current']['end_date'])); ?>'/>
                                            <input type='hidden' name='frequency_<?php echo $k; ?>' value='<?php echo $this->map['schedule_list']['current']['frequency'];?>'/>
                                            <input type='hidden' name='weekly_<?php echo $k; ?>' value='<?php echo $this->map['schedule_list']['current']['weekly'];?>'/>
                                            <input type='hidden' name='priority_<?php echo $k; ?>' value='<?php echo $this->map['schedule_list']['current']['priority'];?>'/>  
                                        </div>
                                        <?php $k++; ?>   
                                    <?php }}unset($this->map['schedule_list']['current']);} ?>
                                 <?php       
                                    } 
                                ?>
                                <input type="hidden" name="countRecord" value="<?php echo $k; ?>" />
                                <div class="clear"></div>
                            </div>		
                        </div>
                 </div>
            </div>
        </div>
        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
        <div id="notice"></div>
    </div>
</div>
<div style="overflow-y: auto;" class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="errorLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="errorLabel"><?php echo Portal::language('Error');?></h4>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="setPriceValue();"><?php echo Portal::language('save');?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Portal::language('close');?></button>
          </div>
        </div>
    </div>
</div>
<script>
        
    var room_level_list_js = <?php echo $this->map['room_level_list_js'];?>;  
    var rate_code_list_contain_js = <?php echo $this->map['rate_code_list_contain_js'];?>;
    
    Array.prototype.remove = function(from, to) {
      var rest = this.slice((to || from) + 1 || this.length);
      this.length = from < 0 ? this.length + from : from;
      return this.push.apply(this, rest);
    };
    
    function check_group_list(){
       var status = jQuery("input#check_all").is(":checked");
       //alert(status.length);
       if(status){
            jQuery("table#customer_info input").each(function(){
                jQuery(this).attr("checked","checked");
            });
       }
       else{
            jQuery("table#customer_info input").each(function(){
                jQuery(this).removeAttr("checked");
            });
       }
    }
    
    function format_date(value){
        var arr = value.split("-");
        return (arr[2]+"/"+arr[1]+"/"+arr[0]);
    }

    function getWeekName(value){
        str = "";
        switch(value){
                        case "1":
                                  str = "<?php echo Portal::language('sun');?>";
                                  break;
                        case "2":
                                  str = "<?php echo Portal::language('mon');?>";
                                  break;
                        case "3":
                                  str = "<?php echo Portal::language('tue');?>";
                                  break;
                        case "4":
                                  str = "<?php echo Portal::language('wed');?>";
                                  break;
                        case "5":
                                  str = "<?php echo Portal::language('thu');?>";
                                  break;
                        case "6":
                                  str = "<?php echo Portal::language('fri');?>";
                                  break;
                        case "7":
                                  str = "<?php echo Portal::language('sat');?>";
                                  break;                                                              
        }
        return str;
    }
    
    function stringDate(){
        var type = jQuery("select#loop").val();
        if(type=="w"){
        var str = "<?php echo Portal::language('Every_week_on');?> ";
        var inweek = true;
        var z = 0;
        var y = 0;
        jQuery("div.checkbox input[name]").each(function(){
            if(this.checked==true){
                val = jQuery(this).attr("fName");
                str +=(" "+val+",");
                if(this.value=='6' || this.value=='7'){
                    inweek = false;
                    y++;
                }
               z++; 
            }
            
        });
        str = str.substr(0,str.length-1);
        if(inweek==true && z==5){
            jQuery("p#content").html("<?php echo Portal::language('Every_week_on_weekdays');?>");
            jQuery("input#in_week").attr("checked","checked");
        }
        
        else if(z==7){
           jQuery("p#content").html("<?php echo Portal::language('Every_week_in_every_day');?>"); 
           jQuery("input#in_week").attr("checked","checked");
           jQuery("input#out_week").attr("checked","checked");
        }
        else if(z==0){
            jQuery("p#content").html("<?php echo Portal::language('every_week');?>");
        }
        else if(y==2 && z==2){
            jQuery("p#content").html("<?php echo Portal::language('Every_week_on_weekends');?>"); 
            jQuery("input#out_week").attr("checked","checked");
        }
        else if(z>=1 && z<7){
            jQuery("input#in_week").removeAttr("checked");
            jQuery("input#out_week").removeAttr("checked");
            jQuery("p#content").html(str);
        }
        else{
            jQuery("input#in_week").removeAttr("checked");
            jQuery("input#out_week").removeAttr("checked");
            
          }
        
        }
        else{
            jQuery("p#content").html("<?php echo Portal::language('every_day');?>");
        }
        jQuery("input#strHidden").val(jQuery("p#content").html());
    }
    jQuery(document).ready(function(){
        <?php
            if(isset($this->map['customer_group_list_js'])){
         ?>
            var customer_group_list_js = <?php echo $this->map['customer_group_list_js'];?>;
            //console.log(customer_group_list_js);
            for(key in customer_group_list_js){
                jQuery("input[check_group_list][value='"+customer_group_list_js[key]['customer_id']+"']").attr("checked","checked");
            }
            
            jQuery("tr.customer_group").each(function(){
                
                var total_customer = jQuery(this).next().find("input[type=checkbox]").length;
                
                var total_customer_checked = jQuery(this).next().find("input[type=checkbox][checked]").length;
                
                if(total_customer==total_customer_checked && total_customer!=0)
                {
                    jQuery(this).find("input[type=checkbox]").attr("checked","checked");
                }
            });
            
            var total_customer_group = jQuery("tr.customer_group").length;
            var real_check = 0;
            jQuery("tr.customer_group input[type=checkbox]").each(function(){
                if(jQuery(this).is(":checked"))
                {
                   real_check++; 
                }
            });
            if(total_customer_group==real_check)
            {
                jQuery("input#check_all").attr("checked","checked");
            }
            else{
                jQuery("input#check_all").removeAttr("checked");
            }
         <?php       
            }
        ?>
        jQuery("select#loop").on('change',function(){
            var loopVal = jQuery("select#loop").val();
            if(loopVal=="d"){
                jQuery("div#loopInfo").css("display","none");
            }
            else{
                jQuery("div#loopInfo").css("display","block");
            }
        });
    });
    <?php
        if(isset($this->map['schedule_list_js'])){
            
     ?>
        var schedule_list_js = <?php echo $this->map['schedule_list_js'];?>;
        var arr_schedule = [];
        var j = 0;
        for(key in schedule_list_js){
           arr_schedule[j]={};
           arr_schedule[j]['from_date'] =  schedule_list_js[key]['start_date'];
           arr_schedule[j]['to_date'] = schedule_list_js[key]['end_date'];  
           arr_schedule[j]['frequency'] = schedule_list_js[key]['frequency'];
           arr_schedule[j]['priority'] = schedule_list_js[key]['priority'];
           arr_schedule[j]['weekly'] = schedule_list_js[key]['weekly'];
           arr_schedule[j]['brief'] =  schedule_list_js[key]['brief'];
           j++;
        }
        //console.log(arr_schedule);
     <?php       
        } 
        else{
     ?>
        var arr_schedule = [];
        var j = 0;
     <?php       
        }
    ?>
    function checkForm(action,target){
        var valueRdo = jQuery("input[name=special]:checked").val();
        var loopVal = jQuery("select#loop").val();
        var str_date = "";
       jQuery("input[fName]:checked").each(function(){
            str_date+=jQuery(this).val()+",";
       });
       if(loopVal=="w"){
            boolen = false;
            jQuery("div.checkbox input").each(function(){
                if(this.checked==true){
                    boolen = true;
                }
            });
            if(!boolen){
                jQuery("#error .modal-body").html("<?php echo Portal::language('You_have_not_selected_weekdays');?>");
                jQuery("#error").modal("show");
                //alert("<?php echo Portal::language('You_have_not_selected_weekdays');?>");
                return false;
               }
       }
       else{
            jQuery("div.checkbox input").each(function(){
                jQuery(this).removeAttr('checked');
            });
       }
           var from_date = jQuery("input#from_date").val();
           var to_date = jQuery("input#to_date").val();
           if(from_date && to_date){
               if(from_date>to_date){
                    //alert("<?php echo Portal::language('Start_date_is_greater_than_the_end_date');?>");
                    jQuery("#error .modal-body").html("<?php echo Portal::language('Start_date_is_greater_than_the_end_date');?>");
                    jQuery("#error").modal("show");
                    return false;
               }  
           }
           else{
               //alert("<?php echo Portal::language('You_did_not_enter_a_start_date_or_end_date');?>"); 
               jQuery("#error .modal-body").html("<?php echo Portal::language('You_did_not_enter_a_start_date_or_end_date');?>");
               jQuery("#error").modal("show");
               return false;
           }
     if(action=="add"){      
       if(arr_schedule.length){
        
          for(k = 0; k< arr_schedule.length; k++){
              date_from = arr_schedule[k]['from_date'];  
              date_to = arr_schedule[k]['to_date'];
              
              if((from_date>=date_from && to_date<=date_to) || (from_date<=date_from && to_date>=date_to) || (from_date<=date_from && date_from<=to_date) || (from_date<=date_to && to_date>=date_to)){
                    //alert("adad");
                    if(valueRdo==arr_schedule[k]['priority']){
                        if(loopVal=="d" || arr_schedule[k]['frequency']=="d"){
                            //alert("<?php echo Portal::language('Conflict_with_the_period_from');?> "+format_date(date_from)+" <?php echo Portal::language('to');?> "+format_date(date_to));
                            jQuery("#error .modal-body").html("<?php echo Portal::language('Conflict_with_the_period_from');?> "+format_date(date_from)+" <?php echo Portal::language('to');?> "+format_date(date_to));
                            jQuery("#error").modal("show");
                            return false;
                        }
                        else if(loopVal=="w" && arr_schedule[k]['frequency']=="w"){
                            arr_date_current = str_date.split(",");
                            arr_date_old =  arr_schedule[k]['weekly'].split(",");
                            str_weeken="";
                            for(var t = 0; t<arr_date_current.length; t++){
                                for(var p = 0; p<arr_date_old.length; p++){
                                    if(arr_date_current[t] == arr_date_old[p]){
                                        str_weeken+= getWeekName(arr_date_current[t])+", ";
                                        break;
                                    }
                                }
                            }
                            if(str_weeken!=""){
                                str_weeken = str_weeken.substr(0,str_weeken.length-2);
                                //alert(str_weeken+" <?php echo Portal::language('was_installed_price_for_the_period_from');?> "+format_date(date_from)+" <?php echo Portal::language('to');?> "+format_date(date_to));
                                jQuery("#error .modal-body").html(str_weeken+" <?php echo Portal::language('was_installed_price_for_the_period_from');?> "+format_date(date_from)+" <?php echo Portal::language('to');?> "+format_date(date_to));
                                jQuery("#error").modal("show");
                                return false;
                            }
                        }
                    }
              } 
              
              
              
          }  
       }    
       //alert(from_date);
       str_date = str_date.substr(0, str_date.length-1);
       arr_schedule[j]={};
       arr_schedule[j]['from_date'] =  from_date;
       arr_schedule[j]['to_date'] =  to_date;  
       
       arr_schedule[j]['frequency'] = loopVal;
       arr_schedule[j]['priority'] = valueRdo;
       arr_schedule[j]['weekly'] = str_date;
       arr_schedule[j]['brief'] = jQuery("p#content").text();
           
        var str = "";
        switch(valueRdo){
            case "1":
                      str="<?php echo Portal::language('every_day');?>";
                      break;
            case "2":
                      str="<?php echo Portal::language('holyday_day');?>";
                      break;
            case "3":       
                      str="<?php echo Portal::language('special_day');?>";
                      break;                   
        }
        var brief = jQuery("p#content").text();
        var content = "<div  id=bodySchedule_"+j+" class='Schedule'>"
                            +"<div style='border-top:none;' id='check_"+j+"'><span class='glyphicon glyphicon-ok' style='color:red; font-size:17px; padding-top:6px; display:none;'></span></div>"
                            +"<div style='border-top:none;' class='loopInfo' id='from_date_info' onclick='viewEditModal("+j+")'>"+format_date(from_date)+"</div>"
                            +"<div style='border-top:none;' class='loopInfo' id='to_date_info' onclick='viewEditModal("+j+")'>"+format_date(to_date)+"</div>"
                            +"<div style='border-top:none;' class='loopInfo' id='brief_info' onclick='viewEditModal("+j+")'>"+brief+"</div>"
                            +"<div style='border-top:none;' class='loopInfo' id='priority_info' onclick='viewEditModal("+j+")'>"+str+"</div>"
                            +"<div style='border-top:none; text-align:center; padding-top:9px;'><button onclick='setPriceValueId("+j+")' type='button' class='btn btn-xs btn-success' data-toggle='modal' data-target='#setPrice'><?php echo Portal::language('price_list');?></button></div>"
                            +"<div style='border-top:none; text-align:center; padding-top:9px;'><button onclick='copyPrice("+j+");' type='button' class='btn btn-sm btn-primary' title='<?php echo Portal::language('copy');?>'><span style='color:white;' class='glyphicon glyphicon glyphicon-copy'></span></button></div>"
                            +"<div style='border-top:none; text-align:center; padding-top:9px;'><button onclick='pastePrice("+j+");' type='button' class='btn btn-sm btn-info' title='<?php echo Portal::language('paste');?>'><span style='color:white;' class='glyphicon glyphicon glyphicon-paste'></span></button></div>"
                            +"<div style='border-top:none; text-align:center; padding-top:9px;'><button onclick='deletePrice("+j+");' type='button' class='btn btn-sm btn-danger'><span style='color:white;' class='glyphicon glyphicon-remove'></span></button></div>";
                     
        for( key in room_level_list_js){
            content+= "<input type='hidden' name='price_"+j+"_"+key+"' />";
        }        
        content+="<input type='hidden' name='start_date_"+j+"' value='"+format_date(from_date)+"' />";
        content+="<input type='hidden' name='end_date_"+j+"' value='"+format_date(to_date)+"'/>";
        content+="<input type='hidden' name='frequency_"+j+"' value='"+loopVal+"'/>";
        content+="<input type='hidden' name='weekly_"+j+"' value='"+str_date+"'/>";
        content+="<input type='hidden' name='priority_"+j+"' value='"+valueRdo+"'/>";
        content+="</div>";
             
        jQuery("div#ScheduleWrapper").append(content);
        jQuery("div#ScheduleWrapper").css("display","");
       jQuery('#setTime').modal('hide');
       j++;
       jQuery("input[name=countRecord]").val(j);
      }
      else{
         if(arr_schedule.length){
          for(k = 0; k< arr_schedule.length; k++){
              if(k==target){
                continue;
              }
              date_from = arr_schedule[k]['from_date'];  
              date_to = arr_schedule[k]['to_date'];
              
              if((from_date>=date_from && to_date<=date_to) || (from_date<=date_from && to_date>=date_to) || (from_date<=date_from && date_from<=to_date) || (from_date<=date_to && to_date>=date_to)){
                    //alert("adad");
                    if(valueRdo==arr_schedule[k]['priority']){
                        if(arr_schedule[k]['frequency']=="d" || loopVal=="d" ){
                            //alert("<?php echo Portal::language('Conflict_with_the_period_from');?> "+format_date(date_from)+" <?php echo Portal::language('to');?> "+format_date(date_to));
                            jQuery("#error .modal-body").html("<?php echo Portal::language('Conflict_with_the_period_from');?> "+format_date(date_from)+" <?php echo Portal::language('to');?> "+format_date(date_to));
                            jQuery("#error").modal("show");
                            return false;
                        }
                        else if(loopVal=="w" && arr_schedule[k]['frequency']=="w"){
                            arr_date_current = str_date.split(",");
                            arr_date_old =  arr_schedule[k]['weekly'].split(",");
                            str_weeken="";
                            for(var t = 0; t<arr_date_current.length; t++){
                                for(var p = 0; p<arr_date_old.length; p++){
                                    if(arr_date_current[t] == arr_date_old[p]){
                                        str_weeken+= getWeekName(arr_date_current[t])+", ";
                                        break;
                                    }
                                }
                            }
                            if(str_weeken!=""){
                                str_weeken = str_weeken.substr(0,str_weeken.length-2);
                                //alert(str_weeken+" <?php echo Portal::language('was_installed_price_for_the_period_from');?> "+format_date(date_from)+" <?php echo Portal::language('to');?> "+format_date(date_to));
                                jQuery("#error .modal-body").html(str_weeken+" <?php echo Portal::language('was_installed_price_for_the_period_from');?> "+format_date(date_from)+" <?php echo Portal::language('to');?> "+format_date(date_to));
                                jQuery("#error").modal("show");
                                return false;
                            }
                        }
                    }
                } 
             }  
          }
          
       str_date = str_date.substr(0, str_date.length-1);
       arr_schedule[target]={};
       arr_schedule[target]['from_date'] =  from_date;
       arr_schedule[target]['to_date'] =  to_date;  
       arr_schedule[target]['frequency'] = loopVal;
       arr_schedule[target]['priority'] = valueRdo;
       arr_schedule[target]['weekly'] = str_date;
       arr_schedule[target]['brief'] = jQuery("p#content").text();
       
       
       for( key in room_level_list_js){
            value = jQuery("input[r_id="+key+"]").val();
            jQuery("input[name=price_"+target+"_"+key+"]").val(value);
       }
       jQuery("input[name=start_date_"+target+"]").val(format_date(from_date));
       jQuery("input[name=end_date_"+target+"]").val(format_date(to_date));
       jQuery("input[name=frequency_"+target+"]").val(loopVal);
       jQuery("input[name=priority_"+target+"]").val(valueRdo);
       jQuery("input[name=weekly_"+target+"]").val(str_date);
        var str = "";
        switch(valueRdo){
            case "1":
                      str="<?php echo Portal::language('every_day');?>";
                      break;
            case "2":
                      str="<?php echo Portal::language('holyday_day');?>";
                      break;
            case "3":       
                      str="<?php echo Portal::language('special_day');?>";
                      break;                   
        }
        var brief = jQuery("p#content").text();
        jQuery("div#bodySchedule_"+target+" div#from_date_info").html(format_date(from_date));
        jQuery("div#bodySchedule_"+target+" div#to_date_info").html(format_date(to_date));  
        jQuery("div#bodySchedule_"+target+" div#brief_info").html(brief);  
        jQuery("div#bodySchedule_"+target+" div#priority_info").html(str);
      } 
       return true;
    }
    
    function checkInWeek(){
        if(jQuery("input#in_week").is(':checked')){
            jQuery("input[fname]").each(function(){
                switch(jQuery(this).val()){
                    case "2":
                                jQuery(this).attr("checked","checked");
                                break;
                    case "3":
                                jQuery(this).attr("checked","checked");
                                break;
                    case "4":
                                jQuery(this).attr("checked","checked");
                                break;
                    case "5":
                                jQuery(this).attr("checked","checked");
                                break;
                    case "1":
                                jQuery(this).attr("checked","checked");
                                break;                                                
                }
            });
        }
        else{
           jQuery("input[fname]").each(function(){
                switch(jQuery(this).val()){
                    case "2":
                                jQuery(this).removeAttr("checked");
                                break;
                    case "3":
                                jQuery(this).removeAttr("checked");
                                break;
                    case "4":
                                jQuery(this).removeAttr("checked");
                                break;
                    case "5":
                                jQuery(this).removeAttr("checked");
                                break;
                    case "1":
                                jQuery(this).removeAttr("checked");
                                break;                                                
                }
            }); 
        }
        if(jQuery("input#out_week").is(':checked')){
            jQuery("input[fname]").each(function(){
                switch(jQuery(this).val()){
                    case "6":
                                jQuery(this).attr("checked","checked");
                                break;
                    case "7":
                                jQuery(this).attr("checked","checked");
                                break;                                              
                }
            });
        }
        else{
           jQuery("input[fname]").each(function(){
                switch(jQuery(this).val()){
                    case "6":
                                jQuery(this).removeAttr("checked");
                                break;
                    case "7":
                                jQuery(this).removeAttr("checked");
                                break;                                              
                }
            }); 
        }
        stringDate();
    }
    function viewEditModal(target){
      from_date = arr_schedule[target]['from_date'];
      to_date =  arr_schedule[target]['to_date'];  
      loopVal = arr_schedule[target]['frequency'];
      valueRdo = arr_schedule[target]['priority'];
      str_date = arr_schedule[target]['weekly'];
      brief = arr_schedule[target]['brief'];
      
      jQuery("input#in_week").removeAttr('checked'); 
      jQuery("input#out_week").removeAttr('checked');
      jQuery("div#setTime input#from_date").val(from_date);
      jQuery("div#setTime input#to_date").val(to_date);
      jQuery("div#setTime select#loop option[value="+loopVal+"]").attr("selected","selected");
      jQuery("div#setTime input[name=special][value="+valueRdo+"]").attr("checked","checked");
      jQuery("div#setTime p#content").html(brief);
      
      jQuery("input[fName]").each(function(){
                jQuery(this).removeAttr("checked");
            });
      
      var arr_d = str_date.split(",");
        if(arr_d!=""){
         jQuery("div#loopInfo").css("display","block");   
         for(var p=0; p< arr_d.length; p++){
            jQuery("input[name=check_"+arr_d[p]+"]").attr("checked","checked");
            }
        }
        else{
            
            jQuery("div#loopInfo").css("display","none");   
        }
      stringDate();
      
      jQuery("button#saveBtn").unbind('click');
      jQuery("button#saveBtn").bind('click',function(){
            checkForm('edit',target);
            jQuery('#setTime').modal('hide');
        });
      
      jQuery('#setTime').modal('show');
    }
    
    function setPriceValueId(target){
        jQuery("input[r_id]").each(function(){
            r_id = jQuery(this).attr('r_id');
            for( key in room_level_list_js){
                if(r_id == key){
                id = "price_"+target+"_"+key;
                jQuery(this).attr('id',id);
                }
            }
            id = jQuery(this).attr('id');
            value = jQuery("input[name="+id+"]").val();
            jQuery(this).val(value);
        });
    }
    
    function setPriceValue(){
        jQuery("input[r_id]").each(function(){
            id = jQuery(this).attr('id');
            value = jQuery(this).val();
            jQuery("input[name="+id+"]").val(value);
        });
        jQuery("#setPrice").modal('hide');
    }
    
    
    var arr_price_copy = [];
    function copyPrice(target){
        for(key in room_level_list_js){
            value = jQuery("input[name=price_"+target+"_"+key+"]").val();
            arr_price_copy[key]={};
            arr_price_copy[key]=value;
        }
        //alert("Copy Successfully! ");
        jQuery("div[id^=check_] span").each(function(){
            jQuery(this).stop(true, true).fadeOut('slow');
        });
        jQuery("div#check_"+target+" span").stop(true, true).fadeIn('slow');
        jQuery("div#notice").html("<?php echo Portal::language('copy_successfully');?>");
        jQuery('div#notice').stop(true,true).fadeIn('slow').delay(1500).fadeOut('slow');
    }
    
    function pastePrice(target){
        if(!arr_price_copy.length){
            jQuery("div#notice").html('<?php echo Portal::language('empty_clipboard');?>');
            jQuery('div#notice').stop(true,true).fadeIn('slow').delay(1500).fadeOut('slow');
        }
        else{
            for(key in room_level_list_js){
                value = arr_price_copy[key];
                jQuery("input[name=price_"+target+"_"+key+"]").val(value);
            }
            jQuery("div#notice").html("<?php echo Portal::language('paste_successfully');?>");
            jQuery('div#notice').stop(true,true).fadeIn('slow').delay(1500).fadeOut('slow');
        }
    }
    
    function quickSetup(){
        if(!arr_schedule.length){
            jQuery("div#notice").html('<?php echo Portal::language('empty_target');?>');
            jQuery('div#notice').stop(true,true).fadeIn('slow').delay(1500).fadeOut('slow');
        }
        else{ 
          for( k = 1 ; k< j ; k++){
             for(key in room_level_list_js){
                value = jQuery("input[name=price_0_"+key+"]").val();
                jQuery("input[name=price_"+k+"_"+key+"]").val(value);
             }
          }
            jQuery("div#notice").html("<?php echo Portal::language('fill_up_successfully');?>");
            jQuery('div#notice').stop(true,true).fadeIn('slow').delay(1500).fadeOut('slow');
        }
    }
    function deletePrice(target){
        if(confirm("<?php echo Portal::language('are_you_sure');?>")){
            jQuery("div#bodySchedule_"+target).remove();
            arr_price_copy=[];
            jQuery("div#notice").html("<?php echo Portal::language('delete_successfully');?>");
            jQuery('div#notice').stop(true,true).fadeIn('slow').delay(1500).fadeOut('slow');
            arr_schedule.remove(target);
            jQuery("input[name=countRecord]").val(j);
            //console.log(arr_schedule);
        }
    }
    
    function checkAll(){
        if(!jQuery("input[check_group_list]:checked").length){
            jQuery("#error .modal-body").html("<?php echo Portal::language('You_have_not_selected_tourists');?>");
            jQuery("#error").modal("show");
            //alert("<?php echo Portal::language('You_have_not_selected_tourists');?>");
            return false;
        }
        if(j==0){
            //alert("<?php echo Portal::language('You_do_not_create_scheduled_Please_check_again');?>");
            jQuery("#error .modal-body").html("<?php echo Portal::language('You_do_not_create_scheduled_Please_check_again');?>");
            jQuery("#error").modal("show");
            return false;
        }
        arr_group = [];
        var k = 0;
        jQuery("input[check_group_list]:checked").each(function(){
            arr_group[k] = jQuery(this).val();
            k++;
        });
        if(arr_schedule.length){
                for( key_current in rate_code_list_contain_js ){
                    var str_group="";
                    var schedule_name = rate_code_list_contain_js[key_current]['name'];
                    var schedule_code = rate_code_list_contain_js[key_current]['code'];
                    for( key_group in rate_code_list_contain_js[key_current]['customer_group']){
                        for(var k=0 ; k<arr_group.length; k++){
                            if(arr_group[k]==key_group){
                               str_group+=rate_code_list_contain_js[key_current]['customer_group'][key_group]['name']+" <br/>";
                            }
                        }
                    }
                    
                    if(str_group!=""){

                       for( key_schedule in arr_schedule ){
                           var date_from = "";
                           var date_to = "";
                           if(arr_schedule[key_schedule]['from_date']) 
                           {
                            date_from =  format_date(arr_schedule[key_schedule]['from_date']);
                           }
                           if(arr_schedule[key_schedule]['to_date'])
                           {
                            date_to =  format_date(arr_schedule[key_schedule]['to_date']);
                           }
                           

                           frequency = arr_schedule[key_schedule]['frequency'];
                           
                           if(frequency=="w"){
                            arr_date = arr_schedule[key_schedule]['weekly'].split(",");
                             
                           }
                           priority = arr_schedule[key_schedule]['priority'];
                           for( key_time in rate_code_list_contain_js[key_current]['rate_code_time']){
                               from_date = rate_code_list_contain_js[key_current]['rate_code_time'][key_time]['start_date'];
                               to_date = rate_code_list_contain_js[key_current]['rate_code_time'][key_time]['end_date'];

                               arr_date_current = rate_code_list_contain_js[key_current]['rate_code_time'][key_time]['weekly'].split(",");
                               
                               frequency_current = rate_code_list_contain_js[key_current]['rate_code_time'][key_time]['frequency'];
                               priority_current =  rate_code_list_contain_js[key_current]['rate_code_time'][key_time]['priority'];
                                if(priority_current==priority){
                                    if((from_date>=date_from && to_date<=date_to) || (from_date<=date_from && to_date>=date_to) || (from_date<=date_from && date_from<=to_date) || (from_date<=date_to && to_date>=date_to)){
                                        if( frequency=="d" || frequency_current=="d" ){
                                            //alert("<?php echo Portal::language('The_tourists');?> :\n"+str_group+"\n"+" <?php echo Portal::language('was_installed_price_for_the_period_from');?> "+from_date+" <?php echo Portal::language('to');?> "+to_date+"\n\n <?php echo Portal::language('code');?>: "+schedule_code+"\n <?php echo Portal::language('name');?>: "+schedule_name);
                                            jQuery("#error .modal-body").html("<?php echo Portal::language('The_tourists');?> : <br/> "+str_group+"<br/>"+" <?php echo Portal::language('was_installed_price_for_the_period_from');?> "+from_date+" <?php echo Portal::language('to');?> "+to_date+"<br/><br/> <?php echo Portal::language('code');?>: "+schedule_code+"<br/> <?php echo Portal::language('name');?>: "+schedule_name);
                                            jQuery("#error").modal("show");
                                            //console.log("<?php echo Portal::language('The_tourists');?> :\n"+str_group+"\n"+" <?php echo Portal::language('was_installed_price_for_the_period_from');?> "+from_date+" <?php echo Portal::language('to');?> "+to_date+"\n\n <?php echo Portal::language('code');?>: "+schedule_code+"\n <?php echo Portal::language('name');?>: "+schedule_name);
                                            return false;
                                        }
                                        else{
                                            var str_day = "";
                                            for(var x = 0; x<arr_date.length; x++){
                                                for(var y = 0; y<arr_date_current.length; y++){
                                                    if(arr_date_current[y]==arr_date[x]){
                                                       str_day+=getWeekName(arr_date_current[y])+", "; 
                                                    }
                                                }
                                            }
                                            if(str_day!=""){
                                                str_day = str_day.substr(0,str_day.length-2);
                                                //alert("<?php echo Portal::language('The_tourists');?> :\n\n"+str_group+"\n"+" <?php echo Portal::language('was_installed_price_on');?> "+str_day+" <?php echo Portal::language('in_between');?> "+from_date+" <?php echo Portal::language('and');?> "+to_date+"\n\n <?php echo Portal::language('code');?>: "+schedule_code+"\n <?php echo Portal::language('name');?>: "+schedule_name);
                                                jQuery("#error .modal-body").html("<?php echo Portal::language('The_tourists');?> :<br/><br/>"+str_group+"<br/>"+" <?php echo Portal::language('was_installed_price_on');?> "+str_day+" <?php echo Portal::language('in_between');?> "+from_date+" <?php echo Portal::language('and');?> "+to_date+"<br/><br/> <?php echo Portal::language('code');?>: "+schedule_code+"<br/> <?php echo Portal::language('name');?>: "+schedule_name);
                                                jQuery("#error").modal("show");
                                                return false;
                                            }
                                        }
                                    }
                                }    
                           }
                       } 
                    }    
                }
           }
    }
    
    
    jQuery(document).ready(function(){
           jQuery("button#setSchedule").click(function(){
                    jQuery("button#saveBtn").unbind('click');
                    jQuery("button#saveBtn").bind('click',function(){
                        checkForm('add','none');
                    });
              jQuery("div#setTime input#from_date").val(<?php echo date('Y/m/d'); ?>);
              jQuery("div#setTime input#to_date").val("<");
              jQuery("div#setTime select#loop option[value=d]").attr("selected","selected");
              jQuery("div#setTime input[name=special][value=1]").attr("checked","checked");
              jQuery("div#setTime p#content").html("<?php echo Portal::language('every_day');?>"); 
              jQuery("input[fName]").each(function(){
                jQuery(this).removeAttr("checked");
              });
              jQuery("div#loopInfo").css("display","none");
              jQuery("input#in_week").removeAttr('checked'); 
              jQuery("input#out_week").removeAttr('checked');     
        });
      });
    function open_customer(obj)
    {
       if(jQuery(obj).parent().find("td:last-child span.glyphicon-chevron-down").length!=0)
       {
          jQuery(obj).parent().find("td:last-child span.glyphicon-chevron-down").addClass("glyphicon-chevron-up"); 
          jQuery(obj).parent().find("td:last-child span.glyphicon-chevron-down").removeClass("glyphicon-chevron-down");
          
          jQuery(obj).parent().next().find("td").removeAttr("style");    
          
       }
       else
       {
          jQuery(obj).parent().find("td:last-child span.glyphicon-chevron-up").addClass("glyphicon-chevron-down"); 
          jQuery(obj).parent().find("td:last-child span.glyphicon-chevron-up").removeClass("glyphicon-chevron-up");
          
          jQuery(obj).parent().next().find("td").css("display","none");    
       }
    }
    
    function checkAllCustomer(obj)
    {
        if(jQuery(obj).is(":checked"))
        {
           jQuery(obj).parent().parent().next().find(":input").each(function(){
                jQuery(this).attr("checked","checked");
            }); 
        }
        else{
            jQuery(obj).parent().parent().next().find(":input").each(function(){
                jQuery(this).removeAttr("checked");
            }); 
        }
        
        var total_customer_group = jQuery("tr.customer_group").length;
        var real_check = 0;
        jQuery("tr.customer_group input[type=checkbox]").each(function(){
            if(jQuery(this).is(":checked"))
            {
               real_check++; 
            }
        });
        if(total_customer_group==real_check)
        {
            jQuery("input#check_all").attr("checked","checked");
        }
        else{
            jQuery("input#check_all").removeAttr("checked");
        }
                
    }
    
    function tick_items(obj)
    {              
            var total_customer = jQuery(obj).parent().parent().find("input[type=checkbox]").length;
            
            var total_customer_checked = 0;
            jQuery(obj).parent().parent().find("input[type=checkbox]").each(function(){
                if(jQuery(this).is(":checked"))
                {
                    total_customer_checked++;
                }
            });
            if(total_customer==total_customer_checked)
            {
                jQuery(obj).parent().parent().parent().prev().find("input[type=checkbox]").attr("checked","checked");
            }
            else{
                jQuery(obj).parent().parent().parent().prev().find("input[type=checkbox]").removeAttr("checked");
            }
            
            var total_customer_group = jQuery("tr.customer_group").length;
            var real_check = 0;
            jQuery("tr.customer_group input[type=checkbox]").each(function(){
                if(jQuery(this).is(":checked"))
                {
                   real_check++; 
                }
            });
            if(total_customer_group==real_check)
            {
                jQuery("input#check_all").attr("checked","checked");
            }
            else{
                jQuery("input#check_all").removeAttr("checked");
            }
    }
    function click_item(obj)
    {
        if(jQuery(obj).prev().is(":checked"))
        {
            jQuery(obj).prev().removeAttr("checked");
        }
        else
        {
            jQuery(obj).prev().attr("checked","checked");
        }
        tick_items(jQuery(obj).prev());
    }
</script>