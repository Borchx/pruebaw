<?php
/*
 * Plugin Name: WooCommerce Wintopay Payment Gateway
 * Description: Wintopay Payment Gateway for woocommerce,Take credit card payments on your store.
 * Author: Wintopay
 * Author URI: https://www.wintopay.com
 * Version: 3.0.0
 */
/*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
error_reporting(E_ERROR || E_STRICT);
add_filter( 'woocommerce_payment_gateways', 'wintopay_add_gateway_class' );
function wintopay_add_gateway_class( $gateways ) {
    $gateways[] = 'Wintopay_Gateway'; // your class name is here
    return $gateways;
}
/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'woocommerce_thankyou', 'coupon_thankyou', 10, 2 );
function coupon_thankyou( $order_id) {
    $status = empty($_REQUEST['status'])?'':$_REQUEST['status'];
    $order = wc_get_order( $order_id );
    if($status){
        if($status == 'paid'){
            echo "<section class='woocommerce-thankyou-coupon'><h2 class='woocommerce-column__title'>Payment Success!</h2><div class='tybox'><p>Thank you for shopping with us. Your account has been charged and your transaction is successful. We will be shipping your order to you soon.</p></div></section>";
        }elseif($status == 'pending'){
            echo "<section class='woocommerce-thankyou-coupon'><h2 class='woocommerce-column__title'>Payment pending!</h2><div class='tybox'><p>The order has been successfully submitted. Waiting payment processing and the payment result will be sent your email.</p></div></section>";
        }else{
            echo "<section class='woocommerce-thankyou-coupon'><h2 class='woocommerce-column__title'>Payment Failed!</h2><div class='tybox'><p>Thank you for shopping with us. However, the transaction has been declined.</p></div></section>";
        }
    }else{
        if ( $order->get_status() == 'pending' ) {
            echo "<section class='woocommerce-thankyou-coupon'><h2 class='woocommerce-column__title'>Payment pending!</h2><div class='tybox'><p>The order has been successfully submitted. Waiting payment processing and the payment result will be sent your email.</p></div></section>";
        }elseif($order->get_status() == 'processing'){
            echo "<section class='woocommerce-thankyou-coupon'><h2 class='woocommerce-column__title'>Payment Success!</h2><div class='tybox'><p>Thank you for shopping with us. Your account has been charged and your transaction is successful. We will be shipping your order to you soon.</p></div></section>";
        }else{
            echo "<section class='woocommerce-thankyou-coupon'><h2 class='woocommerce-column__title'>Payment Failed!</h2><div class='tybox'><p>Thank you for shopping with us. However, the transaction has been declined.</p></div></section>";
        }
    }
}

//异步推送接收
function check_wintopay_response_api()
{
    $apiclass = new Wintopay_Gateway();
    $md5key = $apiclass->md5key;
    $merchant_id = $apiclass->merchant_id;
    $form_data = $_REQUEST;

    if(!empty($form_data['pay_type']) && !empty($form_data['result_code'])){
        //3d 支付跳转
        $id         = isset($form_data['id'])?$form_data['id']:'';
        $pay_type   = isset($form_data['pay_type'])?$form_data['pay_type']:'';
        $result_code  = isset($form_data['result_code'])?$form_data['result_code']:'';
        $card_no   = isset($form_data['card_no'])?$form_data['card_no']:'';
        $card_orgn   = isset($form_data['card_orgn'])?$form_data['card_orgn']:'';
        $order_id   = isset($form_data['order_id'])?$form_data['order_id']:'';
        $amount   = isset($form_data['amount'])?$form_data['amount']:'';
        $currency   = isset($form_data['currency'])?$form_data['currency']:'';
        $sign_verify   = isset($form_data['sign_verify'])?$form_data['sign_verify']:'';
        $result_msg   = isset($form_data['result_msg'])?$form_data['result_msg']:'';
        $metadata   = isset($form_data['metadata'])?$form_data['metadata']:'';
        $website =$_SERVER['HTTP_HOST'];
        $str = $merchant_id.$md5key.$order_id.$amount.$currency.$website.$result_code;
        if(strpos($order_id,'_')){
            $order_arr = explode('_',$order_id);
            $order_id = $order_arr[0];
        }

        if(hash('sha256',$str) == $sign_verify){
            $order = new WC_Order($order_id);
            if($result_code == '0000'){
                $order->update_status('processing','WTP3D return - Payment Succeed.','woocommerce');//WTPCallback
                $order->payment_complete($order_id);
                $pay_method = new Wintopay_Gateway();
                $url = $pay_method->get_return_url( $order );
                //页面跳转
                header('Location: '.$url);
                exit;
            }else{
                $order = new WC_Order($order_id);
                $msg['message'] = "WTP3D return - Payment fail: " .strip_tags($result_msg .' .'. $result_code);
                $order->update_status( 'failed', $msg['message'] );
                $url = esc_url( wc_get_checkout_url() );
                wc_add_notice( $result_msg, 'error' );
                header('Location: '.$url);
                exit;
            }
        }else{
            $url = esc_url( wc_get_checkout_url() );
            wc_add_notice( $result_msg, 'error' );
            header('Location: '.$url);
            exit;
        }
    }

    //异步接收
    $result = file_get_contents('php://input','r');
    $data = json_decode($result,true);
    $id         = isset($data['id'])?$data['id']:''; 			//流水号
    $order_id   = isset($data['order_id'])?$data['order_id']:'';	//订单号
    $status     = isset($data['status'])?$data['status']:'';		//支付状态
    $currency   = isset($data['currency'])?$data['currency']:'';	//币种
    $amount_value= isset($data['amount_value'])?$data['amount_value']:'';//金额，单位为 分
    $metadata   = isset($data['metadata'])?$data['metadata']:'';
    $fail_code  = isset($data['fail_code'])?$data['fail_code']:'';
    $fail_message= isset($data['fail_message'])?$data['fail_message']:'';
    $request_id = isset($data['request_id'])?$data['request_id']:'';
    $sign_verify= isset($data['sign_verify'])?$data['sign_verify']:''; //加密
    $str = $id.$status.$amount_value.$md5key.$merchant_id.$request_id;
    if(strpos($order_id,'_')){
        $order_arr = explode('_',$order_id);
        $order_id = $order_arr[0];
    }
    if(hash('sha256',$str) == $sign_verify){
        $order = new WC_Order($order_id);
        if($order){
            $order_status = $order->get_status();
            if($order_status == 'processing'){
                exit('[success]');
            }
            if($status == 'paid'){
                $order->update_status('processing');
                $order->add_order_note(__( 'WTPCallback - Payment Succeed.','woocommerce'));
                $order->payment_complete($order_id);
            }elseif($status == 'pending'){
                $order->add_order_note(__( 'WTPCallback - Payment Pending.','woocommerce'));
            }else{
                $msg['message'] = "WTPCallback - Payment fail: " .strip_tags($fail_message .' .'. $fail_code);
                $order->update_status( 'failed', $msg['message'] );
            }
        }else{
            die('can not find order in website');   //无此订单号在网站上
        }
        exit('[success]');
    }else{
        exit('[failed]');
    }
}


add_action('woocommerce_api_wintopay_api', 'check_wintopay_response_api' );
add_action('woocommerce_api_wintopay_success', 'check_wintopay_success_page' );
add_action('woocommerce_api_wintopay_processing', 'check_wintopay_processing_page' );
add_action('woocommerce_api_wintopay_fail', 'check_wintopay_fail_page' );


//添加后台插件setting按钮
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'wintopay_woocommerce_addon_settings_link' );
function wintopay_woocommerce_addon_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=wintopay">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
    return $links;
}
//插件初始配置方法
add_action( 'plugins_loaded', 'wintopay_init_gateway_class' );
function wintopay_init_gateway_class() {

    class Wintopay_Gateway extends WC_Payment_Gateway {
        protected $redirect_url = '';
        /**
         * Class constructor, more about it in Step 3
         */
        public function __construct() {
            $this->id = 'wintopay'; // payment gateway plugin ID
            $this->has_fields = true; // in case you need a custom credit card form
            $this->method_title = 'Wintopay Gateway';
            $this->method_description = 'Wintopay payment gateway'; // will be displayed on the options page
            $this->notify_url = home_url('/?wc-api=wintopay_api');
            add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
            $this->supports = array(
                'products'
            );
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option( 'title' );
            $this->description = $this->get_option( 'description' );
            $this->enabled = $this->get_option( 'enabled' );
            $this->payment_options = 'yes' === $this->get_option( 'payment_options' );
            $this->merchant_id = $this->settings['merchant_id'];
            $this->md5key = $this->settings['md5key'];
            $this->wccpay_cardtypes         = $this->get_option( 'wintopay_cardtypes');
            $this->gateway_url    = ( $this->settings['gateway_url']?$this->settings['gateway_url']:'https://api.win4mall.com/api/v1/cashier/payment' );
            $this->notifyapi_url    = ( $this->settings['notifyapi_url']?$this->settings['notifyapi_url']:$this->notify_url );
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_receipt_payment_ccwonline', array( $this, 'receipt_page' ) );

        }

        public function init_form_fields(){
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'cc_gateway'),
                    'type' => 'checkbox',
                    'label' => __('Enable Payment Gateway, it will be visible on checkout page.', 'cc_gateway'),
                    'default' => 'no'
                ),
                'payment_options' => array(
                    'title' => __('popout payment', 'cc_gateway'),
                    'type' => 'checkbox',
                    'default' => 'yes',
                    'description' => __('payment options,popout or redirect.', 'cc_gateway'),
                ),
                'title' => array(
                    'title' => __('Title:', 'cc_gateway'),
                    'type' => 'text',
                    'description' => __('The title will be shown to the user during checkout.', 'cc_gateway'),
                    'default' => __('Credit Card', 'cc_gateway')
                ),
                'merchant_id' => array(
                    'title' => __('Merchant ID', 'cc_gateway'),
                    'type' => 'text',
                    'description' => __('Mercahnt ID/User ID', 'cc_gateway'),
                    'default' => __('70204', 'cc_gateway')
                ),
                'md5key' => array(
                    'title' => __('Md5 Key', 'cc_gateway'),
                    'type' => 'text',
                    'description' => __('Md5 Key with Merchant ID.', 'cc_gateway'),
                    'default' => __('Ak(SKe]rB2Yj', 'cc_gateway')
                ),
                'gateway_url' => array(
                    'title' => __('Gateway URL', 'cc_gateway'),
                    'type' => 'text',
                    'description' => __('Payment Gateway Url', 'cc_gateway'),
                    'default' => __('https://api.win4mall.com/api/v1/cashier/payment', 'cc_gateway')
                ),
                'wintopay_cardtypes' => array(
                    'title'    => __( 'Accepted Cards', 'woocommerce' ),
                    'type'     => 'multiselect',
                    'class'    => 'chosen_select',
                    'css'      => 'width: 350px;',
                    'desc_tip' => __( 'Select the card types to accept.', 'woocommerce' ),
                    'options'  => array(
                        'visa'             => 'Visa',
                        'mastercard'       => 'MasterCard',
                        'jcb'		       => 'JCB',
                        'amex' 		       => 'American Express',
                        'dn'		       => 'Diners club',
                        'dc'		       => 'Discover',
                    ),
                    'default' => array( 'visa','mastercard', 'jcb' ),
                ),
                'notifyapi_url' => array(
                    'title' => __('Return API URL', 'cc_gateway'),
                    'type' => 'text',
                    'description' => __('Payment Return API URL', 'cc_gateway'),
                    'default' => __($this->notify_url , 'cc_gateway')
                ),
            );
        }

        /**
         * You will need it if you want your custom credit card form, Step 4 is about it
         */
        public function payment_fields() {

            $script = 'https://js.win4mall.com/js/shield';
            $nowy_a =date('Y');
            $nowy_b =$nowy_a+17;
            $year_list  = '';
            for($i=$nowy_a;$i<=$nowy_b;$i++){
                $year_list.= '<option value="'.$i.'">'.$i.'</option>';
            }
            echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';
            do_action( 'woocommerce_credit_card_form_start', $this->id );
            $card_width = '50';
            $cardtypes = $this->wccpay_cardtypes;
            foreach ($cardtypes as $key=>$value){
                $img_url = plugins_url('images/'.$value.'.jpg', __FILE__);
                $imagess .= '<img src="'.$img_url.'" style="width: '.$card_width.'px;"  alt=""> ';
            }
            echo <<<Fewfw

<script src="$script"></script>
<div class="container-fluid card-icon" style="text-align: center;width:100%;height:30px;">
$imagess
<input type="hidden" name="session_id" id="session_id" class="session_id" value="">
</div>
		<script type="text/javascript">
		var session_id = wintopayShield.getSessionId();
        document.getElementById("session_id").value=session_id;

</script>
Fewfw;
            echo '<div class="clear"></div></fieldset>';
            do_action( 'woocommerce_credit_card_form_end', $this->id );

        }

        public function payment_scripts() {
            if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
                return;
            }

            if ( 'no' === $this->enabled ) {
                return;
            }

            if ( empty( $this->merchant_no ) || empty( $this->mdkey ) ) {
                return;
            }
        }

        /*
          * 订单信息验证函数
         */
        public function validate_fields() {
            return true;
        }

        /*
         * 订单支付处理函数，提交支付，处理结果。
         */
        public function process_payment( $order_id )
        {
            global $woocommerce;

            // we need it to get any order detailes
            $order = wc_get_order($order_id);
            //支付失败，修改订单号已支持重复支付

            $endline = date('is', time());
            $order_id = $order_id . '_' . $endline;

            $Currency = get_woocommerce_currency();
            $billing_first_name = $order->get_billing_first_name();
            $billing_last_name = $order->get_billing_last_name();
            $billing_company = $order->get_billing_company();
            $billing_country = $order->get_billing_country();
            $billing_address_1 = $order->get_billing_address_1();
            $billing_address_2 = $order->get_billing_address_2();
            $billing_city = $order->get_billing_city();
            $billing_state = $order->get_billing_state() ? $order->get_billing_state() : $billing_city;
            $billing_postcode = $order->get_billing_postcode();
            $billing_phone = $order->get_billing_phone();
            $billing_email = $order->get_billing_email();
            $shipping_first_name = $order->get_shipping_first_name() ? $order->get_shipping_first_name() : $billing_first_name;
            $shipping_last_name = $order->get_shipping_last_name() ? $order->get_shipping_last_name() : $billing_last_name;
            $shipping_company = $order->get_shipping_company() ? $order->get_shipping_company() : $billing_company;
            $shipping_country = $order->get_shipping_country() ? $order->get_shipping_country() : $billing_country;
            $shipping_address_1 = $order->get_shipping_address_1() ? $order->get_shipping_address_1() : $billing_address_1;
            $shipping_address_2 = $order->get_shipping_address_2() ? $order->get_shipping_address_2() : $billing_address_2;
            $shipping_city = $order->get_shipping_city() ? $order->get_shipping_city() : $billing_city;
            $shipping_state = $order->get_shipping_state() ? $order->get_shipping_state() : $billing_state;
            $shipping_postcode = $order->get_shipping_postcode() ? $order->get_shipping_postcode() : $billing_postcode;
            //默认邮编
            if (empty($billing_postcode)) {
                $billing_postcode = $shipping_postcode = '000000';
            }
            if (empty($billing_state)) {
                $billing_state = $shipping_state = $billing_city;
            }
            //card encrypt
            $session_id = isset($_POST['session_id']) ? $_POST['session_id'] : '';

            $items = $order->get_items();

            $products = array();
            foreach ($items as $key => $value) {
                $name = $value->get_name();
                $quantity = $value->get_quantity();
                $data = $value->get_data();
                $product = wc_get_product($data['product_id']);
                $product_sku = $product->get_sku();
                $price = $product->get_price();
                $products[] = [
                    'sku' => $product_sku,
                    'name' => $name,
                    'amount' => $price,
                    'quantity' => $quantity,
                    'currency' => $Currency
                ];
            }
            $Language = 'en';
            $merchant_id = $this->merchant_id;
            $metaData = '';

            $website = $_SERVER['HTTP_HOST'];

            $fail_url = $this->get_return_url($order);
            $success_url = $this->get_return_url($order);
            $pattern = 'skip';
            if ($this->payment_options) {
                $pattern = 'popout';
            }
            //组合订单信息，准备提交
            $submit_data = [
                'billing_first_name' => $billing_first_name,
                'billing_last_name' => $billing_last_name,
                'billing_email' => $billing_email,
                'billing_phone' => $billing_phone,
                'billing_postal_code' => $billing_postcode,
                'billing_address' => $billing_address_1 . $billing_address_2,
                'billing_city' => $billing_city,
                'billing_state' => $billing_state,
                'billing_country' => $billing_country,
                'shipping_first_name' => $shipping_first_name,
                'shipping_last_name' => $shipping_last_name,
                'shipping_email' => $billing_email,
                'shipping_phone' => $billing_phone,
                'shipping_postal_code' => $shipping_postcode,
                'shipping_address' => $shipping_address_1 . $shipping_address_2,
                'shipping_city' => $shipping_city,
                'shipping_state' => $shipping_state,
                'shipping_country' => $shipping_country,
                //获取客户ip
                'ip' => $this->getIP(),
                'products' => json_encode($products), //json类型
                //商户号
                'merchant_id' => $merchant_id,
                'language' => $Language,
                'currency' => $Currency,
                'order_id' => $order_id,
                //HASH,商户号+md5Key+订单号+订单金额+订单币种+网站url，按照顺序拼接，计算SHA-256摘要值，并转为16进制字符串（小写）
                'hash' => md5($merchant_id . $order_id . $order->get_total() . $Currency . $this->md5key . $website),
                'version' => '20201001',
                'session_id' => $session_id,
                'metadata' => json_encode($metaData), //非必填,为空或者json数据
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'pattern' => $pattern,
                'order_amount' => $order->get_total(),
                'freight' => $order->get_shipping_total(),
                'success_url' => $success_url . '&status=paid',//https://www.wintopay.net/checkout/order-received/',
                'fail_url' => $fail_url . '&status=fail',
                'pending_url' => $success_url . '&status=pending',
                'addon_system'=>'woocommerce',
                'addon_version'=>'woocommerce3.0.0',
                'addon_type'=>'cashier',
            ];
            $this->record_logs('requeset data', $submit_data);
            $url = $this->gateway_url;
            $testdata = $this->wccpaycurlPost($url, $submit_data, $website, $merchant_id);
            $result = json_decode($testdata, true);
            $this->record_logs('response redirect url', $result['redirect_url']);
            $status_code = empty($result['status_code']) ? '' : $result['status_code'];
            $status = empty($result['status']) ? '' : $result['status'];
            $message = empty($result['message']) ? '' : $result['message'];
            $fail_code = empty($result['fail_code']) ? '' : $result['fail_code'];
            $cy_id = empty($result['cy_id']) ? '' : $result['cy_id'];
            $expires = empty($result['expires']) ? '' : $result['expires'];
            $redirect_url = empty($result['redirect_url']) ? '' : $result['redirect_url'];

            if ($status == 'authorization') {
                if ($this->payment_options) {
                    //popout
                    WC()->session->set('redirect_url', $redirect_url);
                    $this->record_logs('order-pay', $order->get_checkout_payment_url(true));
                    return array(
                        'result' => 'success',
                        'redirect' => $order->get_checkout_payment_url(true)
                    );
                } else {
                    return array(
                        'result' => 'success',
                        'redirect' => $redirect_url
                    );
                }
            } else {
                $url = esc_url(wc_get_checkout_url());
                wc_add_notice($message, 'error');
                return array(
                    'result' => 'success',
                    'redirect' => $url
                );
            }

        }

        public function webhook() {

        }
        function record_logs($message,$data='')
        {
            $file = getcwd();
            $file_name = $file.'/'.date('Y-m',time()).'.log';

            if(is_array($data)){
                file_put_contents($file_name,date('Y-m-d H:i:s',time()).' - '.$message.' :: '.var_export($data,true).PHP_EOL,FILE_APPEND);
            }elseif($data){
                file_put_contents($file_name,date('Y-m-d H:i:s',time()).' - '.$message.' :: '.$data.PHP_EOL,FILE_APPEND);
            }else{
                file_put_contents($file_name,date('Y-m-d H:i:s',time()).' - '.$message.PHP_EOL,FILE_APPEND);
            }
        }
        //curl封装
        public function wccpaycurlPost($url, $data,$website,$merchant_id)
        {
            $headers = array(
                'MerNo:'.$merchant_id,
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL ,$url);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_REFERER,$website);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
            $data = curl_exec($ch);
            if($data === false){
                echo 'Curl error: ' . curl_error($ch);
            }
            curl_close($ch);
            return $data;
        }
        function receipt_page( $order ) {
            $this->record_logs('call receipt page');
            $url = WC()->session->get('redirect_url');
            $this->record_logs('url',$url);
            if(!$url){
                wp_redirect(home_url());
            }
            $this->getlocationreplace($url);
            exit;
        }

        /**
         *  跳转
         */
        public function getlocationreplace($url)
        {
            echo <<<Fewfw
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://static1.secure-directpay.com/static/js/wtp_payment.js"></script>
<div class="container-fluid card-icon" style="text-align: center;width:100%;height:30px;">
</div>
<script type="text/javascript">
        $(function() {
    Wintopaypayment.init("$url");
  });
</script>
Fewfw;

        }

        //获取客户ip方法
        public function getIP(){
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $online_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
                $online_ip = $_SERVER['HTTP_CLIENT_IP'];
            }
            elseif(isset($_SERVER['HTTP_X_REAL_IP'])){
                $online_ip = $_SERVER['HTTP_X_REAL_IP'];
            }else{
                $online_ip = $_SERVER['REMOTE_ADDR'];
            }
            $ips = explode(",",$online_ip);
            $ip = $ips[0];
            if (substr($ip,0, 7) == "::ffff:") {
                $ip = substr($ip,7);
            }
            return $ip;
        }

        /**
         *卡信息加密
         * @param string $string 需要加密的字符串
         * @param string $md5key 商户md5key
         * @return string
         */
        function cardInfoEncrypt($md5key, $string)
        {
            $key = substr(openssl_digest(openssl_digest($md5key, 'sha1', true), 'sha1', true), 0, 16);

            $data = openssl_encrypt($string, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
            $data = strtolower(bin2hex($data));

            return $data;
        }

        /**
         * HASH加密
         * @param $str
         */
        function hashEncrypt($str)
        {
            return hash('sha256',$str);
        }
    }
}