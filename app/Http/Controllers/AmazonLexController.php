<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class AmazonLexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {   
        
    }


    public function postTextMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inputText' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

       $awsAccessKeyId = 'AKIAJJ37PRPTBUFBIN6A';
       $awsSecretKey   = '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';
       $credentials    = new \Aws\Credentials\Credentials($awsAccessKeyId, $awsSecretKey);

       // #Client
       $client         = new \Aws\LexRuntimeService\LexRuntimeServiceClient([
           'version'     => '2016-11-28',
           'credentials' => $credentials,
           'region'      => 'us-east-1',
           'debug'       => false
       ]);

       // send PostText
       $result = $client->postText([
           'botAlias' => 'chatview', // REQUIRED
           'botName' => 'OrderFlowers', // REQUIRED
           'inputText' => $request->inputText, // REQUIRED
           'userId' => '776176654273', // REQUIRED
       ]);

       return response()->json($result->get('message'), 200);
     
       // return json_encode($result->toArray()['message']);
     
       // send PostContent Here you can send Audio and Text
    /*   $result = $client->postContent([
           'accept' => 'text/plain; charset=utf-8',
           'botAlias' => 'chatview',
           'botName' => 'OrderFlowers',
           'contentType' => 'text/plain; charset=utf-8',
           'inputStream' => 'I would like to order some flowers',
           // 'requestAttributes' => 'x-amz-lex-request-attributes',
           // 'sessionAttributes' => 'x-amz-lex-session-attributes',
           'userId' => 'test'
       ]);  */
        
    }
}
