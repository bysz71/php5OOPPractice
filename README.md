<html>
  <head>
    <title>Report</title>
    <link rel = "stylesheet" type = "text/css" href = "report.css">
  </head>
  <body>
    <h1>php5OOPractice</h1>
    <hr>
    <h2>Summary</h2>
    <p>
      This project is to handle a simple banking system. It reads account
      records from a text file "acct.txt", and perform tranzactions based on
      a tranzaction list text file "tranz.txt". Once tranzactions are
      made, they are written into a text file named "update.txt".
    </p>
    <hr>
    <h2>Index</h2>
    <ul>
      <li><a href='#introduction'>Introduction</a></li>
      <li><a href='#account'>class Account</a></li>
      <ul>
        <li><a href = '#account_intro'>Intro</a></li>
        <li><a href = '#account_properties'>Properties</a></li>
        <li><a href = '#account_constructor'>Constructor</a></li>
        <li><a href = '#account_accessers'>Property accessers</a></li>
        <li><a href = '#account_withdraw'>function withdraw($amount)</a></li>
        <li><a href = '#account_deposit'>function deposit($amount)</a></li>
        <li><a href = '#account_summary'>function summary()</a></li>
      </ul>
      <li><a href='#bank'>class Bank</a></li>
      <ul>
        <li><a href = '#bank_intro'>Intro</a></li>
        <li><a href = '#bank_properties'>Properties</a></li>
        <li><a href = '#bank_accessers'>Accessors</a></li>
        <li><a href = '#bank_add'>function add_account($id,$balance)</a></li>
        <li><a href = '#bank_load'>function load_accounts($filename)</a></li>
        <li><a href = '#bank_withdraw'>function withdraw($id,$amount)</a></li>
        <li><a href = '#bank_deposit'>function deposit($id,$amount)</a></li>
        <li><a href = '#bank_handler'>function tranzaction_handler($statement)</a></li>
        <li><a href = '#bank_make'>function make_tranzactions()</a></li>
        <li><a href = '#bank_print'>function print_invalid_tranz()</a></li>
        <li><a href = '#bank_other'>Other small functions</a></li>
      </ul>
      <li><a href = '#suggestion'>Suggestions/criticism/extensions</a></li>
    </ul>
    <hr>
    <h2 id = 'introduction'>Introduction</h2>
    <p>This project includes 2 main classes, they are class "Account" and class "Bank".
      Account holds information for 1 account and provides very simple functionalities.
      Bank holds a list of Account objects and provides practical functionalities based on
      Account. Account must be associated with Bank.</p>

    <p>Format and other type of validation are mostly implemented in class "Bank"
      but not in class "Account", because "Account" is the most basic class
      in this project, and complicated validations can be and should be implemented
       in a higher tier (in the "Bank" class).</p>

    <p>Most validation in this projects are done by regular expression (regex). Regex
       is a really powerful tool to validate desired pattern of charactors. PHP provides
       built-in functions to handle regex. In addition, regex is also very good at split
       string in a desired way, it is more handy than using "explode()" if you know how to
       use it.
    </p>
    <p>
      Because in practical use, many different type of failure or error could occur, it is
      reasonable to let user know what kind of failure occurred,
      thus to represent if an operation is successful or not, instead of return boolean
      value, I chose to use a code system to represent success and different type of failure:<br/>
      //  0: successful<br/>
      //  1: account not existed<br/>
      //  2: insufficient fund<br/>
      //  3: account invalie format<br/>
      //  4: account duplicate entry<br/>
      //  5: tranzaction invalid format<br/>
      //  6: unkown error<br/>
    </p>
    <hr>
    <h2 id = 'account'>class Account</h2>
    <p id = 'account_intro'><h4>1. Intro</h4>
      Account is a simple class that holds information of one account and
      performs simple functionality.</p>
    <p id = 'account_properties'><h4>2. Properties</h4>
      There are 2 properties and they are marked as protected, so they can be accessed by sub-classes if
      required, but cannot be directly accessed outside of class, for security
      purpose.
    </p>
    <p class = "php-code">
      class Account{<br/>
        &nbspprotected $id;<br/>
        &nbspprotected $balance;<br/>
        &nbsp...<br/>
    </p>
    <p id = 'account_constructor'><h4>3. Constructor</h4>
      Construct a new Account object based on 2 parameters.
    </p>
    <p class = "php-code">
      function __construct($id, $balance){<br/>
        &nbsp$this->id = $id;<br/>
        &nbsp$this->balance = $balance;<br/>
      }<br/>
    </p>
    <p id = 'account_accessers'><h4>4. Property accessers</h4>
      Used to access protected or private properties.
      Setter was written for test reason and might need to
      be disabled in practical use.
    </p>
    <p class=  "php-code">
      public function __get($property){<br/>
        &nbspif(property_exists($this,$property)){<br/>
          &nbsp&nbspreturn $this->$property;<br/>
        &nbsp}<br/>
      }<br/>
      public function __set($property, $value){<br/>
        &nbspif(property_exists($this,$property)){<br/>
          &nbsp&nbsp$this->$property = $value;<br/>
        &nbsp}<br/>
      }<br/>
    </p>
    <p id = 'account_withdraw'><h4>5. withdraw($amount) function</h4>
      Withdraw specific amount of money from this account.If suceeded, it will return code 0
       and the property $balance will be updated. If the $balance is insufficient for withdraw,
       it will return code 2 and do nothing to $balance.
    </p>
    <p class = "php-code">
      public function withdraw($amount){<br/>
        &nbspif($this->balance < $amount){<br/>
          &nbsp&nbspreturn 2;<br/>
        &nbsp}else{<br/>
          &nbsp&nbsp$this->balance -= $amount;<br/>
          &nbsp&nbspreturn 0;<br/>
        &nbsp}<br/>
      }<br/>
    </p>
    <p id = 'account_deposit'><h4>6. deposit($amount) function</h4>
      Deposit specific amount of money into this account. No return value because deposit
      will always success.
    </p>
    <p class = "php-code">
      public function deposit($amount){<br/>
        &nbsp$this->balance += $amount;<br/>
      }<br/>
    </p>
    <p id = 'account_summary'><h4>7. summary() function</h4>
      Return a simply formatted string of the summary of this account.
    </p>
    <p class = "php-code">
      public function summary(){<br/>
        &nbspreturn "ID: " . $this->id . " ; Balance: $". $this->balance;<br/>
      }<br/>
    </p>
    <hr>
    <h2 id = 'bank'>class Bank</h2>
    <p id = 'bank_intro'><h4>1. Intro</h4>
      Bank class holds an array of Account objects, and perform all kinds of
      disired functionalities.
    </p>
    <p id = 'bank_properties'><h4>2. Properties</h4>
      A bank system should have many accounts. $accounts holds an array of Account objects.
      $console_log is an array to hold tranzaction operations and their return code for record and debug reason.
    </p>
    <p class = "php-code">
      protected $accounts = array();<br/>
      protected $console_log = array();<br/>
    </p>
    <p id = 'bank_accessers'><h4>3. Property accessers</h4>
      similar as above.
    </p>
    <p id = 'bank_add'><h4>4. function add_account($id,$balance)</h4>
      This function adds 1 account to account list by pushing a new Account object into $accounts.<br/><br/>
      In this function, due to assignment request, the parameter $id and $balance are strings read from a text file.
      However, parameter $id must represent an integer, and paramter $balance must represent a number which could be
      a integer or a float(double), thus validate if these 2 strings are what we needed become essential.<br/><br/>
      ctype_digit() is a function to check if every character in a string is a digit. It is able to check if a
      string is an integer, but not capable to check a float(double) since there is a dot in it. Thus I choose to
      use regular expression and preg_match() to acheive the validation. preg_match() returns true if input string
       matches the desired pattern.<br/><br/>
      My regex to validate a number (int or float) in here is "/^[-+]?[0-9]*\.?[0-9]+$/":
      <ul>
        <li>"/" at MSB and LSB indicate what's between them are regex.</li>
        <li>"^" means the string should start with this pattern.</li>
        <li>"[-+]?" means none or 1 "-" or "+", this is because someone might have a negative balance.</li>
        <li>"[0-9]*" means none or more digits, this represent the digits before decimal point.</li>
        <li>"\.?" means none or 1 decimal point, if none ".", this string could represent a integer.</li>
        <li>"[0-9]+$" means 1 or more digits and the string ends here.</li>
      </ul>
      <br/>
      If input parameters did not pass the validation, it will return code 3 (account invalid format).
      If an account with same $id is already in $accounts, if will return code 4 (duplicate account entry).
      Otherwise, it will add this account to $Accounts and return code 0 (successful).
    </p>
    <p class = "php-code">
      public function add_account($id,$balance){<br/>
        &nbspif(preg_match("/^[0-9]+$/",$id) && preg_match("/^[-+]?[0-9]*\.?[0-9]+$/",$balance)){<br/>
          &nbsp&nbspif(array_key_exists($id,$this->accounts)){<br/>
            &nbsp&nbsp&nbspreturn 4;<br/>
          &nbsp&nbsp}else{<br/>
            &nbsp&nbsp&nbsp$account = new Account($id,$balance);<br/>
            &nbsp&nbsp&nbsp$this->accounts[$account->id] = $account;<br/>
            &nbsp&nbsp&nbspreturn 0;<br/>
          &nbsp&nbsp}<br/>
        &nbsp}else{<br/>
          &nbsp&nbspreturn 3;<br/>
        &nbsp}<br/>
      }<br/>
    </p>
    <p id = 'bank_load'><h4>5. load_accounts($filename)</h4>
      This function reads account list from a text file (defaultly "acct.txt"),
      and load them into $accounts.<br/><br/>
      In this function, since a line of the file has a format "id balance\n", I
      used regex and preg_split to split it. preg_split() using regex '/[ \t\n\r]+/'
      means split string by one or more space or tab or new line or return. The benefit
      of using this rather than using explode() is that every line has a "\n" new line character at the end, and
      using explode() will not get rid of "\n", which takes at least one more step to
      acheive what we want, that preg_split() only takes 1 step.
    </p>
    <p class = "php-code">
      public function load_accounts($filename){<br/>
        &nbspif(!isset($filename)||$filename=="") $filename = "acct.txt";<br/>
        &nbsp$file = fopen($filename, "r") or die("Unable to open file!");<br/>
        &nbspwhile(!feof($file)){<br/>
          &nbsp&nbsp$line = fgets($file);<br/>
          &nbsp&nbspif($line == "") continue;<br/>
          &nbsp&nbsp$arr = preg_split('/[ \t\n\r]+/',$line);<br/>
          &nbsp&nbsp$this->add_account($arr[0],$arr[1]);<br/>
        &nbsp}<br/>
      }
    </p>
    <p id = 'bank_withdraw'><h4>6. function withdraw($id, $amount)</h4>
      This function withdraw specific $amount from account $id.<br/><br/>
      Because the $amount has the same format as $balance in the "add_account()" function,
      they uses the same way to validate. So is $id.<br/><br/>
      If validation failed, it returns code 5 (tranzaction invalid format); if validation passed,
      but cannot find desired $id in the $accounts, it returns code 1 (account not existed); otherwise,
      it calls and returns the "withdraw()" function of desired Account object which would perform withdraw
      and return proper code (0 or 2).
    </p>
    <p class = "php-code">
      public function withdraw($id, $amount){<br/>
        &nbspif(preg_match("/^[0-9]+$/",$id) && preg_match("/^[-+]?[0-9]*\.?[0-9]+$/",$amount)){<br/>
          &nbsp&nbspif(array_key_exists($id,$this->accounts)){<br/>
            &nbsp&nbsp&nbspreturn $this->accounts[$id]->withdraw($amount);<br/>
          &nbsp&nbsp}else{<br/>
            return 1;<br/>
          &nbsp&nbsp}<br/>
        &nbsp}else{<br/>
          &nbsp&nbspreturn 5;<br/>
        &nbsp}<br/>
      }<br/>
    </p>
    <p id = 'bank_deposit'><h4>7. function deposit($id,$amount)</h4>
      Deposit specific $amount into account $id. Similar to withdraw.
    </p>
    <p class = "php-code">
      public function deposit($id, $amount){<br/>
        &nbspif(preg_match("/^[0-9]+$/",$id) && preg_match("/^[-+]?[0-9]*\.?[0-9]+$/",$amount)){<br/>
          &nbsp&nbspif(array_key_exists($id,$this->accounts)){<br/>
            &nbsp&nbsp&nbsp$this->accounts[$id]->deposit($amount);<br/>
            &nbsp&nbsp&nbspreturn 0;<br/>
          &nbsp&nbsp}else{<br/>
            &nbsp&nbsp&nbspreturn 1;<br/>
          &nbsp&nbsp}<br/>
        &nbsp}else{<br/>
          &nbsp&nbspreturn 5;<br/>
        &nbsp}<br/>
      }<br/>
    </p>
    <p id = 'bank_handler'><h4>8. function tranzaction_handler($statement)</h4>
      This function perform either withdraw or deposit based on $statement.
      This $statement is a string of tranzaction statement with format (id type amount).
      It returns codes based on what happened.
    </p>
    <p class = "php-code">
      public function tranzaction_handler($statement){<br/>
        &nbsp$statement = str_replace("\n","",$statement);<br/>
        &nbsp$statement = str_replace("\r","",$statement);<br/>
        &nbspif(preg_match("/^[0-9]+ [W|D] [0-9]*\.?[0-9]+$/",$statement)){<br/>
          &nbsp&nbsp$token = preg_split("/[ \t]/",$statement);<br/>
          &nbsp&nbsp$id = $token[0];<br/>
          &nbsp&nbsp$key = $token[1];<br/>
          &nbsp&nbsp$amount = $token[2];<br/>
          &nbsp&nbspif($key === 'W'){<br/>
            &nbsp&nbsp&nbspreturn($this->withdraw($id,$amount));<br/>
          &nbsp&nbsp}else if($key === 'D'){<br/>
            &nbsp&nbsp&nbspreturn($this->deposit($id,$amount));<br/>
          &nbsp&nbsp}else{<br/>
            &nbsp&nbsp&nbspreturn 6;<br/>
          &nbsp&nbsp}<br/>
        &nbsp}else{<br/>
          return 5;<br/>
        &nbsp}<br/>
      }<br/>
    </p>
    <p id= 'bank_make'><h4>9. function make_tranzactions()</h4>
      This function makes multi tranzactions based on "tranz.txt". In the mean time
      it record every tranzaction statement, its row number and its result code in the $console_log.<br/><br/>
    </p>
    <p class = "php-code">
      public function make_tranzactions(){<br/>
        &nbsp$this->console_log = array();<br/>
        &nbsp$file = fopen("tranz.txt",'r') or die("Unable to open file!");<br/>
        &nbsp$line_number = 1;<br/>
        &nbsp//skip first line<br/>
        &nbsp$line = fgets($file);<br/>
        &nbsp<br/>
        &nbspwhile(!feof($file)){<br/>
          &nbsp&nbsp$line = fgets($file);<br/>
          &nbsp&nbsp//ignore empty line<br/>
          &nbsp&nbspif($line == "") continue;<br/>
          &nbsp&nbsp$code = $this->tranzaction_handler($line);<br/>
          &nbsp&nbsp$log = array();<br/>
          &nbsp&nbsp$log['line_number'] = $line_number;<br/>
          &nbsp&nbsp$log['line'] = $line;<br/>
          &nbsp&nbsp$log['code'] = $code;<br/>
          &nbsp&nbsp$this->console_log[$line_number] = $log;<br/>
          &nbsp&nbsp$line_number++;<br/>
        &nbsp}<br/>
        &nbspfclose($file);<br/>
      }<br/>
    </p>
    <p id = 'bank_print'><h4>10. function print_invalid_tranz()</h4>
      Pirnt every thing as assignment required. Added one column "Error" in the
      table to describe what kind of error occured.
    </p>
    <p class = "php-code">
      Please check out file in bank.php. Has issues display them in here.
    </p>
    <p id = 'bank_other'><h4>11. Other small functions</h4>
      <ul>
        <li>getErrorText($code) function return error describe message based on error code. Actually after php5.6 we can define constant array which looks better.</li>
        <li>update() function write updated account information into file "update.txt".</li>
        <li>summary() function return accounts information list in a simple format.</li>
        <li>count() function returns number of account.</li>
      </ul>
    </p>
    <p class = "php-code">
      function getErrorText($code){<br/>
        &nbsp$errorText = array("good","account not existed","insufficient fund","invalid account format","duplicate account record","invalid tranzaction format","unknown error");<br/>
        &nbspreturn $errorText[$code];<br/>
      }<br/>

      public function update(){<br/>
        &nbsp$file = fopen("update.txt","w") or die ("Cannot open file!");<br/>
        &nbspforeach($this->accounts as $account){<br/>
          &nbsp&nbsp$line = $account->id." ".$account->balance."\n";<br/>
          &nbsp&nbspfwrite($file,$line);<br/>
        &nbsp}<br/>
        &nbspfclose($file);<br/>
      }<br/>

      //return summary message<br/>
      public function summary(){<br/>
        &nbsp$summary = "";<br/>
        &nbspforeach($this->accounts as $account){<br/>
          &nbsp&nbsp$summary .= $account->summary()."<br/>";<br/>
        &nbsp}<br/>
        &nbspreturn $summary;<br/>
      }<br/>

      //return count of account<br/>
      public function count(){<br/>
        &nbspreturn count($this->accounts);<br/>
      }<br/>
    </p>
    <hr>
    <p id = 'suggestion'>
      <h2>Suggestions/criticism/extensions</h2>
      To print invalid tranzactions, the rendering work should be done in the front
      end, not in the php code like what I was doing in the Bank:print_invalid_tranz() function.
      Although I did not implement it, I prepared for the possibility.
      I store console log in an array so that the
      Bank::console_log can be easily parsed into json format string and pass to the front end.<br/><br/>

      This assignment is a really good practice of OO programming.
    </p>
  </body>
</html>
