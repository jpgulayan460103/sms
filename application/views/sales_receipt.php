<!DOCTYPE html>
<html>
<head>
<?php echo '<title>'.$title.'</title>'.$meta_scripts.$css_scripts; ?>
<style>
*{
	line-height: 1;
}
</style>
</head>

<?php echo $navbar_scripts; ?>
<body>

<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<h3 id="canteen-name"><?php echo $canteen_data->name; ?></h3>
			<h6 id="canteen-address"><?php echo $canteen_data->address; ?></h3>
			<h6 id="canteen-contact_number"><?php echo $canteen_data->contact_number; ?></h3>
			asdasdasd
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>Item Name</th>
							<th style="text-align: center;">Quantity</th>
							<th style="text-align: right;">Price</th>
							<th style="text-align: right;">Line Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$total = 0;
							foreach ($sales_items_list as $sales_item_data) {
								$get_data = array();
								$get_data["id"] = $sales_item_data->item_id;
								$sales_item_data->item_data = $this->canteen_items_model->get_data($get_data);
								$line_total = $sales_item_data->selling_price*$sales_item_data->quantity;
								$total += $line_total;
								echo '
								<tr>
									<td>'.$sales_item_data->item_data->item_name.'</td>
									<td style="text-align: center;">'.$sales_item_data->quantity.'</td>
									<td style="text-align: right;">'.number_format($sales_item_data->selling_price,2).'</td>
									<td style="text-align: right;">'.number_format($line_total,2).'</td>
								</tr>
								';
							}

						?>
					</tbody>
					<tfoot>
						<?php
						echo '
						<tr>
							<th style="text-align: right;" colspan="3">TOTAL:</th>
							<th style="text-align: right;">'.number_format($total,2).'</th>
						</tr>
						';

						?>
					</tfoot>
				</table>
			</div>
		</div>
	</div>

</div>
<?php echo $modaljs_scripts; ?>

<?php echo $js_scripts; ?>
<script>

</script>
</body>
</html>