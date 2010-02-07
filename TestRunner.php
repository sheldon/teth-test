#!/usr/bin/php

<?php
class TestRunner{
  public $bootstrap_paths = array("BaseTest.php","tests/TestTethAutoloader.php");
  public $scan_folders = array();
  public $test_classes = array();
  public $failed = 0;
  public $passed = 0;

  public function __construct($run_from_commandline = false){
    if($run_from_commandline){
      $this->init_constants();
      echo "Bootstrapping Testing System by Testing Autoloader...\n";
      $bootstrap_test = $this->bootstrap_test_autoloader();
      if($bootstrap_test->tests_failed){
        echo "Bootstrap Fail, stopping subsequent tests.\n";
        echo "=== DEBUG ===\n";
        echo $bootstrap_test->output;
        exit;
      }else echo "Bootstrap Pass, continuing...\n";
      echo "Running TethAutoloader::init()\n";
      TethAutoloader::init();
      echo "Running TethAutoloader::add_component()\n";
      TethAutoloader::add_component(TEST_PLUGIN_NAME, SITE_DIR);
      TethAutoloader::add_component(TEST_PLUGIN_NAME, substr(SITE_DIR,0, strrpos(rtrim(SITE_DIR,"/"), "/")+1) );
      echo "Running TethAutoloader::register_classes()\n";
      TethAutoloader::register_classes(array(SITE_DIR));
      $this->test_classes = $this->scan_classes(TethAutoloader::$classes);
      echo "Found ".count($this->test_classes)." test classes ...\n";
      $this->run_tests();
    }
  }

  public function init_constants(){
    //stolen from index.php in our skel :)
    define("SITE_DIR", realpath(dirname(__FILE__)."/../../")."/");
    require SITE_DIR.'/teth/TethAutoloader.php';
    TethAutoloader::constants();
  }

  public function bootstrap_test_autoloader(){
    foreach($this->bootstrap_paths as $path) include_once($path);
    $test_autoloader = new TestTethAutoloader();
    return $test_autoloader->run_tests();
  }

  public function scan_classes($all_classes){
    $test_classes = array();
    foreach((array)$all_classes as $class=>$path) if(is_subclass_of($class, "BaseTest")) $test_classes[] = $class;
    return $test_classes;
  }

  public function run_tests(){
    $start_time = time();
    echo "==== STARTING ====\n";
    $this->failed = 0;
    $this->passed = 0;
    $this->total = 0;
    foreach($this->test_classes as $class){
      echo "--> $class";
      $obj = new $class;
      $ran = $obj->run_tests();
      if($ran->tests_failed) echo "\n".$ran->output;
      else  echo " = ALL OK\n";
    }
    //$ret .= "\n\nTests Passed: $this->tests_passed\nTests Failed: $this->tests_failed\n\n";
    return $ret;
  }
}
if(!$_SERVER['HTTP_HOST']) new TestRunner(true);
?>