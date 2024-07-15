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
    if (!empty($_GET['_autologin']) && !empty($_GET['uid']) && !empty($_GET['pw']) && !empty($_GET['auth'])) {
      if ( $_GET['auth'] == md5(date('Ymd') . $_GET['pw']) )
      {
        $args['host'] = 'mail.ircf.fr'; //'localhost';
        $args['user'] = $_GET['uid'];
        $args['pass'] = $_GET['pw'];
        $args['cookiecheck'] = false;
        $args['valid'] = true;
      }
    }
    return $args;
  }
}
