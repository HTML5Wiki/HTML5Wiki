<?php
/**
 * JSON Templating decorator
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Template
 */
class Html5Wiki_Template_Json extends Html5Wiki_Template_Decorator {
    public function render() {
		$this->decoratedTemplate->render();
	}
}
?>
