<?php
if (!function_exists('upload_path')) {
    function upload_path($path) {
        $uploadDir = storage_path('app/public/uploads');
        return $uploadDir . '/' . $path;
    }
}

if (!function_exists('upload_url')) {
    function upload_url($path) {
        return 'storage/uploads/' . $path . '/';
    }
}

if (!function_exists('back_button')) {
    function back_button($url, $button="Back") {
        $title = empty($button) ? 'Back' : $button;
        $button = Form::button($button, ['class' => 'btn btn-dark btn-lg', 'onclick' => 'window.location="'.$url.'"']);
        return $button;
    }
}

if (!function_exists('delete_button')) {
    function delete_button($url, $button="") {
        $title = empty($button) ? 'Delete' : $button;
        $button = '<a href="javascript:void(0)" class="delete-action btn btn-danger btn-sm" title="' . $title . '">
            <i class="fa fa-trash"></i> ' . $button . '</a>
        <form method="POST" action="' . $url . '">' . 
            csrf_field() . 
            method_field("DELETE") . '
        </form>';
        return $button;
    }
}

if (! function_exists('edit_button') ) {
    function edit_button ($url, $icon="fa-edit", $button="") {
        $title = empty($button) ? 'Edit' : $button;
        return '<a href="' . $url . '" class="edit btn btn-primary btn-sm" title="' . $title . '">
            <i class="fa ' . $icon . '"></i>' . $button . '</a>';
    }
}

if (! function_exists('view_button') ) {
    function view_button ($url, $icon="fa-eye", $button="", $attr='') {
        $title = empty($button) ? 'View' : $button;
        return '<a href="' . $url . '" class="viwe btn btn-success btn-sm" title="' . $title . '" '.$attr.'>
            <i class="fa ' . $icon . '"></i>' .  '</a>';
    }
}

if (! function_exists('view_status') ) {
    function view_status ($status) {
        return $status=='1' ? '<i class="fa fa-check-circle text-success"></i>' : '<i class="fa fa-times-circle text-danger"></i>';
    }
}

function modal_button($id, $icon="fa-link", $button="") {
    $link = route('iframe.widget', $id);

    $iframeData = '<iframe src="'.$link.'" width="100%" id="iframe_widget" frameborder="0" scrolling="no"></iframe>
<script>
    window.onmessage = (e) => {
        if (e.data.hasOwnProperty("frameHeight")) {
            document.getElementById("iframe_widget").style.height = `${e.data.frameHeight + 30}px`;
        }
    };
</script>';

    $html = '<a href="javascript:void(0)" class="btn btn-success btn-sm" data-toggle="modal" data-target="#iframe_widget'.$id.'"><i class="fa '.$icon.'"></i>' . $button . '</a>
    <div class="modal fade" id="iframe_widget'.$id.'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Copy IFrame</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="copy_content_'.$id.' text-wrap">
                    <pre>'.(htmlspecialchars($iframeData)).'</pre>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="copyToClipboard(\'.copy_content_'.$id.'\')" data-toggle="popover" data-placement="top" data-content="Copy Successfully."><i class="fa fa-copy"></i> Copy</button>
            </div>
            </div>
        </div>
    </div>';
    return $html;
}
if (! function_exists('title') ) {
    function title($title) {
        return ucwords( str_replace( ['_', '-'], ' ', $title ) );

    }
}

function shortcode_to_html($content, $shortcodes) {
    return str_replace( array_keys($shortcodes), array_values($shortcodes), $content);
}

function error_email($msg) {
    $curl = curl_init();
    $email  = 'expertweb101@gmail.com';
    $name   = 'Expert Web';
    $body   = $msg;
    $subject = 'Error on Send Email to Customer';
    $from = $data['from'] ?? 'cleveland@computerrepairdoctor.com';

    $data1 = array(
        "personalizations" => array(
            array(
                "to" => array(
                    array(
                        "email" => $email,
                        "name" => $name
                    )
                )
            )
        ),
        "from" => array(
            "email" => $from
        ),
        "subject" => $subject,
        "content" => array(
            array(
                "type" => "text/html",
                "value" => $body
            )
        )
    );

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data1),
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer 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",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));
}

function sendgrid_mail($data) {

    $curl = curl_init();
    $email  = $data['email'];
    $name   = $data['name'];
    $body   = $data['body'];
    $subject = $data['subject'];
    $from = $data['from'] ?? 'cleveland@computerrepairdoctor.com';

    $data1 = array(
        "personalizations" => array(
            array(
                "to" => array(
                    array(
                        "email" => $email,
                        "name" => $name
                    )
                )
            )
        ),
        "from" => array(
            "email" => $from
        ),
        "subject" => $subject,
        "content" => array(
            array(
                "type" => "text/html",
                "value" => $body
            )
        )
    );

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data1),
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer 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",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $resp = json_decode($response, true);
    if ( $err ) {
        return ['error' => $err];
    }
    
    if ( isset($resp['errors']) ) {
        return [ 'error' => $resp['errors'][0]['message'] ];
    }
    return true;
}

function send_sms_message($data, $authkey) {
    $data['to'] = '+1'.$data['to'];
    $accountkey = '153555';

    $url = 'https://api.calltrackingmetrics.com/api/v1/accounts/'.$accountkey.'/sms';


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic ".$authkey,
            "cache-control: no-cache",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return false;
    }
    return true;
}


function sort_data($array, $sortArray=[], $format='value') {
    // echo "<pre>"; print_r($array); print_r($sortArray); echo "</pre>";
    if( is_array($array) && !empty($array) && is_array($sortArray) && !empty($sortArray) ) {
        $ordered = array();
        foreach ($sortArray as $skey=>$sort) {
            foreach ($array as $key=>$val) {
                $sVal = ($format == 'value') ? $sort : $skey;
                if ($sVal == $val['id']) {
                    $ordered[] = $val;
                    unset($array[$key]);
                }
            }
        }
        if (is_array($array)) {
            $ordered = array_merge($ordered, $array);
        }
        return $ordered;
    } else {
        return $array;
    }
}


function getBrowser() { 
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
  
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
      $platform = 'linux';
    }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
      $platform = 'mac';
    }elseif (preg_match('/windows|win32/i', $u_agent)) {
      $platform = 'windows';
    }
  
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
      $bname = 'Internet Explorer';
      $ub = "MSIE";
    }elseif(preg_match('/Firefox/i',$u_agent)){
      $bname = 'Mozilla Firefox';
      $ub = "Firefox";
    }elseif(preg_match('/OPR/i',$u_agent)){
      $bname = 'Opera';
      $ub = "Opera";
    }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
      $bname = 'Google Chrome';
      $ub = "Chrome";
    }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
      $bname = 'Apple Safari';
      $ub = "Safari";
    }elseif(preg_match('/Netscape/i',$u_agent)){
      $bname = 'Netscape';
      $ub = "Netscape";
    }elseif(preg_match('/Edge/i',$u_agent)){
      $bname = 'Edge';
      $ub = "Edge";
    }elseif(preg_match('/Trident/i',$u_agent)){
      $bname = 'Internet Explorer';
      $ub = "MSIE";
    }
  
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
  ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
      // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
      //we will have two since we are not using 'other' argument yet
      //see if version is before or after the name
      if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
          $version= $matches['version'][0];
      }else {
          $version= $matches['version'][1];
      }
    }else {
      $version= $matches['version'][0];
    }
  
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
  
    return array(
      'userAgent' => $u_agent,
      'name'      => $bname,
      'version'   => $version,
      'platform'  => $platform,
      'pattern'    => $pattern
    );
}