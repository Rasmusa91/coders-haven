<!doctype html>
<html class='no-js' lang='<?=$lang?>'>
	<head>
		<meta charset='utf-8'/>
		<title><?=$title . $title_append?></title>
		<?php if(isset($favicon)): ?><link rel='icon' href='<?=$this->url->asset($favicon)?>'/><?php endif; ?>
		<?php foreach($stylesheets as $stylesheet): ?>
		<link rel='stylesheet' type='text/css' href='<?=$this->url->asset($stylesheet)?>'/>
		<?php endforeach; ?>
		<?php if(isset($style)): ?><style><?=$style?></style><?php endif; ?>
		<script src='<?=$this->url->asset($modernizr)?>'></script>
	</head>

	<body>
		<div id="wrapper">
			<div id = "topbarWrapper">
				<div id = "topbar">
					<div id = "topbarLeft">
						<?php $this->views->render("topbarLeft")?>
					</div>
					<div id = "topbarRight">
						<?php $this->views->render("topbarRight")?>
					</div>
				</div>
			</div>
			
			<div id = "headerWrapper">
				<div id="header">
					<div id = "logo" class = "left">
						<?php $this->views->render("header")?>
					</div>
					<div id = "navbar" class = "right">
						<?php $this->views->render("navbar")?>
					</div>
					<div class = "clear"></div>
				</div>			
			</div>
			
			<div id = "mainWrapper">
				<div id="main">
					<div id = "<?= explode('/', $this->di->request->getRoute())[0]; ?>">
						<?php $this->views->render("main")?>
					</div>
				</div>
			</div>
		</div>
		
		<div id="preFooterWrapper">
			<div id = "preFooter">
				<div id = "preFooter1">
					<?php $this->views->render("preFooter1")?>
				</div>
				
				<div id = "preFooter2">
					<?php $this->views->render("preFooter2")?>
				</div>

				<div id = "preFooter3">
					<?php $this->views->render("preFooter3")?>
				</div>

				<div id = "preFooter4">
					<?php $this->views->render("preFooter4")?>
				</div>
			</div>
		</div>
		<div id = "footerWrapper">
			<div id = "footer">
				<div id = "footerLeft">
					<?php $this->views->render("footerLeft")?>
				</div>
				<div id = "footerRight">
					<?php $this->views->render("footerRight")?>
				</div>
			</div>
		</div>
		
		<div id = "login">
			<?php $this->views->render("login")?>
		</div>

		<?php if(isset($jquery)):?><script src='<?=$this->url->asset($jquery)?>'></script><?php endif; ?>

		<?php if(isset($javascript_include)): foreach($javascript_include as $val): ?>
		<script src='<?=$this->url->asset($val)?>'></script>
		<?php endforeach; endif; ?>

		<?php if(isset($google_analytics)): ?>
		<script>
		  var _gaq=[['_setAccount','<?=$google_analytics?>'],['_trackPageview']];
		  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
		  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		  s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>
		<?php endif; ?>
	</body>
</html>
