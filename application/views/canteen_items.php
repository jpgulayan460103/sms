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
		<div class="col-sm-3 col-md-2">
			<label>Controls:</label>
			<button class="btn btn-block btn-primary" onclick="$('#additems-modal').modal('show')">Add Items</button>
			<button class="btn btn-block btn-primary">Edit Items</button>
			<button class="btn btn-block btn-danger">Delete Items</button>
		</div>
		<div class="col-sm-9 col-md-10">
		<?php
		echo form_open("tables/canteen/item_list", 'id="canteen-item-form"');
		?>
		</form>
		<div class="table-responsive">
			<table class="table table-hover" id="canteen-item-table">
				<thead>
					<tr>
						<th>Category</th>
						<th>Item Name</th>
						<th style="text-align:right">Cost Price</th>
						<th style="text-align:right">Selling Price</th>
						<th style="text-align:center;">Quantity</th>
						<th></th>						
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		</div>
	</div>

</div>
<?php echo $modaljs_scripts; ?>


<!-- Add Items Modal -->
<div id="additems-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Items</h4>
      </div>
      <div class="modal-body">
        <p>
        	<?php
        	echo form_open("canteen_ajax/items/add", 'id="additems-form" class="form-horizontal"');
        	?>

        	<div class="form-group">
        	  <label class="col-sm-4" for="category">Category:</label>
        	  <div class="col-sm-8">
        	    <input type="text" name="category" class="form-control" id="category-items-textbox" placeholder="Enter Category">
	        	  <p class="help-block" id="category_help-block"></p>
        	  </div>
        	</div>

          	<div class="form-group">
        	  <label class="col-sm-4" for="item_name">Item Name:</label>
        	  <div class="col-sm-8">
        	    <input type="text" name="item_name" class="form-control" placeholder="Enter Item Name">
	        	  <p class="help-block" id="item_name_help-block"></p>
        	  </div>
        	</div>

          	<div class="form-group">
        	  <label class="col-sm-4" for="cost_price">Cost Price:</label>
        	  <div class="col-sm-8">
        	    <input type="number" min="0" step="0.01" name="cost_price" class="form-control" placeholder="Enter Cost Price">
	        	  <p class="help-block" id="cost_price_help-block"></p>
        	  </div>
        	</div>

          	<div class="form-group">
        	  <label class="col-sm-4" for="selling_price">Selling Price:</label>
        	  <div class="col-sm-8">
        	    <input type="number" min="0" step="0.01" name="selling_price" class="form-control" placeholder="Enter Selling Price">
	        	  <p class="help-block" id="selling_price_help-block"></p>
        	  </div>
        	</div>

        	</form>
        </p>
      </div>
      <div class="modal-footer">
        <button form="additems-form" type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<?php echo $js_scripts; ?>
<script>
$(document).on("submit","#additems-form",function(e) {
	e.preventDefault();
	$('button[form="additems-form"]').prop('disabled', true);
	$.ajax({
		type: "POST",
		data: $("#additems-form").serialize(),
		url: $("#additems-form").attr("action"),
		cache: false,
		dataType: "json",
		success: function(data) {
			// alert(data);
			$('button[form="additems-form"]').prop('disabled', false);
			$("#category_help-block").html(data.category_error);
			$("#item_name_help-block").html(data.item_name_error);
			$("#cost_price_help-block").html(data.cost_price_error);
			$("#selling_price_help-block").html(data.selling_price_error);
		}
	})
});
$(document).on("click",".paging",function(e) {
	show_item_list(e.target.id);
});
show_item_list();
function show_item_list(page=1) {
	$.ajax({
		type: "POST",
		url: $("#canteen-item-form").attr("action"),
		data: "page="+page,
		cache: false,
		success: function(data) {
			$("#canteen-item-table tbody").html(data);
		}
	});
}
</script>
</body>
</html>