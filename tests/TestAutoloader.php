<?php
class TestAutoloader extends BaseTest{
  public function __construct(){
    include("../../teth/Autoloader.php");
    $this->class = new Autoloader();
  }
  public function path_to(){
    print_r(Autoloader::path_to('autoloader')); exit;
  }
  public function class_for(){}
  public function class_in_config(){}
  public function pre_init_hooks(){}
  public function init(){}
  public function register_inis(){}
  public function register_classes(){}
  public function add_component(){}
  public function remove_component(){}
  public function load(){}
  public function fetch_controllers(){}
  public function go(){}
  public function index(){}
}?>