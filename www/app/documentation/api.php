<?php
require("../../vendor/autoload.php");

$openapi = \OpenApi\Generator::scan(['../../app/Http/Controllers', '../../app/Models']);

header('Content-Type: application/x-yaml');
echo $openapi->toYaml();