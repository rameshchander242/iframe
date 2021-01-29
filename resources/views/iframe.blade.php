@extends('layouts.front')
@section('title','Edit')
@push('custom_styles')
<style>
.text-iframe, .fa, .fas {
    color: {{$iframe_color}};
}
.bg-iframe, .check_container input:checked ~ .checkmark {
    background-color: {{$iframe_color}};
}
</style>
@endpush

@section('content')
<?php header("Access-Control-Allow-Origin: *"); ?>
<div id="title">
    <h2>Instant Price Quote</h2>
</div>
<br>
<div id="navButtons">
    <a id="start_over" href="javascript:void(0)"><i class="fa fa-chevron-left"></i></a>
</div>

<div id="myProgress">
    <div id="myBar" class="bg-iframe"></div>
</div>
<br>
<div id="whole_frame">
    <div id="frame">
        <?php $data = json_decode($iframe_data, true); ?>
        <div id="select_device" class="slide-box" data-progress="0">
            @php
                if (!isset($data['categories']) || empty($data['categories'])) {
                    $error['description'] = 'Device Category not Set in Iframe';
                    $err->set_error_log($error);
                }
            @endphp
            @foreach ($data['categories'] as $category)
                <div class="selector" data-id="{{$category['id']}}" data-box="#select_device_{{$category['id']}}">
                    <div class="img-div">
                        <img class="images" src="{{$category['image']}}">
                    </div>
                    <br>
                    <p class="device_label">{{$category['name']}}</p>
                </div>
            @endforeach
        </div>
        
        @foreach ($data['categories'] as $category)
            <div id="select_device_{{$category['id']}}" class="slide-box" data-progress="20">
                @if ( isset($category['brands']) && !empty($category['brands']) )
                    @foreach ($category['brands'] as $brand)
                        <div class="selector" data-id="{{$brand['id']}}" data-box="#select_brand_{{$brand['id']}}">
                            <div class="img-div">
                                <img class="images" src="{{$brand['image']}}">
                            </div>
                            <br>
                            <p class="device_label">{{$brand['name']}}</p>
                        </div>
                    @endforeach
                @elseif ( isset($category['items']) && !empty($category['items']) )
                    @foreach ($category['items'] as $item)
                        <div class="selector" data-id="{{$item['id']}}" data-box="#select_service_{{$category['id']}}">
                            <div class="img-div">
                                <img class="images" src="{{$item['image']}}">
                            </div>
                            <br>
                            <p class="device_label">{{$item['name']}}</p>
                        </div>
                    @endforeach
                @else
                    @php
                        $error['description'] = 'Not any Device or Brand in \'' . $category['name'] . '\' Cateogry';
                        $err->set_error_log($error);
                    @endphp
                @endif
            </div>

            @if ( isset($category['brands']) && !empty($category['brands']) )
            @foreach ($category['brands'] as $brand)
                <div id="select_brand_{{$brand['id']}}" class="slide-box" data-progress="40">
                    @if ( isset($brand['series']) && !empty($brand['series']) )
                        @foreach ($brand['series'] as $series_data)
                          @if( in_array($series_data['id'], $series[$category['id']][$brand['id']] ?? []) )
                            <div class="selector" data-id="{{$series_data['id']}}" data-box="#select_series_{{$series_data['id']}}">
                                <div class="img-div">
                                    <img class="images" src="{{$series_data['image']}}">
                                </div>
                                <br>
                                <p class="device_label">{{$series_data['name']}}</p>
                            </div>
                          @endif
                        @endforeach
                    @elseif ( isset($brand['items']) && !empty($brand['items']) )
                        @foreach ($brand['items'] as $item)
                            <div class="selector" data-id="{{$item['id']}}" data-box="#select_service_{{$category['id']}}">
                                <div class="img-div">
                                    <img class="images" src="{{$item['image']}}">
                                </div>
                                <br>
                                <p class="device_label">{{$item['name']}}</p>
                            </div>
                        @endforeach
                    @else
                        @php
                            $error['description'] = 'Not any Device in \'' . $brand['name'] . '\' Brand';
                            $err->set_error_log($error);
                        @endphp
                    @endif
                </div>

                @if ( isset($brand['series']) && !empty($brand['series']) )
                    @foreach ($brand['series'] as $series_data)
                        <div id="select_series_{{$series_data['id']}}" class="slide-box" data-progress="50">
                            @if ( isset($series_data['items']) && !empty($series_data['items']) )
                            @foreach ($series_data['items'] as $item)
                                <div class="selector" data-id="{{$item['id']}}" data-box="#select_service_{{$category['id']}}">
                                    <div class="img-div">
                                        <img class="images" src="{{$item['image']}}">
                                    </div>
                                    <br>
                                    <p class="device_label">{{$item['name']}}</p>
                                </div>
                            @endforeach
                            @endif
                        </div>
                    @endforeach
                @endif
                
            @endforeach
            @endif
            
            <div id="select_service_{{$category['id']}}" class="slide-box" data-progress="60">
                @if ( isset($category['services']) && !empty($category['services']) )
                    @foreach ($category['services'] as $service)
                        <div class="selector" data-id="{{$service['id']}}" data-box="#select_location">
                            <div class="img-div">
                                <i class="fa-8x {{$service['icon']}}"></i>
                            </div>
                            <br>
                            <p class="device_label">{{$service['name']}}</p>
                            <p class="price_label">Price: <span class="price_details_label text-iframe">Instant Quote!</span></p>
                        </div>
                    @endforeach
                @else
                    @php
                        $error['description'] = 'Not any Service Provided on ' . $category['name'] . ' category';
                        $err->set_error_log($error);
                    @endphp
                @endif
            </div>
        @endforeach

        <div id="select_location" class="slide-box" data-progress="70">
            @if ( isset($data['locations']) && !empty($data['locations']) )
                @foreach ($data['locations'] as $location)
                    <div class="selector" data-id="{{$location['id']}}" data-box="#select_form">
                        <div class="locationInfo hidden"><?php echo json_encode($location); ?></div>
                        <div class="img-div">
                            <i class="fa-8x fa fa-building"></i>
                        </div>
                        <p class="device_label">{{$location['store_name']}}</p>
                        <p class="device_label_address">{{$location['address_1']}}<br>{{$location['address_2']}}</p>
                    </div>
                @endforeach
            @else
                @php
                    $error['description'] = 'Not any Location Provided on Iframe';
                    $err->set_error_log($error);
                @endphp
            @endif
        </div>

        <div id="select_form" class="slide-box" data-progress="80">
            <div id="quote_title">GOOD NEWS - YOUR QUOTE IS READY!</div>
            <div id="quote_form" class="uniform_height">
            
            <div id="form-section">
                <form id="contactForm" ><p class="contact_pref">Deliver by:</p>
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" id="user_id" name="user_id" value="{{$data['user_id']}}">
                    <input type="hidden" id="iframe_id" name="iframe_id" value="{{$data['id']}}">
                    <input type="hidden" class="dvalue" id="category_id" name="category_id" value="">
                    <input type="hidden" class="dvalue" id="brand_id" name="brand_id" value="">
                    <input type="hidden" class="dvalue" id="series_id" name="series_id" value="">
                    <input type="hidden" class="dvalue" id="item_id" name="item_id" value="">
                    <input type="hidden" class="dvalue" id="service_id" name="service_id" value="">
                    <input type="hidden" class="dvalue" id="location_id" name="location_id" value="">
                
                    <label class="check_container">Text (Instant)
                        <input id="radio_sms" type="radio" checked="checked" name="contact" value="sms" required="">
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_container">Email (Instant)
                        <input id="radio_email" type="radio" name="contact" value="email">
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_container">Phone Call
                        <input id="radio_phone" type="radio" name="contact" value="phone">
                        <span class="checkmark"></span>
                    </label>
                
                    <input type="text" id="input_name" class="form-control dvalue" name="fullname" placeholder="*Name" required="">
                    <br>
                    <input type="email" id="input_email" class="form-control dvalue" name="email" placeholder="*Email" required="">
                    <br>
                    <input type="text" id="input_phone" class="form-control dvalue" name="phone" placeholder="*Phone" required="">
                    <br>
                    <textarea id="input_notes" type="text" class="form-control dvalue" name="message" placeholder="Message (optional)" rows="4" cols="50"></textarea>
                    <br>
                    <button id="submit_btn" class="bg-iframe">Get Quote Now</button>
                </form>
            </div>
            </div>
            
            <div id="selected-options" class="uniform_height">
                <div id="selected-options-inside">
                    <p class="repair_title">Selected Repair</p>
                    <p class="dev_opt">Device: <span id="device_lbl" class="dev_sel text-iframe"></span></p>
                    <p class="dev_opt">Problem: <span id="service_lbl" class="dev_sel text-iframe"></span></p>
                    <p class="dev_opt">Timeframe: <span id="timeframe_lbl" class="dev_sel text-iframe">60 Minutes</span></p>
                    <p class="dev_opt">Warranty: <span id="warranty_lbl" class="dev_sel text-iframe">1 Year Warranty</span></p>
                    <p class="dev_opt">Description: <span id="desc_lbl" class="dev_sel_desc">Most LG Phone repairs are completed in an hour or two, though we often have to order parts. We use high quality parts and our technicians are expert trained. We also price match! Find another local shop with better pricing, we'll match it! Thousands of people have loved our service and we're sure you will too. Get your price quote now to get started with your repair. Look forward to speaking with you!</span></p>
                </div>
            </div>
            <div id="store_info" class="uniform_height">
                <table width="100%" border="0" style="padding: 5px 20px 0px 20px;">
                  <tr>
                    <td width="60%"><p class="repair_title">Selected Store:</p> </td>
                    <td width="2%"> </td>
                    <td width="38%"> <p style="font-size:12px; text-align: right;"><a id="change-location" class="text-iframe" href="javascript:void(0)">change</button></p></td>
                  </tr>
                </table>
                <div id="store_info_div" class="selected_store_info"></div>
                <div id="iframeHolder" style="width:100%;">
                    <iframe id="theMapFrame" src="" width="285" height="274" frameborder="0" style="" allowfullscreen=""></iframe>
                </div>
                <br>
            </div>
            
        </div>
        

        <div id="success-submit" class="slide-box" data-progress="100">
            {!! Html::image( 'images/thank_you.png', '', array('class' => 'img-thumb')) !!}
            <h2>We will conatct you as soon as possible</h2>
        </div>
    </div>
</div>

<p>&nbsp;</p>
<div id="fine_print" style="display: inline-block;">*Note: Instant price quotes are not available on ALL repairs. Some repairs require a staff member to collect more information. For these repairs, you will instantly receive some basic repair information, and a staff member will manually follow with a price ASAP.</div>
<p>&nbsp;</p>
@endsection

@push('custom_scripts')
{!! $google_analytic ?? '' !!}
<script type="text/javascript">
var widgetSubmitUrl = '{{ route('iframe.widget.submit') }}';
var redirect_to = "<?php echo $success_page ?? ''; ?>";
var iframe_info = <?php echo json_encode($iframe_info); ?>;
var iframe_infos = <?php echo json_encode($iframe_infos); ?>;

function start_frame() {
    $('.active').removeClass('active');
    $('.slide-box').first().addClass('active');
    $('.slide-box').hide();    
    $('.active').show();
    $('.dvalue').val('');
    move(0);
}
jQuery(document).ready(function($){
    
    $('#start_over').click( function() {
        start_frame();
    });
    start_frame();

    var selected_repair;
    

    $('.selector').click(function(){
        var selectDevice = $(this).attr('data-box');
        var dataId = $(this).attr('data-id');
        var sd = '#select_device_',
        sb = '#select_brand_',
        ss = '#select_series_',
        si = '#select_service_',
        sl = '#select_location',
        sf = '#select_form';
        if (selectDevice.search(sd) > -1) {
            $('#category_id').val(dataId);
            if ($.trim($(selectDevice).html())  == '') {
                goto_service(dataId);
                return;
            }

        } else if (selectDevice.search(sb) > -1) {
            $('#brand_id').val(dataId);
            if ($.trim($(selectDevice).html())  == '') {
                goto_service( $('#category_id').val() );
                return;
            }

        } else if (selectDevice.search(ss) > -1) {
            $('#series_id').val(dataId);
            if ($.trim($(selectDevice).html())  == '') {
                goto_service( $('#category_id').val() );
                return;
            }

        } else if (selectDevice.search(si) > -1) {
            $('#item_id').val(dataId);
            $('#device_lbl').html( $('p.device_label', this).html() );
            $('#desc_lbl').html( $('p.device_label_desc', this).html() );
            sendPostMessage();

        } else if (selectDevice.search(sl) > -1) {
            $('#service_id').val(dataId);
            $('#service_lbl').html( $('p.device_label', this).html() );
            if ($('#select_location .selector').length == 1) {
                $('#select_location .selector').click();
                return;
            }
            sendPostMessage();

        } else if (selectDevice.search(sf) > -1) {
            $('#location_id').val(dataId);
            var locationInfo = JSON.parse($('div.locationInfo', this).html());

            var cat_id = $('#category_id').val();
            var brand_id = $('#brand_id').val();
            var series_id = $('#series_id').val();
            var item_id = $('#item_id').val();
            
            if (cat_id != '' && iframe_info.categories && iframe_info.categories[cat_id]) {
                $('#timeframe_lbl').html(iframe_info.categories[cat_id][dataId].timeframe);
                $('#warranty_lbl').html(iframe_info.categories[cat_id][dataId].warranty);
                $('#desc_lbl').html(iframe_info.categories[cat_id][dataId].description);
            }
            if (brand_id != '' && iframe_info.brands && iframe_info.brands[cat_id]) {
                $('#timeframe_lbl').html(iframe_info.brands[brand_id][dataId].timeframe);
                $('#warranty_lbl').html(iframe_info.brands[brand_id][dataId].warranty);
                $('#desc_lbl').html(iframe_info.brands[brand_id][dataId].description);
            } 
            if (series_id != '' && iframe_info.serieses && iframe_info.serieses[series_id]) {
                $('#timeframe_lbl').html(iframe_info.serieses[series_id][dataId].timeframe);
                $('#warranty_lbl').html(iframe_info.serieses[series_id][dataId].warranty);
                $('#desc_lbl').html(iframe_info.serieses[series_id][dataId].description);
            } 
            if (item_id != '' && iframe_info.items && iframe_info.items[item_id]) {
                $('#timeframe_lbl').html(iframe_info.items[item_id][dataId].timeframe);
                $('#warranty_lbl').html(iframe_info.items[item_id][dataId].warranty);
                $('#desc_lbl').html(iframe_info.items[item_id][dataId].description);
            }
            var strore_info = locationInfo.address_1 ? locationInfo.address_1 + '<br>' : '';
            strore_info += locationInfo.address_2 ? locationInfo.address_2 + '<br>' : '';
            strore_info += locationInfo.phone ? locationInfo.phone + '<br>' : '';
            $('#store_info_div').html( strore_info );
            $('#theMapFrame').attr('src', locationInfo.map_url);
            sendPostMessage();
            
        }

        $('.active').removeClass('active'); 
        $(selectDevice).addClass('active');

        $('.slide-box').hide();
        $('.active').fadeIn("slow");
        
        var progress = $(selectDevice).attr('data-progress');
        move(progress)
    });

    $('#change-location').click( function() {
        $('.active').removeClass('active'); 
        $('#select_location').addClass('active');
        $('.slide-box').hide();
        $('.active').fadeIn("slow");
    });
    
    $('#contactForm').submit(function(){
        var formData = $(this).serialize();
        $('#submit_btn').html('Submitting &nbsp; <i class="fa fa-spin fa-spinner"></i>');
        $('#submit_btn').attr('disabled', 'disabled');
        $('#app-iframe').addClass('cursor-progress');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            url:widgetSubmitUrl,
            type:'POST',
            data:formData,
            success:function(result){
                if (redirect_to != '') {
                    window.top.location.href = redirect_to;
                    // window.parent.location = redirect_to;
                } else {
                    $('.slide-box').hide();
                    $('#success-submit').show();
                    $('.dvalue').val('');
                }
            }
        });
        return false;
    });
});


var i = 0;
function move(percentage = 0) {
    if (percentage > 0) {
        $('#myProgress').show();
        $('#start_over').show();
    } else {
        $('#myProgress').hide();
        $('#start_over').hide();
    }
    if (i == 0) {
        i = 1;
        var elem = document.getElementById("myBar");
        var width = 1;
        var id = setInterval(frame, 10);
        function frame() {
            if (width >= percentage) {
                clearInterval(id);
                i = 0;
            } else {
                width++;
                elem.style.width = width + "%";
            }
        }
    };
    sendPostMessage();
}

function goto_service(cat_service_id) {
    $('.active').removeClass('active');
    var selectDevice = '#select_service_' + cat_service_id;
    $(selectDevice).addClass('active');
    $('.slide-box').hide();
    $('.active').fadeIn("slow");
    
    var progress = $(selectDevice).attr('data-progress');
    move(progress)
}

var appIframe = 'app-iframe';
var height;
const sendPostMessage = () => {
    if (height !== document.getElementById(appIframe).offsetHeight) {
      height = document.getElementById(appIframe).offsetHeight;
      window.parent.postMessage({
        frameHeight: height
      }, '*');
    }
}
window.onload = () => sendPostMessage();
window.onresize = () => sendPostMessage();
</script>
@endpush