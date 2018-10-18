<script src="packages/core/includes/js/multi_items.js"></script>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('synchronize_product'));?>
<div align="center">
<form name="SynProductForm" method="post">
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr height="40">
                    <td class="form-title" width="60%">[[.synchronize_product.]]</td>
					<td width="5%" align="right"><input name="save" type="submit" value="[[.save.]]" class="button-medium-save"></td>
					<td width="5%" align="right"><a href="<?php echo URL::build_current();?>"  class="button-medium-back">[[.back.]]</a></td>
                </tr>
            </table>
		</td>
	</tr>
	<tr>
	<td>
        <fieldset>
        <legend class="title">[[.product_list.]]</legend>
        <table border="0" cellpadding="3" cellspacing="0">
            <tr>
                <td align="right" nowrap style="font-weight:bold">[[.function.]]</td>
                <td>:</td>
                <td nowrap>
                    <select name="function" id="function" style="width:150px;" onchange="window.location='<?php echo Url::build_current(array('cmd','function'));?>&function='+this.value"></select>
                </td>                
                <td colspan="3">
					<label>[[.category.]]</label><select name="category_id" id="category_id" onchange="window.location='<?php echo Url::build_current(array('cmd','hotel_id','function'));?>&category_id='+this.value"></select>
                </td>
            </tr>
         </table>
        <table cellpadding="3" cellspacing="0">
            <tr>
                <td class="title">[[.product_list.]]</td>
                <td></td>
                <td class="title">[[.product_in_function.]]</td> 
            </tr>
            <tr>
                <td valign="top"><select name="product_id[]" id="product_id" multiple="multiple" style="width:350px;height:350px"></select></td>
                <td><input type="button" onclick="moveOptions($('product_id'), $('product_syn'));" value="   >>   " /><br /><input type="button" onclick="moveOptions($('product_syn'), $('product_id'));" value="   <<   " /></td>
                <td valign="top"><select name="product_syn[]" id="product_syn" multiple="multiple" style="width:350px;height:350px"></select></td>
            </tr>
        </table>
        </fieldset>
	</td>
</tr>
</table>
</form>
<script language="JavaScript" type="text/javascript">
<!--

var NS4 = (navigator.appName == "Netscape" && parseInt(navigator.appVersion) < 5);

function addOption(theSel, theText, theValue)
{
  var newOpt = new Option(theText, theValue);
  var selLength = theSel.length;
  theSel.options[selLength] = newOpt;
}

function deleteOption(theSel, theIndex)
{ 
  var selLength = theSel.length;
  if(selLength>0)
  {
    theSel.options[theIndex] = null;
  }
}

function moveOptions(theSelFrom, theSelTo)
{
  
  var selLength = theSelFrom.length;
  var selectedText = new Array();
  var selectedValues = new Array();
  var selectedCount = 0;
  
  var i;
  
  // Find the selected Options in reverse order
  // and delete them from the 'from' Select.
  for(i=selLength-1; i>=0; i--)
  {
    if(theSelFrom.options[i].selected)
    {
      selectedText[selectedCount] = theSelFrom.options[i].text;
      selectedValues[selectedCount] = theSelFrom.options[i].value;
      deleteOption(theSelFrom, i);
      selectedCount++;
    }
  }
  
  // Add the selected text/values in reverse order.
  // This will add the Options to the 'to' Select
  // in the same order as they were in the 'from' Select.
  for(i=selectedCount-1; i>=0; i--)
  {
    addOption(theSelTo, selectedText[i], selectedValues[i]);
  }
  if(theSelTo.id=='product_syn')
  {
  	update_value(selectedValues,$('function').value,'update');
  }
  else
  {
	update_value(selectedValues,$('function').value,'delete_product');
  }
  if(NS4) history.go(0);
}
function update_value(arr,func,cmd)
{
	str = arr.toString();
	jQuery.ajax({
		  url: "form.php?block_id=<?php echo Module::$current->data['id'];?>",
		  type: "POST",
		  data: ({id : str,func:func,cmd:cmd}),
		  dataType: "html",
		  beforeSend: function(){
		  	jQuery('#loading-layer').fadeIn(100);
		  },
		  success: function(msg){
			 //jQuery('#printer').html(msg);
			 //$('printer').innerHTML = msg;
		  },
		  complete:function(){
		  	jQuery('#loading-layer').fadeOut(100);
		  }
	})	
}
//-->
</script>
