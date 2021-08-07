<?php
function main()
{
	$currencyMySqlExtDAO = new CurrencyMySqlExtDAO();
	$orderBy = "desc";
	$fieldName = "id";
	$condition = "";

	// paging
	$limit = 10000;
	$offset = 0;

	if (isset($_REQUEST["page"]) && !empty($_REQUEST["page"])) {
		$page = $_REQUEST["page"];
		$offset = ($page - 1) * $limit;
	}


	$condition .= " limit $limit offset $offset ";
	$records = $currencyMySqlExtDAO->queryAllOrderBy('display_order ASC');
?>
	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-globe"></i>CURRENCY MANAGEMENT
			</div>
			<div class="actions">
				<div class="btn-group">
					<a class="btn default" href="#" data-toggle="dropdown">
						Columns
						<i class="fa fa-angle-down"></i>
					</a>
					<div id="sample_2_column_toggler" class="dropdown-menu hold-on-click dropdown-checkboxes pull-right">
						<label><input type="checkbox" checked data-column="0">ID</label>
						<label><input type="checkbox" checked data-column="<?php echo "1"; ?>"><?php echo "Name"; ?></label>
						<label><input type="checkbox" checked data-column="<?php echo "2"; ?>"><?php echo "Symbol"; ?></label>
						<label><input type="checkbox" checked data-column="<?php echo "3"; ?>"><?php echo "Display Order"; ?></label>
					</div>
				</div>
				<div class="btn-group">
					<a id="sample_editable_1_new" class="btn green" href="new_item_brand.php">
						Add New <i class="fa fa-plus"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="portlet-body">
			<table class="table table-striped table-bordered table-hover table-full-width" id="sample_2">
				<thead>
					<tr>
						<th>ID</th>
						<th><?php echo "Name"; ?></th>
						<th><?php echo "Symbol"; ?></th>
						<th><?php echo "Display Order"; ?></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>

					<?php
					for ($rc = 0; $rc < count($records); $rc++) {
						$row = $records[$rc];
					?>
						<tr id="<?php echo $row->id; ?>">
							<!-- primary key -->
							<td><?php echo $row->id; ?></td>
							<td><?php echo $row->currencyName; ?></td>
							<td><?php echo $row->currencySymbol; ?></td>
							<td align="center"><?php echo $row->displayOrder; ?></td>
							<td>
								<a class="btn btn-xs yellow" href="edit_currency.php?id=<?php echo $row->id; ?>">
									Edit
									<i class="fa fa-edit"></i>
								</a>
							</td>
							<td>
								<a class="btn btn-xs red" href="javascript:deleteAjax('currency', '<?php echo $row->id; ?>')">
									<i class="fa fa-times"></i>
									Delete
								</a>
							</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
<?php  }
include "template.php"; ?>