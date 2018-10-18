current_menu = false;
function ContextMenu(xname,xclass,xtarget)
{
	this._name=xname;
	this._class=xclass;
	this._target=xtarget;
}
ContextMenu.prototype.create = function()
{
	echo ('<div style="z-index:100;padding:2px;width:180px;background-color:#FFFFFF;border:1px solid #999999;position:absolute;display:none;" id="'+this._name+'" >&nbsp;</div>');
}
ContextMenu.prototype.clear = function()
{
	$(this._name).innerHTML = '';
}
ContextMenu.prototype.add = function(name, url)
{
	$(this._name).innerHTML += '<a '+(this._target?'target="'+this._target+'" ':'')+'href="'+url+'" class="'+this._class+'" style="white-space:nowrap" onmouseup="current_menu.hide();">'+name+'</a>';
}
ContextMenu.prototype.show = function()
{
	current_menu = this;
	switch_display_layer_at_mouse_position(this._name,0,0);
}
ContextMenu.prototype.hide = function()
{
	current_menu = false;
	$(this._name).style.display='none';
}