<?php
require "../vendor/autoload.php";

use App\System\Kernel;

try {
    Kernel::run();
} catch (\Exception $e) {
    echo $e->getMessage();
}