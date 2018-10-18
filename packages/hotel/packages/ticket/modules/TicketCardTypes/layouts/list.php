<div class="row">
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">[[.ticket_card_type_list.]]</h4>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-bottom: 20px;">
                        <button class="btn btn-info btn-sm pull-right" onclick="window.location='?page=ticket_card_types&cmd=edit<?php if(Url::get('area_type')) echo "&area_type=TICKET_ORDER"; ?>'"><span class="glyphicon glyphicon-plus" style="margin-right: 4px; color: white;"></span>[[.Add.]]</button>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 20px;">
                      <form name="TicketCardTypesForm" id="TicketCardTypesForm" method="POST">
                           <div class="col-md-3">
                             <label class="form-label">[[.Ticket_name.]]</label>
                             <input name="ticket_name" id="ticket_name" list="ticket_name_list" <?php if(isset($_REQUEST['ticket_name'])) echo "value='".$_REQUEST['ticket_name']."'" ?> class="form-control"/>
                             <datalist id="ticket_name_list">
                               <!--LIST:ticket_card_types_list-->
                                  <option value="[[|ticket_card_types_list.name|]]"></option>
                               <!--/LIST:ticket_card_types_list-->
                             </datalist>
                           </div> 
                           <div class="col-md-4">
                             <label class="form-label">[[.Ticket_area.]]</label>
                             <select name="ticket_area" id="ticket_area" class="form-control">
                                <option value="all" <?php if(isset($_REQUEST['ticket_area']) && $_REQUEST['ticket_area']=='all') echo "selected=''"; ?> >[[.All.]]</option>
                                <!--LIST:ticket_card_area_list-->
                                  <option value="[[|ticket_card_area_list.id|]]" <?php if(isset($_REQUEST['ticket_area']) && $_REQUEST['ticket_area']==[[=ticket_card_area_list.id=]]) echo "selected=''"; ?> >[[|ticket_card_area_list.name|]]</option>
                                <!--/LIST:ticket_card_area_list-->
                             </select>
                           </div> 
                           <div class="col-md-3">
                              <button name="search" class="btn btn-danger" type="submit" style="margin-top: 20px;" value="1">[[.Search.]]</button>
                           </div>
                       </form>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>[[.Code.]]</th>
                                <th>[[.Name.]]</th>
                                <th>[[.Price.]]</th>
                                <th>[[.area_list.]]</th>
                                <th>[[.Status.]]</th>
                                <th>[[.Edit.]]</th>
                                <?php
                                  if(User::can_delete(false,ANY_CATEGORY))
                                  {
                                ?>
                                <th>[[.Delete.]]</th>
                                <?php    
                                  } 
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; ?>
                            <!--LIST:ticket_card_types-->
                                <tr>
                                    <td><?php echo $i; $i++; ?></td>
                                    <td>[[|ticket_card_types.code|]]</td>
                                    <td>[[|ticket_card_types.name|]]</td>
                                    <td><?php echo System::display_number([[=ticket_card_types.price=]]); ?> đ</td>
                                    <td>
                                        <!--LIST:ticket_card_types.items-->
                                            <span style="color: blue; font-weight: bold;"> + <?php echo mb_strtoupper([[=ticket_card_types.items.name=]],'utf-8'); ?></span>
                                            <br />
                                            <br />
                                        <!--/LIST:ticket_card_types.items-->
                                    </td>
                                    <td>
                                        <?php
                                            if([[=ticket_card_types.hidden=]]==1) echo "Ẩn"; else echo "Hiện";
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            if([[=ticket_card_types.can_change_status=]]==1){
                                        ?>
                                        <button onclick="window.open('<?php if(!Url::get('area_type')) echo Url::build_current(array('cmd'=>'edit','id'=>[[=ticket_card_types.id=]])); else echo Url::build_current(array('cmd'=>'edit','id'=>[[=ticket_card_types.id=]],'area_type'=>Url::get('area_type'))); ?>')"><span class="glyphicon glyphicon-pencil" style="color: blue; font-weight: bold;"></span></button>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <?php
                                      if(User::can_delete(false,ANY_CATEGORY))
                                      {
                                    ?>
                                    <td><button onclick="checkDelete(this,[[|ticket_card_types.can_delete|]],[[|ticket_card_types.id|]]);"><span class="glyphicon glyphicon-remove" style="color: red; font-weight: bold;"></span></button></td>
                                    <?php    
                                      } 
                                    ?>
                                </tr>
                            <!--/LIST:ticket_card_types-->
                        </tbody>
                    </table>
                    <div class="col-md-4 col-md-offset-8">
                        <nav>
                          <ul class="pagination">
                            <li class="page-item <?php if( (!isset($_GET['page_no'])) || (isset($_GET['page_no']) && $_GET['page_no']==1)) echo "disabled"; ?>">
                              <a class="page-link" href="<?php if(isset($_GET['page_no']) && $_GET['page_no']>1) echo Url::build_current(array('page_no'=>$_GET['page_no']-1)); else echo "javascript:void(0);"; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                              </a>
                            </li>
                            <?php
                                for($i=1;$i<=[[=rows=]];$i++){
                            ?>
                                <li class="page-item <?php if(isset($_GET['page_no']) && $_GET['page_no']==$i) echo "active"; ?>"><a style="line-height: inherit;" class="page-link" href="<?php echo Url::build_current(array('page_no'=>$i)); ?>"><?php echo $i; ?></a></li>                            
                            <?php
                                }        
                            ?>                                        
                            <li class="page-item <?php if( ([[=rows=]]==1) || (isset($_GET['page_no']) && $_GET['page_no']==$i-1)) echo "disabled"; ?>">
                              <a class="page-link" href="<?php if(isset($_GET['page_no']) && $_GET['page_no']<($i-1)) echo Url::build_current(array('page_no'=>$_GET['page_no']+1)); else echo "javascript:void(0);"; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                              </a>
                            </li>
                          </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  function checkDelete(obj,can_delete,card_type_id)
  {
    if(can_delete==1)
    {
        if(confirm('[[.Are_you_sure_to_delete_this_ticket.]]?'))
        {
            window.location = '?page=ticket_card_types&cmd=delete&delete_id='+card_type_id;
        }
    }
    else{
        alert("[[.This_ticket_has_been_used_to_sell.]]");
    }
  }
</script>