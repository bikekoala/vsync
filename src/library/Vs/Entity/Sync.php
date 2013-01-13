<?PHP
class Vs_Entity_Sync extends Vs_Entity_Abstract
{
    protected $table = 'sync';

    public function update($id, int $type)
    {
        $sql = "UPDATE `{$this->table}`
                SET `type`={$type}
                WHERE `id`='{$id}'
                LIMIT 1";
        try {
            return (bool) $this->pdo->query($sql);
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
}
