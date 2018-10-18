<style>
.simple-layout-middle{
    width: 100%;
}
.simple-layout-content{
    border-right: none;
}
#DeclareCounter
{
    width: 100%;
    margin: 0 auto;
}
#ContentData{
    width: 50%;
    margin: 0 auto;
}
input{
    width: 150px;
    height: 25px;
}
.highlight .button{
		display: inline-block;
		color: #FFF;
		background: #009688;
		margin: 0;
		height: 30px;
		line-height: 30px;
		border-radius: 4px;
		position: relative;
		overflow: hidden;
        cursor: pointer;
}

.highlight .button:before{
		content: "";
		position: absolute;
		top: -30px;
		left: -80px;
		height: 100px;
		width: 70px;
		background: rgba(255, 255, 255, .7);
		transform: rotate(20deg);
}

.highlight .button:hover:before{
		left: 150px;
		transition: all .5s;
        cursor: pointer;
}
.highlight{
    margin-bottom:100px;
    font-weight: bold;
    text-align: left;
}
.button{
    cursor: pointer;
}
</style>
<div id="DeclareCounter">
    <h2 style="text-align: center;color: #069696;">[[.declare_counter.]]</h2>
    <hr />
    <form id="DeclareCounterForm" method="post">
        <div style="width: 100%;">
            <table>
                <tr>
                    <td width="90%">&nbsp;</td>
                    <?php if (User::can_add(false, ANY_CATEGORY)) { ?><td class="highlight"><span class="button" id="Save_Data" onclick="GetRequest(Save);">&nbsp; <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp; [[.save_and_continue.]] <input name="Save" type="hidden" id="Save" /> &nbsp;</span></td><?php } ?>
                </tr>
            </table>
            <hr style="border: 1px solid red;" />
        </div>
        <div id="ContentData">
            <table width="100%" style="margin: 0 auto; border-color: #009688;" border="1">
                <tr style="background: #ECEFF1; color: #009688; height: 25px; line-height: 25px; text-align: center; font-weight: bold;">
                    <td width="50px">[[.stt.]]</td>
                    <td width="250px">[[.area.]]</td>
                    <td width="150px">[[.print_kitchen_name.]]</td>
                    <td width="150px">[[.print_bar_name.]]</td>
                </tr>
                <?php $i = 1; ?>
                <!--LIST:items-->
                <tr>
                    <td align="center" style="display: none;">
                    <?php
                        echo '<input name=vending['. [[=items.id=]] .'][id] type=text id=vending_id_'.[[=items.id=]].' value='.[[=items.vending_counter_id=]].' >';
                    ?>
                    </td>
                    <td align="center" style="display: none;">
                    <?php
                        echo '<input name=vending['. [[=items.id=]] .'][department_id] type=text id=vending_department_id_'.[[=items.id=]].' value='.[[=items.id=]].' >';
                    ?>
                    </td>
                    <td align="center"><?php echo $i++; ?></td>
                    <td align="left">[[|items.name|]]</td>
                    <td align="left">
                    <?php
                        echo '<input name=vending['. [[=items.id=]] .'][print_kitchen_name] type=text id=vending_print_kitchen_name_'.[[=items.id=]].' value='.[[=items.print_kitchen_name=]].' >';
                    ?>
                    </td>
                    <td align="left">
                    <?php
                        echo '<input name=vending['. [[=items.id=]] .'][print_bar_name] type=text id=vending_print_bar_name_'.[[=items.id=]].' value='.[[=items.print_bar_name=]].' >';
                    ?>
                    </td>
                </tr>
                <!--/LIST:items-->
            </table>
        </div>
    </form>
</div>
<script>
function GetRequest(obj)
{
    jQuery('#Save_Data').css('display', 'none');
    jQuery('#'+obj.id).val(obj.id);
    DeclareCounterForm.submit();
}
</script>