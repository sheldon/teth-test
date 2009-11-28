<?php
class TestAutoloader extends BaseTest{
  public $class = "AutoLoader";
  public $class_path = "../../teth/Autoloader.php";
  
  public function path_to(){
    $ret = true;
    
    //normal syntax usage
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass');
    if(Autoloader::path_to('foo_index') != "FRAMEWORK_DIRFooClass.php") $ret = false;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass', 'component'=>'FooComponent');
    if(Autoloader::path_to('foo_index') != "FRAMEWORK_DIRFooComponent/FooClass.php") $ret = false;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass', 'module'=>'FooModule');
    if(Autoloader::path_to('foo_index') != "FRAMEWORK_DIRFooModule/FooClass.php") $ret = false;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass', 'component'=>'FooComponent', 'module'=>'FooModule');
    if(Autoloader::path_to('foo_index') != "FRAMEWORK_DIRFooComponent/FooModule/FooClass.php") $ret = false;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass', 'component'=>'FooComponent', 'module'=>'FooModule', 'suffix'=>'.test');
    if(Autoloader::path_to('foo_index') != "FRAMEWORK_DIRFooComponent/FooModule/FooClass.test") $ret = false;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass', 'component'=>'FooComponent', 'module'=>'FooModule', 'base'=>'/a/file/path/instead/of/a/constant/');
    if(Autoloader::path_to('foo_index') != "/a/file/path/instead/of/a/constant/FooComponent/FooModule/FooClass.php") $ret = false;
    
    return $ret;
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