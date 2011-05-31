<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Library
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
