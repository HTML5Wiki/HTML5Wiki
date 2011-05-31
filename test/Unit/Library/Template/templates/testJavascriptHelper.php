<?php
$this->javascriptHelper()->appendScript('document.write("test");');
$this->javascriptHelper()->appendFile('foo.js', true, true);

echo $this->javascriptHelper();
?>
