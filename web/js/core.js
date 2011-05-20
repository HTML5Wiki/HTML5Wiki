var pageReadyCallbacks = new Array();

function appendPageReadyCallback(callback, parameters) {
	var callbackData = '';
	
	if(jQuery.isFunction(callback)) {
		callbackData = {
			method: callback
		};
	} else {
		callbackData = {
			name: callback
			,parameters: parameters
		};
	}
	
	pageReadyCallbacks.push(callbackData);
}

function runPageReadyCallbacks() {
	for(var i = 0, l = pageReadyCallbacks.length; i < l; i++) {
		var callback = pageReadyCallbacks[i];
		
		if(callback.method != undefined) {
			// Direct methodcall:
			callback.method();
		} else {
			// Assemble methodcall with parameters:
			var name = callback.name;
			var parameters = '';
			var methodCall = '';

			if(callback.parameters != undefined && callback.parameters.length > 0) {
				for(j = 0, h = callback.parameters.length; j < h; j++) {
					parameters += '"' + callback.parameters[j] + '"';
					if(j < h-1) parameters += ',';
				}
			}

			methodCall = name + '(' + parameters + ');';

			eval(methodCall);
		}
	}
}

$(document).ready(function() {
	runPageReadyCallbacks();
});