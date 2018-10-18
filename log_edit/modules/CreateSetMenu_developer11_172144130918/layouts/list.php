<div class="row">
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">[[.set_menu_list.]]</h4>
                </div>
                <div class="panel-body">
                    <div class="col-md-3 pull-right" style="margin-bottom:30px;">
                        <div class="pull-right"><button class="btn btn-sm btn-info" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'edit')); ?>')">[[.Add.]]</button></div>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>[[.Code.]]</th>
                                <th>[[.Name.]]</th>
                                <th>[[.Department.]]</th>
                                <th>[[.Product_list.]]</th>
                                <th>[[.Total.]]</th>
                                <th>[[.Start_date.]]</th>
                                <th>[[.End_date.]]</th>
                                <th>[[.Copy.]]</th>
                                <th>[[.Edit.]]</th>
                                <th>[[.Delete.]]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--LIST:bar_set_menu-->
                                <tr <?php if(isset([[=bar_set_menu.count=]])) echo "style='background:#D5FFD5;'"; ?> >
                                    <td>[[|bar_set_menu.rnk|]]</td>
                                    <td>[[|bar_set_menu.code|]]</td>
                                    <td>[[|bar_set_menu.name|]]</td>
                                    <td>[[|bar_set_menu.department_name|]]</td>
                                    <td>
                                        <!--LIST:bar_set_menu.items-->
                                            <span> - <?php echo mb_strtoupper([[=bar_set_menu.items.product_name=]],'utf-8'); ?></span><span style="float: right; font-style: italic;"> x [[|bar_set_menu.items.quantity|]]</span>
                                            <br />
                                        <!--/LIST:bar_set_menu.items-->
                                    </td>
                                    <td> 
                                        <?php echo number_format([[=bar_set_menu.total=]],0,'.',','); ?> d   
                                    </td>
                                    <td>[[|bar_set_menu.start_date|]]</td>
                                    <td>[[|bar_set_menu.end_date|]]</td>
                                    <td>
                                        <button onclick="window.open('<?php echo Url::build_current(array('cmd'=>'edit','copy'=>1,'id'=>[[=bar_set_menu.id=]])); ?>')"><span class="glyphicon glyphicon-copy" style="color: green; font-weight: bold;"></span></button>
                                    </td>
                                    <td>
                                        <?php
                                            if([[=bar_set_menu.can_change_status=]]==1){
                                        ?>
                                        <button onclick="window.open('<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=bar_set_menu.id=]])); ?>')"><span class="glyphicon glyphicon-pencil" style="color: blue; font-weight: bold;"></span></button>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                    <?php
                                        if([[=bar_set_menu.can_change_status=]]==1){
                                    ?>
                                        <button onclick="if(confirm('[[.Are_you_sure.]]?')) window.location='<?php echo Url::build_current(array('cmd'=>'list','delete_id'=>[[=bar_set_menu.id=]])); ?>'"><span class="glyphicon glyphicon-remove" style="color: red; font-weight: bold;"></span></button>
                                    <?php        
                                        }
                                    ?>                                       
                                    </td>
                                </tr>
                            <!--/LIST:bar_set_menu-->
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