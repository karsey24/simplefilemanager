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
              <div class="p-1 p-sm-3">
                  <div class="alert col-12 col-md-3 position-absolute end-0 mt-1 me-sm-3 bg-dark text-light" role="alert" id="LoginResult" style="display:none">

                  </div>

                  <div class="row mt-5">
                      <div class="col">
                          <h1>Simple File Manager</h1>
                          <p class="mt-3 mb-2">Restricted access.</p>
                      </div>
                  </div>
                  <br/>
                  <br/>
                  <br/>
                  <div class="row w-100 mx-auto">
                      <div class="col-md-3 col-xs-9 mx-auto px-2 text-center">
                          <h3>Sign in</h3>
                          <form id="signin">
                              <div class="mb-2">
                                  <input type="password" class="form-control" id="pword" placeholder="Enter your code">
                              </div>
                              <button type="submit" class="btn btn-primary mt-3" id="loginBtn">LOGIN</button>
                          </form>
                      </div>
                  </div>
              </div>

              <script>
                  var progressInterval;
                  $(document).ready(function () {
                      $("#signin").on("submit", function (event) {
                          event.preventDefault();

                          var xhr = $.ajax({
                              url: "login.php",
                              method: "POST",
                              data: { password: $("#pword").val() },
                              xhrFields: {
                                withCredentials: true
                             }
                          })
                          .done(function(data) {
                              console.log("Sent login request");
                          });

                          xhr.done(function(data, textStatus, jqXHR) {
                              if(jqXHR.responseText == "true"){
                                  $("#LoginResult").html("<b>Success: </b> Signing in, please wait...");
                                  $("#LoginResult").addClass("bg-dark");
                                  $("#LoginResult").removeClass("bg-danger");
                                  $("#progressBar").width("0");

                                  $("#progressZone").show(50);
                                  progressInterval = setInterval(updateProgress, 33);
                              } else {
                                  $("#LoginResult").removeClass("bg-dark");
                                  $("#LoginResult").addClass("bg-danger");
                                  $("#LoginResult").html("<b>Failed: </b> "+jqXHR.responseText);
                                  $('#loginBtn').prop('disabled', false);
                              }
                              window.scrollTo(0, 0);
                              $("#LoginResult").show(220);
                              setTimeout(function(){ $("#LoginResult").hide(220); }, 7000);
                          });
                      });

                  });

                  var i = 0;
                  function updateProgress(){
                      if(i < 100){
                          $("#progressBar").width((i*5)+"%");
                          i++;
                      } else {
                          clearInterval(progressInterval);
                          window.location = "[[[MANAGELINK]]]";
                          $("#progressBar").width("100");
                          $("#progressZone").hide(150);
                      }
                  }
              </script>
        </div>
    </body>
</html>
