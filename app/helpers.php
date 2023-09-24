<?php
function htmlEmail($view, $data)
{
    return view($view, $data)->render();
}
