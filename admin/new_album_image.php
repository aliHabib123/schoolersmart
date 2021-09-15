<?php function main()
{ ?>
	<div class="portlet box green">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-reorder"></i>Create Album Image</div>
		</div>
		<div class="portlet-body form">
			<form action="insert_album_image.php" method="post" enctype="multipart/form-data" name="frm" id="frm" class="form-horizontal form-bordered">
				<div class="form-body">
					<input type="hidden" name="location" value="<?php echo $_GET['id']; ?>" />
					<div class="form-group">
						<label class="col-md-3 control-label">Image</label>
						<div class="col-md-9">
							<div class="fileupload fileupload-new" data-provides="fileupload">
								<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
									<!-- <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> -->
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
							<!-- <span>1200 * 600 px</span> -->
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Caption 1</label>
						<div class="col-md-3">
							<input name="caption1" type="text" class="form-control" id="caption1" value="" placeholder="Enter caption 1">
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