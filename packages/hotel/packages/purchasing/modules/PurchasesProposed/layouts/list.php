<style>
    a:hover{
        text-decoration: none;
    }
</style>
<form name="ListPurchasesProposedForm" method="post">
    <table style="width: 90%; margin: 0px auto;">
        <tr>
            <td style="width: 45px;">
                <img src="packages\hotel\packages\purchasing\skins\default\images\purchases_proposed\iconarchive.png" style="width: 40px; height: auto; float: left; border-radius: 50%; border: 3px solid #09435b;" />
            </td>
            <td>
                <h3 style="float: left; line-height: 40px; font-size: 25px; text-transform: uppercase;">[[.list_purchases_proposed.]]</h3>
            </td>
            <td style="text-align: right;">
                <a href="?page=purchases_proposed&cmd=add" style="padding: 10px; margin-right: 30px; color: #fff; background: #00b2f9;">[[.add_purchases_proposed.]]</a>
            </td>
        </tr>
    </table>
    <fieldset style="width: 90%; margin: 0px auto; border: 1px solid #09435b;">
        <legend>[[.option_search.]]</legend>
        <table  cellpadding="5" cellspacing="5" width="90%">
            <tr>
                <td>[[.from_date.]]: <input name="from_date" type="text" id="from_date" style="border: 1px solid #09435b;" /></td>
                <td>[[.to_date.]]: <input name="to_date" type="text" id="to_date" style="border: 1px solid #09435b;" /></td>
                <td>[[.creater.]]: <select name="creater" id="creater" style="border: 1px solid #09435b;"></select></td>
                <td>[[.status.]]: <select name="status" id="status" style="border: 1px solid #09435b;"></select></td>
                <td>[[.confirm_user.]]: <select name="confirm_user" id="confirm_user" style="border: 1px solid #09435b;"></select></td>
                <td><input type="submit" name="do_search" value="[[.search.]]" style="color: #ffffff; background: #09435b; padding: 10px; font-weight: bold; border: none;" /></td>
            </tr>
        </table>
    </fieldset>
    <table cellpadding="5" cellspacing="5" width="90%" border="1" bordercolor="#646464" style="margin: 20px auto;">
        <tr style="background: #09435b; color: #ffffff; font-weight: bold;">
            <td style="text-align: center; color: #ffffff;">[[.stt.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.description.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.creater.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.create_time.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.status.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.confirm_user.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.confirm_time.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.edit.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.delete.]]</td>
        </tr>
        <!--LIST:items-->
        <tr style="text-align: center;">
            <td>[[|items.stt|]]</td>
            <td id="description_[[|items.id|]]">[[|items.description|]]</td>
            <td>[[|items.creater|]]</td>
            <td>[[|items.create_time|]]</td>
            <td><?php if([[=items.status=]]=='CONFIRMING'){ ?> [[.confirming.]] <?php }elseif([[=items.status=]]=='CONFIRM'){ ?> [[.confirm_1.]] <?php }elseif([[=items.status=]]=='CANCEL'){ ?> [[.cancel.]] <?php } ?></td>
            <td>[[|items.confirm_user|]]</td>
            <td>[[|items.confirm_time|]]</td>
            <td><?php if((User::can_edit(false,ANY_CATEGORY) AND [[=items.status=]]=='CONFIRMING') OR (User::can_admin(false,ANY_CATEGORY))){ ?> <a href="?page=purchases_proposed&cmd=edit&id=[[|items.id|]]"><img src="packages\hotel\packages\purchasing\skins\default\images\purchases_proposed\file_edit.png" style="width: 15px; height: auto;" /></a> <?php } ?></td>
            <td><?php if((User::can_delete(false,ANY_CATEGORY) AND ([[=items.status=]]=='CONFIRMING' OR [[=items.status=]]=='CANCEL'))){ ?> <img src="packages\hotel\packages\purchasing\skins\default\images\purchases_proposed\file_delete.png" style="width: 15px; height: auto; cursor: pointer;" onclick="fun_delete([[|items.id|]]);" /> <?php } ?></td>
        </tr>
        <!--/LIST:items-->
    </table>
</form>
<script>
    function fun_delete(id)
    {
        if(confirm("Bạn chắc chắn muốn xóa đề xuất "+jQuery("#description_"+id).html()))
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
                        alert('Thao tác xóa thành công!');
                        location.reload();
                        
                    }
                    else
                    {
                        alert('Thao tác xóa không thành công!');
                        location.reload();
                    }
                }
            }
            xmlhttp.open("GET","get_purchasing.php?data=delete&id="+id,true);
            xmlhttp.send();
        }
        else
        {
            
        }
    }
    
    function fun_flat(key)
    {
        
    }
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>