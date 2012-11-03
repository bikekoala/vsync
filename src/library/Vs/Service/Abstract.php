<?PHP
abstract class Vs_Service_Abstract
{
    public $conf;

    public function __construct()
    {
        $this->conf = Vs_Config::single();
    }
}
