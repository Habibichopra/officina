<?php

function error($msg)
{
    return json_encode([
        "status" => false,
        "message" => $msg,
    ]);
}


function ok($msg)
{
    return json_encode([
        "status" => true,
        "message" => $msg,
    ]);
}