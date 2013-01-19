<?PHP   
/**
 * Vs_Config
 * app配置信息
 *
 * @author popfeng <popfeng@yeah.net>
 */
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
        $conf['tencent']['expire_time'] = 7; // accesstoken有效期,初级7天

        /**
         * Sina
         */
        $conf['sina']['account'] = '熊者孙';
        $conf['sina']['app_key'] = 3416304519;
        $conf['sina']['app_secret'] = '63982b8a4c7eb681ecb4702b4f270c54';
        $conf['sina']['expire_time'] = 7; // accesstoken有效期,初级7天
        
        /**
         * Sync type
         */
        $conf['sync']['duplex'] = 1;
        $conf['sync']['t2s'] = 2;
        $conf['sync']['s2t'] = 3;
        $conf['sync']['close'] = 0;

        /**
         * cookie
         */
        $conf['cookie']['key'] = 'suv_auth'; // the key of cookie
        $conf['cookie']['encrypt_key'] = 'lhasa'; // cookie des加密的key
        $conf['cookie']['serial_secret'] = 'Let life be beautiful like summer flowers'; // 验证auth信息完整性的字符串 

        /**
         * Mysql pdo
         */
        $conf['pdo'] = 'mysql://root@127.0.0.1:3306?dbname=vsync';

		$this->data = $conf;   
	}

	/**
	 * single
	 * single 单例调用的实现   
	 *
	 * @return object
	 */
	public static function single() 
	{ 
		static $instance;      
		return $instance ? $instance : ($instance = new self());
	} 
}
