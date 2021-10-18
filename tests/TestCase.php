<?php

namespace Tests;

use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    public function testName()
    {
        //Create user
        $this->json('post', 'api/register', [
            'name' => 'test_name',
            'password' => 'password',
            'email' => 'test@example.coms'
        ])->assertCreated();

        $token = $this->json('post', 'api/login', [
            'password' => 'password',
            'email' => 'test@example.com'
        ])->assertOk()
            ->json('access_token');

        $this->makeImageStoreRequest($token);
    }


    private function makeImageStoreRequest($token) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'localhost:8000/api/images?XDEBUG_SESSION_START=19311',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('image'=> new CURLFILE('test_image.jpg')),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer '.$token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }


}
