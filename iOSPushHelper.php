<?php

namespace App\Helper;


use Illuminate\Http\Request;

class iOSPushHelper
{

    /**
     * @param $token
     * @param $msg
     * @throws \Exception
     */
    public function sendNotification($token,$msg): void
    {
        // open connection
        if (!defined('CURL_HTTP_VERSION_2_0')) {
            define('CURL_HTTP_VERSION_2_0', 3);
        }

        $http2ch = curl_init();
        curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        $message        = '{"aps":{"alert":"'.$msg.'","sound":"default"}}';

        // pem cetificate save in config/certificates/file-name.pem
        $apple_cert     = config_path('certificates/CertificateAPNDevelopment.pem');
        $app_bundle_id  = 'com.development.userapp';

        //$http2_server   = 'https://api.push.apple.com';   // for live/production .pem
        $http2_server   = 'https://api.development.push.apple.com';   // for development/test .pem

        $this->sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token);

        curl_close($http2ch);
    }


    /**
     * @param $http2ch
     * @param $http2_server
     * @param $apple_cert
     * @param $app_bundle_id
     * @param $message
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    private function sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token)
    {
        // url (endpoint)
        $url = "{$http2_server}/3/device/{$token}";

        // certificate
        $cert = realpath($apple_cert);

        // headers
        $headers = array(
            "apns-topic: {$app_bundle_id}",
            "User-Agent: My Sender"
        );

        // other curl options
        curl_setopt_array($http2ch, array(
            CURLOPT_URL => "{$url}",
            CURLOPT_PORT => 443,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $message,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSLCERT => $cert,
            CURLOPT_HEADER => 1
        ));

        // go...
        $result = curl_exec($http2ch);
        if ($result === FALSE) {
            throw new \Exception('Curl failed with error: ' . curl_error($http2ch));
        }

        // get respnse
        return curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
    }

}