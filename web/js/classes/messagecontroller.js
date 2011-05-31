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
		,message_slideup_time = 'slow'
		,message_show_time = 6000;
	
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
			var box = createBox(message);
			if(box != null) {
				box.hide();
				container.append(box);
				
				// Autohide?
				var hideCallback = undefined;
				if(message.autohide != undefined && message.autohide == true) {
					hideCallback = function() {
						$(this).delay(message_show_time).queue(function() {
							hideBox(this);
							$(this).dequeue();
						});
					};
				}
				
				// Show:
				box.slideDown(message_slidedown_time, hideCallback);
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
			
			var buttons = createButtons(message);
			if(buttons != null) box.append(buttons);
		}
		
		return box;
	}
	
	/**
	 * Creates buttons for a message.<br/>
	 * If no buttons are present and the messages autohide-poperty is false, null
	 * is returned. Otherwise a default close button is added.
	 *
	 * @return DOM element or null
	 * @access private
	 */
	function createButtons(message) {
		var container = null;
		
		// If no buttons present, add at least a close-button
		if(!message.buttons && message.autohide == false) {
			message.buttons = [{
				text: 'Schliessen'
				,showAsButton: true
			}];
		} else {
			message.buttons = [];
		}
		
		if(message.buttons.length > 0) {
			container = $('<div class="options" />');
			for(i = 0, l = message.buttons.length; i<l; i++) {
				var data = message.buttons[i];
				var button = createButton(data);
				if(button != null) container.append(button);
			}
		}
		
		return container;
	}
	
	/**
	 * Creates a button from the information stored in data, a property list.
	 *
	 * @param data
	 * @see MessageController#createButtons
	 * @access private
	 */
	function createButton(data) {
		var button = null;
		
		if(data.text != undefined) {
			button = $('<a href="#">' + data.text + '</a>');

			if(data.showAsButton != undefined && data.showAsButton == true) {
				button.addClass('button');
			} else {
				button.addClass('option');
			}
			
			button.bind('click', { action: data.action }, function(event) {
				hideBox($(this).parents('.box'), event.data.action);
			});
		}
		
		return button;
	}
	
	/**
	 * Hides a Box and calls, if present, a callback afterwards.
	 *
	 * @param box DOM Element
	 * @param Callback or undefined
	 * @access private
	 */
	function hideBox(box, callback) {
		$(box).slideUp(message_slideup_time, function() {
			$(this).remove();
			if($('.box').length == 0) $('.messages-container').remove();
			if(callback != undefined) callback();
		});
	}
	
	return self;
	
}());  // end MessageController