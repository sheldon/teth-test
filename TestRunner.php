<?php
class TestRunner{
  public $scan_folders = array();
  public $test_files = array();
  public $tests = array();
  public $tests_passed = 0;
  public $tests_failed = 0;

  function __construct($scan_folders = array()){
    include("BaseTest.php");
  }
  
  public function scan(){
    $this->include_files();
    $this->scan_methods();
  }
  
  public function include_files(){
    foreach($this->scan_folders as $folder){
      $dirs = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder), true);
      foreach($dirs as $dir){
        $this->test_files[] = $dir->getPathname();
        include($dir->getPathname());
      }
    }
  }
  
  public function scan_methods(){
    foreach(get_declared_classes() as $class) {
      if(is_subclass_of($class, "BaseTest")) {
        foreach(get_class_methods($class) as $method){
          $this->tests[$class][] = $method;
        }
      }
    }
  }
  
  public function run_tests(){
    $this->tests_passed = 0;
    $this->tests_failed = 0;
    foreach($this->tests as $class => $tests){
      $ret .= "\nRunning Tests in $class...\n";
      $test_class = new $class;
      if($test_class->class_path) include_once($test_class->class_path);
      if($test_class->class) $test_class->class = new $test_class->class;
      foreach($tests as $test){
        $ret .= "  Running $test ... ";
        if($test_class->$test()){
          $this->tests_passed++;
          $ret .= "pass\n";
        }else{
          $this->tests_failed++;
          $ret .= "fail\n";
        }
      }
    }
    $ret .= "\n\nTests Passed: $this->tests_passed\nTests Failed: $this->tests_failed\n\n";
    return $ret;
  }
}
?>