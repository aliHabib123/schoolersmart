<?php
require "session_start.php";
function main()
{
	$id = $_REQUEST["id"];
	$categoriesMySqlExtDAO = new ItemCategoryMySqlExtDAO();
	$allCategories = $categoriesMySqlExtDAO->select("parent_id = 0 AND active = 1");

	$itemCategoryMappingMySqlExtDAO = new ItemCategoryMappingMySqlExtDAO();
	$itemCategoryMapping  = $itemCategoryMappingMySqlExtDAO->queryByItemId($id);
	$itemCategoryMapping = array_map(function ($a) {
		return $a->categoryId;
	}, $itemCategoryMapping);

	$brandsMySqlExtDAO = new ItemBrandMySqlExtDAO();
	$allBrands = $brandsMySqlExtDAO->queryAllOrderBy('name ASC');

	$itemBrandMappingMySqlExtDAO = new ItemBrandMappingMySqlExtDAO();
	$itemBrandMapping = $itemBrandMappingMySqlExtDAO->queryByItemId($id);
	$itemBrandMapping = array_map(function ($a) {
		return $a->brandId;
	}, $itemBrandMapping);


	$queryString = $_SERVER['QUERY_STRING'];


	$itemMySqlExtDAO = new ItemMySqlExtDAO();
	$itemTagMappingMySqlExtDAO = new ItemTagMappingMySqlExtDAO();
	$mapping = $itemTagMappingMySqlExtDAO->queryByItemId($id);
	$tags = [
		'best-deals' => false,
		'hot-selling' => false,
		'featured' => false,
	];
	foreach ($mapping as $map) {
		if ($map->tagId == 1) {
			$tags['best-deals'] = true;
		}
		if ($map->tagId == 2) {
			$tags['hot-selling'] = true;
		}
		if ($map->tagId == 3) {
			$tags['featured'] = true;
		}
	}
	$item = $itemMySqlExtDAO->load($id);
?>
	<link href="css/css.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="javascript/pop_up.js"></script>
	<script language="JavaScript" type="text/javascript" src="javascript/delete_file_confirmation.js"></script>
	<div class="portlet box green">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-reorder"></i>Edit Product</div>
		</div>
		<div class="portlet-body form">
			<form action="update_product.php" method="post" enctype="multipart/form-data" name="frm" id="frm" class="form-horizontal form-bordered">
				<div class="form-body">
					<input name="id" type="hidden" value="<?php echo $item->id ?>" />
					<input name="slug" type="hidden" value="<?php echo $item->slug ?>" />
					<input name="query_string" type="hidden" value="<?php echo $queryString ?>" />

					<div class="form-group">
						<label class="col-md-3 control-label">Title</label>
						<div class="col-md-9">
							<input name="title" type="text" class="form-control" id="title" value="<?php echo stripslashes($item->title) ?>" placeholder="Enter Page title">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">SKU</label>
						<div class="col-md-3">
							<input name="sku" type="text" class="form-control" id="sku" value="<?php echo stripslashes($item->sku) ?>" placeholder="Enter SKU">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Image</label>
						<div class="col-md-9">
							<div class="fileupload fileupload-new" data-provides="fileupload">
								<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
									<?php
									if (is_file(IMAGES_PATH . $item->image)) { ?>
										<img src="<?php echo IMAGES_PATH . $item->image ?>" alt="<?php echo $item->image ?>" />
									<?php } ?>
									<input type="hidden" value="<?php echo $item->image; ?>" name="current_image" />
								</div>
								<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
								<div>
									<span class="btn default btn-file">
										<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
										<span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
										<input type="file" class="default" name="image" id="image" />
									</span>
									<a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> Remove</a>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Description</label>
						<div class="form-group">
							<?php
							$fck = new FCKeditor("description");
							$fck->BasePath = "fckeditor/";
							$fck->Value = $item->description;
							$fck->Config["EnterMode"] = "br";
							$fck->Create(); ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Sepcification</label>
						<div class="form-group">
							<?php
							$fck = new FCKeditor("specification");
							$fck->BasePath = "fckeditor/";
							$fck->Value = $item->description;
							$fck->Config["EnterMode"] = "br";
							$fck->Create(); ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Regular Price</label>
						<div class="col-md-3">
							<input name="regular_price" type="text" class="form-control" id="regular_price" value="<?php echo stripslashes($item->regularPrice) ?>" placeholder="Enter Regular Price">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Sale Price</label>
						<div class="col-md-3">
							<input name="sale_price" type="text" class="form-control" id="sale_price" value="<?php echo stripslashes($item->salePrice) ?>" placeholder="Enter Sale Price">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Weight</label>
						<div class="col-md-3">
							<input name="weight" type="text" class="form-control" id="weight" value="<?php echo stripslashes($item->weight) ?>" placeholder="Enter weight">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Height</label>
						<div class="col-md-3">
							<input name="height" type="text" class="form-control" id="height" value="<?php echo stripslashes($item->height) ?>" placeholder="Enter Height">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Width</label>
						<div class="col-md-3">
							<input name="width" type="text" class="form-control" id="width" value="<?php echo stripslashes($item->width) ?>" placeholder="Enter Width">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Color</label>
						<div class="col-md-3">
							<input name="color" type="text" class="form-control" id="color" value="<?php echo stripslashes($item->color) ?>" placeholder="Enter Color">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Warranty</label>
						<div class="col-md-3">
							<input name="warranty" type="text" class="form-control" id="warranty" value="<?php echo stripslashes($item->warranty) ?>" placeholder="Enter Warranty">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Exchange</label>
						<div class="col-md-3">
							<input name="exchange" type="text" class="form-control" id="exchange" value="<?php echo stripslashes($item->exchange) ?>" placeholder="Enter Exchange">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Categories</label>
						<div class="col-md-9">
							<div class="row">
								<?php foreach ($allCategories as $cat) {
									$subCategories = $categoriesMySqlExtDAO->select("parent_id = $cat->id and active = 1"); ?>
									<div class="col-md-3 catgeories-wrap">
										<div class="category-title"><?php echo $cat->name; ?></div>

										<?php foreach ($subCategories as $subCat) {
											$checked = (in_array($subCat->id, $itemCategoryMapping)) ? 'checked' : ''; ?>
											<label>
												<input type="checkbox" class="toggle" name="c[]" value="<?php echo $subCat->id; ?>" <?php echo $checked; ?> />
												<?php echo $subCat->name; ?>
											</label>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Brands</label>
						<div class="col-md-9">
							<div class="row">
								<?php foreach ($allBrands as $brand) { ?>
									<div class="col-md-3">
										<?php
										$checked = (in_array($brand->id, $itemBrandMapping)) ? 'checked' : ''; ?>
										<label>
											<input type="checkbox" class="toggle" name="brand[]" value="<?php echo $brand->id; ?>" <?php echo $checked; ?> />
											<?php echo $brand->name; ?>
										</label>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Is Best Deals</label>
						<div class="col-md-9">
							<div class="make-switch" data-on="warning" data-off="danger">
								<input type="checkbox" class="toggle" name="best_deals" <?php if ($tags['best-deals']) {
																							echo "checked";
																						} ?> />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Is Hot Selling</label>
						<div class="col-md-9">
							<div class="make-switch" data-on="warning" data-off="danger">
								<input type="checkbox" class="toggle" name="hot_selling" <?php if ($tags['hot-selling']) {
																								echo "checked";
																							} ?> />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Is Featured</label>
						<div class="col-md-9">
							<div class="make-switch" data-on="warning" data-off="danger">
								<input type="checkbox" class="toggle" name="featured" <?php if ($tags['featured']) {
																							echo "checked";
																						} ?> />
							</div>
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