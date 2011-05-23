/**
 * Handles the creation, management and control of messages for the
 * GUI in one central place.
 *
 * @see MessageController#addMessage(type,message,options)
 * @author Manuel Alabor
 */
var MessageController = (function() {
	var self = {}
		,messageQueue = new Array()
		,message_slidedown_time = 'fast'
		,message_slideup_time = 'fast'
		,overlay_fadein_time = 300
		,overlay_fadeout_time = 200
		,message_show_time = 2000;
	
	/**
	 * Adds a Message with given parameters to the queue and ensures
	 * that the oldest queued message gets displayed.<br/>
	 * <br/>
	 * The parameter <code>options</code> enables you to display buttons
	 * together with your message.<br/>
	 * The following snippet shows how you can populate an array with
	 * the necessary information:
	 * <pre>
	 *     var options = [
	 *         {
	 *             text: 'OK'
	 *             ,button: true
	 *             ,callback: function() { console.log('OK clicked'); }
	 *         },{
	 *             text: 'Cancel'
	 *             ,callback: function() { console.log('Cancel clicked'); }
	 *         }
	 *     ];
	 * </pre>
	 *
	 * Along with the passed message type and text, the user will see a button
	 * labled "OK" and a link "Cancel".<br/>
	 * As soon as the user clicks on one of them, the specific callback method
	 * will be called.
	 *
	 * @param type		[info, error, question]
	 * @param message	Messagetext
	 * @param options	An optional array with dictionary-items for each available option
	 * @access public
	 */
	self.addMessage = function(type, message, options) {
		var messageData = {
			type: type,
			message: message,
			options: options
		};
		messageQueue = new Array(messageData).concat(messageQueue);
		if($('.messagebox').size() == 0) self.displayQueuedMessage();
	}

	/**
	 * Checks the message queue for enqueued messages.<br/>
	 * If a message is available, it will be displayed.<br/>
	 * If more than one message is available, the oldest one gets displayed.
	 *
	 * @access public
	 */
	self.displayQueuedMessage = function() {
		var messageData = messageQueue.pop();
	
		if(messageData != undefined) {
			var messageBox = createMessageBox(messageData);
			var modal = false;
			$('.header-overall').after(messageBox);
			
			if(messageData.options != undefined) {
				if(messageData.options.modal != undefined) modal = messageData.options.modal;
			}
			
			if(modal) {
				// Show modal with overlay:
				var overlay = createOverlay();
				$('body').append(overlay);
				overlay.fadeIn(overlay_fadein_time, function() {
					$(messageBox).slideDown(message_slidedown_time);
				});
			} else {
				if(messageData.options != undefined && messageData.options.buttons != undefined && messageData.options.buttons.length > 0) {
					// If buttons are available, only slide down the message:
					$(messageBox).slideDown(message_slidedown_time);
				} else {
					// If no buttons are there, slide down, wait and slide up after:
					$(messageBox).slideDown(message_slidedown_time)
					.delay(message_show_time)
					.slideUp(message_slideup_time,function() {
						$(this).remove();
						self.displayQueuedMessage();
					});
				}
			}
			
		}
	}

	/**
     * Creates a messagebox DOM element with given parameters.
     *
     * @param messageData Dictionary with message data
	 * @see MessageController#addMessage(type,message,options)
	 * @access private
     */
	function createMessageBox(messageData) {
        var messagebox = $('<div class="messagebox" />');
        var content = $('<div class="message ' + messageData.type + '">' + messageData.message + '</div>');
        messagebox.append('<div class="barshadow">&nbsp;</div>');

        if(messageData.options != undefined
			&& messageData.options.buttons != undefined
			&& messageData.options.buttons.length > 0) {
			content.append(createButtons(messageData.options.buttons));
		}

        messagebox.append(content);
        messagebox.hide();
        
        return messagebox;
	}
	
	/**
	 * Creates clickable buttons and links from a buttons Information
	 * Array.
	 *
	 * @param buttonsData Array with information about the options
	 * @see MessageController#addMessage
	 * @see MessageController#createMessageBox
	 * @access private
	 */
	function createButtons(buttonsData) {
		var container = $('<div class="options" />');
		
    	for(var i = 0, l = buttonsData.length; i < l; i++) {
			var text = buttonsData[i]['text'];
    		var showAsButton = buttonsData[i]['button'];
			var callback = buttonsData[i]['callback'];
			
			// The option:
    		var class = ' class="option"';
			if(showAsButton) class = ' class="button"';
    		var option = $('<a href="#"' + class + '>' + text + '</a>');
    		
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
		
		return container;
	}
	
	/**
	 * Creates an overlay and returns it as jQuery DOM-Element.<br/>
	 * The overlay is used to mask the rest of the UI if a message has
	 * options to select (= modal dialog actually).
	 *
	 * @access private
	 */
	function createOverlay() {
		var overlay = $('<div class="overlay" />');
		overlay.hide();
		return overlay;
	}
	
	return self;
}());  // end MessageController