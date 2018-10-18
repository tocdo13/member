jQuery.fn.keyboard_action = function(){
	var $write = jQuery('#text_keyboard'),
		shift = false,
		capslock = false;
		var obj = jQuery('#name_keyboard').val();
		jQuery('#keyboard_'+obj+' li').click(function(){
		var $this = jQuery(this),
			character = $this.html(); // If it's a lowercase letter, nothing happens to this variable
		
		// Shift keys
		if ($this.hasClass('left-shift') || $this.hasClass('right-shift')) {
			jQuery('.letter').toggleClass('uppercase');
			jQuery('.symbol span').toggle();
			shift = (shift === true) ? false : true;
			capslock = false;
			return false;
		}
		if ($this.hasClass('cancel')) {
			jQuery('#text_keyboard').val('');
			jQuery('#'+obj).val('');
			return false;
		}
		if ($this.hasClass('accept')) {
			//jQuery('#product_name').val(jQuery('#text_keyboard').val());
			return false;
		}
		// Caps lock
		if ($this.hasClass('capslock')) {
			jQuery('.letter').toggleClass('uppercase');
			capslock = true;
			return false;
		}
		
		// Delete
		if ($this.hasClass('delete')) {
			var html = jQuery('#text_keyboard').val();
			jQuery('#text_keyboard').val(html.substr(0, html.length - 1));
			jQuery('#'+obj).val(html.substr(0, html.length - 1));
			return false;
		}
		
		// Special characters
		if ($this.hasClass('symbol')) character = jQuery('span:visible', $this).html();
		if ($this.hasClass('space')) character = ' ';
		if ($this.hasClass('tab')) character = "\t";
		if ($this.hasClass('return')) character = "\n";
		
		// Uppercase letter
		if ($this.hasClass('uppercase')) character = character.toUpperCase();
		
		// Remove shift once a key is clicked.
		if (shift === true) {
			jQuery('.symbol span').toggle();
			if (capslock === false) jQuery('.letter').toggleClass('uppercase');
			
			shift = false;
		}
		
		// Add the character
		var text = jQuery('#text_keyboard').val() + character;
		jQuery('#text_keyboard').val(text);
		if(obj=='input_product_name'){
			jQuery('#'+obj).val(text);
			searchProduct('','');
		}
	});
}
