<?php

class b5wps_extensions_page
{
  private $slug = 'extensions_page';

  private $title;

  public function __construct()
  {
    $this->title = 'Extensions';
    $this->hooks();
  }

  public function hooks()
  {
    add_action( 'admin_menu', array( $this, 'init_page' ) );
    add_action( 'admin_init', array( $this, 'init' ) );
  }

  public function init()
  {
    register_setting( $this->slug, $this->slug );
  }

  public function init_page()
  {
    add_submenu_page('admin_page', $this->title,  $this->title , 'manage_options', $this->slug, array($this, 'extensions_style'));
  }

  public function extensions_style()
  {
    ?>
    <div class="emimen">
      <h1 class="rtfd">Extensions for WPSponsorship</h1>
      <div class="iframe_2pac"><iframe class="wp_iframe" src="http://www.wp-inbound.com/iframe"></iframe></div>
    </div>
    <?php
  }
}

new b5wps_extensions_page();

?>
