<?PHP
/**
 * Vs_Entity_Sync
 * 同步表的操作方法
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Entity_Sync extends Vs_Entity_Abstract
{
    protected $table = 'sync'; // 表名

    /**
     * add
     * 添加一条同步记录
     *
     * @param array $params
     * @return bool
     */
    public function add($params)
    {
        // bindvalue
        $keys = array_keys($params);
        $vals= Su_Db::genSqlValueStr($keys, $params);
        $sql = "INSERT INTO `{$this->table}` SET {$vals}";
        $sth = $this->pdo->prepare($sql);
        Su_Db::genSqlBindValue($sth, $keys, $params);

        // update
        try {
            return (bool) $sth->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    /**
     * update
     * 通过唯一标识更新同步数据
     *
     * @param string $id
     * @param array $params
     * @return bool
     */
    public function update($id, $params = array())
    {
        // bindvalue
        $keys = array_keys($params);
        $sql = "UPDATE `{$this->table}` SET ";
        $sql .= Su_Db::genSqlValueStr($keys, $params);
        $sql .= " WHERE id='{$id}'";
        $sth = $this->pdo->prepare($sql);
        Su_Db::genSqlBindValue($sth, $keys, $params);

        // update
        try {
            return (bool) $sth->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    /**
     * get
     * 根据id获取一条同步记录
     *
     * @param string $id
     * @return array
     */
    public function get($id)
    {
        // 静态化
        static $data;
        if ($data) {
            return $data;
        }

        $sql = "SELECT *
            FROM `{$this->table}`
            WHERE `id`='{$id}'";
        $sth = $this->pdo->prepare($sql);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        try {
            $sth->execute();
            $data = $sth->fetch();
            return $data;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    /**
     * getList
     * 获取同步记录列表
     *
     * @return array
     */
    public function getList()
    {
        $sql = "SELECT *
            FROM `{$this->table}`
            WHERE type!={$this->conf['sync']['close']}
            ORDER BY `time` DESC";
        $sth = $this->pdo->prepare($sql);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        try {
            $sth->execute();
            return $sth->fetchAll();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}
