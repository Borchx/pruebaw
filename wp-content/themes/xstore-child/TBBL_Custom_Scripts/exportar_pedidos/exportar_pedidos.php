<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing; 

if (bot_detected()){
    exit;
}

function bot_detected() {
    return (
        isset($_SERVER['HTTP_USER_AGENT'])
        && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
    );
}

require_once("../../../../../wp-load.php");

$lista_ids = $_GET["ids"];

if (isset($lista_ids) and !empty($lista_ids)){
    $arr_ids = explode(",", $lista_ids);
    $contador_fila = 0;
    $array_res_pedido = array();
    $arr_imagenes = array();
    foreach($arr_ids as $order_id){
        //echo "<BR><BR><b>PEDIDO (".$order_id.") ITERACION: <BR></b>";
        $order = new WC_Order( $order_id );

        if (isset($order)){
            $order_id  = $order->get_id(); // Get the order ID
            $parent_id = $order->get_parent_id(); // Get the parent order ID (for subscriptions…)

            $user_id   = $order->get_user_id(); // Get the costumer ID
            $user      = $order->get_user(); // Get the WP_User object

            $order_status  = $order->get_status(); // Get the order status (see the conditional method has_status() below)
            $currency      = $order->get_currency(); // Get the currency used  
            $payment_method = $order->get_payment_method(); // Get the payment method ID
            $payment_title = $order->get_payment_method_title(); // Get the payment method title
            $date_created  = $order->get_date_created(); // Get date created (WC_DateTime object)
            $date_modified = $order->get_date_modified(); // Get date modified (WC_DateTime object)

            
            $arr_items = $order->get_items();
            $contador_items = 1;
            foreach($arr_items as $item){
                    //echo "<br>Order Number: ".$order_id;
                    //echo "<br> -- Contador Item: ".$contador_items;
                $cantidad_item = $item->get_quantity();
                    //echo "<br> -- Cantidad Item: ".$cantidad_item;

                $product_id = $item['product_id'];
                $product = new WC_Product($product_id);
                $image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail');
                $arr_imagenes[$contador_fila] = $image[0];

                $variation_id = $item->get_variation_id();
                $variation    = new WC_Product_Variation( $variation_id );
                $attributes   = $variation->get_attributes();
                $talla = "";
                foreach($attributes as $attribute_taxonomy => $term_slug ){
                    $taxonomy = str_replace('attribute_', '', $attribute_taxonomy );
                    $attribute_name = wc_attribute_label( $taxonomy, $product );
                    if( taxonomy_exists($taxonomy) ) {
                        $attribute_value = get_term_by( 'slug', $term_slug, $taxonomy )->name;
                    } else {
                        $attribute_value = $term_slug; // For custom product attributes
                    }
                    if ($attribute_name == "Size"){
                        $talla = $attribute_value;
                        break;
                    }
                }

                $item_name = $product->get_name();
                    //echo "<br> -- Nombre Item: ".$item_name." - ".$talla;
                    //echo "<br> -- Variación Item: Size: ".$talla;

                $sku = $product->get_sku();
                    //echo "<br> -- SKU: ".$sku;

                $product_subtotal = round($item->get_subtotal(), 2);
                $product_tax = round($item->get_subtotal_tax(), 2);
                $product_total = round($product_subtotal + $product_tax, 2);
                    //echo "<br> -- Price: ".$product_total;


                    //echo "<br>Order Status: ".$order_status;
                    //echo "<br>Order Date: ".$date_created;
                
                $nota_cliente = $order->get_customer_note();
                    //echo "<br>Order Note: ".$nota_cliente;

                $shipping_first_name = $order->get_shipping_first_name();
                    //echo "<br>First Name (Shipping): ".$shipping_first_name;
                $shipping_last_name = $order->get_shipping_last_name();
                    //echo "<br>Last Name (Shipping): ".$shipping_last_name;

                $shipping_address = $order->get_shipping_address_1();
                $shipping_address2 = $order->get_shipping_address_2();
                    //echo "<br>Address 1 and 2: ".$shipping_address.", ".$shipping_address2;

                $shipping_city = $order->get_shipping_city();
                    //echo "<br>City: ".$shipping_city;

                $shipping_postcode = $order->get_shipping_postcode();
                    //echo "<br>Post Code: ".$shipping_postcode;
                $shipping_state = $order->get_shipping_state();
                    //echo "<br>State Name: ".$shipping_state;

                $shipping_country = $order->get_shipping_country();
                    //echo "<br>Country Name: ".$shipping_country;

                $billing_phone = $order->get_billing_phone();
                    //echo "<br>Phone (Billing): ".$billing_phone;

                $shipping_method = $order->get_shipping_method();
                    //echo "<br>Shipping Method: ".$shipping_method;

                $cupones_usados = "";
                $amount_usados = "";
                $contador_cupon = 0;
                foreach($order->get_used_coupons() as $coupon_code){
                    $coupon_post_obj = get_page_by_title($coupon_code, OBJECT, 'shop_coupon');
                    $coupon_id       = $coupon_post_obj->ID;
                    $coupon = new WC_Coupon($coupon_id);
                    $cupon_usado = $coupon->get_code();
                    $amount_usado = $coupon->get_amount();
                    if ($contador_cupon > 0){
                        $cupones_usados .= ", ".$cupon_usado;
                        $amount_usados .= ", ".$amount_usado;
                    } else {
                        $cupones_usados .= $cupon_usado;
                        $amount_usados .= $amount_usado;
                    }
                }
                //echo "<br>Coupon Codes: ".$cupones_usados;
               //echo "<br>Coupon Amounts: ".$amount_usados;

                array_push($array_res_pedido, array(
                    "Order Number"  => filterData($order_id),
                    "Item Counter"  => filterData($contador_items),
                    "Item Qty"  => filterData($cantidad_item),
                    "Item Image" => " ",
                    "Item Name"  => filterData($item_name)." - ".filterData($talla),
                    "Size"  => filterData($talla),
                    "SKU"   => filterData($sku),
                    "Item Price" => filterData($product_total),
                    "Order Status"  => filterData($order_status),
                    "Order Date"  => filterData($date_created),
                    "Customer Note"  => filterData($nota_cliente),
                    "Fisrt Name (Shipping)"  => filterData($shipping_first_name),
                    "Last Name (Shipping)"  => filterData($shipping_last_name),
                    "Address (Shipping)"  => filterData($shipping_address).", ".filterData($shipping_address2),
                    "City"  => filterData($shipping_city),
                    "Post Code"  => filterData($shipping_postcode),
                    "State Name"  => filterData($shipping_state),
                    "Country"  => filterData($shipping_country),
                    "Phone (Billing)"  => filterData($billing_phone),
                    "Shipping Method"  => filterData($shipping_method),
                    "Coupon Codes"  => filterData($cupones_usados),
                    "Counpon Amounts"  => filterData($amount_usados)
                ));
                $contador_items++;
                $contador_fila++;
            }

            

        }
    }

    if (count($array_res_pedido) > 0){

        // Creates New Spreadsheet 
        $spreadsheet = new Spreadsheet(); 
        
        // Retrieve the current active worksheet 
        $sheet = $spreadsheet->getActiveSheet(); 

        // sample data from db
        // call the db get data function here
        //delete line from 18 to 20 and call the db function
        $data_from_db=$array_res_pedido;

        //set column header
        //set your own column header
        $column_header=[
            "Order Number",
            "Item Counter",
            "Item Qty",
            "Item Image",
            "Item Name",
            "Size",
            "SKU",
            "Item Price",
            "Order Status",
            "Order Date",
            "Customer Note",
            "Fisrt Name (Shipping)",
            "Last Name (Shipping)",
            "Address (Shipping)",
            "City",
            "Post Code",
            "State Name",
            "Country",
            "Phone (Billing)",
            "Shipping Method",
            "Coupon Codes",
            "Counpon Amounts"
        ];
        $j=1;
        foreach($column_header as $x_value) {
                $sheet->setCellValueByColumnAndRow($j,1,$x_value);
                $j=$j+1;
                
            }

        //set value row
        for($i=0;$i<count($data_from_db);$i++)
        {

        //set value for indi cell
        $row=$data_from_db[$i];

        $j=1;

            foreach($row as $x => $x_value) {
                $fila_actual = $i+2;
                if ($j == 4){
                    //Columna de la imagen
                    $imagen_relativa = str_replace("https://pruebas.sneakersales.es/", "", $arr_imagenes[$i]);
                    $imagen_relativa = str_replace("https://www.sneakersales.es/", "", $imagen_relativa);
                    $imagen_relativa = "../../../../../".str_replace("https://sneakersales.es/", "", $imagen_relativa);
                    $drawing = new Drawing();
                    $drawing->setName('SneakerSales');
                    $drawing->setDescription('SneakerSales');
                    $drawing->setPath($imagen_relativa);
                    $drawing->setHeight(36);
                    $drawing->setCoordinates('D'.$fila_actual);
                    $drawing->setOffsetX(10);
                    $drawing->setWorksheet($spreadsheet->getActiveSheet());

                } else {
                    $sheet->setCellValueByColumnAndRow($j,$i+2,$x_value);
                }
                $j=$j+1;
            }

        }
        
        // Write an .xlsx file  
        $writer = new Xlsx($spreadsheet); 
        
        // Save .xlsx file to the files directory 
        $fileName = "exportaciones/sneakersales_export_orders-" . date('Ymd') . ".xlsx"; 
        $writer->save($fileName);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
        
        exit;
    } else {
        echo "ERROR, no hay datos para exportar.";
        exit;
    }

}

function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
    return $str;
}

?>