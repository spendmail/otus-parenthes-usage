#!/usr/bin/env php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Parentheses\Model;
use Parentheses\Service;
use Parentheses\Exception\EmptyStringException;
use Parentheses\Exception\BadSymbolException;

//Checking the path is entered
if (!isset($argv[1])) {
    die("Please specify the path." . PHP_EOL . "input-example.txt: ./console.php path/to/file" . PHP_EOL);
}

$path = $argv[1];

//Checking the file is exist
if (!file_exists($path)) {
    die("File \"{$path}\" is not exist." . PHP_EOL);
}

//Checking the file is readable
if (!is_readable($path)) {
    die("File \"{$path}\" is not readable." . PHP_EOL);
}

try {

    if (!$df = fopen($path, 'r')) {
        die("Can not read the file \"{$path}\"." . PHP_EOL);
    }

    $inputString = fgets($df, 4096);
    $parenthesesModel = new Model($inputString);

    if ($parenthesesModel->isValid()) {

        $parenthesesService = new Service($parenthesesModel);
        $result = $parenthesesService->isCorrect();;
    }

} catch (EmptyStringException $e) {

    die($e->getMessage() . PHP_EOL);

} catch (BadSymbolException $e) {

    die($e->getMessage() . PHP_EOL);

} catch (\Exception $e) {

    die("Unrecognized error: " . $e->getMessage() . PHP_EOL);
}

echo $result ? "true" : "false";

fclose($df);
