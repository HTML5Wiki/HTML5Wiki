<?php
$this->javascriptHelper()->appendScript('document.write("test");');
$this->javascriptHelper()->appendFile('foo.js', true, true);
$this->javascriptHelper()->appendFile('foo2.js', true, true, 0);

echo $this->javascriptHelper();
?>
