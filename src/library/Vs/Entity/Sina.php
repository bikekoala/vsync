<?PHP
class Vs_Entity_Sina extends Vs_Entity_Abstract
{
    protected $table = 'sina';

    public function add($accessToken, $uid)
    {
        $time = time();
        $sql = "INSERT INTO 
                `{$this->table}` (`access_token`, `uid`, `ctime`)
                VALUES ('{$accessToken}', {$uid}, {$time})";
        try {
            $this->pdo->exec($sql);
            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}
