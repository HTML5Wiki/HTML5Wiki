/**
 * Handles the creation, management and control of messages for the
 * GUI in one central place.
 *
 * @see MessageController#addMessage(type,message,options)
 * @author Manuel Alabor
 */
var MessageController = (function() {
	var self = {}
		,messages = new Array()
		,message_slidedown_time = 'fast'
		,message_slideup_time = 'fast'
		,message_show_time = 2000;
	
	/**
	 * Adds an array if messages to the internal messages-array
	 *
	 * @param messages
	 * @access public
	 */
	self.addMessages = function(newMessages) {
		messages = messages.concat(newMessages);
		self.displayMessages();
	}
	
	/**
	 * Adds a new message.
	 *
	 * @param newMessage
	 * @access public
	 */
	self.addMessage = function(newMessage) {
		messages = messages.concat([newMessage]);
	}
	
	/**
	 * Displays queued messages.
	 *
	 * @see MessageController#addMessage
	 * @see MessageController#addMessages
	 * @access public
	 */
	self.displayMessages = function() {
		var container = $('.messages-container-main');
		if(container.length == 0) {
			container = $('<div class="grid_12 messages-container messages-container-main" />');
			$('.messagemarker').after(container);
			container.after($('<div class="clearfix messages-container" />'));
		}
		
		messages = messages.reverse();
		var message = null;
		while((message = messages.pop()) != null) {
			var box = createBox(message).hide();
			if(box != null) {
				container.append(box);
				box.slideDown(message_slidedown_time);
			}
		}
		
	}
	
	/**
     * Creates a messagebox DOM element with given parameters.
     *
     * @param messageData Dictionary with message data
	 * @see MessageController#addMessage(type,text,options)
	 * @return null or DOM element
	 * @access private
     */
	function createBox(message) {
		var box = null;
		
		if(message.type != undefined && message.text != undefined) {
	        box = $('<div class="box ' + message.type + '" />');
			box.append('<h2>' + message.title + '</h2>');
			box.append(message.text);
			box.append(createButtons(message))
		}
		
		return box;
	}
	
	/**
	 *
	 * @return DOM element
	 * @access private
	 */
	function createButtons(message) {
		var container = $('<div class="options" />');
		
		// If no buttons present, add at least a close-button
		if(!message.buttons) {
			message.buttons = [{
				text: 'Schliessen'
				,action: function() { console.log('ok'); }
			}];
		}
		
		for(i = 0, l = message.buttons.length; i<l; i++) {
			var data = message.buttons[i];
			var button = $('<a href="#" class="button">' + data.text + '</a>');
			button.bind('click', { action: data.action }, function(event) {
				$(this).parents('.box').slideUp(message_slideup_time, function() {
					$(this).remove();
					if($('.box').length == 0) $('.messages-container').remove();
					
					if(event.data.action != undefined) event.data.action();
				});
			});
			
			container.append(button);
		}
		
		return container;
		
		/*
    	for(var i = 0, l = buttonsData.length; i < l; i++) {
			var text = buttonsData[i]['text'];
    		var showAsButton = buttonsData[i]['button'];
			var callback = buttonsData[i]['callback'];
			
			// The option:
    		var klass = ' class="option"';
			if(showAsButton) klass = ' class="button"';
    		var option = $('<a href="#"' + klass + '>' + text + '</a>');
    		
			// Eventbinding:
			// A click on an option makes the messagebox disappear in a first step.
			// After that, the callback of the option is called.
    		$(option).bind('click', { callback : callback }, function(event) {
    			$(this).parents('.messagebox').slideUp(message_slideup_time, function() {
					$(this).remove();
					
					var overlay = $('.overlay');
					if(overlay.length > 0) {
						overlay.fadeOut(overlay_fadeout_time, function() {
	        				$(this).remove();
	        				if(event.data.callback != undefined) event.data.callback();
	        				self.displayQueuedMessage();
						});						
					} else {
        				if(event.data.callback != undefined) event.data.callback();
        				self.displayQueuedMessage();
					}
    			});
    		});
    		
    		container.append(option);
    	}
		
		return container;*/
	}
	
	return self;
}());  // end MessageController