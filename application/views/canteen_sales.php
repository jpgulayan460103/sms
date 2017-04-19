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

		
		<?php
		if($customer_data["customer_name"]!=""){
			echo '
			<div class="col-sm-6">
			<label>Customer Name:</label>
			<div class="input-group">

			';
			echo '<input type="text" placeholder="Enter Customer Name" class="form-control" autocomplete="off" value="'.$customer_data["customer_name"].'" name="customer_name" form="sales-form" readonly>';
			echo '
			<span class="input-group-btn">
			  <button class="btn btn-danger" type="button" id="remove_customer">&times;</button>
			</span>
			';
			echo '
			</div><!-- /input-group -->
			</div>
			';
		}else{
			echo '
			<div class="col-sm-6">
			<label>Customer Name:</label>
			<div class="form-group">

			';
			echo '<input type="text" id="canteen_sales_customer_name" name="customer_name" form="sales-form" placeholder="Enter Customer Name" class="form-control" autocomplete="off" required>';
			echo '
			</div><!-- /input-group -->
			</div>
			';
		}
		?>



		<div class="col-sm-2">
		<label>Scan RFID:</label>
			<?php echo form_open("rfid_ajax/canteen/sales",'id="canteen_sales_customer-form"'); ?>
			<input type="text" id="rfid_scan_canteen_sales" name="rfid_scan" placeholder="Scan RFID...." class="form-control" autocomplete="off">
			<p class="help-block" id="rfid_scan_canteen_sales_help-block"></p>
			</form>
		</div>
		<div class="col-sm-4">
			<div class="col-sm-12" style="border: 1px solid black">
			<label>TOTAL:</label>
			<br>
			<p style="font-size: 250%;text-align: right;" id="canteen_sales_total_top">0.00</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4 col-md-2">
			<?php
			if(isset($customer_data["rfid_data"]["load_credits"])){
				echo '
				<label>Remaining Load Credits:</label>
				<br>
				<span class="form-control" id="remaining_load_credits">'.number_format($customer_data["load_credits"],2).'</span>
				<br>
				';
			}
			?>
			<label>Controls:</label>
			<button class="btn btn-primary btn-block" type="submit" form="sales-form">Submit</button><br>
			<label>Comments:</label>
			<textarea class="form-control" name="comments" id="canteen_sales_comments" form="sales-form"><?php echo $comments; ?></textarea>

		</div>
		<div class="col-sm-8 col-md-10">
			<div class="table-responsive">
				<input type="text" id="search-item_name_sales" placeholder="Search for Item Name" class="form-control">
				<?php echo form_open("canteen_ajax/sales/submit",'id="sales-form"'); ?>
				<table class="table table-hover" id="sales-cart-table">
					<thead>
						<tr>
							<th></th>
							<th>Item Name</th>
							<th style="text-align:center;">Remaining Stocks</th>
							<th style="text-align:center;">Quantity</th>
							<th style="text-align:right;">Price</th>
							<th style="text-align:right;">Line Total</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $modaljs_scripts; ?>

<!-- Modal -->
<div id="student-data-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
          <p>
          <input name="rfid_id" type="hidden">
          <div class="row">

            <div class="col-sm-3">
              <img src="" class="img-responsive" alt="Student Photo" id="addloadstudent_display-photo">
            </div>
            <div class="col-sm-9">
                <table class="table">
                  <tbody>
                    <tr>
                      <th>Name:</th>
                      <td><span id="addloadstudent_full_name"></span></td>
                    </tr>
                    <tr>
                      <th>Remaining Load:</th>
                      <td><span id="addloadstudent_remaining_load"></span></td>
                    </tr>
                  </tbody>
                </table>
            </div>

          </div>


          </p>
      </div>
      <div class="modal-footer">
      	<div class="form-group">
      		<?php echo form_open("canteen_ajax/sales/confirm_pin/",'id="canteen_sales-confirm_pin"'); ?>
      		<input type="hidden" name="rfid_id">
      		<label>PIN:</label>
      		<input name="pin" type="password" autocomplete="new-password">
      		<p class="help-block" id="canteen_sales-confirm_pin_help-block"></p>
      		</form>
      	</div>
        <button type="submit" class="btn btn-primary" form="canteen_sales-confirm_pin">Confirm</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<?php echo $js_scripts; ?>
<script>
$(document).on("submit","#canteen_sales-confirm_pin",function(e) {
	// e.preventDefault();
	$.ajax({
		type: "POST",
		url: $("#canteen_sales-confirm_pin").attr("action"),
		data: $("#canteen_sales-confirm_pin").serialize(),
		cache: false,
		dataType: "json",
		success: function(data) {
			if(data.is_valid){
				$("#canteen_sales-confirm_pin")[0].reset();
				$(".help-block").html("");
				location.reload();
			}else{
				$("#canteen_sales-confirm_pin_help-block").html("Incorrect PIN");
			}
			// alert(data);
		}
	});
});
$(document).on("click",".sales_cart-remove_item",function(e) {
	var item_id = e.target.id;
	$.ajax({
		type: "POST",
		url: "<?php echo base_url("canteen_ajax/sales/delete_items_to_cart"); ?>",
		data: "item_id="+item_id,
		cache: false,
		success: function(data) {
			show_cart();
		}
	});
});

$("#search-item_name_sales").autocomplete({
	source: "<?php echo base_url("search/canteen/items_to_cart"); ?>",
	select: function(event, ui){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url("canteen_ajax/sales/add_items_to_cart"); ?>",
			data: "item_id="+ui.item.data,
			cache: false,
			success: function(data) {
				$("#search-item_name_sales").val("");
				show_cart();
				// alert(data);
			}
		});
		// window.location='item?s='+ui.item.data;
	}
});

$(document).on("change keyup",".sales_cart_quantity",function(e) {
	var item_id = e.target.id;
	var data = "item_id="+item_id+"&quantity="+e.target.value;
	$.ajax({
		type: "POST",
		url: "<?php echo base_url("canteen_ajax/sales/edit_quantity_items_to_cart"); ?>",
		data: data,
		cache: false,
		dataType: "json",
		success: function(data) {
			$(".line_total#"+item_id).html(data.line_total);
			$(".cart_total").html(data.total);
			$("#canteen_sales_total_top").html(data.total);
		}
	});
});

$(document).on("submit","#sales-form",function(e) {
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: $("#sales-form").attr("action"),
		data: $("#sales-form").serialize(),
		cache: false,
		dataType: "json",
		success: function(data) {
			window.location = "<?php echo base_url("canteen/sales/receipt/"); ?>"+data.id;
			// alert(data);
			// location.reload();
		}
	});
});

$(document).on("submit","#canteen_sales_customer-form",function(e) {
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: $("#canteen_sales_customer-form").attr("action"),
		data: $("#canteen_sales_customer-form").serialize(),
		cache: false,
		dataType: "json",
		success: function(data) {
			// alert(data.rfid_data.id);
			if(data.is_valid){
				$("#addloadstudent_display-photo").attr("src",data.display_photo);
				$("#addloadstudent_full_name").html(data.full_name);
				$('input[name="rfid_id"]').val(data.rfid_data.id);
				$("#addloadstudent_remaining_load").html(data.load_credits);

				$("#canteen_sales_customer-form")[0].reset();
				$("#student-data-modal").modal("show");
				$(".help-block").html("");
			}else{
				$("#rfid_scan_canteen_sales_help-block").html("RFID is invalid.");
			}
		}
	});
	// alert("asdasd");
});
$(document).on("click","#remove_customer",function(e) {
	$.ajax({
		type: "POST",
		url: "<?php echo base_url("canteen_ajax/sales/remove_customer"); ?>",
		data: "remove=1",
		cache: false,
		success: function function_name(data) {
			location.reload();
		}
	});
});
$(document).on("change keyup","#canteen_sales_comments",function(e) {
	var datastring = "comments="+e.target.value;
	$.ajax({
		type: "POST",
		url: "<?php echo base_url("canteen_ajax/sales/add_comments"); ?>",
		data: datastring,
		cache: false,
		success: function function_name(data) {
			// location.reload();
		}
	});
});
$(document).on("change keyup","#canteen_sales_customer_name",function(e) {
	var datastring = "customer_name="+e.target.value;
	$.ajax({
		type: "POST",
		url: "<?php echo base_url("canteen_ajax/sales/add_customer"); ?>",
		data: datastring,
		cache: false,
		success: function function_name(data) {
			// location.reload();
		}
	});
});
show_cart();
function show_cart() {
	$.ajax({
		type: "POST",
		url: "<?php echo base_url("tables/canteen/sales_cart"); ?>",
		data: "d=1",
		cache: false,
		success: function(data) {
			$("#sales-cart-table tbody").html(data);
			$("#canteen_sales_total_top").html($(".cart_total").html());
		}
	});
}
</script>
</body>
</html>