<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Iframe;
use App\Models\Iframe_Info;
use App\Models\EmailTemplate;
use App\Models\Location;
use App\Models\User;
use App\Models\Widget_Quote;
use App\Http\Controllers\ErrorLogController;
use App\Mail\SendGridEmail;

class IframeController extends Controller {
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function iframe($id) {

        $iframe_infos = Iframe_Info::where('iframe_id', $id)->where('status', '1')->get()->toArray();
        $iframe_info['categories'] = $iframe_info['brands'] = [];
        foreach ($iframe_infos as $info) {
            $data = [
                    'timeframe'     => $info['timeframe'], 
                    'warranty'      => $info['warranty'], 
                    'description'   => $info['description']
            ];
            if ( !empty($info['item_id']) ) {
                $iframe_info['items'][$info['item_id']][$info['location_id']] = $data;
            } elseif ( !empty($info['series_id']) ) {
                $iframe_info['serieses'][$info['series_id']][$info['location_id']] = $data;
            } elseif ( !empty($info['brand_id']) ) {
                $iframe_info['brands'][$info['brand_id']][$info['location_id']] = $data;
            } else {
                $iframe_info['categories'][$info['category_id']][$info['location_id']] = $data;
            }
        }
        $error = [
            'iframe_id' => $id,
            'client_name'   => '',
        ];
        $err = new ErrorlogController();
        // $err->set_error_log($error);

        $iframe = Iframe::findOrFail($id);
            return view('iframe', $iframe, compact('iframe_infos', 'iframe_info', 'error', 'err'));
    }

    public function submit_widget(Request $request) {
        $err = new ErrorlogController();
        try{
            $data = $request->all();
            $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $iframe = Iframe::find($data['iframe_id']);
            $itemServices = json_decode($iframe['item_service'], true);
            $price = $itemServices[$data['category_id']][$data['item_id']][$data['service_id']][$data['location_id']] ?? '';
            if ( empty($price) ) {
                $price = $itemServices[$data['category_id']][$data['item_id']][$data['service_id']]['default'] ?? '';
            }
            $data['price'] = $price;
            $widget = Widget_Quote::create($data);
        } catch(Exception $e) {
            $error = [
                'iframe_id' => $data['iframe_id'],
                'client_name'   => '',
                'description'   => 'Error to Submit Form'
            ];
            $err->set_error_log($error);
        }

        $query = Widget_Quote::with('category', 'item', 'service', 'location')->find($widget->id);
        $shortcodes = [
            '[CUSTOMER_NAME]' => $query['fullname'],
            '[CUSTOMER_EMAIL]' => $query['email'],
            '[CUSTOMER_PHONE]' => $query['phone'],
            '[CUSTOMER_MESSAGE]' => $query['message'],
            '[PRICE]' => $query['price'],
            '[DEVICE]' => $query['category']['name'],
            '[MODEL]' => $query['item']['name'],
            '[SERVICE]' => $query['service']['name'],
            '[STORE_NAME]' => $query['location']['store_name'],
            '[STORE_DETAIL]' => $query['location']['address_1'],
        ];
        // echo "<pre>"; print_r($email_shortcode); exit;
        
        $location = $query['location'];

        /* Send EMail/SMS to Customer */
        $where = ['iframe_id'=>$widget['iframe_id'], 'email_type'=>'client_email'];
        $emailTemplate = EmailTemplate::where($where)->first()->toArray();
        if ( $widget['contact'] == 'sms' ) {
            $sms_data = [
                'from'  => $location['ctm_code'],
                'to'  => $widget['phone'],
                'msg'  => shortcode_to_html( $emailTemplate['sms_message'], $shortcodes )
            ];
            $user = USER::find($iframe['user_id']);
            if ( !send_sms_message($sms_data, $user['ctm_auth']) ) {
                $error = [
                    'iframe_id' => $data['iframe_id'],
                    'client_name'   => '',
                    'description'   => 'Error to Send SMS to Customer'
                ];
                $err->set_error_log($error);
            }
        } elseif ( $widget['contact'] == 'email' ) {
            $mail_data = [
                'email' => $widget['email'], 
                'name'  => $widget['fullname'], 
                'subject'   => $emailTemplate['subject'], 
                'body'  => shortcode_to_html( $emailTemplate['body'], $shortcodes )
            ];
            $resp = sendgrid_mail( $mail_data );
            if ( isset($resp['error']) ) {
                $error = [
                    'iframe_id' => $data['iframe_id'],
                    'client_name'   => '',
                    'description'   => 'Error to Send Mail to Customer on Email ID '.$mail_data['email']
                ];
                $err->set_error_log($error);
            }
        }

        /* Send Email to Client */
        $where = ['iframe_id'=>$widget['iframe_id'], 'email_type'=>'user_email'];
        $emailTemplate = EmailTemplate::where($where)->first()->toArray();
        $froms = explode(',', ($location['additional_email']));
        $froms[] = $location['email'];
        
        $emailHtml = '<table>
            <tr><td>Name</td><td>'.$widget['fullname'].'</td></tr>
            <tr><td>Email</td><td>'.$widget['email'].'</td></tr>
            <tr><td>Phone</td><td>'.$widget['phone'].'</td></tr>
            <tr><td>Message</td><td>'.$widget['message'].'</td></tr>
        </table>';
        foreach ($froms as $from) {
            if (empty( trim($from) ))
                continue;
            $mail_data = [
                'email' => trim($from),
                'name'  => $widget['store_name'], 
                'subject'   => $emailTemplate['subject'], 
                'body'  => $emailHtml . shortcode_to_html( $emailTemplate['body'], $shortcodes )
            ];
            $resp1 = sendgrid_mail( $mail_data );
            if ( isset($resp1['error']) ) {
                $error = [
                    'iframe_id' => $data['iframe_id'],
                    'client_name'   => '',
                    'description'   => 'Error to Send Mail to Owner'
                ];
                $err->set_error_log($error);
            }
        }
        
        return response()->json(['success' => true, 'data'   => 'Successfully Submit']); 
    }
}