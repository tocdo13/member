if(typeof(tinyMCE)=='undefined')
{
	/*echo('<script src="packages/core/includes/js/tinymce_3_2_3/jscripts/tiny_mce/tiny_mce.js"/></script>');
	*/
	function init_rich_editor(id_list)
	{
		tinyMCE.init({
			mode : "exact",				 
			theme : "advanced",
			elements : id_list,
			display_tab_class : "showTab",
			content_css : "packages/core/templates/default/css/editor.css",
				plugins : "style,layer,table,iespell,inlinepopups,media,print,contextmenu,paste,directionality,fullscreen,noneditable",
			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true
	
		});
	}
	function init_simple_rich_editor(id_list,lang)
	{
		 tinyMCE.init({
			mode : "exact",
			elements : id_list,
			theme : "advanced",
			language :lang,
			
			skin : "o2k7",
			format:'html',		
			theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,link,unlink,fontselect,fontsizeselect,|,forecolor,backcolor,|,pasteword,removeformat",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : "",      
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			//theme_advanced_path_location : "bottom",
			theme_advanced_font_sizes : "1,2,3,4,5,6",
			entity_encoding : "raw",
			plugins : "paste,inlinepopups",
			//plugins : "paste,inlinepopups,bbcode",
			paste_create_paragraphs : false,
			paste_create_linebreaks : false,
			paste_use_dialog : true,
			paste_convert_middot_lists : true,
			paste_unindented_list_class : "unindentedList",
			paste_convert_headers_to_strong : true,
			paste_insert_word_content_callback : "convertWord"
	
		});		
	}
	function convertWord(type, content) {
		switch (type) {
			// Gets executed before the built in logic performes it's cleanups
			case "before":
				//content = content.toLowerCase(); // Some dummy logic
				break;
	
			// Gets executed after the built in logic performes it's cleanups
			case "after":
				//content = content.toLowerCase(); // Some dummy logic
				break;
		}
	
		return content;
	}
}