#!/usr/bin/php
<?php
include("TestRunner.php");
$test_runner = new TestRunner();
$test_runner->scan_folders[] = "tests";
$test_runner->scan();
echo($test_runner->run_tests());
?>