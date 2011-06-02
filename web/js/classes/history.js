/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Web
 * @subpackage Javascript
 */

/**
 * Implements ui functionalities for the history screen.
 *
 * @see templates/wiki/history.php
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
