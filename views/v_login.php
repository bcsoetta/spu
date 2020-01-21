<!doctype html>
<html>
	<head>
		<title><?php echo $pagetitle;?></title>
		<meta charset="utf-8">
		<?php
		link_css('jquery-ui.min.css');
		link_css('reset.css');
		link_css('style.css');

		link_js('jquery.min.js');
		link_js('jquery-ui.min.js');
		?>
		<script type="text/javascript">
		$(function(){
			$('#username').focus();
		})
		</script>
	</head>
	<body>
		<div id="loginContainer">
			<div class="dialogTitle">
				<p>Aplikasi PIBK</p>
			</div>
			<div class="textual section">
				<p style="text-align:center;">Login</p>
			</div>
			<form id="loginForm" class="clearfix" method="POST" action="<?php echo base_url('user/validate');?>">
				<div class="textual section">
					<p>
						<span>Username</span>
						<input type="text" name="username" id="username" class="shAnim"/>
					</p>
					<p>
						<span>Password</span>
						<input type="password" name="password" class="shAnim"/>
						<?php
						if(isset($loginErr)){
						?>
						<p class="errorText">
						username/password salah!
						</p>
						<?php
						}
						?>
					</p>
				</div>
				<div class="submit">
					<input type="submit" value = "Login" class="commonButton shAnim"/>
				</div>
			</form>
		</div>
	</body>
</html>