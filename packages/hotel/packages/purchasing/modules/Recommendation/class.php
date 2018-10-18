<?php 
class Recommendation extends Module
{
    function Recommendation($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            switch (Url::get('cmd'))
            {
                case 'add':
                case 'edit':
                case 'copy':
                    require_once 'forms/edit.php';
					$this->add_form(new EditRecommendationForm());
    				break;
                case 'delete':
                    
                    $this->delete_recommend(Url::get('id'));
                    require_once 'forms/list.php';
					$this->add_form(new RecommendationForm());
                    break;
                case 'delete_group':
                    if(Url::get('item_check_box'))
                        $this->delete_group(Url::get('item_check_box'));
                    require_once 'forms/list.php';
                    $this->add_form(new RecommendationForm());
                    break;
                case 'view':
                {
                    require_once 'forms/view.php';
                    $this->add_form(new ViewRecommendationForm());
                    break;
                }
                case 'list_move':
                {
                    require_once 'forms/list_move.php';
                    $this->add_form(new ListMoveForm());
                    break;
                }
                case 'view_move':
                {
                    require_once 'forms/view_move.php';
                    $this->add_form(new ViewMoveRecommendationForm());
                    break;
                }
                case 'list_product_require':
                {
                    require_once 'forms/list_product_require.php';
                    $this->add_form(new ListProductRequireForm());
                    break;
                }
                default:
                    require_once 'forms/list.php';
                    $this->add_form(new RecommendationForm());
                    break;
            }
        }
        else
        {
            Url::access_denied();
        }
        
    }
    
    function delete_recommend($id)
    {
        DB::delete('pc_recommendation','id='.$id);
        DB::delete('pc_recommend_detail','recommend_id='.$id);
    }
    function delete_group($arr)
    {
        $str = "(";
        foreach($arr as $row)
        {
            $str .=$row.',';
        }
        $str = substr($str,0,strlen($str)-1);
        $str .=")";
        DB::delete('pc_recommendation','id in '.$str);
        DB::delete('pc_recommend_detail','recommend_id in '.$str);
    }	
}
?>