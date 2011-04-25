Html5Wiki = {

	baseURL: '',

	init: function(baseURL) {
		this.baseURL = baseURL;
	},

	getUrl: function(path) {
		return window.location.protocol + '//' + window.location.host + Html5Wiki.baseURL + path;
	}

};