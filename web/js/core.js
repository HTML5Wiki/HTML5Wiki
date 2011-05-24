/**
 * This is the core of the javascript loading engine of HTML5Wiki.<br/>
 * It enables the application to add multiple actions to the $(document).ready
 * event without overriding the handler itself.<br/>
 * <br/>
 * Just call appendPageReadyCallback() to add a new action to the
 * pageready-event.<br/>
 * <br/>
 * Please notice: These methods are not encapsulted because for ease of use.
 *
 * @author Manuel Alabor
 */
var pageReadyCallbacks = new Array();

/**
 * Adds a new action to the $(document).ready()-event.<br/>
 * You can pass a method-reference directly to the callback-parameter. In this
 * case, parameters will be ignored.<br/>
 * <br/>
 * Example: <code>appendPageReadyCallback(myCallbackFunction);</code><br/>
 * <br/>
 * If you want to add a method-call with specific parameters, pass the full
 * qualified method name as string and pass an array with your parameters.<br/>
 * <br/>
 * Example: <code>appendPageReadyCallback("MyObject.init",["myParam"]);<br/>
 *
 * @param callback string or function
 * @param array with parameters; optional
 */
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

/**
 * Executes all actions which where added before.
 *
 * @see #appendPageReadyCallback(callback,parameters)
 */
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

/**
 * The one and only $(document).ready()-handler.<br/>
 * It just calls the #runPageReadyCallbacks() method as soon the page/DOM is
 * fully loaded.
 *
 * @see #runPageReadyCallbacks()
 */
$(document).ready(function() {
	runPageReadyCallbacks();
});