<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/4/17
 * Time: 4:15 PM
 */

namespace App\Utils;


use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;

class ApiLogs
{

    public static $_id;
    public static $timestart = 0;

    public static function writeRequest($request){

        static::$timestart = microtime(true);

        $collection = (new Client)->latifa->logs;

        $res = $collection->insertOne(
            [
                "device_id" => "deviceId",
                "env"       => config("app.env", "local"),
                "request"   => $request->all(),
                "header"    => $request->header(),
                "timestamp_request" => new UTCDateTime(strtotime(date('Y-m-d H:i:s'))),
                "ip"        => $request->ip(),
            ]);

        static::$_id = $res->getInsertedId();
    }

    /**
     * @param Response $response
     */
    public static function writeResponse($response){

        $collection = (new Client)->latifa->logs;

        $userId = null;
        if(isset(request()->user()->id)){
            $userId = request()->user()->id;
        }

        $updateResult = $collection->updateOne(
            ['_id' => static::$_id],
            ['$set' => [
                "user_id"   => $userId,
                'response' => $response,
                "timestamp_response" => new UTCDateTime(strtotime(date('Y-m-d H:i:s'))),
                "execution_time" => microtime(true) -  static::$timestart
            ]]
        );


    }
}