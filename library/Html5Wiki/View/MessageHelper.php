<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage View
 */

/**
 * Maintains a central point where messages can be added during processing of a
 * request.<br/>
 * The main template (template/layout.php) will gather and show them afterwards
 * in the UI using the MessageController.js.
 *
 * @see templates/layout.php
 * @see web/js/classes/messagecontroller.js
 */
class Html5Wiki_View_MessageHelper extends Html5Wiki_View_Helper {
	private static $messages = array();
	
	/**
	 * Appends an info message to the queue.
	 *
	 * @param $title
	 * @param $text
	 * @param $autohide should the message dissappear automaticaly in the UI?
	 * @see Html5Wiki_View_MessageBoxHelper#appendMessageBox
	 */
	public function appendInfoMessage($title, $text, $autohide=false) {
		$this->appendMessage('info', $title, $text, $autohide);
	}
	
	/**
	 * Appends an error message to the queue.
	 *
	 * @param $title
	 * @param $text
	 * @param $autohide should the message dissappear automaticaly in the UI?
	 * @see Html5Wiki_View_MessageBoxHelper#appendMessageBox
	 */
	public function appendErrorMessage($title, $text, $autohide=false) {
		$this->appendMessage('error', $title, $text, $autohide);
	}
	
	/**
	 * Appends an question message to the queue.
	 *
	 * @param $title
	 * @param $text
	 * @param $autohide should the message dissappear automaticaly in the UI?
	 * @see Html5Wiki_View_MessageBoxHelper#appendMessageBox
	 */
	public function appendQuestionMessage($title, $text, $autohide=false) {
		$this->appendMessage('question', $title, $text, $autohide);
	}
	
	/**
	 * After calling an #append*Message-method, use this method to an action to
	 * the last appended message.<br/>
	 * Actions will usually be displayed as a button or link in the frontend.<br/>
	 * You don't have to add an action if you dont want to do anything special.
	 * The UI's library will add a default-close-button to messages, which have
	 * no actions assigned (except if autohide was passed as true).<br/>
	 * Use $javascriptCallback to inject frontend javascript code for the click-event
	 * of your action.
	 *
	 * @param $text
	 * @param $showAsButton (optional) Show action as link or as button?
	 * @param $javascriptCallback (optional), if not passed, action closes message
	 */
	public function addButton($text, $showAsButton=true, $javascriptCallback=null) {
		if($this->hasMessages() === false) {
			throw new Html5Wiki_Exception_Template('append first a message before trying to add an action!');
		}
		
		$newButton = array(
			'text' => $text
			,'showAsButton' => $showAsButton
		);
		if($javascriptCallback !== null && strlen($javascriptCallback) > 0) {
			$newButton['action'] = $javascriptCallback;
		}
		
		$latestIndex = sizeof(self::$messages)-1;
		$latestMessage = self::$messages[$latestIndex];
		if(!isset($latestMessage['buttons'])) $latestMessage['buttons'] = array();
		$latestMessage['buttons'][] = $newButton;
		self::$messages[$latestIndex] = $latestMessage;
	}
	
	/**
	 * Checks if messageboxes are present.
	 *
	 * @return true/false
	 */
	public function hasMessages() {
		return (sizeof(self::$messages) > 0);
	}
	
	/**
	 * Returns all present messageboxes.
	 * 
	 * @return array
	 */
	public function getMessages() {
		return self::$messages;
	}
	
	/**
	 * Appends a new message.
	 *
	 * @param $type [info|error|question]
	 * @param $title
	 * @param $text
	 * @param $autohide should the message dissappear automaticaly in the UI?
	 */
	private function appendMessage($type, $title, $text, $autohide) {
		self::$messages[] = array(
			'type' => $type
			,'title' => $title
			,'text' => $text
			,'autohide' => $autohide
		);
	}
	
}

?>
