<?php
namespace Employee;
use \Employee\Administrator\Manager as MG;
//use \DateTime;
require "employee.php";
require "manager.php";
$s = new Staff();
$s->index();
$s2 = new Administrator\Manager();
$s2->index();
$s3 = new \Employee\Administrator\Manager();
$s3->index();
$s4 = new MG();
$s4->index();
$d = new \DateTime();
echo "Today is ", $d->format('Y-m-d');
//$d = new DateTime();
//echo "Today is ", $d->format('Y-m-d'), "again";