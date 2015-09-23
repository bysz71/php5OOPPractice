<?php
//error code referece:
//  0: all good
//  1: account not existed
//  2: insufficient fund
//  3: account invalie format
//  4: account duplicate entry
//  5: tranz invalid format
//  6: unkown error

require_once "account.php";
class Bank{
    protected $accounts = array();
    protected $console_log = array();
    
    public function __construct(){}
        
    public function load_accounts($filename){
        if(!isset($filename)||$filename=="") $filename = "acct.txt";
        $file = fopen($filename, "r") or die("Unable to open file!");
        while(!feof($file)){
            $line = fgets($file);
            if($line == "") continue;
            $arr = preg_split('/[ \t\n\r]+/',$line);
            $this->add_account($arr[0],$arr[1]);
        }
    }
    
    public function __get($property){
        if(property_exists($this,$property)){
            return $this->$property;
        }
    }
    
    public function __set($property, $value){
        if(property_exists($this,$property)){
            $this->$property = $value;
        }
    }
    
    //add a new account object to the bank object
    //regular expression for float number validation
    public function add_account($id,$balance){
        if(preg_match("/^[0-9]+$/",$id) && preg_match("/^[-+]?[0-9]*\.?[0-9]+$/",$balance)){
            if(array_key_exists($id,$this->accounts)){
                return 4;
            }else{
                $account = new Account($id,$balance);
                $this->accounts[$account->id] = $account;
                return 0;
            }
        }else{
            return 3;
        }
    }
    
    //withdraw specific amount from specifc account
    public function withdraw($id, $amount){
        if(preg_match("/^[0-9]+$/",$id) && preg_match("/^[-+]?[0-9]*\.?[0-9]+$/",$amount)){
            if(array_key_exists($id,$this->accounts)){
                return $this->accounts[$id]->withdraw($amount);
            }else{
                return 1;
            }
        }else{
            return 5;
        }
    }
    
    //deposit specific amount into specific account
    public function deposit($id, $amount){
        if(preg_match("/^[0-9]+$/",$id) && preg_match("/^[-+]?[0-9]*\.?[0-9]+$/",$amount)){
            if(array_key_exists($id,$this->accounts)){
                $this->accounts[$id]->deposit($amount);
                return 0;
            }else{
                return 1;
            }
        }else{
            return 5;
        }
    }
    
    //handle an operation statement exmple"111 W 111.00"
    public function tranzaction_handler($statement){
        $statement = str_replace("\n","",$statement);
        $statement = str_replace("\r","",$statement);
        if(preg_match("/^[0-9]+ [W|D] [0-9]*\.?[0-9]+$/",$statement)){
            $token = preg_split("/[ \t]/",$statement);
            $id = $token[0];
            $key = $token[1];
            $amount = $token[2];
            if($key === 'W'){
                return($this->withdraw($id,$amount));
            }else if($key === 'D'){
                return($this->deposit($id,$amount));
            }else{
                return 6;
            }
        }else{
            return 5;
        }
    }
    
    //make multiple tranzactions based on tranz.txt
    public function make_tranzactions(){
        $this->console_log = array();
        $file = fopen("tranz.txt",'r') or die("Unable to open file!");
        $line_number = 1;
        //skip first line
        $line = fgets($file);
        
        while(!feof($file)){
            $line = fgets($file);
            //ignore empty line
            if($line == "") continue;
            $code = $this->tranzaction_handler($line);
            $log = array();
            $log['line_number'] = $line_number;
            $log['line'] = $line;
            $log['code'] = $code;
            $this->console_log[$line_number] = $log;
            $line_number++;
        }
        fclose($file);
    }
    
    //print everything as assignment required, added one additional column "error"
    //in table to describe error type
    public function print_invalid_tranz(){
        echo "<a href='report.html'>See report</a>";
        $tranz_count = 0;
        $success_count = 0;
        foreach($this->console_log as $key=> $value){
            $tranz_count++;
            if($value['code'] == 0) $success_count++;
        }
        echo "<h1>There were $tranz_count tranzaction in total</h1>";
        echo "<h1>There were $success_count valid tranzaction</h1>";
        echo "<table border = '1'><tr><td>Line#</td><td>ID</td><td>Type</td><td>Amount</td><td>Error</td></tr>";
        foreach($this->console_log as $key => $value){
            if($value['code'] == 1){
                $tokens = preg_split("/[ \t\r\n]/",$value['line']);
                echo "<tr><td>$key</td><td>".$tokens[0]."</td><td>".$tokens[1]."</td><td>".$tokens[2]."</td><td>Account Not Existed</td></tr>";
            }else if($value['code'] == 2){
                $tokens = preg_split("/[ \t\r\n]/",$value['line']);
                echo "<tr><td>$key</td><td>".$tokens[0]."</td><td>".$tokens[1]."</td><td>".$tokens[2]."</td><td>Insufficient Fund</td></tr>";
            }else if($value['code'] == 5){
                echo "<tr><td>$key</td><td></td><td></td><td></td><td>Invalid Tranzaction Format</td></tr>";
            }else{
                continue;
            }
        }
    }
    
    //return error message based on error code
    function getErrorText($code){
        $errorText = array("good","account not existed","insufficient fund","invalid account format","duplicate account record","invalid tranzaction format","unknown error");
        return $errorText[$code];
    }
    
    //write updated account informations into "update.txt"
    public function update(){
        $file = fopen("update.txt","w") or die ("Cannot open file!");
        foreach($this->accounts as $account){
            $line = $account->id." ".$account->balance."\n";
            fwrite($file,$line);
        }
        fclose($file);
    }
    
    //return summary message
    public function summary(){
        $summary = "";
        foreach($this->accounts as $account){
            $summary .= $account->summary()."<br/>";
        }
        return $summary;
    }
    
    //return count of account
    public function count(){
        return count($this->accounts);
    }
}
?>
