<?php
class DB{
    private $USER   =   "root";
    private $PW     =   "";
    private $dns    =   "mysql:dbname=img_bbs;host=localhost;charset=utf8";
    private $pdo    =   null; //初期値を指定
    
    private function Connectdb(){   //データベースに接続する。tryは通信を実行するイメージ。if文でpdoがnullの場合pdoを実行。実行するとpdoに代入されるためnewの二度目は行われない。
        try {
            if ($this->pdo == null) {
                $this->pdo = new PDO($this->dns,$this->USER,$this->PW);
            }            
            return $this->pdo;
        }catch(Exception $e){
            return false;
        }
    }
    
    public function executeSQL($sql,$array){    //SQL文を実行する
        try{
            if(!$pdo = $this->Connectdb())return false;
            $stmt   =   $pdo->prepare($sql);
            $stmt->execute($array);
            return $stmt;
        }catch(Exception $e){
            return false;
        }
    }
    public function beginTransaction(){
        if(!$pdo = $this->Connectdb())return false;
        $pdo->beginTransaction();
    }
    public function rollBack() {
        if(!$pdo = $this->Connectdb())return false;
        $pdo->rollBack();
    }
    public function lastInsertId() {
        if(!$pdo = $this->Connectdb())return false;
        return $pdo->lastInsertId();
        
    }
    public function commit() {
        if(!$pdo = $this->Connectdb())return false;
        $pdo->commit();
    }
}
?>