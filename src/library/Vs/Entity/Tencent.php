<?PHP
class Vs_Entity_Tencent extends Vs_Entity_Abstract
{
    protected $table = 'tencent';

    public function add($accessToken, $refreshToken, $openId)
    {
        $time = time();
        $sql = "INSERT INTO 
                `{$this->table}` (`access_token`, `refresh_token`, `openid`, `ctime`)
                VALUES ('{$accessToken}', '{$refreshToken}', '{$openId}', {$time})";
        try {
            $this->pdo->exec($sql);
            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}
