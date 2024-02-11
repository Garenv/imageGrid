<?php

use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

function getS3PathForEnv() {
    return config('app.env') === "local" || config('app.env') === "stage" ? config('app.aws_s3_path_stage') : config('app.aws_s3_path_prod');
}

function setRedisKey($key, $jsonEncodedData)
{
    Log::info("Setting Redis key: $key");
    Redis::connection(getSiteEnv())->set($key, $jsonEncodedData);
    Log::info("Key set successfully.");
}

function getDataFromRedisKey($key)
{
    Log::info("Getting Redis key: $key");
    return Redis::connection(getSiteEnv())->get($key);
}

function getUserIpAddr()
{
    if (isset($_SERVER['HTTP_CLIENT_IP'])) $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED'])) $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED'])) $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR'])) $ipaddress = $_SERVER['REMOTE_ADDR'];
    else $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function getSiteEnv()
{
    switch(config('app.env')) {
        case "local":
            return "local";
        case "stage":
            return "stage";
        case "prod":
            return "prod";
    }

    return "Unknown Environment";
}

function isNotProduction(): bool
{
    return !(config('app.env') === "prod");
}

function getUserDeviceData()
{
    $agent = new Agent();

    $deviceOs = $agent->platform();
    $osVersion = $agent->version($deviceOs);
    $device = $agent->device();

    return [
        'device_os' => $deviceOs,
        'os_version' => $osVersion,
        'device' => $device
    ];
}

function isUserOnMobile()
{
    $agent = new Agent();

    if ($agent->isMobile()) {
        return true;
    }

    return false;
}

function getUserBrowserData()
{
    $agent = new Agent();

    $browser = $agent->browser();
    $browserVersion = $agent->version($browser);

    return [
        'browser' => $browser,
        'browser_version' => $browserVersion
    ];
}

function getAuthenticatedUser()
{
    return Auth::user();
}
