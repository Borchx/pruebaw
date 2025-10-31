<?php

function dameURLConPuerto(){
	$url="http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
	return $url;
}


function dameDirectoriosURL(){
	$url=$_SERVER['REQUEST_URI'];
	if ($url[0] == "/" ) { 
		$maximo = strlen($url); 
		$url = substr($url,1,$maximo); 
	}
	return $url;
}


function dameURL(){
	$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	return $url;
}


function dameURLSinGET(){
	$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$PosicionInicioGET =  strpos ($url, "?");
	if ($PosicionInicioGET !== false){
		$url = substr($url,0, $PosicionInicioGET);
	}
	return $url;
}


function url_exists ($url , $con_get_headers = true) {
    if ( $con_get_headers ) {
            if( empty( $url ) ){
                return false;
            }
            // get_headers() realiza una petici�n GET por defecto,
            // cambiar el m�todo predeterminadao a HEAD
            // Ver http://php.net/manual/es/function.get-headers.php
            stream_context_set_default(
                array(
                    'http' => array(
                        'method' => 'HEAD'
                     )
                )
            );
            $headers = @get_headers( $url );
            sscanf( $headers[0], 'HTTP/%*d.%*d %d', $httpcode );

            // Aceptar solo respuesta 200 (Ok), 301 (redirecci�n permanente) o 302 (redirecci�n temporal)
            $accepted_response = array( 200, 301, 302 );
            if( in_array( $httpcode, $accepted_response ) ) {
                return true;
            } else {
                return false;
            }
      
    } else { // con curl
            if( empty( $url ) ){
                return false;
            }

            $ch = curl_init( $url );

            // Establecer un tiempo de espera
            curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
            curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );

            // Establecer NOBODY en true para hacer una solicitud tipo HEAD
            curl_setopt( $ch, CURLOPT_NOBODY, true );
            // Permitir seguir redireccionamientos
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
            // Recibir la respuesta como string, no output
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            // Descomentar si tu servidor requiere un user-agent, referrer u otra configuraci�n espec�fica
            // $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36';
            // curl_setopt($ch, CURLOPT_USERAGENT, $agent)

            $data = curl_exec( $ch );

            // Obtener el c�digo de respuesta
            $httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            //cerrar conexi�n
            curl_close( $ch );

            // Aceptar solo respuesta 200 (Ok), 301 (redirecci�n permanente) o 302 (redirecci�n temporal)
            $accepted_response = array( 200, 301, 302 );
            if( in_array( $httpcode, $accepted_response ) ) {
                return true;
            } else {
                return false;
            } 
    }
}


function resultado_api_curl ( $url , $array_datos, $Ver_resultado = false) {
    /*
        $array_datos = array(
            'CUI' => CUI_Clave_Unica_Instalacion_local ()
        );    
    */
  
$ch = curl_init();
  /*
$headers  = [
            'x-api-key: XXXXXX',
            'Content-Type: text/plain'
        ];
$array_datos = [
    'data1' => 'value1',
    'data2' => 'value2'
];
*/
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array_datos));           
//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result     = curl_exec ($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  
  
            if ( $Ver_resultado ) { print_r ($result); };
        return $result;
}


function callAPI($method, $url, $data){
   $curl = curl_init();
   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   // OPTIONS:
       curl_setopt($curl, CURLOPT_URL, $url);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          'APIKEY: 111111111111111111111',
          'Content-Type: application/json',
       ));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  
   // EXECUTE:
       $result = curl_exec($curl);
       if(!$result){die("Connection Failure");}
       curl_close($curl);
       return $result;
}
