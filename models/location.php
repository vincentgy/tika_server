<?php
class Location {		
    public $latitude;		
    public $longitude;		

    function __construct($lat, $lon) {		
        $this->latitude = $lat;
        $this->longitude = $lon;
    }
}

class Address {
    public $country;
    public $city;
    public $formatted_address;

    function __construct($Formatted_address) {
        $this->formatted_address = $Formatted_address;
    }

    static function getregionbyId($link, $id) {
        $sql = "SELECT * FROM regions WHERE id = ?";
        $rows = [];

        if($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $id);
            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                while ($row=mysqli_fetch_assoc($result)) {
                    $rows[] = $row;
                }
                // Free result set
                mysqli_free_result($result);
            }
        }
        error_log(print_r($rows, true));
        $region = false;
        if (count($rows) === 1) {
            $region = $rows[0]['name'];
        }
        return $region;
    }

    static function getdistrictbyId($link, $id) {
        $sql = "SELECT * FROM districts WHERE id = ?";
        $rows = [];

        if($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $id);
            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                while ($row=mysqli_fetch_assoc($result)) {
                    $rows[] = $row;
                }
                // Free result set
                mysqli_free_result($result);
            }
        }

        $district = false;
        error_log(print_r($rows, true));
        if (count($rows) === 1) {
            $district = $rows[0]['name'];
        }
        return $district;
    }

    static function getcountry($country_code) {
        $c = false;
        switch ($country_code) {
            case 'NZ':
                $c = 'New Zealand';
            break;

        }
        return $c;
    }

    static function getaddress($link, $location, $district_id, $region_id, $country_code = 'NZ') {

        return $location . ',' . Address::getdistrictbyId($link, $district_id) . ',' . Address::getregionbyId($link, $region_id) . ',' . Address::getcountry($country_code);
    }
}

?>

