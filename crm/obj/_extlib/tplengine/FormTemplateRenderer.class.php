<?php 
/* 	FormTemplateRenderer helps render partial templates wrapping <form></form> tags
		and javascript around each form group created 
*/ 
class FormTemplateRenderer { 
    var $templatePath;
    function FormTemplateRenderer($templatePath) { 
        $this->templatePath=$templatePath; 
    }
		
		function getProcessedFormTemplate(&$renderer, $template, $form='') {
				ob_start();
					$renderer;
					$form;
					include($this->templatePath.$template); 
					$contents = ob_get_contents();
				ob_end_clean();
				
				return $renderer->toHtml($contents);
		}
}
?>