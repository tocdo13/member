<?php //System::debug([[=items=]]); ?>

<div id="style_table">
    
   
    <table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
        <tr bgcolor="#EFEFEF">
            <th align="center">
                STT
            </th>
            <th align="center">
                [[.company_name.]]
            </th>
            <th align="center">
               Địa chỉ
            </th>
            <th align="center">Ngành</th>
            <th align="center">Tỉnh</th>
            <th align="center">Loại CT</th>
            <th align="center">Mã số thuế</th>
            <th align="center">Phone</th>
            <th align="center">Fax</th>
            <th align="center">Email</th>
            <th align="center">Người liên hệ</th>
            <th align="center">Chức vụ</th>
            <th align="center">NV</th>
            
        </tr>
       
        <!--LIST:items-->
        <tr bgcolor="white">
            <td align="center">[[|items.cid|]]</td>
            <td align="left">[[|items.name|]]</td>
            <td align="left">[[|items.address|]]</td>
            <td align="left">[[|items.sname|]]</td>
            <td align="left">[[|items.name_1|]]</td>
            <td align="left">[[|items.groupname|]]</td>
            <td align="right">[[|items.tax_code|]]</td>
            <td align="right">[[|items.phone|]]</td>
            <td align="right">[[|items.fax|]]</td>
            <td align="left">[[|items.email|]]</td>
            <td align="left">[[|items.contact_person_name|]]</td>
            <td align="left">[[|items.cregency|]]</td>
            <td align="left">[[|items.sale_code|]]</td>
            
        </tr>
        <!--/LIST:items-->
    </table>
    <div style="width: 100px; text-align: center; margin: 0px auto 20px;"><?php if(isset([[=num_page=]])){ ?>[[.page.]][[|num_page|]]/[[|total_page|]]<?php } ?></div>
</div>