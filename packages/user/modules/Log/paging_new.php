<?php
    //*****************************************
    //COPYRIGHT  BY NGUYEN VAN MANH TCVSOFTVARE
    //CODE: 28/07/2012. PAGING NO 1.0
    // TÙY BIẾN: CUNG CẤP CHO NGƯỜI DÙNG HAI TÙY BIẾN GIAO DIỆN: CLASS.paging_new CHO LINK CHƯA XEM VÀ CLASS.page_active CHO LINK ĐANG XEM.
    // THAM SỐ: 
    //         $sum_items = Tổng số bản ghi. 
    //         $line_per_page = số dòng trên một trang.  
    //         $page_name = Tên của page.
    //         $arr_link = mảng chứa các tham số trên link.
    //         $page_no = số của trang đang được xem. 
    //*****************************************
    function paging_new($sum_items,$line_per_page,$page_name,$arr_link,$page_no){
        $total_page = ceil($sum_items/$line_per_page);
        //echo $total_page;
        if($total_page<2){
            return;
        }else{
            $content_link = $page_name;
            if(sizeof($arr_link)!=0){
                foreach($arr_link as $link=>$value){
                    $content_link .= '&'.$link.'='.$value;
                }
            }
            if(!$page_no){
                $page_no = 1;
            }
            $totalpageshow = 10;
            $show_page = '<span id="title_page">Trang:</span>';
            // đầu trang
            if($page_no!=1){
                $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no=1">Trang đầu tiên</a>';
                $page_prev = $page_no-1;
                $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no='.$page_prev.'">Trang trước</a>';
            }
            // thân phân trang
            if($total_page<=$totalpageshow){// số trang nhỏ hơn 10 - list tất cả các trang ra
                for($i=1;$i<=$total_page;$i++){
                    if($page_no==$i){
                        $show_page .= '<span class="page_active">'.$i.'</span>';
                    }else{
                        $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no='.$i.'">'.$i.'</a>';
                    }
                }
            }else{//
                if(($page_no>=5) AND ($page_no<($total_page-5))){
                    $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no=1">1</a>';
                    $show_page .= '<span class="paging_new">...</span>';
                    for($i=$page_no-4;$i<=$page_no+5;$i++){
                        if(($i>=1) AND ($i<=$total_page)){
                            if($page_no==$i){
                                $show_page .= '<span class="page_active">'.$i.'</span>';
                            }else{
                                $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no='.$i.'">'.$i.'</a>';
                            }
                        }
                    }
                    $show_page .= '<span class="paging_new">...</span>';
                    $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no='.$total_page.'">'.$total_page.'</a>';
                }elseif($page_no<5){
                   for($i=$page_no-4;$i<=$page_no+5;$i++){
                        if(($i>=1) AND ($i<=$total_page)){
                            if($page_no==$i){
                                $show_page .= '<span class="page_active">'.$i.'</span>';
                            }else{
                                $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no='.$i.'">'.$i.'</a>';
                            }
                        }
                    }
                    $show_page .= '<span class="paging_new">...</span>'; 
                    $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no='.$total_page.'">'.$total_page.'</a>';
                }else{
                    $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no=1">1</a>';
                    $show_page .= '<span class="paging_new">...</span>';
                    for($i=$page_no-4;$i<=$page_no+5;$i++){
                        if(($i>=1) AND ($i<=$total_page)){
                            if($page_no==$i){
                                $show_page .= '<span class="page_active">'.$i.'</span>';
                            }else{
                                $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no='.$i.'">'.$i.'</a>';
                            }
                        }
                    }
                }
            }
            //cuối trang
            if($page_no!=$total_page){
                $page_next = $page_no+1;
                $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no='.$page_next.'">Trang sau</a>';
                $show_page .= '<a class="paging_new" href="?page='.$content_link.'&page_no='.$total_page.'">Trang cuối cùng</a>';
                
            }
            //echo $show_page;
            return $show_page;
        }
        //echo $show_page;
    }
?>
<style>
    span#title_page{
        font-weight: bold;
        padding: 3px 5px;
        margin: 2px;
        background: #00b9f2;
        color: #ffffff;
        text-transform: uppercase;
    }
</style>
<style>
    a{
        text-decoration: none;
    }
    a:hover{
         text-decoration: none;
    }
    .paging_new{
        color: #171717;
        padding: 3px 5px;
        margin: 2px;
        text-decoration: none;
        background: #ffffff;
        box-shadow: none;
        transition: all .5s ease-out;
        opacity: 0.7;
    }
    .paging_new:hover{
        background: #eeeeee;
        opacity: 1;
        box-shadow: 0px 5px 3px #171717;
    }
    .page_active{
        color: #000000;
        font-weight: bold;
        padding: 3px 5px;
        margin: 2px;
    }
</style>