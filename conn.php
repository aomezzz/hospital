<?php
//ห้ามเปลี่ยนชื่อตัวแปร บรรทัดที่ 3-6 ของอาจารย์
$sName = 'localhost';
$uName = 'root';
$uPass = '';
$db = 'qsystem2';

try {
  $conn = new PDO(
    "mysql:host=$sName;dbname=$db;charset=UTF8",
    $uName,
    $uPass
  );

  echo "You are now connecting database!!";
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Sorry! You cannot connect to database";
}
