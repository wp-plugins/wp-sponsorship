<?php

	class b5wps_sponsorship_admin_page {
	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */

	//key for the admin page, handles meta data for the "mail format" page
	private $key = 'admin_page';
	//array of values stored in meta from admin page
	private $settings;
	/**
 	 * Options page metabox id
 	 * @var string
 	 */

	//id for the metabox on the admin page
	private $metabox_id = 'myprefix_option_metabox';
	//id for metabox on user page
	private $mail_settings_meta;

	/**
	 * Options Page title
	 * @var string
	 */
	//variable for the title of the menu
	protected $title;
	/**
	 * Options Page hook
	 * @var string
	 */

	//variables to hold the page objects, need them for the metaboxes
	protected $options_page = '';

	protected $settings_page = '';

	//keys for the settings page

	private $port_key='port_settings';

	private $port_metabox_id='port_metabox';

	//variables to hold the error messages

	private $connection_message;

	private $email_body_warning;
	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct()
  {
		// initialize variables for pages
		$this->settings = get_option('admin_page');
		$this->mail_settings_meta = get_option($this->port_key);
		$this->title = __('Sponsorship', 'sponsorship');
		//this initializes the display
		$this->hooks();
	}
	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks()
  {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_init', array( $this, 'add_options_page_metabox' ) );
		add_action('cmb2_init', array($this, 'add_mail_settings_metabox'));
	}
	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init()
	{
		register_setting( $this->key, $this->key );
		register_setting( $this->port_key, $this->port_key );
	}
	/**
	 * Add menu options page
	 * @since 0.1.0
	 */



	public function add_options_page()
	{
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
		add_submenu_page($this->key, 'Mail', __('Mail Format', 'sponsorship'), 'manage_options', $this->key);
		$this->settings_page = add_submenu_page($this->key, "Mail Settings", 'Mail Settings', 'manage_options', $this->port_key, array($this, 'mail_settings_style'));
		// Include CMB CSS in the head to avoid FOUT
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
		add_action( "admin_print_styles-{$this->settings_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}
	//this function chooses the correct dsiplay for the "Mail Settings" page upon which mail provider is selected
	public function mail_settings_style()
	{
		/*if ($this->settings['email_provider']=='1')
		{*/
			echo "<h1>You are using SMTP Mail</h1>";
			?>
		<div class="wrap cmb2-options-page <?php echo $this->port_key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->port_metabox_id, $this->port_key, array( 'cmb_styles' => false ) ); ?>
		</div>
		<?php
	//}

		/*else if ($this->settings['email_provider']=='2')
		{
			echo "<h1>No Settings needed when using PHP Mail!</h1>";

		}

		else
		{
			echo "<h1>No Settings needed when using Wordpress Mail!</h1>";
		}*/
	}
	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {


		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key, array( 'cmb_styles' => false ) ); ?>
		</div>

		<?php



	}
	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	//metaboxes for SMTP settings
	public function add_mail_settings_metabox()
	{
		$cmb = new_cmb2_box( array(
			'id'      => $this->port_metabox_id,
			'hookup'  => false,
			'show_names' => true,
			'show_on' => array(
				'key'   => 'options-page',
				'value' => array( $this->port_key, )
			),
		) );

		//if ($this->settings['email_provider']=='1') //SMTP settings
		//{


			$cmb->add_field( array(
		    'name'    => __('Host', 'sponsorship'),
		    'desc'    => __('Server in which the email will be sent', 'sponsorship'),
		    'id'      => 'host_name_smtp',
		    'type'    => 'text'
			) );

			$cmb->add_field( array(
		    'name'    => __('Port Number','sponsorship'),
		    'desc'    => __('Port in which to send SMTP', 'sponsorship'),
		    'default' => '25',
		    'id'      => 'port_number_smtp',
		    'type'    => 'text'
			) );

			$cmb->add_field( array(
		    'name'    => __('User Name', 'sponsorship'),
		    'desc'    => __('User Name for your server that will send the mail', 'sponsorship'),
		    'id'      => 'user_name_smtp',
		    'type'    => 'text'
			) );


			$cmb->add_field( array(
		    'name'    => __('Password', 'sponsorship'),
		    'desc'    => __('Password for your server that will send the mail', 'sponsorship'),
		    'id'      => 'password_smtp',
		    'type'    => 'text'
			) );

			$cmb->add_field( array(
	    	'name'    => __('Choose Encryption Type',  'sponsorship'),
	    	'id'      => 'encryption_smtp',
	    	'type'    => 'select',
	    	'options' => array(
		        '0' => __( 'No encryption', 'sponsorship' ),
		        'SSL'   => __( 'SSL', 'sponsorship' ),
		        'TLS'     => __( 'TLS', 'sponsorship' ),
		    ),
		) );

			$cmb->add_field( array(
		    'name' => __('Test Connection', 'sponsorship'),
		    'desc' => __('If settings are correct, you will recieve an email at the "sender" address from the Mail format page', 'sponsorship'),
		    'id'   => 'test_connection',
		    'type' => 'checkbox'
			) );


		//}

	}
	function add_options_page_metabox() {

		echo $this->connection_message;
		$cmb = new_cmb2_box( array(
			'id'      => $this->metabox_id,
			'hookup'  => false,
			'show_on' => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );
		// Set our CMB2 fields

		$cmb->add_field( array(
		    'name'    => __('Sender Email:', 'sponsorship'),
		    'desc'    => __('Email address for users to reply to', 'sponsorship'),
		    'id'      => 'sender_address',
		    'type'    => 'text'
			) );
		echo $this->email_body_warning;
		$cmb->add_field( array(
			'name' => __( 'Email Body', 'sponsorship' ),
			'desc' => __( 'Save when done editing', 'sponsorship' ),
			'id'   => 'email_body',
			'type' => 'wysiwyg',
		) );



		/*$cmb->add_field( array(
	    	'name'    => __('Choose Email Service', 'sponsorship'),
	    	'id'      => 'email_provider',
	    	'type'    => 'radio_inline',
	    	'options' => array(
		        '0' => __( 'Wordpress Mail', 'sponsorship' ),
		        '1'   => __( 'SMTP', 'sponsorship' ),
		        '2'     => __( 'PHP Mail', 'sponsorship' ),
		    ),
		) );*/
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve data from backend
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}
		throw new Exception( 'Invalid property: ' . $field );
	}

}

new b5wps_sponsorship_admin_page();
