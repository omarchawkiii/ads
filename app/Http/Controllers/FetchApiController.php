<?php

namespace App\Http\Controllers;

use App\Models\Config;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
class FetchApiController extends Controller
{
    public function call($url,$params)
    {
        $config = Config::first() ;

        $url = rtrim($config->link, '/') . $url;

            $client = new Client();
            $response = $client->request('GET', $url,[
                'connect_timeout' => 5,
                'query' => [
                    'username' => $config->user,
                    'password' =>$config->password,
                    'uuid' => $params['id_content'],
                    'from_date' => $params['from_date'],
                    'to_date' => $params['to_date']
                ],
            ]);

            $contents = json_decode($response->getBody(), true);

            if($contents)
            {
                if( $contents['status'])
                {
                    return response()->json([
                        'status' =>1,
                        'data' =>$contents['data'],
                        'message' => 'Content Retrieved Successfully.',
                    ], 200);
                }
                else
                {
                    return response()->json([
                        'status' =>0,
                        'message' => $contents['message'],
                    ], 200);
                }

            }
            else
            {
                return response()->json([
                    'status' =>0,
                    'message' => "No Data",
                ], 200);
            }


    }
}
