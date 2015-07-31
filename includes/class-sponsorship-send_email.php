<?php

class b5wps_send_mail
{
  private $mail_service;

  private $server_settings;

  private $mail_settings;

  private $sender_email;

  private $sender_name;

  private $sender_subject;

  private $mail_list = array();

  public function __construct($sender_email, $sender_name, $sender_subject)
  {
    $admin_page = get_option('admin_page');

    $this->mail_service=$admin_page['email_provider'];
    $this->server_settings=get_option('port_settings');
		$this->mail_settings=$admin_page;
    $this->sender_name=$sender_name;
    $this->sender_email=$sender_email;
    $this->sender_subject=$sender_subject;
    foreach($_SESSION['contact_array'] as $temp) array_push($this->mail_list, $temp[1]);
    $email_message=new b5wps_mail_message();
    $this->email_body=$email_message->content;
    //$this->email_init();
    $this->send_SMTP_mail();
  }
  public function email_init()
  {
    if ($this->mail_service==0) $this->send_WP_mail();
    else if($this->mail_service==1) $this->send_SMTP_mail();
    else if($this->mail_service==2) $this->send_PHP_mail();
  }


  public function send_WP_mail()
  {
    foreach($this->mail_list as $contact)
    {
      wp_mail($contact, $this->sender_subject, $this->mail_settings['email_body']);
    }
  }

  public function send_SMTP_mail()
  {
    $transport = Swift_SmtpTransport::newInstance()
      ->setHost($this->server_settings['host_name_smtp'])
      ->setPort($this->server_settings['port_number_smtp'])
      ->setUsername($this->server_settings['user_name_smtp'])
      ->setPassword($this->server_settings['password_smtp']);
    if ($this->server_settings['encryption_smtp']) ($this->server_settings['encryption_smtp']=='SSL') ? $transport->setEncryption('ssl') : $transport->setEncryption('tls');
    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance($this->sender_subject)
      ->setContentType("text/html")
      ->setFrom($this->mail_settings['sender_address'])
      ->setReplyTo($this->sender_email)
      ->setBcc($this->mail_list)
      ->setBody($this->email_body, 'text/html');
    $numSent = $mailer->send($message);
  }

  public function send_PHP_mail()
  {
    foreach($this->mail_list as $contact)
    {
      mail($contact, $this->sender_subject,$this->mail_settings['email_body']);
    }
  }
}
?>
