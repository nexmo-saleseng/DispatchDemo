<?php
require __DIR__ . '/vendor/autoload.php';

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

function generate_jwt( $application_id, $keyfile) {

    $jwt = false;
    date_default_timezone_set('UTC');    //Set the time for UTC + 0
    $key = file_get_contents($keyfile);  //Retrieve your private key
    $signer = new Sha256();
    $privateKey = new Key($key);

    $jwt = (new Builder())->setIssuedAt(time() - date('Z')) // Time token was generated in UTC+0
        ->set('application_id', $application_id) // ID for the application you are working with
        ->setId( base64_encode( mt_rand (  )), true)
        ->sign($signer,  $privateKey) // Create a signature using your private key
        ->getToken(); // Retrieves the JWT

    return $jwt;
}

$jwt = generate_jwt("your-application-id","your-private-key");
$data = $_POST['workflow'];

$curl = curl_init();
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer '.$jwt
));
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_URL, "https://api.nexmo.com/v0.1/dispatch");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($curl);
curl_close($curl);

echo $result;

?>
