<?php function main()
{
    global $totalPages;
    global $currentPage;
    $totalPages = 5;
    $currentPage = 5; ?>
    <div class="portlet box green">
        <div class="page-loader"></div>
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-reorder"></i>Bulk Import</div>
        </div>

        <div class="portlet-body form">
            <div class="desc" style="padding: 15px;">
                <p style="font-size: 15px;">
                    Before Using the "Bulk Import Feature, make sure you have already
                    entered the categories, subcategories and brands from the CMS with the exact names."<br><br>
                    Also make sure to use the correct excel file structure with the corect header names,
                    if you don't have the file, download a sample file
                    from <a style="font-size: 16px;color:blue;font-weight: bold;" download href="<?php echo SITE_LINK . 'public/files/excel_sample.xlsx'; ?>">here</a>
                <h3 style="font-weight: bold !important;">Guidelines:</h3>
                <p>Categories: comma separated <span style="color:red">ex: category 1, category 2</span></p>
                <p>Subcategories: comma separated <span style="color:red">ex: subcategory 1, subcategory 2</span></p>
                <p>age Range: <span style="color:red">ex: 2-4 or 5+ or 0</span></p>
                </p>
            </div>
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
                            `importing your file, please wait. <i class='fas fa-spinner fa-spin'></i><br>
                            <span style="color:red">Do not leave the page</span>`
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
                            `importing your file, please wait. <i class='fas fa-spinner fa-spin'></i><br>
                            <span style="color:red">Do not leave the page</span>`
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
                            `importing your file, please wait <i class='fas fa-spinner fa-spin'></i><br>
                            <span style="color:red">Do not leave the page</span>`
                        );
                    },
                    success: function(response) {
                        console.log(response);
                        jQuery(".notice-area").html("finished");
                    },
                    error: function() {},
                });
            }

            function checkTempTable() {
                jQuery.ajax({
                    url: mainUrl + "insert-batch",
                    type: "POST",
                    dataType: "json",
                    data: {},
                    beforeSend: function() {},
                    success: function(response) {
                        jQuery(".page-loader").hide();
                        console.log(response);
                        if (response.res == true) {
                            jQuery(".notice-area").html(
                                `importing your file, please wait. <i class='fas fa-spinner fa-spin'></i><br>
                            <span style="color:red">Do not leave the page</span>`
                            );
                            insertItemsBatches();
                        }
                    },
                    error: function() {
                        jQuery(".page-loader").hide();
                    },
                });
            }
            jQuery('.page-loader').toggle();
            checkTempTable();
        });
    </script>
    <style>
        .page-loader {
            display: none;
            position: fixed;
            background-color: rgba(0, 0, 0, 0.5);
            width: 100%;
            height: 100%;
            z-index: 999999;
        }

        .page-loader span {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .page-loader span i {
            color: #fff;
            font-size: 60px;
        }
    </style>
<?php
}
include "template.php"; ?>