<?php
class BaseTest{
  public $class_path = false;
  public $class = false;
  public $output = "";
  public $total_tests = 0;
  public $tests_passed = 0;
  public $tests_failed = 0;

  public function run_tests() {
    if($this->class_path) include_once($this->class_path);
    if($this->class) $this->class = new $this->class;
    foreach(array_diff(get_class_methods(get_class($this)),array("run_tests")) as $test){
      $test_class = get_class($this);
      $test_object = new $test_class;
      $this->output .= "  --> $test\n";
      $this->total_tests ++;
      $res = $test_object->$test();
      foreach((array)$test_object->results[$test] as $k=>$v) $this->output .= "      $k = ".(($v) ? "OK": "FAIL")."\n";
      if($res) $this->tests_passed++;
      else $this->tests_failed++;        
    }
    return $this;
  }
}?>