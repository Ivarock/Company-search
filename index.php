<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

$companyName = readline("Enter company name you're looking for: ");

if (empty($companyName)) {
    exit("Company name cannot be empty");
}

$url = 'https://data.gov.lv/dati/lv/api/3/action/datastore_search';
$parameters = [
    'resource_id' => '25e80bf3-f107-4ab4-89ef-251b5b9374e9',
    'q' => $companyName
];

$requestUrl = $url . '?' . http_build_query($parameters);
$response = file_get_contents($requestUrl);
if ($response === false) {
    exit("Error fetching data");
}

$companyData = json_decode($response);
$output = new ConsoleOutput();
$table = new Table($output);
$table->setHeaders(['ID', 'Registration code', 'SEPA', 'Name', 'Address']);
foreach ($companyData->result->records as $record) {
    $table->addRow([
        $record->_id,
        $record->regcode,
        $record->sepa,
        $record->name,
        $record->address
    ]);
}
$table->render();
