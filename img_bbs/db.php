<?php
class DB{
    private $USER   =   "root";
    private $PW     =   "";
    private $dns    =   "mysql:dbname=img_bbs;host=localhost;charset=utf8";
    
    private function Connectdb(){
        try {
            $pdo    =   new PDO($this->dns,$this->USER,$this->PW);
            return $pdo;
        }catch(Exception $e){
            return false;
        }
    }
    
    public function executeSQL($sql,$array){
        try{
            if(!$pdo = $this->Connectdb())return false;
            $stmt   =   $pdo->prepare($sql);
            $stmt->execute($array);
            return $stmt;
        }catch(Exception $e){
            return false;
        }
    }
}
?>