<!DOCTYPE html>
<html>
<head>
<?php echo '<title>'.$title.'</title>'.$meta_scripts.$css_scripts; ?>
<style>

</style>
</head>

<?php echo $navbar_scripts; ?>
<body>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php echo form_open("home/aw",'id="form-test"'); ?>
				<center>
				<input type="text" name="rfid" autofocus>
				</center>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-sm-4 col-md-2 col-lg-2 col-xs-push-3 col-sm-push-4 col-md-push-5 col-lg-push-5">
			<div id="display-photo-container">
				<img class="img-responsive" id="display-photo" src="https://www.w3schools.com/w3images/lights.jpg" alt="Chania">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-5 col-sm-5 col-md-6 col-lg-6 col-xs-push-3 col-sm-push-4 col-md-push-5 col-lg-push-3">
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th colspan="2" class="table-header">Student's Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>Name:</th>
							<td>Value:</td>
						</tr>
						<tr>
							<th>Name:</th>
							<td>Value:</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php echo $js_scripts; ?>
<script>

</script>
</body>
</html>