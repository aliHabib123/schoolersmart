<?php function main()
{ ?>

	<div class="portlet box green">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-reorder"></i>Create Career</div>
		</div>
		<div class="portlet-body form">
			<form action="insert_career.php" method="post" enctype="multipart/form-data" name="frm" id="frm" class="form-horizontal form-bordered">
				<div class="form-body">

					<div class="form-group">
						<label class="col-md-3 control-label">Position</label>
						<div class="col-md-3">
							<input name="position" type="text" class="form-control" id="position" value="" placeholder="Enter Position">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Employment Type</label>
						<div class="col-md-3">
							<input name="employment_type" type="text" class="form-control" id="employment_type" value="" placeholder="Enter Type">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Description</label>
						<div class="form-group">
							<?php
							$fck = new FCKeditor("details");
							$fck->BasePath = "fckeditor/";
							$fck->Value = "";
							$fck->Config["EnterMode"] = "br";
							$fck->Create();
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Is active</label>
						<div class="col-md-9">
							<div class="make-switch" data-on="warning" data-off="danger">
								<input type="checkbox" checked class="toggle" name="active" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Display Order</label>
						<div class="col-md-3">
							<input name="display_order" type="number" class="form-control" id="title" value="0" placeholder="Ex: 1">
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
		</div>
	</div>
<?php  }
include "template.php"; ?>