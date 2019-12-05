<?php
declare(strict_types=1);

namespace SimpleMVC\Model;


class DataBase
{
  $db = require 'src/Model/dbConfig.php';
  $sql = require 'src/Model/query.php';

  // function to run SELECT
  public function Select(string $query): ?array
  {
      try {
          $dbh = new PDO($db['dns'], $db['user'], $db['password']);   // connect
          $sth = $dbh->prepare($sql[$query]);   // prepare query
          try {
            $sth->exec();   // execute query
            $sth->fetchAll();
            $dbh->close();
            return $sth;
          } catch (PDOException $e) {
              echo 'Query execution failed: ' . $e->getMessage();
              $dbh->close();
          }
      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
      }
  }

  // function to run ALTER, DELETE, CREATE, ...
  public function Modify(string $query): void
  {

      try {

          $dbh = new PDO($db['dns'], $db['user'], $db['password']);   // connect
          $dbh->beginTransaction();   // disable autocommit
          $sth = $dbh->prepare($sql[$query]);   // prepare query

          try {
            $sth->exec();   // execute query
            $dbh->commit();   // commit changes
            $dbh->close();
          } catch (PDOException $e) {
              echo 'Query execution failed: ' . $e->getMessage();    //cathc connection error
              $dbh->rollBack();   // rollBack on error
              $dbh->close();
          }

      } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();    //cathc connection error
      }
  }

}
