<?php
function status($status) {
    if($status == 1) {
        $str = "<span class='badge badge-danger'>已用</span>";
    }
    else{
        $str = " <span class='badge badge-primary'>可用</span>";
    }
    return $str;
}

function cityStatus($status) {
    if($status == 1) {
        $str = "<span class='badge badge-primary'>已开通</span>";
    }
    else{
        $str = " <span class='badge badge-warning'>未开通</span>";
    }
    return $str;
}