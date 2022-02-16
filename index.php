<?php

class ViamoBank {
  private $balance = 0;
  private $credit = 0;
  private $logs;
  private $cvvAtempts = 3;
  public $account;

  public function __construct($account, $cvv) {
    $this->account = $account;
    $this->cvv = $cvv;
  }

  private function checkCVV($cvv) {
    if ($this->cvvAtempts == 0) {
      $this->logging('Card Blocked');
      die('Your card is blocked<br>');
    }
    if ($cvv !== $this->cvv) {
      $this->logging('Wrong CVV');
      $this->cvvAtempts -=1;
      die("CVV is wrong, you have $this->cvvAtempts atempts<br>");
    } else {
      $this->cvvAtempts = 3;
      return true;
    }
  }

  private function isNumber($sum) {
    if (!preg_match('/^\+?\d+$/', $sum)) {
      die('Error, only numbers<br>');
    }
  }

  public function logging($event, $sum = '') {
    $cont = count($this->logs);
    $time = date('c');

    $this->logs[$cont][$this->account][$event]['time'] = $time;
    $this->logs[$cont][$this->account][$event]['sum'] = $sum;

  }

  public function show() {
    echo 'Account: ' . $this->account . '<br>';
    echo 'balance: ' . $this->balance . '<br>';
  }

  public function addCash($sum) {
    $this->isNumber($sum);
    $this->logging('addCash', $sum);
    $this->balance += $sum;
  }

  public function getBalance($cvv) {
    $this->logging('getBalance');
    $this->checkCVV($cvv);
    echo "On your balance $this->balance <br>";

  }

  public function getCash($sum,$cvv) {
    $this->checkCVV($cvv);
    $this->isNumber($sum);
    if ($sum > $this->balance) {
      echo "You don't have enough money on your balance<br>";
      $this->logging('getCashError', $sum);
    } else {
      $this->logging('getCash', $sum);
      $this->balance -= $sum;
      echo "You cash out $sum <br>";
    }
  }

}

$first = new ViamoBank(999, 223);


$first->addCash(300);

$first->addCash('300');

$first->getBalance(223);

$first->getCash(400,223);

$first->getBalance(223);

$first->getBalance(23);
$first->getBalance(23);
$first->getBalance(23);
