<?php
if(!empty($_REQUEST['t3n-url'])) {
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<style type="text/css">
			html, body {margin:0; padding:0; width:auto; /*overflow:hidden;*/}
			iframe {border:none;}
			</style>
		</head>
		<body>
			<div class="t3nAggregator" data-url="<?php echo strip_tags($_REQUEST['t3n-url']); ?>"></div>
			<script type="text/javascript">
			(function() {
				var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
				po.src = "http://t3n.de/aggregator/ebutton_async";
				var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
			})();
			</script>
		</body>
	</html>
	<?php
}