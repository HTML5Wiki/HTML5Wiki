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
		,resultContainer = ''
		,url = 'index/search';
	
	/**
	 * Initializes the Event-Handling for a Searchbox
	 * (Text-Inputfield)
	 *
	 * @param DOMElement searchBox jQuery-DOM-Object
	 * @param string     url       Url to call for doing search
	 * @access public
	 */
	self.initWithSearchBox = function(searchBox, url) {
		self.url = url;

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
	
	function search(term) {
		$.ajax({
			type: 'GET',
			url: self.url, 
			data: { 'term' : term },
			success: displaySearchResults.bind(this, term)
		});		
	}
	
	
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
			// Following line has to be replaced with an ajax request
			search(term);
		}
	};
	
	function displaySearchResults(term, json) {
		var results = json.results;
		
		// Save response data:
		resultItems = results;
		if(results) totalResultItems = results.length;
		else totalResultItems = 0;
		self.setSelectedResultItem(-1);

		// Show or hide Result-Container
		if(totalResultItems > 0) {
			resultContainer.show();
		} else {
			resultContainer.hide();
		}

		// Prepare result items:
		var resultList = $('<ol/>');
		for(var i = 0, l = totalResultItems; i < l; i++) {
			var title = results[i].title;
			var mediaType = results[i].mediaType;
			var matchOrigins = results[i].matchOrigins
			var url = results[i].url;
			
			resultList.append(createResultItem(title, mediaType, matchOrigins, url));
		}
		resultContainer.empty();
		resultContainer.append(resultList);
	}
	
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
						window.location.href = resultItems[selectedResultItem].url;
					} else {
						window.location.href = self.url + '?term=' + $(this).val();
					}
					break;
				case 27 : // Escape
					preventDefault = true;
					resultContainer.hide();
					self.setSelectedResultItem(-1);
					break;
					
			}
			
			if(preventDefault) {
				event.preventDefault();
			}
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
	 * Ensures that keys like CTRL or SHIFT don't trigger a search request.<br/>
	 * These are JavaScript Keycodes! No ascii codes :-)
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
	function createResultItem(title, mediaType, matchOrigins, url) {
		var matchOriginSlug = '';
		for(i=0,l=matchOrigins.length; i<l; i++) {
			matchOriginSlug += translateMatchOrigin(matchOrigins[i]);
			if(i<l-1) matchOriginSlug += ', ';
		}
		
		var item = $('<li class="result-item mediatype-'+mediaType+'"><a href="'+url+'"><p class="title">'+title+'</p><p class="matchorigins">'+ matchOriginSlug + '</p></a></li>');
		item.hover(function() {
			self.setSelectedResultItem($(this).index());
		});
		item.click(function() {
			window.location.href = url;
			e.preventDefault();
		});
		
		// Ensures that the a-Element reacts as expected when clicked:
		item.parent().click(function(event) { 
			event.stopPropagation(); 
		});
		
		return item;
	}
	
	/**
	 * Translates a match origin of a search result with the translation array.
	 * If theres no fitting entry in the array, the original origin is returned.
	 *
	 * @param $matchOrigion
	 * @return Translated origin
	 * @see SearchBoxController#matchOriginTranslations
	 * @access private
	 */
	function translateMatchOrigin(matchOrigin) {
		var translated = Translate._(matchOrigin);
		if(translated == undefined) translated = matchOrigin;
		
		return translated;
	}
	
	return self;
}());  // end SearchBoxController