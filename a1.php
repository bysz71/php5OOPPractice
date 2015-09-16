<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once "account.php";
require_once "bank.php";
include_once "makeDummyFile.php";

$bank = new Bank();
$bank->load_accounts("acct.txt");
$bank->make_tranzactions();
$bank->print_invalid_tranz();
$bank->update();
?>
