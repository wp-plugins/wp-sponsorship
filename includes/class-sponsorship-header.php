<?php
/**
*
*This File handles the init and Header hooks and accordingly handles $_SESSION and $_POST variables for each page
*
*/
add_action('plugins_loaded', 'b5wps_translate_sponsorship');
add_action('admin_notices', 'b5wps_admin_messages');
add_action('init', 'b5wps_my_start_session', 1);
add_action( 'after_setup_theme', 'b5wps_theme_setup' );


//Removed <link> altogether, wrote it before I realized Wordpress had all the icons I needed.
function b5wps_translate_sponsorship()
{
  load_plugin_textdomain('sponsorship', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

function b5wps_theme_setup() {
    add_image_size('wps_logo_email_size', 200, 100);
}

function b5wps_admin_messages()
{
  global $post;
  if(isset($_POST['object_id']))
  {
    ?>
      <div class="updated"><p><?php _e('Settings Saved', 'sponsorship')?></p></div>
    <?php
  }

  if(isset($_POST['test_connection']) && $_POST['test_connection'] == 'on')
  {
    $settings=get_option('port_settings');
    $admin_page = get_option('admin_page');
    try
    {
      $transport = Swift_SmtpTransport::newInstance()
        ->setHost($_POST['host_name_smtp'])
        ->setPort($_POST['port_number_smtp'])
        ->setUsername($_POST['user_name_smtp'])
        ->setPassword($_POST['password_smtp']);
      if ($_POST['encryption_smtp']) ($_POST['encryption_smtp']=='SSL') ? $transport->setEncryption('ssl') : $transport->setEncryption('tls') ;
      $mailer = Swift_Mailer::newInstance($transport);
      $message = Swift_Message::newInstance('Connection Test')
        ->setContentType("text/html")
        ->setFrom($admin_page['sender_address'])
        ->setTo($admin_page['sender_address'])
        ->setBody('DO NOT REPLY TEST');
      $numSent = $mailer->send($message);
    }

    catch(Exception $e)
    {
      //do nothing, just to prevent site from crashing
    }

    if(is_null($e))
    {
      ?>
        <div class="updated"><p><strong><span class="dashicons dashicons-yes"></span><?php _e('E-mail Sent Succesfully, check inbox for confirmation', 'sponsorship')?></strong></p></div>
      <?php
    }

    else
    {
      ?>
        <div class="error"><p><span class="dashicons dashicons-no"></span><strong><?php _e('E-mail settings are incorrect, the following errors were generated', 'sponsorship')?>:</strong> <?php print $e->getmessage(); ?> </p></div>
      <?php
    }
  }
}

function b5wps_my_start_session()
{
  	ob_start();
    if(!session_id()) session_start();

    global $post;
    global $wpdb;
    //regex pattern used to ensure security of inputs
    $regex = "/^[a-zàâçéèêëîïôûùüÿñæœ! .-]*$/i";

    if(isset($_SESSION['sender'])) unset($_SESSION['sender']);
    if(isset($_SESSION['contact'])) unset($_SESSION['contact']);

    $contact_array = array();
    if(!isset($_SESSION['contact_array'])) $_SESSION['contact_array'] = array();
		else $contact_array = $_SESSION['contact_array'];
		$temp_array = $_SESSION['contact_array'];
		$index = 0;
		foreach ($_SESSION['contact_array'] as $temp)
    {
			$target = 'delete_'. $index;
			if(isset($_POST[$target]))
			{
				unset($temp_array[$index]);
        unset($_POST[$target]);
			}
			$index++;
		}
		$_SESSION['contact_array'] =  array_values($temp_array);

    if(isset($_POST['contact_mail']) && isset($_POST['contact_name']))
		{
			if(empty($_POST['contact_mail']) || empty($_POST['contact_name'])) $_SESSION['contact']= 'error';

			else if(!preg_match($regex, $_POST['contact_name'], $output) || !filter_var($_POST['contact_mail'], FILTER_VALIDATE_EMAIL)) $_SESSION['contact']='invalid';

			//if its OK insert it into session variable
			else
			{
        $_SESSION['contact'] = 'yes';
        //double check security, correction after rejection from wordpress
        $contact_name = sanitize_text_field( $_POST['contact_name'] );
        $contact_mail = sanitize_email( $_POST['contact_mail'] );
				$new_contact =  array( $contact_name, $contact_mail);
				array_push($contact_array, $new_contact);
				$_SESSION['contact_array'] = $contact_array;
			}
		}
    if(isset($_POST['sender_email']) AND isset($_POST['sender_name']) AND isset($_POST['sender_subject']))
    {
      //added sanitization calls here as well
    	if(!empty($_POST['sender_email']) && (filter_var($_POST['sender_email'], FILTER_VALIDATE_EMAIL)==$_POST['sender_email'])) $_SESSION['sender_email'] = sanitize_email($_POST['sender_email']);
    	if(!empty($_POST['sender_name']) && preg_match($regex, $_POST['sender_name'], $output)) $_SESSION['sender_name'] = sanitize_text_field($_POST['sender_name']);
    	if(!empty($_POST['sender_subject']) && preg_match($regex,$_POST['sender_subject'], $output2)) $_SESSION['sender_subject'] = sanitize_text_field($_POST['sender_subject']);
    	if(empty($_POST['sender_email']) || empty($_POST['sender_name']) || empty($_POST['sender_subject'])) $_SESSION['sender'] = 'error';
			else if(!filter_var($_POST['sender_email'], FILTER_VALIDATE_EMAIL) || !preg_match($regex, $_POST['sender_name']) || !preg_match($regex,$_POST['sender_subject'])) $_SESSION['sender'] = 'invalid';
      else $_SESSION['sender'] = 'yes';
    }
    if(isset($_POST['send_variable']) && isset($_SESSION['sender_email']))
    {
      //Based this from wordpress codex in link provided
      $inv_sender_ID = intval($_POST['send_variable']);
      if(!$inv_sender_ID) $inv_sender_ID='';

      new b5wps_send_mail($_SESSION['sender_email'],$_SESSION['sender_name'],$_SESSION['sender_subject']);
      $time = date('Y-m-d H:i:s',time());
    	$wpdb->insert(
				$wpdb->prefix . 'email_sender',
				array(
					'ID' =>    $inv_sender_ID,
					'name' =>  $_SESSION['sender_name'],
					'email' => $_SESSION['sender_email'],
					'time' =>  $time,
				));
      $email_table = $wpdb->prefix . 'email_sender';
      $parent_ID = $wpdb->get_row("SELECT ID FROM $email_table WHERE name='". $_SESSION['sender_name'] ."'");
			foreach($_SESSION['contact_array'] as $contact)
			{
				$wpdb->insert(
  				$wpdb->prefix . 'email_contacts',
  				array(
  					'name' => $contact[0],
  					'email' => $contact[1],
  					'parent_ID' => $parent_ID->ID,
            'time_sent' => $time = date('Y-m-d H:i:s',time()),
  				));
			}

      session_destroy();
      $location = get_permalink($post->ID);
      wp_safe_redirect($location . '?send_variable=yes', '302');
      exit;
    }
}

?>
