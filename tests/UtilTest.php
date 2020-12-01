<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\UtilController;

class UtilTest extends TestCase
{
 // webコンテナに入らないとエラーになる。
 public function testDbConnect()
 {
  $mock = $this->createMock(UtilController::class);

  $db = new PDO(
   'pgsql:host=db;dbname=offshoa_db;',
   'test_user',
   'secret',
  );

  $mock->method('dbconnect')
   ->willReturn($db);

  $result = $mock->dbconnect();
  var_dump($result);
  $this->assertIsObject($result);
  $this->assertEquals($db, $result);
 }
}