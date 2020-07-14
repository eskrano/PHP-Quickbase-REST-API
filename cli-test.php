<?php

/**
 * Test script
 */

if (php_sapi_name() !== 'cli') {
    exit(0);
}

require __DIR__ . '/vendor/autoload.php';

$test_realm = '';
$test_user_token = '';
$test_table = '';
$test_app = '';

$quickbase = new \Eskrano\QuickbaseRest\QuickbaseREST($test_realm, $test_user_token);
/*$resp = $quickbase->query('GET', 'tables', [], [
    'appId' => $test_app
]);*/

/*$resp = $quickbase->records()->query(
    'dbid',
    "{'3'.EX.'29'}"
);*/

/*$resp = $quickbase->records()->editRecords(
    $test_table,
    [
        1 => [
            6 => 'Test 3',
        ],
        2 => [
            6 => 'Test !'
        ],
    ]
);*/

/*
$resp = $quickbase->records()->addRecords(
    $test_table,
    [
        [
            6 => '444 Test',
            7 => 74,
        ],
    ]
);*/

/*
$resp = $quickbase->records()->deleteRecords(
    $test_table,
    "{'6'.CT.'Test'}"
);*/