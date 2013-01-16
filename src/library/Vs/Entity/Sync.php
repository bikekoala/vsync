<?PHP
class Vs_Entity_Sync extends Vs_Entity_Abstract
{
    protected $table = 'sync';

    public function update($id, $type)
    {
        $time = time();
        $sql = "UPDATE `{$this->table}`
                SET `type`={$type},
                    `mtime`={$time}
                WHERE `id`='{$id}'
                LIMIT 1";
        try {
            return (bool) $this->pdo->exec($sql);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public function add($id, $tid, $sid, $type)
    {
        // relation
        $fields['id'] = $id;
        $fields['t_id'] = $tid;
        $fields['s_id'] = $sid;
        $fields['type'] = $type;
        $fields['ctime'] = time();
        // bindvalue
        $keys = array_keys($fields);
        $sql = "INSERT INTO `{$this->table}` SET ";
        $sql .= Su_Db::genSqlValueStr($keys, $fields);
        $sth = $this->pdo->prepare($sql);
        Su_Db::genSqlBindValue($sth, $keys, $fields);
        // insert
        try {
            return $sth->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public function get()
    {
        $sql = "SELECT m.type,
                       t.access_token AS t_access_token,
                       t.refresh_token AS t_refresh_token,
                       t.openid AS t_openid,
                       s.access_token AS s_access_token,
                       s.uid AS s_uid
                FROM `{$this->table}` AS m
                LEFT JOIN `tencent` AS t
                ON m.t_id=t.id
                LEFT JOIN `sina` AS s
                ON m.s_id=s.id
                WHERE m.type!={$this->conf['sync']['close']}";
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
