<?php

use Jenssegers\Agent\Agent;

function getS3PathForEnv() {
    return config('app.env') === "local" || config('app.env') === "stage" ? config('app.aws_s3_path_stage') : config('app.aws_s3_path_prod');
}

function getUserIpAddr()
{
    $ipaddress = '';
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
