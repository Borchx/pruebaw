<?php

if (isset($order)){
    require __DIR__."/WooCommerce/Client.php";

    $woocommerce = new Client(
        'https://www.yourwebsitehere.es/', //ENDPOINT
        'ck_5af70ddc6a9b57e4004e4b3c5fddc367a7533458', //Key
        'cs_6d2e4d9d2f20312238c9d2fce72ff5be8d0f46f6', //Secret
        [
            'wp_api' => true,
            'version' => 'wc/v3',
            'query_string_auth' => true // Force Basic Authentication as query string true and using under HTTPS
        ]
    );

    //Calculamos las unidades totales del pedido, ya que el cupón solo se crea si el usuario ha comprado 1 sola unidad
    $items = $order->get_items();
    $unidades_totales = 0;
    foreach ($items as $item){
        $product_quantity = $item->get_quantity();
        $unidades_totales += $product_quantity;
    }

    //Controlamos si en este pedido, el cliente ya ha usado un cupón de este tipo. Si lo ha hecho, entonces no se genera otro cupón
    $bCheckCupon = true; //Controla si pasa la validación de cupón usado
    foreach( $order->get_used_coupons() as $coupon_code ){
        $coupon_post_obj = get_page_by_title($coupon_code, OBJECT, 'shop_coupon');
        $coupon_id       = $coupon_post_obj->ID;
        $coupon = new WC_Coupon($coupon_id);
        $cupon_usado = $coupon->get_code();
        if (strpos($cupon_usado, "save30_") !== false){
            $bCheckCupon = false;
        }
    }

    if ($unidades_totales == 1 and $bCheckCupon){
        $nombre_cupon = "SAVE30_".$order->get_id()."_".strtoupper(generateRandomString(2));
        $tomorrow = date("Y-m-d H:i:s", time() + (86400*2)); // Cupón válido entre 24 y 48 horas siguientes

        $data = [
            'code' => $nombre_cupon,
            'discount_type' => 'percent',
            'amount' => '30',
            'individual_use' => true,
            'email_restrictions' => $email->recipient,
            'usage_limit' => 1,
            'limit_usage_to_x_items' => 1, // Con esto el cupón solo se aplica a 1 par de zapatillas
            'date_expires' => $tomorrow
        ];

        $woocommerce->post('coupons', $data);
    }

}
//print_r($woocommerce->get('coupons'));

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
