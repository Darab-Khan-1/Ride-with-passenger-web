<?php

namespace App\Helpers;
use \stdClass;
use Config;
use DateTime;
Use DateTimeZone;

Trait Timezone
{

    public static function isCDT() {
        date_default_timezone_set('America/Chicago');
        $now = new DateTime();
        return $now->format('I') == 1;
    }
}
