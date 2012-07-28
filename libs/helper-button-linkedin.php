<?php
if(!empty($_REQUEST['linkedin-url'])) {
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<style type="text/css">
			html, body {margin:0; padding:0; width:auto; /*overflow:hidden;*/}
			</style>
		</head>
		<body>
			<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
			<script type="IN/Share" data-url="<?php echo strip_tags($_REQUEST['linkedin-url']); ?>" data-counter="right"></script>
		</body>
	</html>
	<?php
}