<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Error_log;

class ErrorLogController extends Controller {

    public function set_error_log($data) {
        $browser = getBrowser();
        $ip = $_SERVER['REMOTE_ADDR'];
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
        $location =  ($details->city ?? '') . ', ' . ($details->region ?? '');
        $defaultData = [
            'browser'       => $browser['name'] . ' ' . $browser['version'] . ' in ' . $browser['platform'],
            'ip_address'    => $ip,
            'location'      => rtrim( trim($location), ','),
            'date_time'     => date('Y-m-d H:i:s'),
            'description'   => 'Error in IFrame',
        ];
        $data = array_merge($defaultData, $data);
        $el_id = Error_log::insert($data);
        
        return $el_id;
    }
}
