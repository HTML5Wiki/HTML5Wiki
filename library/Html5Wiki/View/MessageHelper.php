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
 * in the UI.
 *
 * @see templates/layout.php
 */
class Html5Wiki_View_MessageHelper extends Html5Wiki_View_Helper {
	private static $messages = array();
	
	/**
	 * Appends an info message to the queue.
	 *
	 * @param $title
	 * @param $text
	 * @see Html5Wiki_View_MessageBoxHelper#appendMessageBox
	 */
	public function appendInfoMessage($title, $text) {
		$this->appendMessage('info', $title, $text);
	}
	
	/**
	 * Appends an error message to the queue.
	 *
	 * @param $title
	 * @param $text
	 * @see Html5Wiki_View_MessageBoxHelper#appendMessageBox
	 */
	public function appendErrorMessage($title, $text) {
		$this->appendMessage('error', $title, $text);
	}
	
	/**
	 * Appends an question message to the queue.
	 *
	 * @param $title
	 * @param $text
	 * @see Html5Wiki_View_MessageBoxHelper#appendMessageBox
	 */
	public function appendQuestionMessage($title, $text) {
		$this->appendMessage('question', $title, $text);
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
	
	private function appendMessage($type, $title, $text) {
		self::$messages[] = array(
			'type' => $type
			,'title' => $title
			,'text' => $text
		);
	}
	
}

?>
