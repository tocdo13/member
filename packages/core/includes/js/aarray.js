var Class = {
  create: function() {
	return function() {
	  this.initialize.apply(this, arguments);
	}
  }
}
 
var AArray = Class.create();
 
AArray.prototype = {
  AA: {},
  initialize: function() {
	args = this.initialize.arguments;
	if(args.length > 0) {
	  this.add(args[0], args[1]);
	}
  },
  add: function(key, value) { this.AA[key] = value; },
  num: function() { return this.AA.length; },
  get: function(key) { return this.AA[key]; },
  set: function(key, value) { this.add(key, value); }/*,
  test: function() {
	var aa = '';
	var num = this.num();
	for(key in this.AA) aa += '[' + key + ']: ' + this.AA[key] + "\n";
	alert(aa);
  }*/
}