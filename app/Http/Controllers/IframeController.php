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

class IframeController extends Controller
{

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
        $data = $request->all();
        try{
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
        $price = !empty($query['price']) ? $query['price'] : 'Varies. Please call us for a price';
        $query = Widget_Quote::with('category', 'item', 'service', 'location')->find($widget->id);
        
        $store_hour = '';
        if (isset($query['location']['hours']) && !empty($query['location']['hours'])) {
            foreach ($query['location']['hours'] as $day=>$time) {
                $store_hour .= $day . ':' . date('h:iA', strtotime($time['from'])) . '-' . date('h:iA', strtotime($time['to'])) . ', ';
            }
            $store_hour = substr(trim($store_hour), 0, -1);
        }

        $shortcodes = [
            '[CUSTOMER_NAME]'   => $query['fullname'],
            '[CUSTOMER_EMAIL]'  => $query['email'],
            '[CUSTOMER_PHONE]'  => $query['phone'],
            '[CUSTOMER_MESSAGE]'=> $query['message'],
            '[PRICE]'           => $price,
            '[DEVICE]'          => $query['category']['name'],
            '[MODEL]'           => $query['item']['name'] ?? '',
            '[SERVICE]'         => $query['service']['name'],
            '[STORE_NAME]'      => $query['location']['store_name'],
            '[STORE_ADDRESS_1]' => $query['location']['address_1'],
            '[STORE_ADDRESS_2]' => $query['location']['address_2'],
            '[STORE_CITY]'      => $query['location']['city'],
            '[STORE_PHONE]'     => $query['location']['phone'],
            '[STORE_HOUR]'      => $store_hour,
            '[STORE_LOCATION_DESC]'  => $query['location']['description'],
        ];
        
        $location = $query['location'];

        /* Send EMail/SMS to Customer */
        $emailTemplate = [];
        $where = ['iframe_id'=>$widget['iframe_id'], 'email_type'=>'user_email'];
        if ( isset($data['series_id']) && !empty($data['series_id']) ) {
            $where_series = array_merge($where, ['series_id'=>$data['series_id']]);
            $emailTemplate = EmailTemplate::where($where_series)->first();
        }
        if ( !$emailTemplate ) {
            if ( isset($data['brand_id']) && !empty($data['brand_id']) ) {
                $where_brand = array_merge($where, ['brand_id'=>$data['brand_id']]);
                $emailTemplate = EmailTemplate::where($where_brand)->first();
            }
        }
        if ( !$emailTemplate ) {
            if ( isset($data['category_id']) && !empty($data['category_id']) ) {
                $where_category = array_merge($where, ['category_id'=>$data['category_id']]);
                $emailTemplate = EmailTemplate::where($where_category)->first();
            }
        }
        if ( !$emailTemplate ) {
            $emailTemplate = EmailTemplate::where($where)->first();
        }
        $emailTemplate = $emailTemplate->toArray();

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
        $where = ['iframe_id'=>$widget['iframe_id'], 'email_type'=>'client_email'];
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