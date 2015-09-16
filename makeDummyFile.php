<?php
class FileMaker{
  //make a dummy acct.txt file, random id and balance
  public static function makeAcctFile(){
    $file = fopen("acct.txt","w") or die("Unable to open file!");
    $n = 0;
    for ($n = 0; $n < 20; $n++){
      $id = rand(0, 200);
      $balance = rand(0,1000000)/100;
      fwrite($file,"$id $balance\n");
    }
    fclose($file);
  }

  //add a $line to a file
  public static function addLine($filename,$line){
    $file = fopen($filename,"a") or die("Unable to open file!");
    fwrite($file,$line);
    fclose($file);
  }

  //make dummy tranz.txt based on ids from acct.txt
  public static function makeTranzFile(){
    $file = fopen("acct.txt","r") or die("Unable to open file!");
    $count = 0;
    $IDs = array();
    while(!feof($file)){
      $line = fgets($file);
      if($line == "") continue;
      $tokens = preg_split("/[ \t\r\n]/",$line);
      echo "token:$tokens[0]<br/>";
      array_push($IDs,$tokens[0]);
      $count++;
    }
    fclose($file);

    $file = fopen("tranz.txt","w") or die("Unable to open file!");
    fwrite($file,"ID Tranz type(W,D) Ammount\n");
    $n = 0;
    for ($n = 0; $n < 300; $n++){
      $id_index = rand(0,$count-1);
      $id = $IDs[$id_index];

      $amount = rand(0,1000000)/100;

      $type_index = rand(0,1);
      if($type_index == 0){
        $type = 'W';
      }else{
        $type = 'D';
      }

      fwrite($file,"$id $type $amount\n");
    }
    fclose($file);
  }

  //add corrupted lines to tranz.txt. These lines will never pass the validation
  //and flag error 5 format error
  public static function addCorruptedLine(){
    $file = fopen("tranz.txt","a") or die("Unable to open file!");
    fwrite($file,";skadfj;sadkf\n");
    fwrite($file,"123W123\n");
    fwrite($file,"123 W123\n");
    fwrite($file,"123W 123\n");
    fwrite($file,"1a3 W 123\n");
    fwrite($file," W 123\n");
    fwrite($file,"123 W 1a3\n");
    fwrite($file,"123 W \n");
    fwrite($file,"123 W 1123.3.3\n");
    fwrite($file,"123 X 123213\n");
    fclose($file);
  }

  //add lines which id are not found in acct.txt
  //will never pass validation and flog error 2 account not existed
  public static function addNotExistedLine(){
    $file = fopen("tranz.txt","a") or die("Unable to open file!");
    $n = 0;
    for($n = 0; $n<10; $n++)
      fwrite($file,"9999 D 123.0\n");
    fclose($file);
  }
}

//execute
//FileMaker::makeAcctFile();
//FileMaker::addLine("acct.txt","123 0.00\n");
//FileMaker::makeTranzFile();
//FileMaker::addCorruptedLine();
//FileMaker::addNotExistedLine();
?>
