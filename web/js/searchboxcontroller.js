/**
 * Controller for a Searchbox-Field.<br/>
 * To use, wrap an input-element (suggest to use one with the type "text")
 * into a parent DOM element. Afterwards call SearchBoxController#initWithSearchBox
 * and pass the JQuery representation of the input element as argument.<br/>
 * SearchBoxController will then create all necessary event bindings and creates a
 * DOM-object for keeping the result list in the searchbox' parent.
 *
 * @author Manuel Alabor
 * @see SearchBoxController#initWithSearchBox
 */
var SearchBoxController = (function() {
	var self = {}
		,selectedResultItem = -1
		,totalResultItems = 0
		,resultItems = ''
		,resultContainer = '';
	
	/**
	 * Initializes the Event-Handling for a Searchbox
	 * (Text-Inputfield)
	 *
	 * @param searchBox jQuery-DOM-Object
	 * @access public
	 */
	self.initWithSearchBox = function(searchBox) {
		/* Eventbindings: */
		// Bind search-functionalities:
		$(searchBox).bind('keyup',handleKeyUp);
		$(searchBox).bind('keydown',handleKeyDown);
		
		// Bind functionalities to hide resultlist on focus-lose:
		$('body').bind('click',handleBodyClick);
		
		
		/* Resultcontainer: */
		// This container is created in the parent of the searchbox.
		// It will contain an ordered list with matched searchresults
		// later on.
		resultContainer = $('<div id="header-searchbox-results" class="searchbox-results" />');
		resultContainer.hide();
		$(searchBox).parent().append(resultContainer);
	};
	
	/**
	 * Returns the currently selected Resultitem inside the
	 * Resultlist
	 *
	 * @returns Index
	 * @access public
	 */
	self.getSelectedResultItem = function() {
		return self.selectedResultItem;
	};
	
	/**
	 * Selects a specific item in the current resultlist according
	 * to its index.
	 *
	 * @param Index
	 * @access public
	 */
	self.setSelectedResultItem = function(index) {
		if(selectedResultItem != index) {
			$('.result-item.selected',resultContainer).removeClass('selected');
			if(index > -1) $($('.result-item',resultContainer)[index]).addClass('selected');
			selectedResultItem = index;
		}
	};
	
	/**
	 * Selects, if possible, the next result item in the result list.
	 *
	 * @returns true if selection was possible, false if not
	 * @access public
	 */
	self.selectNextResultItem = function() {
		var wasAbleToSelect = false;
		var newIndex = selectedResultItem+1;
		
		if(newIndex < totalResultItems) {
			self.setSelectedResultItem(newIndex);
			wasAbleToSelect = true;
		}
		
		return wasAbleToSelect;
	};
	
	/**
	 * Selects, if possible, the previous result item in the result list.
	 *
	 * @returns true if selection was possible, false if not
	 * @access public
	 */
	self.selectPreviousResultItem = function() {
		var wasAbleToSelect = false;
		
	 	if(selectedResultItem > 0) {
			self.setSelectedResultItem(selectedResultItem - 1);
			wasAbleToSelect = true;
		}
		
		return wasAbleToSelect;
	};
	
	
	/**
	 * Handles KeyEvents for the keyUp-Event.<br/>
	 * Search requests are sent here.
	 *
	 * @param event
	 * @access private
	 * @see SearchBoxController#handleKeyDown for UI control
	 */
	function handleKeyUp(event) {
		var keycode = event.which;
		var term = $(this).val();

		if(keycodeTriggersSearch(keycode)) {
			
			
			// @todo AJAX request
			// Following line has to be replaced with an ajax request
			var results = search(term);
			
			
			// Save response data:
			resultItems = results;
			totalResultItems = results.length;
			self.setSelectedResultItem(-1);
			
			// Show or hide Result-Container
			if(totalResultItems > 0) resultContainer.show();
			else resultContainer.hide();
			
			// Prepare result items:
			var resultList = $('<ol/>');
			for(var i = 0, l = totalResultItems; i < l; i++) {
				var text = results[i].text;
				var url = results[i].url;
				
				text = '<span class="typed">'
					   + text.substring(0,term.length)
					   + '</span>'
					   + text.substring(term.length);
				
				resultList.append(createResultItem(text,url));
			}
			resultContainer.empty();
			resultContainer.append(resultList);
		}
	};
	
	/**
	 * Handles KeyEvents for the keyDown-Event.<br/>
	 * If the user wants to select a specific result item, this
	 * action is handled here.
	 *
	 * @param event
	 * @see SearchBoxController#handleKeyUp for search requests
	 * @access private
	 */
	function handleKeyDown(event) {
		var keycode = event.which;

		if(!keycodeTriggersSearch(keycode)) {
			var preventDefault = false;

			switch(keycode) {
				case 9 : // Tab
					hideResultList();
					break;
					
				case 40 : // Down
					preventDefault = true;
					self.selectNextResultItem();
					break;
				case 38 : // Up
					preventDefault = true;
					self.selectPreviousResultItem();
					break;
				case 13 : // Enter
					// If an item is selected, open that item;
					// Otherwise, trigger search for the entered term.
					if(selectedResultItem > -1) {
						
						
						// @todo Open selected item
						console.log('Open selected result item!');
						window.href = resultItems[selectedResultItem].url;
						
						
					} else {
						
						
						// @todo Trigger search here
						console.log('Trigger search here!')
						
						
					}
					break;
				case 27 : // Escape
					preventDefault = true;
					resultContainer.hide();
					self.setSelectedResultItem(-1);
					break;
					
			}
			
			if(preventDefault) event.preventDefault();
		}
	};
	
	/**
	 * Eventhandler for the <body>'s-Clickevent.<br/>
	 * Hides the resultlist (of possible).
	 *
	 * @see SearchBoxController#hideResultList
	 * @access private
	 */
	function handleBodyClick(event) {
		if(resultContainer.is(':visible')) {
			hideResultList();
		}
	}
	
	/**
	 * Resets the selected Resultitem and hides the resultlist itselfs.
	 *
	 * @access private
	 */
	function hideResultList() {
		self.setSelectedResultItem(-1);
		resultContainer.hide();
	}
	
	/**
	 * Ensures that keys like CTRL or SHIFT don't trigger a search request.
	 *
	 * @param keycode
	 * @returns true/false
	 * @access private
	 */
	function keycodeTriggersSearch(keycode) {
		return (keycode >= 48
				|| keycode == 46
				|| keycode == 8
				|| keycode == 32
				|| keycode == 0);
	}
	
	/**
	 * Creates a list item for a searchresult with a specific text and url.<br/>
	 * There is a hover-eventbinding added by default which will selects the
	 * hovered result item.
	 *
	 * @param text Items text
	 * @param url Items url
	 * @returns DOM-Objekt
	 * @see SearchBoxController#handleKeyUp
	 * @access private
	 */
	function createResultItem(text, url) {
		var item = $('<li class="result-item"><a href="'+url+'">'+text+'</a></li>');
		item.hover(function() {
			self.setSelectedResultItem($(this).index());
		});
		
		// Ensures that the a-Element reacts as expected when clicked:
		item.parent().click(function(event) { event.stopPropagation(); })
		
		return item;
	}
	
	return self;
}());  // end SearchBoxController