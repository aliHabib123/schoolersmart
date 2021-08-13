<?php function main()
{
    global $totalPages;
    global $currentPage;
    $totalPages = 5;
    $currentPage = 5; ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-reorder"></i>Bulk Import</div>
        </div>
        <div class="portlet-body form">
            <form action="<?php echo SITE_LINK . 'submit-import' ?>" method="post" enctype="multipart/form-data" name="frm" id="import-form" class="form-horizontal form-bordered">
                <div class="form-body">

                    <div class="form-group">
                        <label class="col-md-3 control-label">Select File</label>
                        <div class="col-md-3">
                            <input name="excel" type="file" class="form-control" id="title" value="">
                        </div>
                    </div>
                    <div class="notice-area"></div>

                    <br />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                    <br />
                </div> <!-- end div form body-->
            </form>
        </div>
    </div>
    <script>
        //Import
        jQuery(function() {
            function showMsg(selector, status, msg) {
                let html = `<div class="${status ? "success" : "error"}">${msg}</div>`;
                jQuery(selector).html(html);
            }
            jQuery("#import-form").submit(function(e) {
                var formData = new FormData(this);
                var formUrl = jQuery(this).attr("action");
                jQuery.ajax({
                    url: formUrl,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        showMsg(
                            ".notice-area",
                            true,
                            "Importing your file, please dont click anywhere..."
                        );
                    },
                    success: function(response) {
                        console.log(response);
                        showMsg(".notice-area", response.status, response.msg);
                        if (response.status == true) {
                            insertItemsBatches();
                            //location.href = response.redirectUrl;
                        }
                    },
                    error: function() {
                        showMsg(".notice-area", false, "An error occured, please try again!");
                    },
                });
                e.preventDefault();
            });

            function insertItemsBatches() {
                jQuery.ajax({
                    url: mainUrl + "insert-batch",
                    type: "POST",
                    dataType: "json",
                    data: {},
                    beforeSend: function() {
                        jQuery(".notice-area").html(
                            "importing your file, please wait. <i class='fas fa-spinner fa-spin'></i>"
                        );
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.res == true) {
                            setTimeout(function() {
                                insertItemsBatches();
                            }, 1000);
                        } else {
                            //deleteDeletedItems();
                            cleanTempTable();
                        }
                    },
                    error: function() {},
                });
            }

            function deleteDeletedItems() {
                jQuery.ajax({
                    url: mainUrl + "delete-deleted",
                    type: "POST",
                    dataType: "json",
                    data: {},
                    beforeSend: function() {
                        jQuery(".notice-area").html(
                            "importing your file, please wait. <i class='fas fa-spinner fa-spin'></i>"
                        );
                    },
                    success: function(response) {
                        console.log(response);
                        cleanTempTable();
                    },
                    error: function() {},
                });
            }

            function cleanTempTable() {
                jQuery.ajax({
                    url: mainUrl + "clean-temp-table",
                    type: "POST",
                    dataType: "json",
                    data: {},
                    beforeSend: function() {
                        jQuery(".notice-area").html(
                            "importing your file, please wait. <i class='fas fa-spinner fa-spin'></i>"
                        );
                    },
                    success: function(response) {
                        console.log(response);
                        jQuery(".notice-area").html("finished");
                    },
                    error: function() {},
                });
            }
        });
    </script>
<?php
}
include "template.php"; ?>