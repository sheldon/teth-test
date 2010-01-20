<?php
class TestAutoloader extends BaseTest{
  public $class = "AutoLoader";
  public $class_path = "../../teth/Autoloader.php";
  public $results = array();
  
  public function path_to(){
    $ret=true;
    //wrong syntax usage
    unset(Config::$settings['classes']['foo_index']);
    if(Autoloader::path_to('foo_index')) $this->results['path_to']['empty_class_config'] = $ret = false;
    else $this->results['path_to']['empty_class_config'] = true;
    
    Config::$settings['classes']['foo_index'] = array();
    if(Autoloader::path_to('foo_index')) $this->results['path_to']['empty_array'] = $ret = false;
    else $this->results['path_to']['empty_array'] = true;
    
    Config::$settings['classes']['foo_index'] = "foo_wrong_syntax_to_config";
    if(Autoloader::path_to('foo_index')) $this->results['path_to']['wrong_syntax'] = $ret = false;
    else $this->results['path_to']['wrong_syntax'] = true;
    
    //normal syntax usage
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass');
    if(Autoloader::path_to('foo_index') != FRAMEWORK_DIR."FooClass.php") $this->results['path_to']['foo_class'] = $ret = false;
    else $this->results['path_to']['foo_class'] = true;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass', 'component'=>'FooComponent');
    if(Autoloader::path_to('foo_index') != FRAMEWORK_DIR."FooComponent/FooClass.php") $this->results['path_to']['foo_class_foo_component'] = $ret = false;
    else $this->results['path_to']['foo_class_foo_component'] = true;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass', 'module'=>'FooModule');
    if(Autoloader::path_to('foo_index') != FRAMEWORK_DIR."FooModule/FooClass.php") $this->results['path_to']['foo_class_foo_module'] = $ret = false;
    else $this->results['path_to']['foo_class_foo_module'] = true;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass', 'component'=>'FooComponent', 'module'=>'FooModule');
    if(Autoloader::path_to('foo_index') != FRAMEWORK_DIR."FooComponent/FooModule/FooClass.php") $this->results['path_to']['foo_class_foo_module_foo_component'] = $ret = false;
    else $this->results['path_to']['foo_class_foo_component_foo_module'] = true;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass', 'component'=>'FooComponent', 'module'=>'FooModule', 'suffix'=>'.test');
    if(Autoloader::path_to('foo_index') != FRAMEWORK_DIR."FooComponent/FooModule/FooClass.test") $this->results['path_to']['foo_class_foo_module_foo_component_test_suffix'] = $ret = false;
    else $this->results['path_to']['foo_class_foo_module_foo_component_test_suffix'] = true;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass', 'component'=>'FooComponent', 'module'=>'FooModule', 'base'=>'/a/file/path/instead/of/a/constant/');
    if(Autoloader::path_to('foo_index') != "/a/file/path/instead/of/a/constant/FooComponent/FooModule/FooClass.php") $this->results['path_to']['foo_class_foo_module_foo_component_test_full_path'] = $ret = false;
    else $this->results['path_to']['foo_class_foo_module_foo_component_test_full_path'] = true;
    
    return $ret;
  }
  
  public function class_for(){
    $ret = true;
  
    unset(Config::$settings['classes']['foo_index']);
    if(Autoloader::class_for('foo_index') != null) $this->results['class_for']['empty_config'] = $ret = false;
    else $this->results['class_for']['empty_config'] = true;
    
    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass');
    if(Autoloader::class_for('foo_index') != "FooClass") $this->results['class_for']['foo_class'] = $ret = false;
    else $this->results['class_for']['foo_class'] = true;

    return $ret;
  }
  
  public function class_in_config(){
    return true;    
  }
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