<div id = "background" OnClick="location.replace(<?= "'" . $this->di->url->create($this->di->request->getRoute()) . "'"; ?>)">
</div>
<div id = "content">
	<a id = "close" href = "<?= $this->di->url->create($this->di->request->getRoute()); ?>">X</a>
	
	<h2>Login</h2>
	<?= $form->getHTML(["use_fieldset" => false]); ?>
</div>