<?php

namespace Mworx\VehicleService\Entities;

class Debug {

    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function testWrite() {
        $query = "INSERT INTO test_table SET value = :thing";
        $params = ['thing' => rand(10,1000000)];
        return $this->dbh->executeUpdate($query, $params);
    }

    public function testRead() {
        $query = "SELECT * FROM test_table";
        $result = $this->dbh->fetchAll($query);
        return $result;
    }
}
