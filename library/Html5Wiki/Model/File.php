<?php
<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

/**
 * 
 * @author	Nicolas Karrer <nkarrer@hsr.ch>
 *
 */
class Html5Wiki_Model_Article extends Html5Wiki_Model_Media {
	
	/**
	 * 
	 * @var String
	 */
	private $mediaVersionType = 'FIlE';
	
	/**
	 * 
	 * @param	Integer	$idFileVersion
	 * @param	Integer	$timestampFileVersion
	 */
	public function __construct($idFileVersion, $timestampFileVersion) {
		parent::__construct($idFileVersion, $timestampFileVersion);
		
		$this->dbAdapter = new Html5Wiki_Model_File_Table();
		
		$this->data['mediaVersionType'] = $this->mediaVersionType;
		
		$this->load($idFileVersion, $timestampFileVersion);
	}
	
	/**
	 * 
	 * @param	Integer	$idArticleVersion
	 * @param	Integer	$timestampFileVersion
	 */
	private function load($idFileVersion, $timestampFileVersion) {
		array_merge($this->data, $this->dbAdapter->getFileData($idFileVersion, $timestampFileVersion));
	}

	/**
	 * (non-PHPdoc)
	 * @see html5wiki/library/Html5Wiki/Model/Html5Wiki_Model_Media#save()
	 */
	public function save() {
		list($idMediaVersion, $timestampMediaVersion) = parent::save($this->data);
		
		$saveData['idMediaVersion'] = $idMediaVersion;
		$saveData['timestampMediaVersion'] = $timestampMediaVersion;
		
		$this->dbAdapter->saveFile($saveData);
	}
}


?>
?>