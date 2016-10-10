<?php

	###################################################
	### Name: editsettingsvoicemail.php 			###
	### Functions: Edit Voicemail 			 		###
	### Copyright: GOAutoDial Ltd. (c) 2011-2016	###
	### Version: 4.0 								###
	### Written by: Alexander Jim H. Abenoja		###
	### License: AGPLv2								###
	###################################################

	require_once('./php/CRMDefaults.php');
	require_once('./php/UIHandler.php');
	require_once('./php/LanguageHandler.php');
	require('./php/Session.php');
	require_once('./php/goCRMAPISettings.php');

	// initialize structures
	$ui = \creamy\UIHandler::getInstance();
	$lh = \creamy\LanguageHandler::getInstance();
	$user = \creamy\CreamyUser::currentUser();

$vmid = NULL;
if (isset($_POST["vmid"])) {
	$vmid = $_POST["vmid"];
}else{
	header("location: settingsvoicemails.php");
}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Voicemail</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
       	
       	<?php print $ui->standardizedThemeCSS(); ?> 

        <?php print $ui->creamyThemeCSS(); ?>

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
                        <?php $lh->translateText("Settings"); ?>
                        <small><?php $lh->translateText("Voice Mail Edit"); ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="./index.php"><i class="fa fa-edit"></i> <?php $lh->translateText("home"); ?></a></li>
                        <li> <?php $lh->translateText("settings"); ?></li>
                        <?php
							if(isset($_POST["vmid"])){
						?>	
							<li><a href="./settingsvoicemails.php"><?php $lh->translateText("Voicemail"); ?></a></li>
                        <?php
							}
                        ?>	                    
                        <li class="active"><?php $lh->translateText("modify"); ?></li>
                    </ol>
                </section>

                
					<!-- standard custom edition form -->
					<?php
					$errormessage = NULL;
					
					//if(isset($extenid)) {
						$url = gourl."/goVoicemails/goAPI.php"; #URL to GoAutoDial API. (required)
				        $postfields["goUser"] = goUser; #Username goes here. (required)
				        $postfields["goPass"] = goPass; #Password goes here. (required)
				        $postfields["goAction"] = "getVoicemailInfo"; #action performed by the [[API:Functions]]. (required)
				        $postfields["responsetype"] = responsetype; #json. (required)
				        $postfields["voicemail_id"] = $vmid; #Desired exten ID. (required)

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

				        //var_dump($data);

						if ($output->result=="success") {
							
						# Result was OK!
							for($i=0;$i<count($output->voicemail_id);$i++){
					?>
                <!-- Main content -->
                <section class="content">
					<div class="panel panel-default">
                    
                    <div class="panel-body">
						<legend>MODIFY VOICEMAIL ID: <u><?php echo $output->voicemail_id[$i];?></u></legend>
	
							<form id="modifyform">
								<input type="hidden" name="modifyid" value="<?php echo $vmid;?>">
							
						<!-- Custom Tabs -->
						<div role="tabpanel">
						<!--<div class="nav-tabs-custom">-->
							<ul role="tablist" class="nav nav-tabs nav-justified">
								<li class="active"><a href="#tab_1" data-toggle="tab"> Basic Settings</a></li>
							</ul>
			               <!-- Tab panes-->
			               <div class="tab-content">

				               	<!-- BASIC SETTINGS -->
				                <div id="tab_1" class="tab-pane fade in active">
				                	<fieldset>
										<div class="form-group mt">
											<label for="password" class="col-sm-3 control-label">Your Password</label>
											<div class="col-sm-9 mb">
												<input type="text" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo $output->password[$i];?>">
											</div>
										</div>
										<div class="form-group">
											<label for="fullname" class="col-sm-3 control-label">Name</label>
											<div class="col-sm-9 mb">
												<input type="text" class="form-control" name="fullname" id="fullname" value="<?php echo $output->fullname[$i];?>">
											</div>
										</div>
										<div class="form-group">
											<label for="email" class="col-sm-3 control-label">Email</label>
											<div class="col-sm-9 mb">
												<input type="text" class="form-control" name="email" id="email" value="<?php echo $output->email[$i];?>">
											</div>
										</div>
									
										<div class="form-group">
											<label for="active" class="col-sm-3 control-label">Active</label>
											<div class="col-sm-9 mb">
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
										<div class="form-group">
											<label for="delete_vm_after_email" class="col-sm-3 control-label">Delete Voicemail After Email</label>
											<div class="col-sm-9 mb">
												<select class="form-control" name="delete_vm_after_email" id="delete_vm_after_email">
												<?php
													$delete_vm_after_email = NULL;
													if($output->delete_vm_after_email[$i] == "Y"){
														$delete_vm_after_email .= '<option value="Y" selected> YES </option>';
													}else{
														$delete_vm_after_email .= '<option value="Y" > YES </option>';
													}
													
													if($output->delete_vm_after_email[$i] == "N" || $output->delete_vm_after_email[$i] == NULL){
														$delete_vm_after_email .= '<option value="N" selected> NO </option>';
													}else{
														$delete_vm_after_email .= '<option value="N" > NO </option>';
													}
													echo $delete_vm_after_email;
												?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">New Messages: </label>
												<span style="padding-left:20px; font-size: 20;"><?php echo $output->messages[$i];?></span>
											
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Old Messages: </label>
												<span style="padding-left:20px; font-size: 20;"><?php echo $output->old_messages[$i];?></span>
											
										</div>
									</fieldset>
								</div><!-- end tab1 -->

						<!-- FOOTER BUTTONS -->
		                    <fieldset class="footer-buttons">
		                        <div class="box-footer">
		                           <div class="col-sm-3 pull-right">
											<a href="settingsvoicemails.php" type="button" id="cancel" class="btn btn-danger"><i class="fa fa-close"></i> Cancel </a>
		                           	
		                                	<button type="submit" class="btn btn-primary" id="modifyVoicemailOkButton" href=""> <span id="update_button"><i class="fa fa-check"></i> Update</span></button>
										
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
        </div><!-- ./wrapper -->

		<!-- Modal Dialogs -->
		<?php print $ui->standardizedThemeJS(); ?> 
		<?php include_once "./php/ModalPasswordDialogs.php" ?>
		

		<script type="text/javascript">
			$(document).ready(function() {
				
				// for cancelling
				$(document).on('click', '#cancel', function(){
					swal("Cancelled", "No action has been done :)", "error");
				});

				/** 
				 * Modifies a telephony list
			 	 */
				$("#modifyform").validate({
                	submitHandler: function() {
						//submit the form
						
							$('#update_button').html("<i class='fa fa-edit'></i> Updating.....");
							$('#modifyVoicemailOkButton').prop("disabled", true);

							$("#resultmessage").html();
							$("#resultmessage").fadeOut();
							$.post("./php/ModifySettingsVoicemail.php", //post
							$("#modifyform").serialize(), 
								function(data){
									//if message is sent
									if (data == 1) {
										swal("Success!", "Voicemail Successfully Updated!", "success");
                                        window.setTimeout(function(){location.replace("settingsvoicemails.php")},2000)
                                        $('#update_button').html("<i class='fa fa-check'></i> Update");
                                        $('#modifyVoicemailOkButton').prop("disabled", false);
									} else {
										sweetAlert("Oops...", "Something went wrong! "+data, "error");
										$('#update_button').html("<i class='fa fa-check'></i> Update");
										$('#modifyVoicemailOkButton').prop("disabled", false);
									}
									//
								});
						return false; //don't let the form refresh the page...
					}					
				});
				
				 
			});
		</script>

		<?php print $ui->getRightSidebar($user->getUserId(), $user->getUserName(), $user->getUserAvatar()); ?>
		<?php print $ui->creamyFooter(); ?>
    </body>
</html>
