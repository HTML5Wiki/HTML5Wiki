<?php
$this->javascriptHelper()->appendScript('document.write("test");');
$this->javascriptHelper()->appendFile('foo.js');

echo $this->javascriptHelper();
?>
