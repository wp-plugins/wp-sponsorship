<?php
//this class is what the shortcode displays on the page, the function 'layout' takes care of all the front end display
//EDIT: added esc_html() when data is displayed to user

class b5wps_sponsorship_shortcode
{
	private $mail_service;

	private $sender_address;

	private $contact_array;

	private $custom_fields;

	private $activated_plugins;

	private $sponsor_code;

	private $user_ID;

	private $code_table;

	public function __construct($custom)
	{
		global $wpdb;
		$this->mail_service = $param['email_provider'];
		$this->custom_fields = $custom;
		//init array containing activated plugins
		if(class_exists( 'b5wpsc_email_send_code' )) $this->code_table = $wpdb->prefix . 'sponsor_codes';
		else if(class_exists( 'b5wpsw_email_send_woo' )) $this->code_table = $wpdb->prefix . 'woo_codes';
		if(class_exists( 'b5wpsw_email_send_woo' ) || class_exists( 'b5wpsc_email_send_code' ))
		{
			$this->user_ID = get_current_user_id();
			$this->is_code_set();
      $_SESSION['CODE'] = $this->sponsor_code;
		}

		//if((class_exists( 'b5wpsc_email_send_code' ) || class_exists( 'b5wpsw_email_send_woo' )) && !get_current_user_id())	echo '<p>You are not a User on this site, Register now!</p>';
		//else
		 $this->user_layout();
	}

	public function is_code_set()
	{
		if($this->user_ID)
		{
			global $wpdb;
			$dbcode = $wpdb->get_row("SELECT CODE FROM $this->code_table WHERE User_ID =" . $this->user_ID );
			$code_prop= $dbcode->CODE;
			$this->sponsor_code = $code_prop;
			if (is_null($this->sponsor_code)) $this->sponsor_code = $this->generate_code();
		}
	}

	public function generate_code()
	{
		global $wpdb;
		$time = time();
		$index = 6 - strlen(get_current_user_id());
		$cut_string = substr($time, strlen($time) - $index,strlen($time));
		$decimal = $this->user_ID . "" . $cut_string;
		$hexadecimal = dechex($decimal);
		$wpdb->insert(
				$this->code_table ,array(
					'User_ID' => get_current_user_id(),
					'CODE' => $hexadecimal,
					'time_generated' => date('Y-m-d H:i:s',time())
				));
		return $hexadecimal;
	}



	public function user_layout()
	{
		?>
		<div id="fb-root"></div>

    <?php
		if(isset($_GET['send_variable']))
		{
			echo "<h3 style='color:green'><span class=\"dashicons dashicons-yes\"></span>" . __('Invitations Sent!') ."</h3>";
		}

		?>

		<div class="wps_step_1">
		<h3 class="wps_title"><u><?php _e('Step 1: Add your Information', 'sponsorship') ?></u></h3>
				<?php
					if(class_exists( 'b5wpsc_email_send_code' ) || class_exists( 'b5wpsw_email_send_woo' ))
					{
						global $wpdb;
						$user_code = $wpdb->get_row("SELECT CODE FROM $this->code_table WHERE User_ID ='" . get_current_user_id() . "'");
						$this->sponsor_code = $user_code->CODE;
						$_SESSION['CODE'] = $this->sponsor_code;
						$url = get_option('code_settings');
            $link = "" . $url['registration_url'] . "?code=" . $this->sponsor_code;
						?>
						<h3>Your Code: <span style='color:red'><?php echo $this->sponsor_code?></span></h3>
            <ul class="wps_sharing-buttons">
							<li>Share code with:&nbsp</li>
              <li>
                <a class="wps_facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $link?>" target="_blank"><span class="dashicons dashicons-facebook fa"></span></i>Facebook</a>
              </li>
              <li>
                <a class="wps_twitter" href="https://twitter.com/intent/tweet?url=<?php echo urlencode($link)?>&text=<?php echo urlencode($url['share_title'])?>.&via=<?php echo $url['comp_name']?>" target="_blank"><span class="dashicons dashicons-twitter fa"></span></i>Tweet</a>
              </li>
              <li>
                <a class="wps_google-plus" href="https://plus.google.com/share?url=<?php echo urlencode($link)?>" target="_blank"><span class="dashicons dashicons-googleplus fa"></span></i>Google+</a>
              </li>
            </ul>


						<?php
					}
          //the following is the form for the sender's information
				?>

				<form method="post">
					<label for="send_email_id"><?php _e('Your E-mail Address','sponsorship');?></label></br>
					<input type='email' id = "send_email_id"class="wps_input" name='sender_email' <?php echo (isset($_SESSION['sender_email'])? "value='". esc_html($_SESSION['sender_email']) ."'": "") ?> style="margin-bottom: 20px"></br>
					<label for="send_name_id" style="margin-top: 20px"><?php _e(" Your Name", 'sponsorship');?></label></br>
					<input type='text' id = "send_name_id"class="wps_input" name='sender_name' <?php echo (isset($_SESSION['sender_name'])? "value='". esc_html($_SESSION['sender_name']) ."'": "") ?>  style="margin-bottom: 20px"></br>
					<label for="send_subject_id" style="margin-top: 20px"><?php _e('E-mail Subject','sponsorship');?></label></br>
					<input type='text' id="send_subject_id"class="wps_input" name='sender_subject' <?php echo (isset($_SESSION['sender_subject'])? "value='". esc_html($_SESSION['sender_subject']) ."'": "") ?>   style="margin-bottom: 20px"></br>
					<?php //this simply displays wether an error occured or not
						echo ((isset($_SESSION['sender']) && $_SESSION['sender']=='error')? "<p style='color: red'>".__("please fill in all fields", 'sponsorship')."</p>": "");
					  echo ((isset($_SESSION['sender']) && $_SESSION['sender']=='yes')? "<p style='color: green'>".__("submission succesful",'sponsorship')."</p>": "");
					  echo ((isset($_SESSION['sender']) && $_SESSION['sender']=='invalid')? "<p style='color: red'>".__("Invalid Input: only letters and numbers allowed", 'sponsorship')."</p>": "");?>
					<input type='submit' id='target_scroll' <?php echo (isset($this->custom_fields['sender_submit'])? "value='". esc_html($this->custom_fields['sender_submit']) ."'": "") ?> style="margin-top: 10px; margin-bottom: 20px;">
				</form>
		</div>
		<?php
		//If statement to check if the user has completed step one
		//same as before, displays form for sender with appropriate error messages
		if(isset($_SESSION['sender_email']) && isset($_SESSION['sender_name']) && isset($_SESSION['sender_subject']))
			{?>
			<div class="wps_step_2">
				<h3 class="wps_title" style="margin-top: 30px;"><u><?php _e('Step 2: Add your Contacts', 'Sponsorship')?></u></h3>
        <?php  if (class_exists( 'gmail_config' )){
          $client_settings = get_option('gmail_settings');
          ?>
        <ul class="wps_sharing-buttons">
          <li><a class="wps_google-plus" href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo $client_settings['client_id']?>&redirect_uri=<?php echo $client_settings['redirect_url']?>&scope=https://www.google.com/m8/feeds/&response_type=code"><span class="dashicons dashicons-googleplus"></span>Import Gmail Contacts</a></li>
        </ul>
        <?php } ?>

				<form method="post">
					<label for="contact_name_id" style="margin-top: 20px"><?php _e('Name','sponsorship')?></label></br>
					<input type="text" id="contact_name_id"class="wps_input" name="contact_name"  style="margin-bottom: 20px" ></br>
					<label for="contact_email_id" style="margin-top: 20px"><?php _e('E-Mail', 'sponsorship')?></label></br>
					<input type = "email" id="contact_email_id"class="wps_input" name="contact_mail"  style="margin-bottom: 20px"></br>
					<?php echo ((isset($_SESSION['contact']) && $_SESSION['contact']=='error')? "<p style='color: red'>" .__("please fill in all fields", 'sponsorship')."</p>": "");
					      echo ((isset($_SESSION['contact']) && $_SESSION['contact']=='yes')? "<p style='color: green'>" . __("submission succesful",'sponsorship')."</p>": "");
					      echo ((isset($_GET['yes']) && $_GET['yes']=='delete')? "<p style='color: green'>" .__("deletion succesful", 'sponsorship')."</p>": "");
					      echo ((isset($_SESSION['contact']) && $_SESSION['contact']=='invalid')? "<p style='color: red'>" . __("Invlaid input: only letters and `numbers allowed",'sponsorship') ."</p>": "");
					?>
					<input type = "submit" <?php echo (isset($this->custom_fields['contact_submit'])? "value='". esc_html($this->custom_fields['contact_submit']) ."'": "") ?> style="margin-top:20px;margin-bottom: 20px">
				</form>
			</div>
		<?php } ?>
			<div>
				<form method="post">
				<table style="margin-top:50px">
				<?php

				if(count($_SESSION['contact_array'])) echo "<thead><th><input type=\"checkbox\" id=\"selectall\"></th><th>".__("Name", 'sponsorship')."</th><th>".__("E-mail", 'sponsorship')."</th></thead>";
					$count= 0;
					//here i handle the delete GET variables as well insure what i am displaying is secure
					foreach($_SESSION['contact_array'] as $contact)
					{
						$checkbox_name = 'delete_'. $count;
						$secure_name = esc_html($contact[0]);
						$secure_email = esc_html($contact[1]);
						?>
							<tr><td><input type='checkbox' value='yes' name=<?php print $checkbox_name; ?>></td><td><?php print $secure_name; ?></td><td><?php print $secure_email; ?></td></tr>
						<?php
						$count++;
					}
					?>
						</table>
						<?php if(count($_SESSION['contact_array'])) echo "<input type='submit' value=" . __("Delete Selected items", 'sponsorship') . ">";?>
						</form>
					</div>
					<?php
				if(count($_SESSION['contact_array']))
				{?>
				<form method="post">
					<h3 class="wps_title"><u><?php _e('Step 3: Send Emails', 'sponsorship')?></u></h3>
					<input type='hidden' name='send_variable' <?php echo "value =" . get_current_user_id()?>>
					<input type = 'submit' <?php echo (isset($this->custom_fields['email_submit'])? "value='". esc_html($this->custom_fields['email_submit']) ."'": "") ?> style="margin-top: 20px">
				</form>

				<?php
		  }
	}
}

add_shortcode('Sponsorship', 'b5wpsc_shortcode');

function b5wpsc_shortcode($atts)
{
	$options = get_option('admin_page');

	if($options['email_provider'] == "1") :
		if( null==get_option('port_settings') || null==get_option('admin_page')) :
			echo "Settings not configured! Admin must set up application";
		else :
			new b5wps_sponsorship_shortcode($atts);
		endif;
	else :
		new b5wps_sponsorship_shortcode($atts);
	endif;
}
