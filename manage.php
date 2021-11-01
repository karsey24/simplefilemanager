<?php
    session_start();

    if($_SESSION['valid'] !== true){
        echo '<a href="[[[HOMELINK]]]">leave</a>';
        exit;
    }

    $list = "";
    $files = scandir("/var/www/site/");
    foreach($files as $file){
        if($file != "." && $file != ".."){
            $list .= "<tr>
                        <td>$file</td>
                        <td><a href=\"[[[PUBLICLINK]]]$file\" target=\"_blank\">LINK</a></td>
                        <td><button type=\"button\" class=\"btn btn-sm btn-danger deletebtn\" data-bs-id=\"$file\">Delete</button></td>
                      </tr>";
        }
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Simple File Manager</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="style.css">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    </head>

    <body>
        <div class="content-box">
            <div class="progress position-absolute w-100 top-0 p-0 bg-light" style="height:0.2rem!important; display:none;" id="progressZone">
                <div class="progress-bar" role="progressbar" aria-valuenow="10" aria-valuemax="100" id="progressBar"></div>
            </div>
            <div class="p-sm-3 p-2">
                <div class="alert col-12 col-md-3 position-absolute end-0 mt-0 me-3 bg-dark text-light text-break" role="alert" id="ActionResult" style="display:none"> </div>

                <div class="row">
                    <div class="col">
                        <h1>
                            Simple File Manager
                        </h1>
                    </div>
                </div>

                <br/>
                <br/>

                <div class="row w-100 mx-auto">
                    <div class="mx-auto w-50 border border-primary" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" style="height:300px;">
                        <p class="lead mt-5">Drag files here...</p>
                    </div>
                </div>

                <br class="d-none d-sm-block">
                <br/>
                <br/>

                <div class="row w-100 mx-auto px-2">
                    <table class="table table-bordered text-center col-md-5 col-12 mx-auto">
                        <thead>
                            <tr>
                                <th scope="col">File</th>
                                <th scope="col">Link</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $list; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Delete Modal -->
                <div class="modal" id="deleteconfirm" tabindex="-1">
                	<div class="modal-dialog">
                		<div class="modal-content">
                			<div class="modal-header">
                				<h5 class="modal-title">Delete <span id="fileName"></span></h5>
                				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                			</div>
                			<div class="modal-body">
                				<p>Are you sure you want to delete this file? This cannot be undone.</p>
                			</div>
                			<div class="modal-footer">
                                <p class="text-danger" id="modalErrorBox2"></p>
                				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                				<button type="button" class="btn btn-danger" id="deleteconfirmation">Delete</button>
                			</div>
                		</div>
                	</div>
                </div>
            </div>

            <script>
                var progressInterval;
                var mainModal;
                var deleteModal;
                var deleteTarget = 0;
                $(document).ready(function () {
                    deleteModal = new bootstrap.Modal(document.getElementById('deleteconfirm'),  { keyboard: false, focus: true});

                    $("body").on("click", ".deletebtn", function (e) {
                        var button = e.target;
                        deleteTarget = button.getAttribute('data-bs-id');

                        $("#fileName").text(deleteTarget);
                        deleteModal.show();
                    });

                    $("#deleteconfirmation").on("click", function (e) {
                        var xhr = $.ajax({
                            url: "delete.php",
                            method: "POST",
                            data: { action: "delete", id: deleteTarget, },
                            xhrFields: {
                              withCredentials: true
                           }
                        })
                        .done(function(data) {
                            console.log("Sent deletion request");
                        });
                        $("#progressBar").width("0");
                        $("#progressZone").show(50);
                        progressInterval = setInterval(updateProgress, 45);

                        xhr.done(function(data, textStatus, jqXHR) {
                            $("#progressBar").width("100");
                            $("#progressZone").hide(150);

                            if(jqXHR.responseText.includes("true")){
                                $("#ActionResult").html(jqXHR.responseText);
                                $("#tableBody").load("account.php", { tableonly: "true" });
                                deleteModal.hide();

                                $("#ActionResult").show(220);
                                setTimeout(function(){ $("#ActionResult").hide(220); }, 20000);
                            } else {
                                $("#modalErrorBox2").html(jqXHR.responseText);
                                $("#modalErrorBox2").show(220);
                                setTimeout(function(){ $("#modalErrorBox2").hide(220); }, 7000);
                            }

                            clearInterval(progressInterval);
                        });
                    });
                });

                function dropHandler(ev) {
                      console.log('File(s) dropped');
                      // Prevent default behavior (Prevent file from being opened)
                      ev.preventDefault();

                      if (ev.dataTransfer.items) {
                            // Use DataTransferItemList interface to access the file(s)
                            for (var i = 0; i < ev.dataTransfer.items.length; i++) {
                                  // If dropped items aren't files, reject them
                                  if (ev.dataTransfer.items[i].kind === 'file') {
                                      var file = ev.dataTransfer.items[i].getAsFile();
                                      var formdata = new FormData();

                                      formdata.append('file',file);
                                      $.ajax({
                                        url: 'upload.php',
                                        type: 'post',
                                        data: formdata,
                                        contentType: false,
                                        processData: false,
                                        dataType: 'json',
                                        complete: function(response){
                                            alert(response.responseText);
                                        }
                                    });
                                  }
                            }
                      } else {
                            // Use DataTransfer interface to access the file(s)
                            for (var i = 0; i < ev.dataTransfer.files.length; i++) {
                                console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i].name);
                            }
                      }
                }

                function dragOverHandler(ev) {
                    ev.preventDefault();
                }

                var i = 0;
                function updateProgress(){
                    if(i < 100){
                        $("#progressBar").width((i*5)+"%");
                        i++;
                    }
                }

                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            </script>
        </div>
    </body>
</html>
