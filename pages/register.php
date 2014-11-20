<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo HEAD_TITLE . " - " . gettext("Registration Form"); ?></title>

        <!-- Bootstrap -->
        <link href="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/Bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <link href="<?php echo CSS_LIBRARY_PATH; ?>/base.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/jQuery-File-Upload-9.8.0/css/jquery.fileupload.css">
        
    </head>

    <body>
        <?php include("sketches/header_no_login.php"); ?>
        <div class="container">
            <div class="row text-center pad-top ">
                <div class="col-md-12">
                    <h2>Start using Investmatic now!</h2>
                </div>
            </div>
            <div class="row  pad-top">
                <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-5 col-xs-12 col-xs-offset-1">
                    <div style="display: none;" class="alert alert-danger" id="errormsg">Sorry pal, something went wrong with you registration. Please <a href="#" class="alert-link" id="retry_register">try again</a>.</div>
                    <div style="display: none;" class="alert alert-success" id="thanks">Thanks for your registration. You should receive an e-mail with your data shortly. Login <a href="<?php echo SITE_BASE_URL."login"; ?>" class="alert-link">here</a></div>
                    <div class="col-md-6 col-md-offset-4 col-sm-8 col-sm-offset-5 col-xs-12 col-xs-offset-1">
                        <button class="btn btn-lg btn-warning" id="loading" style="display: none;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</button>
                    </div>
                    <div class="panel panel-default" id="form-content">
                        <div class="panel-heading">
                            <h3 class="panel-title">Please sign up for Investmatic <small>It's free!</small></h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" name="registration" id="registration">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <!-- The fileinput-button span is used to style the file input field as button -->
                                        <span class="btn btn-success fileinput-button" id="file_upload_btn">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>Add files...</span>
                                            <!-- The file input field used as target for the file upload widget -->
                                            <input id="fileupload" type="file" name="files[]">
                                            <!-- The global progress bar -->
                                            <div id="progress" class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                            <!-- The container for the uploaded files -->
                                            <div id="files" class="files"></div>
                                        </span>
                                        <img src="" class="img-rounded" id="upload_preview">
                                    </div>
                                </div>
                                <div class="row">
                                        <br>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-circle-o-notch"  ></i></span>
                                            <input type="text" class="form-control" placeholder="Your Name" name="usuario_nome" id="usuario_nome" data-validation="required"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
                                            <input type="text" class="form-control" placeholder="Desired Username" name="usuario_nick" id="usuario_nick" data-validation="required"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control input phone_with_ddd" placeholder="Telephone" name="usuario_telefone" id="usuario_telefone"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">@</span>
                                        <input type="email" class="form-control" placeholder="Your Email" name="usuario_email" id="usuario_email" data-validation="email">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                              <span class="input-group-addon">@</span>
                                              <input type="text" class="form-control" placeholder="Twitter" name="usuario_twitter" id="usuario_twitter"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control input" placeholder="Facebook" name="usuario_facebook" id="usuario_facebook"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="password" name="usuario_senha_confirmation" class="form-control" placeholder="Enter Password" data-validation="length" data-validation-length="min8">
                                            <span class="help-block">Minimum of 8 characters</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="password" name="usuario_senha" class="form-control" placeholder="Retype Password" data-validation="confirmation">
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" value="" id="usuario_foto" name="usuario_foto">
                                <input type="submit" value="Register Me" class="btn btn-success" id="submit">
                                <input type="button" value="Register with Facebook" class="btn btn-primary" id="submitFb">
                                <hr>
                                Already Registered ?  <a href="<?php echo SITE_BASE_URL."login"; ?>" >Login here</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <?php include("sketches/footer.php"); ?>


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/jQuery/jquery-2.1.1.js"></script>
        <script src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/jquery.mask.min.js"></script>

        <!-- jQuery File Upload -->
        <script src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/jQuery-File-Upload-9.8.0/js/vendor/jquery.ui.widget.js"></script>
        <script src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/jQuery-File-Upload-9.8.0/js/jquery.iframe-transport.js"></script>
        <script src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/jQuery-File-Upload-9.8.0/js/jquery.fileupload.js"></script>
        
        <script src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/form-validator/jquery.form-validator.min.js"></script>
        
        
        <script>
            $(document).ready(function(){
                $('.phone_with_ddd').mask('(00) 0000-00000');
                
                  $.validate({
                    modules : 'security',
                    //errorMessagePosition : 'top',
                    validateOnBlur : true,
                    onSuccess : function() {
                        
                        /*var jsonData = {};
                        var formData = $("#registration").serializeArray();
                        $.each(formData, function() {
                            if (jsonData[this.name]) {
                                if (!jsonData[this.name].push) {
                                    jsonData[this.name] = [jsonData[this.name]];
                                }
                                jsonData[this.name].push(this.value || '');
                            } else {
                                jsonData[this.name] = this.value || '';
                            }
                        });*/
                        
                        
                        
                        $("#form-content").fadeOut( 300 ); //hide popup  
                        $("#loading").delay(300).show( 300 ); //hide popup  
                        $.ajax({
                            type: "POST",
                            url: "ajax/register.php", //process to mail
                            data: $('#registration').serialize(),
                            //data: JSON.stringify(jsonData),
                            //contentType: "application/json; charset=utf-8",
                            //dataType: "json",
                            success: function(msg){
                                $("#loading").hide(); //hide popup  
                                //$("#form-content").fadeOut( 300 ); //hide popup  
                                //$("#thanks").html(msg) //put the feedback message from request
                                $("#thanks").delay(1000).fadeIn(1000).show(); //show thank you note
                            },
                            error: function(){
                                $("#loading").hide(); //hide popup  
                                $("#errormsg").delay(1000).fadeIn(1000).show();
                            }
                        });
                        return false; //stop submiting the form
                    }
                  });
                
                $("#retry_register").click(function(){
                    $("#errormsg").hide().delay(100); //hide popup  
                    $("#loading").hide().delay(100); //hide popup  
                    $("#form-content").fadeIn( 300 ); //hide popup  
                });
                
                $(function () {
                    'use strict';
                    // Change this to the location of your server-side upload handler:
                    var url = '<?php echo SITE_BASE_URL."upload/"; ?>';
                    //var url = 'http://localhost/jQuery-File-Upload/server/php';
                    $('#fileupload').fileupload({
                        url: url,
                        dataType: 'json',
                        done: function (e, data) {
                            $.each(data.result.files, function (index, file) {
                                //$('<p/>').text(file.name).appendTo('#files');
                                $("#usuario_foto").val(file.url);
                                $("#file_upload_btn").hide();
                                $("#upload_preview").attr("src",file.thumbnailUrl);
                                
                            });
                        },
                        progressall: function (e, data) {
                            var progress = parseInt(data.loaded / data.total * 100, 10);
                            $('#progress .progress-bar').css(
                                'width',
                                progress + '%'
                            );
                        }
                    }).prop('disabled', !$.support.fileInput)
                        .parent().addClass($.support.fileInput ? undefined : 'disabled');
                });

                
            });
            
            
            
            
        </script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/Bootstrap/3.3.1/js/bootstrap.min.js"></script>
    </body>
</html>
