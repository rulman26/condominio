
<?php
$apikey='26e5a09c1b904b77a83e8548700647ee';
$apisecret='96964721325d48da9f89a1070ece9a79';
function bittrexbalance($apikey, $apisecret){
    $nonce=time();
    $uri='https://bittrex.com/api/v1.1/account/getbalance?apikey='.$apikey.'&currency=BTC&nonce='.$nonce;
    $sign=hash_hmac('sha512',$uri,$apisecret);    
    $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'.$sign));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $execResult = curl_exec($ch);
    $obj = json_decode($execResult, true);
    $balance = $obj;
    return $balance;
}

$hola=bittrexbalance($apikey,$apisecret);
echo json_encode($hola);

?>