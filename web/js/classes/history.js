/**
 * Implements ui functionalities for the history screen.
 *
 * @see templates/wiki/history.php
 * @author Manuel Alabor
 */
var History = (function() {
	var self = {};
	
	/**
	 * Calls History#updateVersionSelectors and binds the change-event of the
	 * version radio buttons to the History#updateVersionSelectors method.
	 *
	 * @see #updateVersionSelectors
	 * @access public
	 */
	self.init = function() {
		self.updateVersionSelectors();
		$(':radio[name=left|right][type=radio]').change(self.updateVersionSelectors);
	}
	
	/**
	 * This method ensures that only possible combinations of version radio
	 * boxes are selected.
	 *
	 * @access public
	 */
	self.updateVersionSelectors = function() {
		var leftSelectors = $(':radio[name=left]');
		var rightSelectors = $(':radio[name=right]');
		var leftSelected = $(':radio:checked[name=left]');
		var rightSelected = $(':radio:checked[name=right]');
		var leftSelectedTimestamp = leftSelected.attr('value');
		var rightSelectedTimestamp = rightSelected.attr('value');

		enableRadioButtonsRegardingTimestamp(
			rightSelectedTimestamp
			,leftSelectors
			,function(a,b) { return (a>=b); }
		);
		enableRadioButtonsRegardingTimestamp(
			leftSelectedTimestamp
			,rightSelectors
			,function(a,b) { return (a<=b); }
		);
	}
	
	/**
	 * Selects or deselects a radio button.
	 * 
	 * @param radioButton
	 * @param selected select or deselect? (true/false)
	 * @access private
	 */
	function selectRadioButton(radioButton, selected) {
		if(selected) radioButton.attr('checked','checked');
		else radioButton.removeAttr('checked');
	}
	
	/**
	 * Enables or desables a form element.
	 * 
	 * @param element
	 * @param enabled (true/false)
	 * @access private
	 */
	function enableFormElement(element, enabled) {
		if(enabled) element.removeAttr('disabled');
		else element.attr('disabled','disabled');
	}
	
	/**
	 * Compares the timestamp (contained in the value attribute) of an array of
	 * radio buttons against a passed timestamp. If the used comperator callback
	 * returns true, the specific radio button gets disabled, otherwise enabled.<br/>
	 * <br/>
	 * Example for a comperator callback: function(a,b) { return (a<=b); }
	 *
	 * @param timestamp to compare with
	 * @param radioButtons array
	 * @param comperator function callback with two parameters
	 * @see History#updateVersionSelectors
	 * @access private
	 */
	function enableRadioButtonsRegardingTimestamp(timestamp,radioButtons,comperator) {
		radioButtons.each(function(i,item) {
			item = $(item);
			
			if(comperator(item.attr('value'), timestamp)) {
				enableFormElement(item, false)
			} else {
				enableFormElement(item, true)
			}
		});
	}

	return self;
	
}());  // end History
