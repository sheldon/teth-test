<?php
class TestAutoloader extends BaseTest{
  public $class = "AutoLoader";
  public $class_path = "../../teth/Autoloader.php";
  
  public function path_to(){
    return true;
    $ret = true;
    
    //wrong syntax usage
    unset(Config::$settings['classes']['foo_index']);
    if(Autoloader::path_to('foo_index')) $ret = false;
    
    Config::$settings['classes']['foo_index'] = array();
    if(Autoloader::path_to('foo_index')) $ret = false;
    
    Config::$settings['classes']['foo_index'] = "foo_wrong_syntax_to_config";
    if(Autoloader::path_to('foo_index')) $ret = false;
    
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
  
  public function class_for(){
    return true;
    $ret = true;
    
    unset(Config::$settings['classes']['foo_index']);
    if(Autoloader::class_for('foo_index') != null) $ret = false;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass');
    if(Autoloader::class_for('foo_index') != "FooClass") $ret = false;

    return $ret;
  }
  
  public function class_in_config(){return true;}
  public function pre_init_hooks(){return true;}
  public function init(){return true;}
  public function register_inis(){return true;}
  public function register_classes(){return true;}
  public function add_component(){return true;}
  public function remove_component(){return true;}
  public function load(){return true;}
  public function fetch_controllers(){return true;}
  public function go(){return true;}
  public function index(){return true;}
}?>