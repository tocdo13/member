<div class="row">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title">[[.add_new_type_of_ticket.]]</h4>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <form name="EditTicketCardTypesForm" method="POST" onsubmit="return checkForm();">
                        <div class="col-md-12" style="margin-bottom: 20px;">
                            <label for="hidden">[[.Hidden.]]  </label> <input name="hidden" type="checkbox" id="hidden" <?php if(!empty([[=hidden=]]) && [[=hidden=]]==1) echo "checked=''"; ?> value="1" style="padding-top: 2px;" />
                            <button class="btn btn-info btn-sm pull-right" type="submit" name="submit" value="[[.add.]]"><span class="glyphicon glyphicon-floppy-saved" style="margin-right: 4px; color: white;"></span><?php echo (Url::get('id')?"Sửa":"Thêm"); ?></button>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group col-md-2 col-md-offset-1">
                                <label class="form-label">[[.Code.]] : </label>
                                <input name="code" type="text" id="code" class="form-control" required="" value="<?php echo (isset([[=code=]])?[[=code=]]:""); ?>" <?php echo (Url::get('id')?"readonly=''":""); ?> />
                            </div>
                            <div class="form-group col-md-4 col-md-offset-1">
                                <label class="form-label">[[.Name.]] : </label>
                                <input name="name" type="text" id="name" class="form-control" required="" value="<?php echo (isset([[=name=]])?[[=name=]]:""); ?>" />
                            </div>
                            <div class="form-group col-md-2 col-md-offset-1">
                                <label class="form-label">[[.Price.]] : </label>
                                <input class="form-control" name="price" id="price" type="text" required="" oninput="jQuery(this).ForceNumericOnly().FormatNumber();" value="<?php echo (isset([[=price=]])?System::display_number([[=price=]]):""); ?>"  />
                            </div>
                            <div class="col-md-10 col-md-offset-1" style="margin-top: 30px;">
                                <!--LIST:ticket_card_area-->
                                    <div class="col-md-6" style="margin-top: 30px; text-align: left;">
                                        <label>
                                            <input type="checkbox" name="area[]" id="area_[[|ticket_card_area.id|]]" value="[[|ticket_card_area.id|]]" style="width: 20px; height: 20px; margin-right: 3px; display: block; float: left;" <?php if([[=ticket_card_area.checked=]]==1) echo "checked=''"; ?> />
                                            <span class="btn btn-sm btn-danger" style="margin-left: 2px; min-width: 150px;">[[|ticket_card_area.name|]]</span>
                                        </label>
                                    </div>
                                <!--/LIST:ticket_card_area-->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var ticket_card_type_list = <?php if(!empty([[=ticket_card_type_list=]])) echo [[=ticket_card_type_list=]]; else echo "''"; ?>;
    
    function checkForm(){
        var condition = false;
        var current_code = jQuery("#code").val().trim();
        var current_name = jQuery("#name").val().trim();
        if(jQuery("#code").val().trim()==""){
            alert("Mã không được để trống!");
            jQuery(this).focus();
            return condition;
        }
        if(jQuery("#name").val().trim()==""){
            alert("Tên không được để trống!");
            jQuery(this).focus();
            return condition;
        }
        if(jQuery("#price").val().trim()==""){
            alert("Giá không được để trống!");
            jQuery(this).focus();
            return condition;
        }
        for(var code in ticket_card_type_list)
        {
            if(current_code == ticket_card_type_list[code]['code'])
            {
                alert("Mã này đã tồn tại trong hệ thống! Xin vui lòng kiểm tra lại!");
                jQuery("#code").focus();
                return condition;
            }
            if(current_name == ticket_card_type_list[code]['name'])
            {
                alert("Tên này đã tồn tại trong hệ thống! Xin vui lòng kiểm tra lại!");
                jQuery("#name").focus();
                return condition;
            }
        }
        jQuery("input[id^=area]").each(function(){
            if(jQuery(this).is(":checked")){
               condition = true;
               return condition;
            }
        });
        if(!condition){
            alert("Bạn bắt buộc phải chọn ít nhất một khu vực cho loại vé này !");
        }
        return condition;
    }
</script>