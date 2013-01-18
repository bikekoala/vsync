<?PHP
/**
 * Vs_Entity_Abstract
 * 实体抽象类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Entity_Abstract
{
	protected $pdo;
	protected $conf;
    protected $table;

	public function __construct()
	{
		$this->conf = Vs_Config::single();
		$this->pdo = Su_Db::getInstance($this->conf['pdo']);
    }

    /**
     * single
     * single 单例调用的实现
     *
     * @return void
     */
    public static function single()
    {
        static $instance;
        $class = get_called_class();
        return $instance ? $instance : ($instance = new $class());
    }
}
