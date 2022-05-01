<?php

class Database
{
    public $id;

    public $name;

    public $familyName;

    public $dateOfBirth;

    public $gender;

    public $cityOfBirth;

    public function __construct($id = null, $name = null, $familyName = null, $dateOfBirth = null,
                                $gender = null, $cityOfBirth=null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->familyName = $familyName;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->cityOfBirth = $cityOfBirth;


        if($id == null) {
            $this->addPeople();
        } elseif ($id == $this->getId($id)) {
            try {
                $conn = new PDO("mysql:host=127.0.0.1;dbname=testovoe_db", "root");
                $sql = "SELECT * FROM People_db WHERE Id = '$id'";
                $response = $conn->query($sql);
                $result = $response->fetch();
                $this->id = $result[0];
                $this->name = $result[1];
                $this->familyName = $result[2];
                $this->dateOfBirth = $result[3];
                $this->gender = $result[4];
                $this->cityOfBirth = $result[5];
            }
            catch (PDOException $e) {
                return "Database error: " . $e->getMessage();
            }
        } else {
            return print_r('Такого id нет в базе данных');
        }

    }

    public function addPeople()
    {
        try {
            $conn = new PDO("mysql:host=127.0.0.1;dbname=testovoe_db", "root");
            $sql = "INSERT INTO People_db (Name, Family_Name, Date_of_Birth, Gender, City_of_Birth) VALUES
                    ('$this->name', '$this->familyName', '$this->dateOfBirth', $this->gender, '$this->cityOfBirth')";
            $conn->exec($sql);
        }
        catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
        }
    }

    public function deletePeopleById()
    {
        try {
            $conn = new PDO("mysql:host=127.0.0.1;dbname=testovoe_db", "root");
            $sql = "DELETE FROM People_db WHERE id = $this->id";
            $affectedRowsNumber = $conn->exec($sql);
            return "таблицу Users удалено строк: $affectedRowsNumber";
        }
        catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
        }
    }

    public static function getAgeById($id)
    {
        try {
            $conn = new PDO("mysql:host=127.0.0.1;dbname=testovoe_db", "root");
            $sql = "SELECT Date_of_Birth FROM People_db WHERE id = $id";
            $response = $conn->query($sql);
            $result = $response->fetch();
            $age = date_diff(date_create($result[0]), date_create('now'))->y;
            $conn->exec($sql);
           echo $age;
        }
        catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
        }
    }

    public static function getGenderById($id)
    {
        try {
            $conn = new PDO("mysql:host=127.0.0.1;dbname=testovoe_db", "root");
            $sql = "SELECT Gender FROM People_db WHERE id = '$id'";
            $response = $conn->query($sql);
            $result = $response->fetch();
            if($result[0] == 1) {
                $genderWord = 'муж';
                $conn->exec($sql);
                return $genderWord;
            } else {
                $genderWord = 'жен';
                $conn->exec($sql);
                return $genderWord;
            }
        }
        catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
        }
    }

    private function getId($id)
    {
        $conn = new PDO("mysql:host=127.0.0.1;dbname=testovoe_db", "root");
        $sql = "SELECT Id FROM People_db WHERE id = $id";
        $response = $conn->query($sql);
        $result = $response->fetch();
        return $result[0];
    }

    public function formatPeopleData($formatAge = 'No', $formatGender = 'No')
    {
        $newstd = new stdClass();
        $newstd->id = $this->id;
        $newstd->name = $this->name;
        $newstd->familyName = $this->familyName;
        $newstd->dateofBirth = $this->dateOfBirth;
        $newstd->gender = $this->gender;
        if($formatAge == 'Yes' && $formatGender == 'Yes' ) {
            $age = self::getAgeById($this->id);
            $genderWord = self::getGenderById($this->id);
            $newstd->dateofBirth = $age;
            $newstd->gender = $genderWord;
        } elseif ($formatAge == 'Yes') {
            $age = self::getAgeById($this->id);
            $newstd->dateofBirth = $age;
        } elseif ($formatGender == 'Yes') {
            $genderWord = self::getGenderById($this->id);
            $newstd->gender = $genderWord;
        }
        $newstd->cityOfBirth = $this->cityOfBirth;
        return $newstd;
    }
}