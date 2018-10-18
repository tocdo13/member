<div class="row">
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title"><?php echo Portal::language('set_menu_list');?></h4>
                </div>
                <div class="panel-body">
                    <div class="col-md-3 pull-right" style="margin-bottom:30px;">
                        <div class="pull-right"><button class="btn btn-sm btn-info" onclick="window.open('<?php echo Url::build_current(array('cmd'=>'edit')); ?>')"><?php echo Portal::language('Add');?></button></div>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th><?php echo Portal::language('Code');?></th>
                                <th><?php echo Portal::language('Name');?></th>
                                <th><?php echo Portal::language('Department');?></th>
                                <th><?php echo Portal::language('Product_list');?></th>
                                <th><?php echo Portal::language('Total');?></th>
                                <th><?php echo Portal::language('Start_date');?></th>
                                <th><?php echo Portal::language('End_date');?></th>
                                <th><?php echo Portal::language('Copy');?></th>
                                <th><?php echo Portal::language('Edit');?></th>
                                <th><?php echo Portal::language('Delete');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($this->map['bar_set_menu']) and is_array($this->map['bar_set_menu'])){ foreach($this->map['bar_set_menu'] as $key1=>&$item1){if($key1!='current'){$this->map['bar_set_menu']['current'] = &$item1;?>
                                <tr <?php if(isset($this->map['bar_set_menu']['current']['count'])) echo "style='background:#D5FFD5;'"; ?> >
                                    <td><?php echo $this->map['bar_set_menu']['current']['rnk'];?></td>
                                    <td><?php echo $this->map['bar_set_menu']['current']['code'];?></td>
                                    <td><?php echo $this->map['bar_set_menu']['current']['name'];?></td>
                                    <td><?php echo $this->map['bar_set_menu']['current']['department_name'];?></td>
                                    <td>
                                        <?php if(isset($this->map['bar_set_menu']['current']['items']) and is_array($this->map['bar_set_menu']['current']['items'])){ foreach($this->map['bar_set_menu']['current']['items'] as $key2=>&$item2){if($key2!='current'){$this->map['bar_set_menu']['current']['items']['current'] = &$item2;?>
                                            <span> - <?php echo mb_strtoupper($this->map['bar_set_menu']['current']['items']['current']['product_name'],'utf-8'); ?></span><span style="float: right; font-style: italic;"> x <?php echo $this->map['bar_set_menu']['current']['items']['current']['quantity'];?></span>
                                            <br />
                                        <?php }}unset($this->map['bar_set_menu']['current']['items']['current']);} ?>
                                    </td>
                                    <td> 
                                        <?php echo number_format($this->map['bar_set_menu']['current']['total'],0,'.',','); ?> d   
                                    </td>
                                    <td><?php echo $this->map['bar_set_menu']['current']['start_date'];?></td>
                                    <td><?php echo $this->map['bar_set_menu']['current']['end_date'];?></td>
                                    <td>
                                        <button onclick="window.open('<?php echo Url::build_current(array('cmd'=>'edit','copy'=>1,'id'=>$this->map['bar_set_menu']['current']['id'])); ?>')"><span class="glyphicon glyphicon-copy" style="color: green; font-weight: bold;"></span></button>
                                    </td>
                                    <td>
                                        <?php
                                            if($this->map['bar_set_menu']['current']['can_change_status']==1){
                                        ?>
                                        <button onclick="window.open('<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['bar_set_menu']['current']['id'])); ?>')"><span class="glyphicon glyphicon-pencil" style="color: blue; font-weight: bold;"></span></button>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                    <?php
                                        if($this->map['bar_set_menu']['current']['can_change_status']==1){
                                    ?>
                                        <button onclick="if(confirm('<?php echo Portal::language('Are_you_sure');?>?')) window.location='<?php echo Url::build_current(array('cmd'=>'list','delete_id'=>$this->map['bar_set_menu']['current']['id'])); ?>'"><span class="glyphicon glyphicon-remove" style="color: red; font-weight: bold;"></span></button>
                                    <?php        
                                        }
                                    ?>                                       
                                    </td>
                                </tr>
                            <?php }}unset($this->map['bar_set_menu']['current']);} ?>
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
                                for($i=1;$i<=$this->map['rows'];$i++){
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