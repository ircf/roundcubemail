<?php

class autologon extends rcube_plugin
{
  public $task = 'login';

  function init()
  {
    $this->add_hook('startup', array($this, 'startup'));
    $this->add_hook('authenticate', array($this, 'authenticate'));
  }

  function startup($args)
  {
    $rcmail = rcmail::get_instance();
    // change action to login
    if (empty($_SESSION['user_id']) && !empty($_GET['_autologin']))
      $args['action'] = 'login';
    return $args;
  }

  function authenticate($args)
  {
    if (!empty($_GET['_autologin']) && !empty($_GET['uid']) && !empty($_GET['auth'])) {
      $rcmail = rcmail::get_instance();
      $db = $rcmail->get_dbh();
      $result = $db->query("SELECT `email`,`pw` FROM `our_user_table` WHERE `id` = '{$_GET['uid']}'");
      $data = $db->fetch_assoc($result);
      if ( !empty($data) )
      {
        $email = $data['email'];
        $pw = $data['pw'];
        $date = date('Ymd');
	// YYYYMMDD (no time since this will increase the likelihood of an authentication failure)
        $expect = md5($date . $pw);
        $auth = $_GET['auth'];
        if ( $auth == $expect )
        {
          $args['user'] = $email;
          $args['pass'] = $pw;
          //$args['host'] = 'localhost';  // not sure why this was needed
        }
      }
    }
    return $args;
  }

}
