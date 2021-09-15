<?php
require "session_start.php";
function main()
{
	$id = $_REQUEST["id"];
	$currencyMySqlExtDAO = new CurrencyMySqlExtDAO();
	$res = $currencyMySqlExtDAO->load($id); ?>
	<link href="css/css.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="javascript/pop_up.js"></script>
	<script language="JavaScript" type="text/javascript" src="javascript/delete_file_confirmation.js"></script>
	<div class="portlet box green">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-reorder"></i>Edit Currency</div>
		</div>
		<div class="portlet-body form">
			<form action="update_currency.php" method="post" enctype="multipart/form-data" name="frm" id="frm" class="form-horizontal form-bordered">
				<div class="form-body">
					<input name="id" type="hidden" value="<?php echo $res->id ?>" />

					<div class="form-group">
						<label class="col-md-3 control-label">Name</label>
						<div class="col-md-3">
							<input name="name" type="text" class="form-control" id="name" value="<?php echo stripslashes($res->currencyName) ?>" placeholder="Enter Name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Symbol</label>
						<div class="col-md-3">
							<input name="symbol" type="text" class="form-control" id="symbol" value="<?php echo stripslashes($res->currencySymbol) ?>" placeholder="Enter Symbol">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Rate</label>
						<div class="col-md-3">
							<input name="rate" type="text" class="form-control" id="rate" value="<?php echo stripslashes($res->conversionRate); ?>" placeholder="Enter Symbol">
						</div>
					</div>



					<div class="form-group">
						<?php
						$countryMySqlExtDAO = new CountryMySqlExtDAO();
						$countries = $countryMySqlExtDAO->queryAllOrderBy("name ASC");
						$countryCurrencyMappingMySqlExtDAO = new CurrencyCountryMySqlExtDAO();
						$countryCurrencyMapping = $countryCurrencyMappingMySqlExtDAO->queryByCurrencyId($res->id);
						$countryCurrencyMapping = array_map(function ($a) {
							return $a->countryId;
						}, $countryCurrencyMapping); ?>
						<label class="control-label col-md-3">Countries</label>
						<div class="col-md-4">
							<select class="form-control select2me" data-placeholder="Select..." name="countries[]" id="categories" multiple>
								<?php
								foreach ($countries as $row) {
									$sel = "";
									if (in_array($row->id, $countryCurrencyMapping)) {
										$sel = "selected";
									} ?>
									<option value="<?php echo $row->id; ?>" <?php echo $sel; ?>><?php echo $row->name; ?></option>
								<?php
								} ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Display Order</label>
						<div class="col-md-3">
							<input name="display_order" type="number" class="form-control" id="display_order" value="<?php echo $res->displayOrder ?>" placeholder="Ex: 1">
						</div>
					</div>

					<br />
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-offset-3 col-md-9">
								<button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
								<button type="button" class="btn default" onclick="javascript:if(confirm('Are you sure you want to leave this page?')) history.back()">Cancel</button>
							</div>
						</div>
					</div>
					<br />
				</div> <!-- end div form body-->
			</form>
		<?php
	}
	include "template.php"; ?>