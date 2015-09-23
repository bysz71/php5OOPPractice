<?php
//This class represent a bank account,
//which holds 2 properties, one is account id, the other is account balance.
//It provides basic withdraw, deposit and summary functions

//error code referece:
//  0: all good
//  1: account not existed
//  2: insufficient fund
//  3: account invalie format
//  4: tranz invalid format

class Account{
    protected $id;
    protected $balance;
    
    //constructor
    function __construct($id, $balance){
        $this->id = $id;
        $this->balance = $balance;
    }
    
    //property getter
    public function __get($property){
        if(property_exists($this,$property)){
            return $this->$property;
        }
    }
    
    //property setter
    public function __set($property, $value){
        if(property_exists($this,$property)){
            $this->$property = $value;
        }
    }
    
    //perform withdraw on this account
    //will return true if withdraw is successful
    //will return false if fund's insufficient
    public function withdraw($amount){
        if($this->balance < $amount){
            return 2;
        }else{
            $this->balance -= $amount;
            return 0;
        }
    }
    
    //perform deposit on this account
    public function deposit($amount){
        $this->balance += $amount;
    }
    
    //return a string of account summary
    public function summary(){
        return "ID: " . $this->id . " ; Balance: $". $this->balance;
    }
}
?>
