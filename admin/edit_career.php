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
			<div class="caption"><i class="fa fa-reorder"></i>Edit Career</div>
		</div>
		<div class="portlet-body form">
			<form action="update_career.php" method="post" enctype="multipart/form-data" name="frm" id="frm" class="form-horizontal form-bordered">
				<div class="form-body">
					<input name="id" type="hidden" value="<?php echo $page->id ?>" />

					<div class="form-group">
						<label class="col-md-3 control-label">Position</label>
						<div class="col-md-3">
							<input name="position" type="text" class="form-control" id="position" value="<?php echo stripslashes($page->title) ?>" placeholder="Enter Page title">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Employment Type</label>
						<div class="col-md-3">
							<input name="employment_type" type="text" class="form-control" id="employment_type" value="<?php echo stripslashes($page->subtitle) ?>" placeholder="Enter Page title">
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
								<input type="checkbox" class="toggle" name="active" <?php if ($page->active == "1") { echo "checked"; } ?> />
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