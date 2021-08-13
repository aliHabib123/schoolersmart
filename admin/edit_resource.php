<?php
require "session_start.php";
function main()
{
	$id = $_REQUEST["id"];
	$contentMySqlExtDAO = new ContentMySqlExtDAO();
	$page = $contentMySqlExtDAO->load($id); ?>
	<link href="css/css.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="javascript/pop_up.js"></script>
	<script language="JavaScript" type="text/javascript" src="javascript/delete_file_confirmation.js"></script>
	<div class="portlet box green">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-reorder"></i>Edit Idea & Resource</div>
		</div>
		<div class="portlet-body form">
			<form action="update_resource.php" method="post" enctype="multipart/form-data" name="frm" id="frm" class="form-horizontal form-bordered">
				<div class="form-body">
					<input name="id" type="hidden" value="<?php echo $page->id ?>" />

					<div class="form-group">
						<label class="col-md-3 control-label">Title</label>
						<div class="col-md-3">
							<input name="title" type="text" class="form-control" id="title" value="<?php echo stripslashes($page->title) ?>" placeholder="Enter Title">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Date</label>
						<div class="col-md-3">
							<input name="date" type="text" class="form-control" id="date" value="<?php echo stripslashes($page->dateText) ?>" placeholder="Enter Date">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Image</label>
						<div class="col-md-9">
							<div class="fileupload fileupload-new" data-provides="fileupload">
								<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
									<?php
									if (is_file(IMAGES_PATH . $page->image)) { ?>
										<img src="<?php echo IMAGES_PATH . $page->image ?>" alt="<?php echo $page->image ?>" />
									<?php } ?>
									<input type="hidden" value="<?php echo $page->image; ?>" name="current_image" />
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
							$fck = new FCKeditor("details");
							$fck->BasePath = "fckeditor/";
							$fck->Value = $page->details;
							$fck->Config["EnterMode"] = "br";
							$fck->Create(); ?>
						</div>
					</div>


					<div class="form-group">
						<label class="col-md-3 control-label">Is Active</label>
						<div class="col-md-9">
							<div class="make-switch" data-on="warning" data-off="danger">
								<input type="checkbox" class="toggle" name="active" <?php if ($page->active == "1") {
																						echo "checked";
																					} ?> />
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Display Order</label>
						<div class="col-md-3">
							<input name="display_order" type="number" class="form-control" id="title" value="<?php echo $page->displayOrder ?>" placeholder="Ex: 1">
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