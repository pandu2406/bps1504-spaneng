<?php
// Define path to CI system
define('BASEPATH', 'system/');
define('APPPATH', 'application/');
define('ENVIRONMENT', 'development');

// Mock CodeIgniter Instance
class CI_Controller
{
    public static $instance;
    public $email;
    public $load;

    public function __construct()
    {
        self::$instance = $this;
        $this->load = new Loader();
    }

    public static function get_instance()
    {
        return self::$instance;
    }
}

class Loader
{
    public function library($lib)
    {
        if ($lib == 'email') {
            include_once('system/libraries/Email.php');
            CI_Controller::get_instance()->email = new CI_Email();
        }
    }
}

// Minimal include to bootstrap Email lib
// Actually, bootstrapping CI from CLI completely is hard.
// Better approach: Create a Controller in application/controllers/DebugEmail.php 
// and run it via curl or browser.

?>