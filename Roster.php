<?php

use Database;

class Roster
{
    public $collectionId;

    public function __construct($column = 'Name' , $sign = '=', $values = '0')
    {

        if(class_exists('Database') == 1) {
            $conn = new PDO("mysql:host=127.0.0.1;dbname=testovoe_db", "root");
            $sql = "SELECT Id FROM People_db WHERE `$column` $sign '$values'";
            $response = $conn->query($sql);
            $this->collectionId = $response->fetchAll(PDO::FETCH_ASSOC);
            return $this->collectionId;
        } else {
            print_r('Ошибка. Класс 1 не объявлен');
        }
    }

    public function toObjectArray()
    {
        $conn = new PDO("mysql:host=127.0.0.1;dbname=testovoe_db", "root");
        foreach($this->collectionId as $value) {
            $sql = "SELECT * FROM People_db WHERE Id = $value[Id]";
            $response = $conn->query($sql);
            $result = $response->fetch(PDO::FETCH_ASSOC);
            $objectArray = new Database(id: $result['id'], name: $result['Name'],
                familyName: $result['Family_Name'], dateOfBirth: $result['Date_of_Birth'],
                gender: $result['Gender'], cityOfBirth: $result['City_of_Birth']
            );
            $arrayResult[] = $objectArray;
        }
        return $arrayResult;
    }

    public function deletePeopleByObject()
    {
        $conn = new PDO("mysql:host=127.0.0.1;dbname=testovoe_db", "root");
        foreach ($this->collectionId as $value) {
            $sql = "DELETE FROM People_db WHERE Id = $value[Id]";
            $result = $conn->query($sql);
            return print_r('done');
        }
    }
}