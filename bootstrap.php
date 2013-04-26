<?php
$LIB = dirname(__FILE__) . "/library";

spl_autoload_register(function ($class) use ($LIB) {
    $nameSegments = explode("_", $class);

    $classFile = "";

    switch (count($nameSegments)) {
        case 3:
            $classFile = sprintf(
                "%s/%s/%s/%s/%s.php",
                $LIB,
                strtolower($nameSegments[ 0 ]),
                strtolower($nameSegments[ 1 ]),
                $nameSegments[ 2 ],
                $nameSegments[ 2 ]
            );
            break;

        case 2:
            $classFile = sprintf(
                "%s/%s/%s.php",
                $LIB,
                strtolower($nameSegments[ 0 ]),
                $nameSegments[ 1 ]
            );
            break;
        case 1:
            $classFile = sprintf(
                "%s/%s.php",
                $LIB,
                $nameSegments[ 0 ]
            );
            break;
    }

    if (is_readable($classFile)) {

        include($classFile);
    } else {
        error_log("no find class ${class} in ${classFile}!");
    }
});