<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       #
 * @since      1.0.1
 *
 */

class b5wps_mail_message
{
  private $email_settings;

  public $content;

  public function __construct()
  {
    $this->email_settings = get_option('admin_page');
    $this->email_settings['email_body'] = str_replace('{{CODE}}', $_SESSION['CODE'], $this->email_settings['email_body']);
    $this->email_settings['email_body'] = str_replace('{{Name}}', $_SESSION['sender_name'], $this->email_settings['email_body']);
    $this->generate_template();
  }

  public function pippin_get_image_id($image_url)
  {
  	global $wpdb;
  	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
    return $attachment[0];
  }

  public function generate_template()
  {
    //retrieve logo image
    if(!empty($this->email_settings['wps_logo_email']))
    {
      $image_url=$this->email_settings['wps_logo_email'];
      $image_id=$this->pippin_get_image_id($image_url);
      $images=wp_get_attachment_image( $image_id , 'wps_logo_email_size');
      $image_thumb = wp_get_attachment_image_src($image_id, 'wps_logo_email_size');
    }
    //E-mail html Header
    $header='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
    $header.='<html xmlns="http://www.w3.org/1999/xhtml">';
    $header.='<head>';
    $header.='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    $header.='<meta name="viewport" content="width=device-width"/>';

    //Import Styling
    $css = file_get_contents(plugin_dir_path(dirname(__FILE__)) .'public/css/style.min.css', false);
    $style = str_replace("%%primary%%", $this->email_settings['prim_color'], $css);
    $style_1 = str_replace("%%secondary%%", $this->email_settings['sec_color'], $style);
    $style_2 = str_replace("%%txtheader%%", $this->email_settings['head_color'], $style_1);

    $header.='<style>';
    $header.=$style_2;
    $header.='</style>';
    $header.='</head>';

    //Html Body
    $body='<body>';
    $body.='<table class="body">';
    $body.='<tr>';
    $body.='<td class="center" align="center" valign="top">';
    $body.='<center>';
    $body.='<table class="row header">';
    $body.='<tr>';
    $body.='<td class="center" align="center">';
    $body.='<center>';
    $body.='<table class="container">';
    $body.='<tr>';
    $body.='<td class="wrapper last">';
    $body.='<table class="twelve columns">';
    $body.='<tr>';
    $body.='<td class="six sub-columns">';
    if(!empty($this->email_settings['wps_logo_email']))$body.='<a href="'.get_home_url().'"><img src="'.$image_thumb[0].'"></a>';
    if(empty($this->email_settings['wps_logo_email']))$body.='<a href="'.get_home_url().'"><span class="template-label">'.get_bloginfo('name').'</span></a>';
    $body.='</td>';
    $body.='<td class="expander"></td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='</td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='</center>';
    $body.='</td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='<table class="container">';
    $body.='<tr>';
    $body.='<td>';
    $body.='<table class="row">';
    $body.='<tr>';
    $body.='<td class="wrapper last">';
    $body.='<table class="twelve columns">';
    $body.='<tr>';
    $body.='<td>';
    if(!empty($this->email_settings['wps_title_text']))$body.='<h1>'.str_replace('{{Name}}', $_SESSION['sender_name'], $this->email_settings['wps_title_text']).'</h1>';
    $body.='<p class="lead"></p>';
    $body.='<p>'.$this->email_settings['email_body'].'</p>';
    $body.='</td>';
    $body.='<td class="expander"></td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='</td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='<table class="row callout">';
    $body.='<tr>';
    $body.='<td class="wrapper last">';
    $body.='<table class="twelve columns">';
    $body.='<tr>';
    if(!empty($this->email_settings['wps_link_url']) && !empty($this->email_settings['wps_link_text']))$body.='<td class="panel">';
    if(!empty($this->email_settings['wps_link_url']) && !empty($this->email_settings['wps_link_text']))$body.='<p><a href="'.$this->email_settings['wps_link_url'].'">'.$this->email_settings['wps_link_text'].'</a></p>';
    if(!empty($this->email_settings['wps_link_url']) && !empty($this->email_settings['wps_link_text']))$body.='</td>';
    $body.='<td class="expander"></td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='</td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='<table class="row footer">';
    $body.='<tr>';
    $body.='<td class="wrapper">';
    $body.='<table class="six columns">';
    $body.='<tr>';
    $body.='<td class="left-text-pad">';
    $body.='<h5>Connect With Us:</h5>';
    if(!empty($this->email_settings['wps_fb_url']))$body.='<table class="tiny-button facebook">';
    if(!empty($this->email_settings['wps_fb_url']))$body.='<tr>';
    if(!empty($this->email_settings['wps_fb_url']))$body.='<td>';
    if(!empty($this->email_settings['wps_fb_url']))$body.='<a href="'.$this->email_settings['wps_fb_url'].'">Facebook</a>';
    if(!empty($this->email_settings['wps_fb_url']))$body.='</td>';
    if(!empty($this->email_settings['wps_fb_url']))$body.='</tr>';
    if(!empty($this->email_settings['wps_fb_url']))$body.='</table>';
    $body.='<br>';
    if(!empty($this->email_settings['wps_twitter_url']))if(!empty($this->email_settings['wps_twitter_url']))$body.='<table class="tiny-button twitter">';
    if(!empty($this->email_settings['wps_twitter_url']))$body.='<tr>';
    if(!empty($this->email_settings['wps_twitter_url']))$body.='<td>';
    if(!empty($this->email_settings['wps_twitter_url']))$body.='<a href="'.$this->email_settings['wps_twitter_url'].'">Twitter</a>';
    if(!empty($this->email_settings['wps_twitter_url']))$body.='</td>';
    if(!empty($this->email_settings['wps_twitter_url']))$body.='</tr>';
    if(!empty($this->email_settings['wps_twitter_url']))$body.='</table>';
    $body.='<br>';
    if(!empty($this->email_settings['wps_goog_url']))$body.='<table class="tiny-button google-plus">';
    if(!empty($this->email_settings['wps_goog_url']))$body.='<tr>';
    if(!empty($this->email_settings['wps_goog_url']))$body.='<td>';
    if(!empty($this->email_settings['wps_goog_url']))$body.='<a href="'.$this->email_settings['wps_goog_url'].'">Google +</a>';
    if(!empty($this->email_settings['wps_goog_url']))$body.='</td>';
    if(!empty($this->email_settings['wps_goog_url']))$body.='</tr>';
    if(!empty($this->email_settings['wps_goog_url']))$body.='</table>';
    $body.='</td>';
    $body.='<td class="expander"></td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='</td>';
    $body.='<td class="wrapper last">';
    $body.='<table class="six columns">';
    $body.='<tr>';
    $body.='<td class="last right-text-pad">';
    if(!empty($this->email_settings['wps_phone_num']) || !empty($this->email_settings['wps_company_email']))$body.='<h5>Contact Info:</h5>';
    if(!empty($this->email_settings['wps_phone_num']))$body.='<p>Phone: '.$this->email_settings['wps_phone_num'].'</p>';
    if(!empty($this->email_settings['wps_company_email']))$body.='<p>Email: <a href="mailto:'.$this->email_settings['wps_company_email'].'">'.$this->email_settings['wps_company_email'].'</a></p>';
    $body.='</td>';
    $body.='<td class="expander"></td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='</td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='<table class="row">';
    $body.='<tr>';
    $body.='<td class="wrapper last">';
    $body.='<table class="twelve columns">';
    $body.='<tr>';
    $body.='<td align="center">';
    $body.='<center>';
    $body.='</center>';
    $body.='</td>';
    $body.='<td class="expander"></td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='</td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='</td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='</center>';
    $body.='</td>';
    $body.='</tr>';
    $body.='</table>';
    $body.='</body>';
    $body.='</html>';

    //put it together
    $this->content=$header.$body;

  }
}

 ?>
