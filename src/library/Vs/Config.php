<?PHP   
class Vs_Config extends Su_Config
{ 
    /**
     * app configure
     */
	protected function __construct()
	{
        /**
         * Tencent
         */
        $conf['tencent']['account'] = 'popfeng';
        $conf['tencent']['app_key'] = 801263889;
        $conf['tencent']['app_secret'] = '8e7ee4dab1ae84212e563f55ea309ee0';

        /**
         * Sina
         */
        $conf['sina']['app_key'] = 3416304519;
        $conf['sina']['app_secret'] = '63982b8a4c7eb681ecb4702b4f270c54';

        /**
         * Mysql pdo
         */
        $conf['pdo'] = '';

		$this->data = $conf;   
	}

	/**
	 * single 单例调用的实现   
	 */ 
	public static function single() 
	{ 
		static $instance;      
		return $instance ? $instance : ($instance = new self());
	} 
}
