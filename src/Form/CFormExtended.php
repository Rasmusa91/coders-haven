<?php
	namespace Appelicious\Form;
	
	abstract class CFormExtended extends \Mos\HTMLForm\CForm
	{
		use \Anax\DI\TInjectionaware, \Anax\MVC\TRedirectHelpers;
		
		private $m_MultipleForms;
		private $m_SubmitName;
		
		public function __construct($form = [], $elements = [], $multipleForms = false, $submitName = null)
		{
			parent::__construct($form, $elements);
			
			$this->m_MultipleForms = $multipleForms;
			$this->m_SubmitName = $submitName;
		}
		
		public function check($callIfSuccess = null, $callIfFail = null)
		{		
			if($_SERVER['REQUEST_METHOD'] == 'POST' && $this->m_MultipleForms && !$this->di->request->getPost($this->m_SubmitName)) 
			{
				return null;
			}
			
			return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);				
		}
		
		public function callbackSubmit()
		{
			$result = $this->validate($output);
			
			if(!empty($output)) {
				$this->addOutput("* " . $output);
			}			
			
			// When using multiple forms in the same view we don't want the check to handle the callbacks.
			if($this->m_MultipleForms)
			{
				if($result) {
					$this->callbackSuccess();
				}
				else {
					$this->callbackFail();
				}
			}
			else {
				return $result;
			}
			
		}
		
		public abstract function validate(&$output);

		public abstract function callbackSuccess();
		
		public abstract function callbackFail();
		
		public function render()
		{
			return $this->getHTML(["use_fieldset" => false]);
		}
	}