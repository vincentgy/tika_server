<?php
include_once "./models/user.php";
include_once "./models/geometry.php";
include_once "./models/job.php";

$response = array();
$data = json_decode(file_get_contents('php://input'), true);
error_log(print_r($data, true));
$req = $data["param"];

if(isset($req['a'])) {
    switch ($req['a']) {
        case 'ul':// user login
            if (USER::checkuser($req['e'], $req['p']) === true) {
                $response['ret'] = 0;
            }
            else {
                $response['ret'] = 1;
            }
        break;
        case 'ur':// user register
            if (isset($req['e']) === false ||
                isset($req['p']) === false ||
                isset($req['n']) === false) {
                    $response['ret'] = -2;
            }
            else {
                if (USER::adduser($req['e'], $req['p'], $req['n']) === true) {
                    $response['ret'] = 0;
                }
                else {
                    $response['ret'] = 1;
                }
            }
        break;
        case 'jc':// job categories
            $categories = JOB::getcategories();
            if ($categories !== false) {
                $response['ret'] = 0;
                $response['data'] = json_encode($categories);
            }
            else {
                $response['ret'] = 1;
            }
        break;
        case 'jt':// job types
            $categories = JOB::gettypes();
            if ($categories !== false) {
                $response['ret'] = 0;
                $response['data'] = json_encode($categories);
            }
            else {
                $response['ret'] = 1;
            }
        break;
        case 'jpt':// job pay types.
            $categories = JOB::getpaytypes();
            if ($categories !== false) {
                $response['ret'] = 0;
                $response['data'] = json_encode($categories);
            }
            else {
                $response['ret'] = 1;
            }
        break;
        case 'lr':// list regions
            $regions = isset($req['c']) ? JOB::getregionsbycountrycode($req['c']) : JOB::getregionsbycountrycode();
            if ($regions !== false) {
                $response['ret'] = 0;
                $response['data'] = json_encode($regions);
            }
            else {
                $response['ret'] = 1;
            }
        break;
        case 'ld':// get districts by region id.
            if (isset($req['r']) === false) {
                $response['ret'] = -2;
            }
            else {
                $districts = JOB::getdistrictsbyregion($req['r']);
                if ($districts !== false) {
                    $response['ret'] = 0;
                    $response['data'] = json_encode($districts);
                }
                else {
                    $response['ret'] = 1;
                }
            }
        break;
        case 'pj':// post job
            if (isset($req['title']) === false ||
                isset($req['company']) === false ||
                isset($req['user_id']) === false  ||
                isset($req['categories']) === false  ||
                isset($req['type']) === false  ||
                isset($req['pay_type']) === false ||
                isset($req['minimum_pay']) === false ||
                isset($req['region_id']) === false ||
                isset($req['district_id']) === false
                ) {
                $response['ret'] = -2;
            }
            else {
                $r = JOB::addjob($req['title'], $req['company'], $req['user_id'], $req['type'], $req['pay_type'], $req['minimum_pay'], $req['maximum_pay'], $req['number'], $req['region_id'], $req['district_id'], $req['location'], $req['categories']);
                if ($r !== false) {
                    $response['ret'] = 0;
                }
                else {
                    $response['ret'] = 1;
                }
            }
        break;
        case 'cl':// current location
            $address = Geometry::covertToAddress($req['lat'], $req['lng']);
            if ($address !== false) {
                $response['ret'] = 0;
                $response['data'] = json_encode($address);
            }
            else {
                $response['ret'] = 1;
            }
        break;
    default:
        $response['ret'] = -1;
   }
}
else {
}

header('Content-Type: application/json');
echo json_encode($response);
?>
