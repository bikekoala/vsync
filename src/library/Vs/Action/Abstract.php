<?PHP
abstract class Vs_Action_Abstract extends Su_Ctrl_Action
{
	abstract public function run();

	public function execute()
	{
		$this->run();
	}
}
