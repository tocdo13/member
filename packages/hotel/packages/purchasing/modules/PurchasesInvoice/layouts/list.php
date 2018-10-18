<style>
    *{
        margin: 0px;
        padding: 0px;
    }
    body{
        background: #ddd;
    }
    a
    {
        text-decoration: none;
        color: #00b2f9;
    }
    a:hover
    {
        text-decoration: none;
        color: #00b2f9;
    }
    a:active
    {
        text-decoration: none;
        color: #00b2f9;
    }
    a:visited
    {
        text-decoration: none;
        color: #00b2f9;
        font-weight: bold;
    }
    #nav_search{
        border: 2px solid #ffffff;
        transition: all 0.5s ease-out;
        box-shadow: 0px 0px 5px #555555;
        width: 50px; 
        height: 50px; 
        position: fixed; 
        top: 150px; 
        left: 10px;  
        border-radius: 50%; 
        box-shadow: 0px 0px 5px #171717; 
        overflow: hidden;
        cursor: pointer;
        background: #ffffff;
    }
    #nav_search:hover{
        border: 2px solid #09435b;
    }
    .nav_item{
        width: 30px;
        height: 30px;
        position: absolute;
        background: #ffffff;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        border: 2px solid #09435b;
        opacity: 0;
        transition: all 0.5s ease-out;
        top: 0px;
        
    }
    .nav_item_status
    {
        position: absolute;
        background: #ffffff;
        color: #171717;
        text-align: center;
        line-height: 30px;
        border: 2px solid #09435b;
        opacity: 1;
        transition: all 0.7s ease-out;
        padding: 0px 5px;
    }
    .nav_item:hover
    {
        background: #ffa0cd;
    }
    .nav_item .title_nav
    {
        height: 25px;
        width: 100px;
        text-align: center;
        position: absolute;
        padding: 2px;
        top: -30px;
        left: -35px;
        opacity: 0;
        background: url('packages/core/skins/default/images/m_icon/tool_tip.png') no-repeat top left;
        /**background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: 0px 0px;**/
        color: #ffa0cd;
        font-size: 11px;
        line-height: 15px;
        transition: all 0.3s ease-out;
    }
    .nav_item:hover .title_nav
    {
        opacity: 1;
    }
    .t_tip
    {
        width: 348px; 
        height: 346px; 
        margin: 20px 10px; 
        padding: 5px; 
        float: left;
        border-radius: 10px; 
        position: relative;
        cursor: pointer;
        border: 1px solid #555555;
        background: #ffffff;
        opacity: 1;
        -ms-transform: scale(1,1); /* IE 9 */
        -webkit-transform: scale(1,1); /* Chrome, Safari, Opera */
        transform: scale(1,1);
        transition: all 0.7s ease-out;
    }
    #container:hover .t_tip:not(:hover)
    {
        opacity: 0.5;
        -ms-transform: scale(0.8,0.8); /* IE 9 */
        -webkit-transform: scale(0.8,0.8); /* Chrome, Safari, Opera */
        transform: scale(0.8,0.8);
    }
    .t_tip:hover
    {
        border: 1px solid #09435b;
    }
    .t_tip:hover .nav_item
    {
        opacity: 1;
        border: 2px solid #09435b;
    }
    .t_tip:hover .nav_item_status
    {
        background: #09435b;
        color: #ffffff;
    }
    .t_status
    {
        width: 30px; 
        height: 30px; 
        position: absolute; 
        text-align: center; 
        text-transform: uppercase; 
        line-height: 25px; 
        background: ; 
        font-size: 13px; 
        color: #ffffff;
        opacity: 0;
        transition: all 0.3s ease-out;
        /**box-shadow: 0px 0px 5px #555555;**/
    }
    .t_tip:hover .t_status
    {
        opacity: 1;
    }
    
    .t_header
    {
        text-align: center; 
        width: 100%; 
        height: 90px;
    }
    .t_content
    {
        width: 96%; 
        height: 200px; 
        padding: 1%;
        border: 1px solid #ffffff;
        overflow: auto;
    }
    .t_information
    {
        text-align: center; 
        width: 100%; 
        height: 20px;
        font-weight: bold;
        color: #09435b;
        line-height: 20px;
        margin: 5px auto;
        transition: all 0.5s ease-out;
        opacity: 0;
    }
    .t_information span
    {
        font-weight: bold !important;
        color: #09435b;
    }
    .t_tip:hover .t_content
    {
        border: 1px solid #e9e9e9;
    }
    .t_tip:hover .t_information
    {
        opacity: 1;
    }
    #content_search
    {
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0px;
        left: -100%;
        /**background: rgba(255,255,255,0.3);**/
    }
    .detail
    {
        width: 100%;
        height: 100%;
        position: fixed;
        top: -100%;
        left: 0px;
        transition: all 0.3s ease-out;
        background: rgba(0,0,0,0.7);
        opacity: 0;
    }
    #search
    {
        width: 350px; 
        position: fixed; 
        top: 180px; 
        left: -370px; 
        background: #ffffff;
        box-shadow: 0px 0px 3px #171717;
        transition: all 0.3s ease-out;
    }
    #search form table tr td
    {
        padding: 3px;
    }
    .scale_opacity
    {
        opacity: 1;
        -ms-transform: scale(1,1); /* IE 9 */
        -webkit-transform: scale(1,1); /* Chrome, Safari, Opera */
        transform: scale(1,1);
    }
    .scale_opacity_hover
    {
        opacity: 0.5;
        -ms-transform: scale(0.8,0.8); /* IE 9 */
        -webkit-transform: scale(0.8,0.8); /* Chrome, Safari, Opera */
        transform: scale(0.8,0.8);
    }
    .deatil_tip table tr td,.deatil_tip table tr th
    {
        padding: 5px;
        text-align: center;
    }
    .select_supplierlist
    {
        border: 1px solid #09435b; width: 180px; height: 25px;
    }
</style>
    <div style="width: 100%; height: 50px; line-height: 30px; margin: 10px auto; text-align: center; background: #09435b;"><h3 style=" line-height: 50px; font-size: 20px; text-transform: uppercase; color: #ffffff;">[[.list_group_invoice.]]</h3></div>
    
    <div id="container" style="width: 1140px; height: auto; margin: 20px auto;">
        <!--LIST:items-->
            <div id="tip_[[|items.id|]]" class="t_tip">
                <!-- status -->
                <div class="t_status" title="[[.status.]]" style="top: -10px; left: -10px; border-top: 1px double #09435b; border-left: 1px double #09435b;"></div>
                <div class="t_status" title="[[.status.]]" style="right: -10px; top: -10px; border-top: 1px double #09435b; border-right: 1px double #09435b;"></div>
                <div class="t_status" title="[[.status.]]" style="right: -10px; bottom: -10px; border-bottom: 1px double #09435b; border-right: 1px double #09435b;"></div>
                <div class="t_status" title="[[.status.]]" style="bottom: -10px; left: -10px; border-bottom: 1px double #09435b; border-left: 1px double #09435b;"></div>
                <!-- title -->
                <div class="t_header">
                    <p style="text-transform: uppercase; font-size: 20px; margin-top: 20px; line-height: 30px; color: #171717; border-bottom: 1px dashed #d5d5d5; font-weight: normal;">[[.purchases_group_invoice_number.]] [[|items.id|]]</p>
                    <p style="line-height: 20px; color: #5c5c5c; font-weight: normal;">[[.create_time.]]: [[|items.create_time|]] | [[.quantity.]]: <?php echo System::display_number([[=items.quantity=]]) ?> | $: <?php echo System::display_number([[=items.total=]]) ?></p>
                    <p style="line-height: 20px; color: #5c5c5c; font-weight: normal;">[[.creater.]]: [[|items.user_name|]]</p>
                </div>
                <!-- content -->
                <div class="t_content">
                    <span style="font-weight: normal;">[[|items.description|]]</span>
                </div>
                <!-- information -->
                <div class="t_information">
                    <?php if([[=items.product_other=]]!=0) { ?>
                    <span style="font-weight: normal;">[[|items.product_other|]] [[.product_non_invoice.]] [[.of.]] [[|items.supplier_count|]] [[.supplier.]]</span>
                    <?php }else{ ?>
                    <span style="font-weight: normal;">[[.invoice_complated.]]</span>
                    <?php } ?>
                </div>
                <!-- navigation -->
                <div class="nav_item_status" style="top: -10px; left: 40px;">
                    <?php if([[=items.status=]]=='CONFIRMING'){ ?>[[.confirming.]]<?php }else{ ?>[[.confirm.]]<?php } ?>
                </div>
                <?php if([[=items.status=]]=='CONFIRMING'){ ?>
                <div class="nav_item" style="top: -10px; right: 40px;" onclick="flat_confirm_group([[|items.id|]])"><img src="packages\core\skins\default\images\m_icon\flat.png" style="width: 30px; height: auto; position: absolute; top: 0px; left: 0px;" title="[[.flat_confirm.]]" alt="[[.flat_confirm.]]" /><span class="title_nav">[[.flat_confirm.]]</span></div>
                <!--<div class="nav_item" style="top: -10px; right: 80px;"><a href="?page=purchases_invoice&cmd=group_invoice&id=[[|items.id|]]"><img src="packages\core\skins\default\images\m_icon\setting.png" style="width: 30px; height: auto; position: absolute; top: 0px; left: 0px;" title="[[.edit.]]" alt="[[.edit.]]" /><span class="title_nav">[[.edit.]]</span></a></div>-->
                <div class="nav_item" style="top: -10px; right: 120px;" onclick="open_detail('tip_[[|items.id|]]');"><img src="packages\core\skins\default\images\m_icon\detail.png" style="width: 30px; height: auto; position: absolute; top: 0px; left: 0px;" title="[[.detail.]]" alt="[[.detail.]]" /><span class="title_nav">[[.detail.]]</span></div>
                <?php }else{ ?>
                <!--<div class="nav_item" style="top: -10px; right: 40px;"><a href="?page=purchases_invoice&cmd=group_invoice&id=[[|items.id|]]"><img src="packages\core\skins\default\images\m_icon\setting.png" style="width: 30px; height: auto; position: absolute; top: 0px; left: 0px;" title="[[.edit.]]" alt="[[.edit.]]" /><span class="title_nav">[[.edit.]]</span></a></div>-->
                <div class="nav_item" style="top: -10px; right: 80px;" onclick="open_detail('tip_[[|items.id|]]');"><img src="packages\core\skins\default\images\m_icon\detail.png" style="width: 30px; height: auto; position: absolute; top: 0px; left: 0px;" title="[[.detail.]]" alt="[[.detail.]]" /><span class="title_nav">[[.detail.]]</span></div>
                <div class="nav_item" style="top: -10px; right: 120px;"><a href="?page=purchases_invoice&cmd=detail&invoice=group_invoice&id=[[|items.id|]]"><img src="packages\core\skins\default\images\m_icon\view.png" style="width: 30px; height: auto; position: absolute; top: 0px; left: 0px;" title="[[.view.]]" alt="[[.view.]]" /><span class="title_nav">[[.view.]]</span></a></div>
                <?php } ?>
            </div>
        <!--/LIST:items-->
    </div>
    <div id="content_search" onclick="">
        <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px;" onclick="jQuery('#key').val(1);slide_search();"></div>
        <div id="search">
            <form name="ListPurchasesProposedForm" method="post">
            <table style="margin: 30px auto;">
                <tr>
                    <td>[[.from_date.]]:</td><td> <input name="from_date" type="text" id="from_date" style="border: 1px solid #09435b; width: 180px; height: 25px;" /></td>
                </tr>
                <tr>
                    <td>[[.to_date.]]:</td><td> <input name="to_date" type="text" id="to_date" style="border: 1px solid #09435b; width: 180px; height: 25px;" /></td>
                </tr>
                <tr>
                    <td>[[.creater.]]:</td><td> <select name="creater" id="creater" style="border: 1px solid #09435b; width: 180px; height: 25px;"></select></td>
                </tr>
                <tr>
                    <td>[[.status.]]:</td><td> <select name="status" id="status" style="border: 1px solid #09435b; width: 180px; height: 25px;"></select></td>
                </tr>
                <tr>
                    <td style="text-align: center;" colspan="2">
                        <input type="text" id="key" value="0" style="display: none;" />
                        <input type="submit" name="do_search" value="[[.search.]]" style="color: #ffffff; background: #09435b; padding: 10px; font-weight: bold; border: none;" />
                    </td>
                </tr>
            </table>
            </form>
        </div>
    </div>
    <div id="nav_search" onclick="slide_search();">
        <img src="packages\core\skins\default\images\flat_icon\search_black.png" style="width: 40px; height: auto; margin: 5px;" title="[[.search.]]" alt="[[.search.]]" />
    </div>
    <form name="ListPurchasesProposedForm" method="post">
    <!--LIST:items-->
    
    <div id="detail_tip_[[|items.id|]]" class="detail">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px;" onclick="close_detail('tip_[[|items.id|]]');"></div>
    <div onclick="close_detail('tip_[[|items.id|]]');" style="width: 40px; height: 40px; background: #ffffff; cursor: pointer; border: 3px solid #555555; color: #555555; font-size: 30px; line-height: 40px; font-weight: bold; text-align: center; border-radius: 50%; position: absolute; top: 20px; right: 20px; box-shadow: 0px 0px 20px #000000;">X</div>
            
            <div class="deatil_tip" style="width: 960px; height: 500px; overflow: auto; margin: 60px auto; background: #ffffff; border: 2px solid #000000; box-shadow: 0px 0px 20px #000000;">
                <table cellpadding="5" cellspacing="5" border="1" bordercolor="#555555" style="margin: 10px; float: left;">
                    <tr>
                        <td>
                            <p style="text-transform: uppercase; font-size: 20px; margin-top: 20px; line-height: 30px; color: #171717; border-bottom: 1px dashed #555555;">[[.purchases_group_invoice_number.]] [[|items.id|]]</p>
                            <p style="line-height: 20px; color: #5c5c5c;">[[.create_time.]]: [[|items.create_time|]] | [[.quantity.]]: <?php echo System::display_number([[=items.quantity=]]) ?> | $: <?php echo System::display_number([[=items.total=]]) ?></p>
                            <p style="line-height: 20px; color: #5c5c5c;">[[.creater.]]: [[|items.user_name|]]</p>
                        </td>
                    </tr>   
                </table>
                <table cellpadding="5" cellspacing="5" style="margin: 10px; float: right; margin-right: 30px;">
                    <tr>
                        <td>
                            <?php if( sizeof([[=items.supplier_list=]])>0 ){ ?>
                            <span>[[.select_supplier_create_invoice.]]:</span>
                            <select id="supplierlist_[[|items.id|]]" name="supplier_list_[[|items.id|]]" class="select_supplierlist" onchange="select_supplier([[|items.id|]]);">
                                <option value="">[[.select_supplier.]]</option>
                                <!--LIST:items.supplier_list-->
                                <option value="[[|items.supplier_list.id|]]" >[[|items.supplier_list.name|]]</option>
                                <!--/LIST:items.supplier_list-->
                            </select>
                            <input name="submit_[[|items.id|]]" type="submit" value="[[.create_invoice.]]" class="submit_supplierlist" style="color: #ffffff; background: #09435b; padding: 10px; font-weight: bold; border: none;" />
                            <?php } ?>
                        </td>
                    </tr>   
                </table>
                <table cellpadding="5" cellspacing="5" border="1" bordercolor="#555555" style="margin: 10px; width: 940px;">
                    <tr style="color: #f7f7f7; background: #09435b;">
                        <th>...</th>
                        <th>[[.proposed_code.]]</th>
                        <th>[[.product_code.]]</th>
                        <th>[[.product_name.]]</th>
                        <th>[[.quantity.]]</th>
                        <th>[[.price.]]</th>
                        <th>[[.total.]]</th>
                        <th>[[.product_category.]]</th>
                        <th>[[.supplier_name.]]</th>
                        <th>[[.department.]]</th>
                        <th>[[.creater.]]</th>
                        <th>[[.create_date.]]</th>
                        <th>[[.status.]]</th>
                    </tr>
            <!--LIST:items.child-->
            <?php if([[=items.child.id=]]=='OTHER'){ ?>
                    <tr  style="background: #f1f1f1;">
                        <th>...</th>
                        <th colspan="13">[[.product_non_invoice.]]</th>
                    </tr>
                    <!--LIST:items.child.child-->
                    <?php if([[=items.child.child.supplier_code=]]==''){ $code="OTHER"; }else{ $code = [[=items.child.child.supplier_code=]]; } ?>
                    <tr>
                        <td><input name="create[[[|items.id|]]][<?php echo $code; ?>][[[|items.child.child.id|]]]" id="[[|items.id|]]_<?php echo $code; ?>_[[|items.child.child.id|]]" class="[[|items.id|]]_<?php echo $code; ?> input_check" type="checkbox" /></td>
                        <td>[[|items.child.child.dx_id|]]</td>
                        <td>[[|items.child.child.product_code|]]</td>
                        <td>[[|items.child.child.product_name|]]</td>
                        <td>[[|items.child.child.quantity|]]</td>
                        <td>[[|items.child.child.price|]]</td>
                        <td>[[|items.child.child.total|]]</td>
                        <td>[[|items.child.child.product_category_name|]]</td>
                        <td>[[|items.child.child.supplier_name|]]</td>
                        <td>[[|items.child.child.department|]]</td>
                        <td>[[|items.child.child.user_name|]]</td>
                        <td>[[|items.child.child.create_time|]]</td>
                        <td></td>
                    </tr>
                    <!--/LIST:items.child.child-->
             <?php }else{ ?> 
                    <tr  style="background: #f1f1f1;">
                        <th>...</th>
                        <th colspan="8">[[.purchases_invoice_number.]] [[|items.child.id|]]</th>
                        <th colspan="3"><?php if([[=items.child.status=]]=="CONFIRMING"){ $status = Portal::language('confirming'); ?>[[.confirming.]]<?php }else{ if([[=items.child.warehouse_invoice_id=]]==0){ $status = Portal::language('non_warehouse'); ?><a href="?page=warehouse_invoice&cmd=add&type=IMPORT&choose_warehouse=1&purchases_invoice_id=[[|items.child.id|]]">[[.import_warehouse.]]</a> <?php }else{ $status = Portal::language('warehoused'); ?>[[.warehoused.]] <?php } } ?></th>
                        <th colspan="2" style="position: relative; width: 150px; height: 40px;">
                            <?php if([[=items.child.status=]]=="CONFIRMING"){ ?>
                                <a href="?page=purchases_invoice&cmd=invoice&id=[[|items.child.id|]]"><img src="packages\core\skins\default\images\m_icon\setting.png" style="width: 30px; height: auto;" title="[[.edit.]]" alt="[[.edit.]]" /></a>
                            <?php }else{ ?>
                                <a href="?page=purchases_invoice&cmd=invoice&id=[[|items.child.id|]]"><img src="packages\core\skins\default\images\m_icon\setting.png" style="width: 30px; height: auto;" title="[[.edit.]]" alt="[[.edit.]]" /></a>
                                <a href="?page=purchases_invoice&cmd=detail&invoice=invoice&id=[[|items.child.id|]]"><img src="packages\core\skins\default\images\m_icon\view.png" style="width: 30px; height: auto;" title="[[.view.]]" alt="[[.view.]]" /></a>
                            <?php } ?></th>
                    </tr>
                    <!--LIST:items.child.child-->
                    <tr>
                        <td>...</td>
                        <td>[[|items.child.child.dx_id|]]</td>
                        <td>[[|items.child.child.product_code|]]</td>
                        <td>[[|items.child.child.product_name|]]</td>
                        <td>[[|items.child.child.quantity|]]</td>
                        <td>[[|items.child.child.price|]]</td>
                        <td>[[|items.child.child.total|]]</td>
                        <td>[[|items.child.child.product_category_name|]]</td>
                        <td>[[|items.child.child.supplier_name|]]</td>
                        <td>[[|items.child.child.department|]]</td>
                        <td>[[|items.child.child.user_name|]]</td>
                        <td>[[|items.child.child.create_time|]]</td>
                        <td><?php echo $status; ?></td>
                    </tr>
                    <!--/LIST:items.child.child-->
             <?php } ?>
            <!--/LIST:items.child-->
                </table>
            </div>
        </div>
    <!--/LIST:items-->
    </form>
    
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    jQuery.mCustomScrollbar.defaults.scrollButtons.enable=true;
    jQuery.mCustomScrollbar.defaults.axis="yx";
    jQuery(".t_content").mCustomScrollbar({theme:"dark"});
    jQuery(".deatil_tip").mCustomScrollbar({theme:"dark"});
    function slide_search()
    {
        if(to_numeric(jQuery("#key").val())==0)
        {
            jQuery("#key").val(1);
            jQuery("#content_search").css('left','0%');
            jQuery("#search").css('left','0px');
            jQuery(".t_tip").removeClass("scale_opacity");
            jQuery(".t_tip").addClass("scale_opacity_hover");
            
        }
        else
        {
            jQuery("#key").val(0);
            jQuery("#content_search").css('left','-100%');
            jQuery("#search").css('left','-370px');
            jQuery(".t_tip").removeClass("scale_opacity");
            jQuery(".t_tip").removeClass("scale_opacity_hover");
        }
    }
    function open_detail(key)
    {
        jQuery("#detail_"+key).css('top','0%');
        jQuery("#detail_"+key).css('opacity','1');
        jQuery(".t_tip").addClass("scale_opacity_hover");
        jQuery("#"+key).removeClass("scale_opacity_hover");
        
    }
    function close_detail(key)
    {
        jQuery("#detail_"+key).css('top','-100%');
        jQuery("#detail_"+key).css('opacity','0');
        jQuery(".t_tip").removeClass("scale_opacity_hover");
        jQuery(".input_check").removeAttr("checked");
        jQuery(".select_supplierlist").val("");
    }
    function flat_confirm_group(key)
    {
        if(confirm("Bạn chắc chắn muốn duyệt nhóm đơn hàng số "+key+"?"))
        {
            if (window.XMLHttpRequest){
                xmlhttp=new XMLHttpRequest();
            }
            else{
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function()
            {
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                    var text_reponse = xmlhttp.responseText;
                    if(text_reponse==1)
                    {
                        alert('Duyệt thành công!');
                        location.reload();
                        
                    }
                    else
                    {
                        alert('Không thành công');
                        location.reload();
                    }
                }
            }
            xmlhttp.open("GET","get_purchasing.php?data=confirm_group&id="+key,true);
            xmlhttp.send();
        }
    }
    function select_supplier(id)
    {
         jQuery(".input_check").removeAttr("checked");
         var value = jQuery("#supplierlist_"+id).val();
         if(value!="")
         {
            jQuery("."+id+"_"+value).attr("checked","checked");
         }
         else
         {
            jQuery(".input_check").removeAttr("checked");
         }
    }
</script>