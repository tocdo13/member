<div class="row">
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">[[.ticket_card_area_ip_list.]]</h4>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>[[.Code.]]</th>
                                <th>[[.Name.]]</th>
                                <th>[[.IP_list.]]</th>
                                <th>[[.Edit.]]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--LIST:ticket_card_area-->
                                <tr>
                                    <td>[[|ticket_card_area.rnk|]]</td>
                                    <td>[[|ticket_card_area.code|]]</td>
                                    <td>[[|ticket_card_area.name|]]</td>
                                    <td>
                                        <!--LIST:ticket_card_area.items-->
                                            <span style="color: red; font-weight: bold;"> + [[|ticket_card_area.items.ip|]]</span>
                                            <br />
                                            <br />
                                        <!--/LIST:ticket_card_area.items-->
                                    </td>
                                    <td>
                                        <?php
                                            if([[=ticket_card_area.can_change_status=]]==1){
                                        ?>
                                        <button onclick="window.open('<?php if(!Url::get('area_type')) echo Url::build_current(array('cmd'=>'edit','id'=>[[=ticket_card_area.id=]])); else echo Url::build_current(array('cmd'=>'edit','id'=>[[=ticket_card_area.id=]],'area_type'=>Url::get('area_type'))); ?>')"><span class="glyphicon glyphicon-pencil" style="color: blue; font-weight: bold;"></span></button>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <!--/LIST:ticket_card_area-->
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
                            <li class="page-item <?php if( (!isset($_GET['page_no'])) || (isset($_GET['page_no']) && $_GET['page_no']==$i-1)) echo "disabled"; ?>">
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