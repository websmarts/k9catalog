<?php 
/* AwesomeTemplateEngine.class.php */ 
class AwesomeTemplateEngine { 
    var $templatePath; 
    function AwesomeTemplateEngine($templatePath) { 
        $this->templatePath=$templatePath; 
    } 
    function parseTemplate($data,$template) { 
        include($this->templatePath.$template); 
    }
}
?>