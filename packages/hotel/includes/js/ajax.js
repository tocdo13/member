/**********
Cac ham trong lop Ajax:
xml_node_to_object: function (node)
	Chuyen mot nut xml thanh mot object
	Gia su nut 
		<product>
			<title>a title</title>
			<price>10000</price>
		</product>
	chuyen thanh doi tuong
		{'title':'a title','price':10000}
xml_to_object: 	function (xmlDocument)
	Chuyen cac nut xml thanh mot mang cac object tuong tu nhu tren
getHTTPObject: 	function ()
	Lay doi tuong xmlHttpRequest
get_xml:function(url,handle)
	Lay du lieu dang xml tu duong dan url cho ham handle xu ly
get_text:function(url,handle)
	Lay du lieu dang text tu duong dan url cho ham handle xu ly
**********/

function Ajax()
{
	this.isWorking = false;
	this.http = this.getHTTPObject();
}
Ajax.prototype.xml_node_to_object = function(node)
{
	var anItem = {};
	for(var k=0;k<node.childNodes.length;k++)
	{
		if(node.childNodes(k).firstChild)
		{
			if(node.childNodes(k).firstChild.nodeType==1)
			{
				eval('anItem.'+node.childNodes(k).nodeName+' = this.xml_node_to_object(node.childNodes(k).firstChild);');
			}
			else
			{
				eval('anItem.'+node.childNodes(k).nodeName+' = node.childNodes(k).firstChild.data;');
			}
		}
	}
	return anItem;
}
Ajax.prototype.xml_to_object = function(xmlDocument)
{
	var items = {};
	var node = xmlDocument.firstChild;

	while(node)
	{
		var id=node.getElementsByTagName('id')(0).firstChild.data;
		eval('items.item_'+id+' = this.xml_node_to_object(node);');
		node = node.nextSibling;
	}
	return items;
}
Ajax.prototype.get_xml = function(url,handle) {
  if (!this.isWorking && this.http) {
    this.http.open("GET", url, true);
    this.http.onreadystatechange = function()
	{
		if (ajax.http.readyState == 4) {
			if (ajax.http.status == 200) {
				ajax.isWorking = false;
				var xmlDocument = ajax.http.responseXML.documentElement;
				handle(xmlDocument );
			}
		}
	}
    this.isWorking = true;
    this.http.send(null);
  }
}
Ajax.prototype.get_text = function(url,handle,onfail) 
{
	if(this.http)
	{
	  //if (!this.isWorking) {
		this.http.open("GET", url, true);
		this.http.onreadystatechange = function()
		{
			if (ajax.http.readyState == 4) 
            {
				
				if (ajax.http.status == 200) 
                {
					ajax.isWorking = false;
					if(handle)
					{
						handle(ajax.http.responseText);
					}
					return;
				}
				else
				{
					if(onfail!=null)
					{
						ajax.isWorking = false;
						onfail();
					}
				}
			}
		}
		this.isWorking = true;
		this.http.send(null);
	  //}
	}
	
}

Ajax.prototype.getHTTPObject= function() {
  var xmlhttp;
/*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
	  xmlhttp.overrideMimeType("text/xml"); 
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}
ajax = new Ajax();