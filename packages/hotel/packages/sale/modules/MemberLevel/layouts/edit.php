<form name="EditMemberLevelForm" method="POST" enctype="multipart/form-data">
    <div style="margin: 10px auto; padding: 20px;">
        <table cellpadding="5" cellspacing="5">
            <tr style="border-bottom: 1px dashed #CCCCCC;">
                <td colspan="3"><h1><?php if(Url::get('cmd')=='add'){ echo Portal::language('add_member_level'); }else{ echo Portal::language('edit_member_level'); } ?></h1></td>
                
                <td style="text-align: right;">
                    <?php if((User::can_add(false,ANY_CATEGORY) AND Url::get('cmd')=='add') OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY))){ ?> 
                    <input name="save_stay" type="submit" id="save_stay" value="[[.save_stay.]]" style="padding: 10px;" />
                    <input name="save" type="submit" id="save" value="[[.save.]]" style="padding: 10px;" />
                    <?php } ?>
                    <input name="back" type="button" id="back" value="[[.back.]]" style="padding: 10px;" onclick="window.location.href='?page=member_level';" />
                </td>
            </tr>
            <tr>
                <td>
                    <label style="width: 250px; font-weight: bold;">[[.name.]]</label>: 
                </td>
                <td>
                    <input name="name" type="text" value="[[|name|]]" id="name" style="padding: 10px;" />
                </td>
                <td>
                    <label style="width: 250px; font-weight: bold;">[[.def_name.]]</label>: 
                </td>
                <td>
                    <input name="def_name" type="text" value="[[|def_name|]]" id="def_name" style="padding: 10px;" />
                </td>
            </tr>
            <tr>
                <td>
                    <label style="width: 250px; font-weight: bold;">[[.min_point_level.]]</label>: 
                </td>
                <td>
                    <input name="min_point" type="text" value="[[|min_point|]]" id="min_point" style="padding: 10px;" />
                </td>
                <td>
                    <label style="width: 250px; font-weight: bold;">[[.max_point_level.]]</label>: 
                </td>
                <td>
                    <input name="max_point" type="text" value="[[|max_point|]]" id="max_point" style="padding: 10px;" />
                </td>
            </tr>
            <tr>
                <td>
                    <label style="width: 250px; font-weight: bold; display: none;">[[.num_people_attach.]]</label>: 
                </td>
                <td>
                    <input name="num_people" type="text" value="[[|num_people|]]" id="num_people" style="padding: 10px; display: none;" />
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <?php if([[=logo=]]!=''){ ?>
                    <img src="[[|logo|]]" style="width: 50px; height: 50px;" /><br />
                    <?php } ?>
                    [[.logo.]]<br /><input type="file" name="file" id="file" style="padding: 10px;" />
                    <input name="logo" type="text" value="[[|logo|]]" id="logo" style="display: none; padding: 10px;" />
                </td>
            </tr>
        </table>
    </div>
</form>