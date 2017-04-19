<!DOCTYPE html>
<html>
<head>
<?php echo '<title>'.$title.'</title>'.$meta_scripts.$css_scripts; ?>
<style>
#login-container{
	margin-top: 5em;
	border: 1px solid grey;
	padding: 1rem 1rem 1rem 1rem;
}
</style>
</head>

<body id="has-logo">

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-8 col-md-4 col-lg-4 col-sm-push-2 col-md-push-4 col-lg-push-4">
			<div id="login-container" style="background-color: white;">
					<!-- <img class="img-responsive" src="<?php echo base_url("assets/images/logo.png");?>" alt="Chania" id="login-logo"> -->
				<?php echo form_open("gate/login",'class="form-horizontal" id="app-login"');?>
					<div class="form-group">
					  <div class="col-sm-12"> 
					    <input type="password" class="form-control" id="account_password" name="account_password" placeholder="Enter Password">
					    <p class="help-block" id="account_password_help-block"><?php echo form_error('account_password'); ?></p>
					  </div>
					</div>
					<div class="form-group"> 
					  <div class="col-sm-12">
					    <button type="submit" class="btn btn-primary btn-block">Login</button>
					  </div>
					</div>
				</form>
			</div>
		</div>
	</div>

</div>
<?php echo $js_scripts; ?>
</body>
</html>