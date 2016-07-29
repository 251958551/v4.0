<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
require_once('./php/CRMDefaults.php');
require_once('./php/UIHandler.php');
//require_once('./php/DbHandler.php');
require_once('./php/LanguageHandler.php');
require('./php/Session.php');
require_once('./php/goCRMAPISettings.php');

// initialize structures
$ui = \creamy\UIHandler::getInstance();
$lh = \creamy\LanguageHandler::getInstance();
$user = \creamy\CreamyUser::currentUser();

$cid = NULL;
if (isset($_POST["cid"])) {
	$cid = $_POST["cid"];
}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Carrier</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- Creamy style -->
        <link href="css/creamycrm.css" rel="stylesheet" type="text/css" />
        <?php print $ui->creamyThemeCSS(); ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui.min.js" type="text/javascript"></script>
		<!-- Forms and actions -->
		<script src="js/jquery.validate.min.js" type="text/javascript"></script>
        <!-- Creamy App -->
        <script src="js/app.min.js" type="text/javascript"></script>

        	<!-- =============== BOOTSTRAP STYLES ===============-->
			<link rel="stylesheet" href="theme_dashboard/css/bootstrap.css" id="bscss">
				<!-- =============== APP STYLES ===============-->
			<link rel="stylesheet" href="theme_dashboard/css/app.css" id="maincss">

        <!-- preloader -->
        <link rel="stylesheet" href="css/customizedLoader.css">

        <script type="text/javascript">
			$(window).ready(function() {
				$(".preloader").fadeOut("slow");
			})
		</script>
    </head>
    <style>
    	select{
    		font-weight: normal;
    	}
    </style>
    <?php print $ui->creamyBody(); ?>
        <div class="wrapper">
        <!-- header logo: style can be found in header.less -->
		<?php print $ui->creamyHeader($user); ?>
            <!-- Left side column. contains the logo and sidebar -->
			<?php print $ui->getSidebar($user->getUserId(), $user->getUserName(), $user->getUserRole(), $user->getUserAvatar()); ?>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1 style="font-weight:normal;">
                        <?php $lh->translateText("settings"); ?>
                        <small><?php $lh->translateText("Carrier Edit"); ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="./index.php"><i class="fa fa-edit"></i> <?php $lh->translateText("home"); ?></a></li>
                        <li> <?php $lh->translateText("settings"); ?></li>
                        <?php
							if(isset($_POST["cid"])){
						?>	
							<li><a href="./settingscarriers.php"><?php $lh->translateText("Carrier"); ?></a></li>
                        <?php
							}
                        ?>	                    
                        <li class="active"><?php $lh->translateText("modify"); ?></li>
                    </ol>
                </section>
                <!-- Main content -->
	            <section class="content">
					<div class="panel panel-default">
	                    <div class="panel-body">
						<!-- standard custom edition form -->

						<?php
						$errormessage = NULL;
						
						//if(isset($extenid)) {
							$url = gourl."/goCarriers/goAPI.php"; #URL to GoAutoDial API. (required)
					        $postfields["goUser"] = goUser; #Username goes here. (required)
					        $postfields["goPass"] = goPass; #Password goes here. (required)
					        $postfields["goAction"] = "goGetCarrierInfo"; #action performed by the [[API:Functions]]. (required)
					        $postfields["responsetype"] = responsetype; #json. (required)
					        $postfields["carrier_id"] = $cid; #Desired exten ID. (required)

					         $ch = curl_init();
					         curl_setopt($ch, CURLOPT_URL, $url);
					         curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
					         curl_setopt($ch, CURLOPT_POST, 1);
					         curl_setopt($ch, CURLOPT_TIMEOUT, 100);
					         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					         curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
					         $data = curl_exec($ch);
					         curl_close($ch);
					         $output = json_decode($data);
					        
					        //var_dump($output); 

							if ($output->result=="success") {
								
							# Result was OK!
								for($i=0;$i<count($output->user_group);$i++){
						?>

						<legend>MODIFY CARRIER ID : <u><?php echo $cid;?></u></legend>
                    	
							<form id="modifycarrier">
								<input type="hidden" name="modifyid" value="<?php echo $cid;?>">
							
						<!-- Custom Tabs -->
						<div role="tabpanel">
						<!--<div class="nav-tabs-custom">-->
							<ul role="tablist" class="nav nav-tabs nav-justified">
								<li class="active"><a href="#tab_1" data-toggle="tab"> Basic Settings</a></li>
								<li><a href="#tab_2" data-toggle="tab"> Advanced Settings</a></li>
							</ul>
			               <!-- Tab panes-->
			               <div class="tab-content">

				               	<!-- BASIC SETTINGS -->
				                <div id="tab_1" class="tab-pane fade in active">
				                	<fieldset>
										<div class="form-group mt">
											<label for="carrier_name" class="col-sm-2 control-label">Carrier Name</label>
											<div class="col-sm-10 mb">
												<input type="text" class="form-control" name="carrier_name" id="carrier_name" placeholder="Carrier Name" value="<?php echo $output->carrier_name[$i];?>">
											</div>
										</div>
										<div class="form-group">
											<label for="carrier_description" class="col-sm-2 control-label">Carrier Description</label>
											<div class="col-sm-10 mb">
												<input type="text" class="form-control" name="carrier_description" id="carrier_description" placeholder="Carrier Description" value="<?php echo $output->carrier_description[$i];?>">
											</div>
										</div>
										<div class="form-group">
											<label for="carrier_desc" class="col-sm-2 control-label">Authentication</label>
											<div class="col-sm-10 mb">
												<div class="row mt">
													<label class="col-sm-1">
														&nbsp;
													</label>
													<label class="col-sm-2 radio-inline c-radio" for="auth_ip">
														<input id="auth_ip" type="radio" name="authentication" value="auth_ip" checked>
														<span class="fa fa-circle"></span> IP Based
													</label>
													<label class="col-sm-2 radio-inline c-radio" for="auth_reg">
														<input id="auth_reg" type="radio" name="authentication" value="auth_reg">
														<span class="fa fa-circle"></span> Registration
													</label>
												</div>
											</div>
										</div>
										<div class="form-group registration_div" style="display:none;">
											<label for="username" class="col-sm-2 control-label">Username</label>
											<div class="col-sm-10 mb">
												<input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo $output->username[$i];?>">
											</div>
										</div>
										<div class="form-group registration_div" style="display:none;">
											<label for="password" class="col-sm-2 control-label">Password</label>
											<div class="col-sm-10 mb">
												<input type="text" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo $output->password[$i];?>">
											</div>
										</div>
										<div class="form-group">
											<label for="server_ip" class="col-sm-2 control-label">Server IP/Host</label>
											<div class="col-sm-10 mb">
												<input type="text" class="form-control" name="server_ip" id="server_ip" placeholder="Server IP/Host" value="<?php echo $output->server_ip[$i];?>">
											</div>
										</div>
										<div class="form-group registration_div" style="display:none;">
											<label for="carrier_desc" class="col-sm-2 control-label">Port</label>
											<div class="col-sm-10 mb">
												<input type="text" class="form-control" name="carrier_desc" id="carrier_desc" placeholder="Carrier Description" value="<?php echo $output->carrier_description[$i];?>">
											</div>
										</div>
										<div class="form-group">
											<label for="carrier_desc" class="col-sm-2 control-label">Codecs</label>
											<div class="col-sm-10 mb">
												<div class="row mt">
													<label class="col-sm-1">
														&nbsp;
													</label>
													<label class="col-sm-2 checkbox-inline c-checkbox" for="gsm">
														<input type="checkbox" id="gsm" name="codecs" value="GSM" checked>
														<span class="fa fa-check"></span> GSM
													</label>
													<label class="col-sm-2 checkbox-inline c-checkbox" for="ulaw">
														<input type="checkbox" id="ulaw" name="codecs" value="ULAW" checked>
														<span class="fa fa-check"></span> ULAW
													</label>
													<label class="col-sm-2 checkbox-inline c-checkbox" for="alaw">
														<input type="checkbox" id="alaw" name="codecs" value="ALAW">
														<span class="fa fa-check"></span> ALAW
													</label>
													<label class="col-sm-2 checkbox-inline c-checkbox" for="g729">
														<input type="checkbox" id="g729" name="codecs" value="G729">
														<span class="fa fa-check"></span> G729
													</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="carrier_desc" class="col-sm-2 control-label">DTMF Mode</label>
											<div class="col-sm-10 mb">
												<div class="row mt">
													<label class="col-sm-1">
														&nbsp;
													</label>
													<label class="col-sm-2 radio-inline c-radio" for="dtmf_1">
														<input id="dtmf_1" type="radio" name="dtmf" value="RFC2833" checked>
														<span class="fa fa-circle"></span> RFC2833   
													</label>
													<label class="col-sm-2 radio-inline c-radio" for="dtmf_2">
														<input id="dtmf_2" type="radio" name="dtmf" value="inband">
														<span class="fa fa-circle"></span> Inband   
													</label>
													<label class="col-sm-2 radio-inline c-radio" for="dtmf_3">
														<input id="dtmf_3" type="radio" name="dtmf" value="custom">
														<span class="fa fa-circle"></span> Custom      
													</label>
													<span id="input_custom_dtmf" class="col-sm-4 mb" style="display:none;">
														<input type="text" class="form-control" name="custom_dtmf" placeholder="Enter Custom DTMF" >
													</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="protocol" class="col-sm-2 control-label">Protocol</label>
											<div class="col-sm-10 mb">
												<select class="form-control" name="protocol" id="protocol">
												<?php
													$protocol = NULL;
													if($output->protocol[$i] == "SIP"){
														$protocol .= '<option value="SIP" selected> SIP </option>';
													}else{
														$protocol .= '<option value="SIP" > SIP </option>';
													}
													
													if($output->protocol[$i] == "IAX2"){
														$protocol .= '<option value="IAX2" selected> IAX2 </option>';
													}else{
														$protocol .= '<option value="IAX2" > IAX2 </option>';
													}

													if($output->protocol[$i] == "CUSTOM"){
														$protocol .= '<option value="CUSTOM" selected> CUSTOM </option>';
													}else{
														$protocol .= '<option value="CUSTOM" > CUSTOM </option>';
													}
													echo $protocol;
												?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Active</label>
											<div class="col-sm-10 mb">
												<select class="form-control" name="active" id="active">
												<?php
													$active = NULL;
													if($output->active[$i] == "Y"){
														$active .= '<option value="Y" selected> YES </option>';
													}else{
														$active .= '<option value="Y" > YES </option>';
													}
													
													if($output->active[$i] == "N" || $output->active[$i] == NULL){
														$active .= '<option value="N" selected> NO </option>';
													}else{
														$active .= '<option value="N" > NO </option>';
													}
													echo $active;
												?>
												</select>
											</div>
										</div>
									</fieldset>
								</div><!-- tab 1 -->

								
								<!-- NOTIFICATIONS -->
			                    <div id="notifications">
			                        <div class="output-message-success" style="display:none;">
			                            <div class="alert alert-success alert-dismissible" role="alert">
			                              <strong>Success!</strong> Carrier ID <?php echo $cid?> modified !
			                            </div>
			                        </div>
			                        <div class="output-message-error" style="display:none;">
			                            <div class="alert alert-danger alert-dismissible" role="alert">
			                              <span id="modifyCarrierResult"></span>
			                            </div>
			                        </div>
			                    </div>

			                    <!-- FOOTER BUTTONS -->
			                    <fieldset class="footer-buttons">
			                        <div class="box-footer">
			                           <div class="col-sm-3 pull-right">
												<a href="settingscarriers.php" type="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancel </a>
			                           	
			                                	<button type="submit" class="btn btn-primary" id="modifyCarrierOkButton" href=""> <span id="update_button"><i class="fa fa-check"></i> Update</span></button>
											
			                           </div>
			                        </div>
			                    </fieldset>

				            	</div><!-- end of tab content -->
	                    	</div><!-- tab panel -->
	                    </form>
	                </div><!-- body -->
	            </div>
            </section>
					<?php
							}
						}	
                        
					?>
					
				<!-- /.content -->
            </aside><!-- /.right-side -->
			
			<?php //print $ui->creamyFooter(); ?>

        </div><!-- ./wrapper -->

  		
		
		<!-- Modal Dialogs -->
		<?php include_once "./php/ModalPasswordDialogs.php" ?>

		<script type="text/javascript">
			$(document).ready(function() {

			    /* on authorization change */
				$('input[type=radio][name=authentication]').on('change', function() {
				//  alert( this.value ); // or $(this).val()
					if(this.value == "auth_reg") {
					  $('.registration_div').show();
					}
					if(this.value == "auth_ip") {
					  $('.registration_div').hide();
					}
				});

				 /* on custom dtmf select */
				$('input[type=radio][name=dtmf]').on('change', function() {
				//  alert( this.value ); // or $(this).val()
					if(this.value == "custom") {
						$('#input_custom_dtmf').show();
					}else{
						$('#input_custom_dtmf').hide();
					}
				});

				/** 
				 * Modifies a telephony list
			 	 */
				$("#modifycarrier").validate({
                	submitHandler: function() {
						//submit the form
							$('#update_button').html("<i class='fa fa-edit'></i> Updating.....");
							$('#modifyCarrierOkButton').prop("disabled", true);

							$("#resultmessage").html();
							$("#resultmessage").fadeOut();
							$.post("./php/ModifyCarrier.php", //post
							$("#modifycarrier").serialize(), 
								function(data){
									//if message is sent
									if (data == 1) {
										$('.output-message-success').show().focus().delay(5000).fadeOut().queue(function(n){$(this).hide(); n();});
                                        window.setTimeout(function(){location.reload()},2000)
                                        $('#update_button').html("<i class='fa fa-check'></i> Update");
                                        $('#modifyCarrierOkButton').prop("disabled", false);
									} else {
										$('#modifyCarrierResult').show().focus().delay(5000).fadeOut().queue(function(n){$(this).hide(); n();});
									
									$('#update_button').html("<i class='fa fa-check'></i> Update");
									$('#modifyCarrierOkButton').prop("disabled", false);
									}
									//
								});
						return false; //don't let the form refresh the page...
					}					
				});
				
			});
		</script>

    </body>
</html>