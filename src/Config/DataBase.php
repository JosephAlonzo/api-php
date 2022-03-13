<?php
namespace App\Config;
use \PDO;

class DataBase{
  private $dbHost ='josephalonzo.com';
  private $dbUser = 'josephal_api';
  private $dbPass = '';
  private $dbName = 'josephal_apirest';
  //conección 
  public function conectDB(){
    $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName";
    $dbConnecion = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
    $dbConnecion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnecion;
  }
}