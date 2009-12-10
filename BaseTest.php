<?php
class BaseTest{
  public $class_path = false;
  public $class = false;
  public $output = "";
  public $tests_passed = 0;
  public $tests_failed = 0;

  public function run_tests() {
    if($this->class_path) include_once($this->class_path);
    if($this->class) $this->class = new $this->class;
    foreach(array_diff(get_class_methods(get_class($this)),array("run_tests")) as $test){
      $test_class = get_class($this);
      $test_object = new $test_class;
      $this->output .= "  Running $test ... ";
      if($test_object->$test()){
        $this->tests_passed++;
        $this->output .= "pass\n";
      }else{
        $this->tests_failed++;
        $this->output .= "fail\n";
      }
    }
    return $this;
  }
}?>