/**
** function action MICE
** write by TOCDO
*/
    function FunctionAddMice(key,id)
    {
        // Ham add mot dich vu vao MICE
        // key: ma bo phan cua dich vu
        // id: ma mac dinh cua dich vu
        jQuery("#mice_loading").css('display','');
        if(key=='REC' || key=='EXS' || key=='RES' || key=='VENDING' || key=='BANQUET' || key=='TICKET')
        {
            $check = true;
            $folio_ids = '';
            if(key=='REC')
            {
                jQuery.ajax({
        					url:"get_mice.php?",
        					type:"POST",
        					data:{cmd:'check_folio_reservation',id:id},
        					success:function(html)
                            {
                                items = jQuery.parseJSON(html);
                                for(i in items)
                                {
                                    $check = false;
                                    if($folio_ids=='')
                                    {
                                        $folio_ids = items[i]['id'];
                                    }
                                    else
                                    {
                                        $folio_ids += ','+items[i]['id'];
                                    }
                                }
                                if($check==false)
                                {
                                    alert('Bạn chưa thanh toán hết folio: '+$folio_ids+' \n Để tạo MICE bạn phải thanh toán hoặc xóa các Folio này. \n Vui lòng thao tác lại! ');
                                    jQuery("#mice_loading").css('display','none');
                                }
                                else
                                {
                                    setTimeout(function(){
                                        window.location.href='?page=mice_reservation&cmd=add&from_department='+key+'&key_department='+id;
                                        jQuery("#mice_loading").css('display','none');
                                    }, 1000);
                                }
        					}
        		});
            }
            else
            {
                setTimeout(function(){
                    window.location.href='?page=mice_reservation&cmd=add&from_department='+key+'&key_department='+id;
                    jQuery("#mice_loading").css('display','none');
                }, 1000);
            }
            
        }
        else
        {
            jQuery("#mice_loading").css('display','none');
        }
    }
    
    function FunctionSplitMice(mice_id,key,id)
    {
        // Ham tach mot dich vu ra khoi MICE
        // key: ma bo phan cua dich vu
        // id: ma mac dinh cua dich vu
        jQuery("#mice_loading").css('display','');
        if(confirm('Bạnc hắc chắn muốn tách dịch vụ này ra khỏi MICE ?'))
        {
            jQuery.ajax({
    					url:"get_mice.php?",
    					type:"POST",
    					data:{cmd:'split_mice',mice_id:mice_id,key:key,id:id},
    					success:function(html)
                        {
                           if(to_numeric(html)==0)
                           {
                                setTimeout(function(){
                                    jQuery("#mice_loading").css('display','none');
                                    location.reload();
                                }, 1000);
                           } 
                           else if(to_numeric(html)==2)
                           {
                                alert('MICE đã Đóng, Không thể tách dịch vụ ra khỏi MICE');
                                jQuery("#mice_loading").css('display','none');
                           }
                           else
                           {
                                alert('Dịch vụ đã được tạo hóa đơn thanh toán trong MICE, nếu muốn tách dịch vụ ra khỏi MICE \n Bạn phải xóa các hóa đơn đã tạo cho dịch vụ này trong MICE \n Vu lòng thao tác lại!');
                                jQuery("#mice_loading").css('display','none');
                           }
    					}
    		});
        }
        else
        {
            jQuery("#mice_loading").css('display','none');
        }
    }
    function FunctionSelectMice(key,id)
    {
        $check = true;
        jQuery("#mice_loading").css('display','');
        if(key=='REC')
        {
            jQuery.ajax({
        					url:"get_mice.php?",
        					type:"POST",
        					data:{cmd:'check_folio_reservation',id:id},
        					success:function(html)
                            {
                                items = jQuery.parseJSON(html);
                                for(i in items)
                                {
                                    $check = false;
                                    if($folio_ids=='')
                                    {
                                        $folio_ids = items[i]['id'];
                                    }
                                    else
                                    {
                                        $folio_ids += ','+items[i]['id'];
                                    }
                                }
                                if($check==false)
                                {
                                    alert('Bạn chưa thanh toán hết: '+$folio_ids+' \n Để tạo MICE bạn phải thanh toán hết hoặc xóa Dolio này. \n Vui lòng thao tác lại! ');
                                    jQuery("#mice_loading").css('display','none');
                                }
                                else
                                {
                                    jQuery.ajax({
                            					url:"get_mice.php?",
                            					type:"POST",
                            					data:{cmd:'get_list_mice'},
                            					success:function(html)
                                                {
                                                   setTimeout(function(){
                                                        jQuery("#mice_loading").css('display','none');
                                                        items = jQuery.parseJSON(html);
                                                        console.log(items);
                                                        content = '';
                                                        content += '<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#EEEEEE">';
                                                                        content += '<tr style="text-align: center;">';
                                                                            content += '<th>Mã MICE</th>';
                                                                            content += '<th>Mã ngày tạo</th>';
                                                                            content += '<th>Ngày bắt đầu</th>';
                                                                            content += '<th>Ngày kết thúc</th>';
                                                                            content += '<th>Người liên hệ</th>';
                                                                            content += '<th>SĐT người liên hệ</th>';
                                                                            content += '<th>Email Người liên hệ</th>';
                                                                            content += '<th>Chọn</th>';
                                                                        content += '</tr>';
                                                        for(var i in items)
                                                        {
                                                            content += '<tr style="text-align: center;">';
                                                                content += '<td>'+items[i]['id']+'</td>';
                                                                content += '<td>'+items[i]['code_mice']+'</td>';
                                                                if(items[i]['start_date']==null)
                                                                    items[i]['start_date'] = '';
                                                                if(items[i]['end_date']==null)
                                                                    items[i]['end_date'] = '';
                                                                if(items[i]['contact_name']==null)
                                                                    items[i]['contact_name'] = '';
                                                                if(items[i]['contact_phone']==null)
                                                                    items[i]['contact_phone'] = '';
                                                                if(items[i]['contact_email']==null)
                                                                    items[i]['contact_email'] = '';
                                                                
                                                                content += '<td>'+items[i]['start_date']+'</td>';
                                                                content += '<td>'+items[i]['end_date']+'</td>';
                                                                content += '<td>'+items[i]['contact_name']+'</td>';
                                                                content += '<td>'+items[i]['contact_phone']+'</td>';
                                                                content += '<td>'+items[i]['contact_email']+'</td>';
                                                                content += '<td><input type="button" style="padding: 5px;" value="chon" onclick="FunctionJoinMice(\''+items[i]['id']+'\',\''+key+'\',\''+id+'\');" /></td>';
                                                            content += '</tr>';
                                                        }
                                                        content += '</table>';
                                                        document.getElementById("mice_light_box_content").innerHTML = content;
                                                        jQuery("#mice_light_box").css('display','');
                                                    }, 1000);
                            					}
                            		});
                                }
        					}
        		});
        }
        else
        {
            jQuery.ajax({
    					url:"get_mice.php?",
    					type:"POST",
    					data:{cmd:'get_list_mice'},
    					success:function(html)
                        {
                           setTimeout(function(){
                                jQuery("#mice_loading").css('display','none');
                                items = jQuery.parseJSON(html);
                                console.log(items);
                                content = '';
                                content += '<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#EEEEEE">';
                                                content += '<tr style="text-align: center;">';
                                                    content += '<th>Mã MICE</th>';
                                                    content += '<th>Mã ngày tạo</th>';
                                                    content += '<th>Ngày bắt đầu</th>';
                                                    content += '<th>Ngày kết thúc</th>';
                                                    content += '<th>Người liên hệ</th>';
                                                    content += '<th>SĐT người liên hệ</th>';
                                                    content += '<th>Email Người liên hệ</th>';
                                                    content += '<th>Chọn</th>';
                                                content += '</tr>';
                                for(var i in items)
                                {
                                    content += '<tr style="text-align: center;">';
                                        content += '<td>'+items[i]['id']+'</td>';
                                        content += '<td>'+items[i]['code_mice']+'</td>';
                                        if(items[i]['start_date']==null)
                                            items[i]['start_date'] = '';
                                        if(items[i]['end_date']==null)
                                            items[i]['end_date'] = '';
                                        if(items[i]['contact_name']==null)
                                            items[i]['contact_name'] = '';
                                        if(items[i]['contact_phone']==null)
                                            items[i]['contact_phone'] = '';
                                        if(items[i]['contact_email']==null)
                                            items[i]['contact_email'] = '';
                                        
                                        content += '<td>'+items[i]['start_date']+'</td>';
                                        content += '<td>'+items[i]['end_date']+'</td>';
                                        content += '<td>'+items[i]['contact_name']+'</td>';
                                        content += '<td>'+items[i]['contact_phone']+'</td>';
                                        content += '<td>'+items[i]['contact_email']+'</td>';
                                        content += '<td><input type="button" style="padding: 5px;" value="chon" onclick="FunctionJoinMice(\''+items[i]['id']+'\',\''+key+'\',\''+id+'\');" /></td>';
                                    content += '</tr>';
                                }
                                content += '</table>';
                                document.getElementById("mice_light_box_content").innerHTML = content;
                                jQuery("#mice_light_box").css('display','');
                            }, 1000);
    					}
    		});
        }
    }
    
    function FunctionJoinMice(mice_id,key,id)
    {
        if(id!='')
        {
            jQuery("#mice_light_box").css('display','none');
            jQuery("#mice_loading").css('display','');
            jQuery.ajax({
        					url:"get_mice.php?",
        					type:"POST",
        					data:{cmd:'join_mice',mice_id:mice_id,key:key,id:id},
        					success:function(html)
                            {
                               setTimeout(function(){
                                    jQuery("#mice_loading").css('display','none');
                                    location.reload();
                                }, 1000);
        					}
        		});
        }
        else
        {
            jQuery("#mice_action_module").val(mice_id);
            jQuery("#mice_loading").css('display','none');
            jQuery("#mice_light_box").css('display','none');
            FuncShowInput();
        }
    }
    
    