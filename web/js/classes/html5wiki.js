/**
 * Encapsulates main Html5Wiki javscript functions.
 *
 * @author Manuel Alabor
 */
var Html5Wiki = (function() {
	var self = {}
		,baseURL = '';
	
	self.init = function(baseURL) {
		self.baseURL = baseURL;
	}
	
	self.getUrl = function(path) {
		return window.location.protocol + '//' + window.location.host + Html5Wiki.baseURL + path;
	}
	
	return self;
	
}());  // end Html5Wiki