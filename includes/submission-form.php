<?php
/************************************
* Front end submission form for Images
************************************/

function pig_submission_form()
{
	global $pig_base_dir, $current_user;
	
	
	get_currentuserinfo();
	$form = '';
	if( isset( $_GET['image-submitted'] ) && $_GET['image-submitted'] == 1 ) { 
		$form .= '<div class="image_submitted">Thanks! Your image as been added to the gallery. You may edit this image from your dashboard at any time. <a href="' . esc_url( get_permalink( $_GET['image-id'] ) ) . '" title="View Image">View Image</a>.</div>'; 
	}
	
	$form .= '	
	<script type="text/javascript">
		//<![CDATA[
		jQuery(function($){
			$("#pig_image_desc").focus(function() {
				if($(this).val() == "Describe your image") {
					$(this).val("");
				}
			});
			$("#pig_submit").click(function() {
				$(this).attr("disabled", "disabled");
				$("#pig_submission").submit();
			});
		});
		//]]> 
	</script>';
	
		// output form HTML
		$form .= '<form id="pig_submission" action="" method="POST" enctype="multipart/form-data">';
		if(is_user_logged_in()) {		
		
			$form .= '<fieldset>';
				$form .='<h3 class="reveal-modal-header">Upload Image</h3>';
				$form .= '<div>';
					$form .= '<label for="pig_image_name">Image Name</label>';
					$form .= '<input type="text" name="pig_image_name" id="pig_image_name"/>';
				$form .= '</div>';
				$form .= '<div>';
					$form .= '<label for="pig_image_desc">Image Description</label>';
					$form .= '<div><textarea name="pig_image_desc" id="pig_image_desc">Describe your image</textarea></div>';
				$form .= '</div>';
				
				$form .= '<div>';
					$form .= '<label for="pig_image_cat">Select the category that best fits your image</label>';
					$form .= '<div><select name="pig_image_cat" class="ignore" id="pig_image_cat">';
						$terms = get_terms('imagecategories', array('hide_empty' => false));
						foreach($terms as $term) {
							$form .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
						}
						$form .= '</select><br/></div>';
				$form .= '</div>';
				
				$form .= '<div>';
					$form .= '<label for="pig_image_status">The completion status of this image</label>';
					$form .= '<div><select name="pig_image_status" class="ignore" id="pig_image_status">';
						$form .= '<option value="in progress">In Progress</option>';
						$form .= '<option value="finished">Finished</option>';
					$form .= '</select></div>';
				$form .= '</div>';
				
				$form .= '<div>';
					$form .= '<label for="pig_image_file">Choose Image - <strong class="label red">Max file size: 1mb</strong></label>';
					$form .= '<div><input type="file" name="pig_image_file"/></div>';
				$form .= '</div>';
				
				$form .= '<div>';
					$form .= '<input type="hidden" name="pig_user_id" value="' . $current_user->ID . '"/>';
					$form .= '<input type="hidden" name="pig_post_parent_id" value="' . get_the_ID() . '"/>';
					$form .= '<input type="hidden" name="pig_post_parent_name" value="' . get_the_title(get_the_ID()) . '"/>';
					$form .= '<input type="hidden" name="pig_referrer" value="' . get_permalink(get_the_ID()) . '"/>';
					$form .= '<input type="hidden" name="pig_nonce" class="ignore" value="' . wp_create_nonce('pig-nonce') . '"/>';
					$form .= '<input type="submit" id="pig_submit" value="Upload Image"/>';
				$form .= '</div>';
			$form .= '</fieldset>';

		} else {
			$form .= '<p>You must be logged in to upload images. <a href="http://cgcookie.com/membership" title="Register">Register</a></p>';
		}
		$form .= '</form>';

	return $form;
}
add_shortcode('upload_image_form', 'pig_submission_form');


function pig_gallery_submission_form()
{
	global $pig_base_dir, $current_user;

	get_currentuserinfo();

	$form = '';
	
	if($_GET['image-submitted'] == 1) { 
		$form .= '<div class="image_submitted">Thanks! Your image as been added to the gallery. You may edit this image from your dashboard at any time. <a href="' . get_permalink($_GET['image-id']) . '" title="View Image">View Image</a>.</div>'; 
	}
	
	$form .= '	
	<script type="text/javascript">
		//<![CDATA[
		jQuery(function($){
			$.validator.addMethod("notEqual", function(value, element, param) {
			  return this.optional(element) || value != param;
			});
			
			$("#pig_image_desc").focus(function() {
				if($(this).val() == "Image Description") {
					$(this).val("");
				}
			});
			$(".pig_checkbox_wrapper a").click(function(e) {
				e.preventDefault();
				if($(this).hasClass("okay")) {
					$(this).removeClass("okay");
					$(this).parent().find("input").val(0);
				} else {
					$(this).addClass("okay");
					$(this).parent().find("input").val(1);
				}
			});
			$("#pig_gallery_submission").validate({
				ignore: ".ignore",
				debug: true,
				success: function(label) {
					label.addClass("valid");
				},
				rules: {
					pig_image_name: {
						required: true,
						maxlength: 55
					},
					pig_image_desc: {
						required: true,
						minlength: 15,
						maxlength: 1000,
						notEqual: "Image Description"
					},
					pig_image_file: {
						required: true
					},
					pig_agreement: {
						required: true,
						notEqual: 0
					}
				},
				submitHandler: function(form) {
					$("#pig_submit").attr("disabled", "disabled");
					form.submit();
				}
			});
		});
		//]]> 
	</script>';
	
		// output form HTML
		$form .= '<form id="pig_gallery_submission" action="" method="POST" enctype="multipart/form-data">';
		if(is_user_logged_in()) {		
			$form .= '<div id="gallery_tips">';
				$form .= '<h3>Gallery Upload Tips</h3>';
				$form .= '<ul>';
					$form .= '<li>JPG or PNG Recommended</li>';
					$form .= '<li>Image Width 1600PX or Less</li>';
					$form .= '<li>Max File size 1MB</li>';
					$form .= '<li>Image Name / Max 100 Characters</li>';
					$form .= '<li>The Work is of Your Own</li>';
					$form .= '<li>Avoid Obscenity</li>';
				$form .= '</ul>';
				$form .= '<p>When uploading to the gallery, be sure it meets the above requirements and is ready for feedback!</p>';
			$form .= '</div>';
			$form .= '<fieldset>';
				$form .= '<div>';
					$form .= '<input type="text" name="pig_image_name" id="pig_image_name" placeholder="Image Title"/>';
					$form .= '<label for="pig_image_name">Image Name</label>';
				$form .= '</div>';
				$form .= '<div>';
					$form .= '<div><textarea name="pig_image_desc" id="pig_image_desc">Image Description</textarea></div>';
					$form .= '<label for="pig_image_desc">What software was used, how did you make it. Things inquiring minds would want to know. Minimum of 15 characters</label>';
				$form .= '</div>';
				
				$form .= '<div>';
					$form .= '<div><select name="pig_image_cat" class="ignore" id="pig_image_cat">';
						$terms = get_terms('imagecategories', array('hide_empty' => false));
						foreach($terms as $term) {
							$form .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
						}
					$form .= '</select></div>';
					$form .= '<label for="pig_image_cat">Select the category that best fits your image</label>';
				$form .= '</div>';
				
				$form .= '<div>';
					$form .= '<div><select name="pig_image_status" class="ignore" id="pig_image_status">';
						$form .= '<option value="in progress">In Progress</option>';
						$form .= '<option value="finished">Finished</option>';
					$form .= '</select></div>';
					$form .= '<label for="pig_image_status">The completion status of this image</label>';
				$form .= '</div>';
				
				$form .= '<div>';
					$form .= '<div><input type="file" id="pig_image_file" name="pig_image_file"/></div>';
					$form .= '<label for="pig_image_file">.jpg or .png. Less that 1600px. <strong>Max file size: 1mb</strong></label>';
				$form .= '</div>';
				
				$form .= '<div>';
					$form .= '<div class="pig_checkbox_wrapper" id="pig_mature_box">';
						$form .= '<a href="#" id="pig_mature_link"></a>';
						$form .= '<label class="bold" for="pig_mature_link">Does this image contain mature content?</label>';
						$form .= '<input type="hidden" name="pig_mature" value=""/>';
					$form .= '</div>';					
					$form .= '<div class="pig_checkbox_wrapper" id="pig_user_agreement">';
						$form .= '<a href="#" id="pig_agreement_link"></a>';
						$form .= '<label class="bold" for="pig_agreement_link">Agree this image is of your creation and copyright?</label>';
						$form .= '<input type="hidden" name="pig_agreement" value=""/>';
					$form .= '</div>';
					$form .= '<div class="pig_checkbox_wrapper" id="pig_use_image">';
						$form .= '<a href="#" id="pig_okay_to_use_link"></a>';
						$form .= '<label class="bold" for="pig_okay_to_use_link">Is it okay for CG Cookie to use your image on promotional items on the site?<span>Site banners, header images, etc...</span></label>';
						$form .= '<input type="hidden" name="pig_okay_to_use" value=""/>';
					$form .= '</div>';
					
				$form .= '</div>';
				
				$form .= '<div>';
					$form .= '<input type="hidden" name="pig_user_id" class="ignore" value="' . $current_user->ID . '"/>';
					$form .= '<input type="hidden" name="pig_referrer" class="ignore" value="' . get_permalink(get_the_ID()) . '"/>';
					$form .= '<input type="hidden" name="pig_nonce" class="ignore" value="' . wp_create_nonce('pig-nonce') . '"/>';
					$form .= '<input type="submit" id="pig_submit" name="pig_submit" value="Upload Image"/>';
					$form .= '<a href="' . home_url() . '/gallery" id="pig_cancel">Cancel</a>';
				$form .= '</div>';
				
			$form .= '</fieldset>';

		} else {
			$form .= '<p>You must be logged in to upload images. <a href="#login-modal" name="modal">Login</a> or <a href="http://cgcookie.com/membership" title="Register">Register</a></p>';
		}
		$form .= '</form>';

	return $form;
}
add_shortcode('upload_gallery_image_form', 'pig_gallery_submission_form');
