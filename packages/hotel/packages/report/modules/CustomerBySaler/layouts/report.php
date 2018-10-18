<?php //System::debug([[=items=]]);?>


<div id="report_content" style="width: 100%; margin: 10px auto;">
    
   
    <table  cellSpacing=0 border=1 style="width: 100%;">
        <tr bgcolor="#EFEFEF">
            <th style="width: 2%;" align="center">STT</th>
            <th style="width: 12%;" align="center">[[.company_name.]]</th>
            <th style="width: 15%;" align="center">Địa chỉ</th>
            <th style="width: 7%;" align="center">Ngành</th>
            <th style="width: 7%;" align="center">Tỉnh</th>
            <th style="width: 7%;" align="center">Loại CT</th>
            <th style="width: 7%;" align="center">Mã số thuế</th>
            <th style="width: 7%;" align="center">Phone</th>
            <th style="width: 7%;" align="center">Fax</th>
            <th style="width: 7%;" align="center">Email</th>
            <th style="width: 7%;" align="center">Người liên hệ</th>
            <th style="width: 7%;" align="center">Chức vụ</th>
            
            
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
            
            
        </tr>
        
        <!--/LIST:items-->
    </table>
    <div style="width: 100px; text-align: center; margin: 0px auto 20px;"><?php if(isset([[=num_page=]])){ ?>[[.page.]][[|num_page|]]/[[|total_page|]]<?php } ?></div>
    
</div>