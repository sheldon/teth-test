<?php
class TestAutoloader extends BaseTest{
  public $class = "AutoLoader";
  public $class_path = "teth/Autoloader.php";
  public $excluded_functions = array("run_tests", "pre_init_hook_test");


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

    unset(Config::$settings['classes']['foo_index']);

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

    unset(Config::$settings['classes']['foo_index']);

    return $ret;
  }

  public function class_in_config(){
    $ret = true;

    unset(Config::$settings['classes']['foo_index']);
    if(Autoloader::class_in_config('FooClass')) $this->results['class_in_config']['empty_config'] = $ret = false;
    else $this->results['class_in_config']['empty_config'] = true;

    Config::$settings['classes']['foo_index'] = array('class'=>'FooClass');
    if(Autoloader::class_in_config('FooClass') != "foo_index") $this->results['class_in_config']['not_found'] = $ret = false;
    else $this->results['class_in_config']['not_found'];

    unset(Config::$settings['classes']['foo_index']);
    return $ret;
  }

  /**
   * Have to use globals in order to test that a hook function is called,
   * as cmd lines don't have sessions
   */
  public function pre_init_hooks(){
    $GLOBALS['pre_init_hooks_test_value'] = false;
    $ret = true;
    unset(Config::$settings['pre_functions']);
    Autoloader::pre_init_hooks();
    if($GLOBALS['pre_init_hooks_test_value'] !== false) $this->results['pre_init_hooks']['empty_config'] = $ret = false;
    else $this->results['pre_init_hooks']['empty_config'] = true;

    Config::$settings['pre_functions'] = array('file/that/doesnt/exists'=>array('TestAutoloader'=>array('pre_init_hook_test')));
    Autoloader::pre_init_hooks();
    if($GLOBALS['pre_init_hooks_test_value'] !== false) $this->results['pre_init_hooks']['incorrect_file_path'] = $ret = false;
    else $this->results['pre_init_hooks']['incorrect_file_path'] = true;

    $path = __FILE__;
    Config::$settings['pre_functions'] = array($path=>array('TestAutoloader'=>array('pre_init_hook_test')));
    Autoloader::pre_init_hooks();
    if($GLOBALS['pre_init_hooks_test_value'] !== true) $this->results['pre_init_hooks']['not_called_properly'] = $ret = false;
    else $this->results['pre_init_hooks']['not_called_properly'] = true;

    unset(Config::$settings['pre_functions']);
    return $ret;
  }

  public function init(){
    $ret = true;
    Autoloader::init();

    //did autoloader register itself as loaded?
    if(Autoloader::$loaded['Autoloader'] != Autoloader::path_to('autoloader')) $this->results['init']['autoloader_loaded'] = $ret = false;
    else $this->results['init']['autoloader_loaded'] = true;

    $this->results['init']['register_inis'] = false;
    $this->results['init']['pre_init_hooks'] = false;
    $this->results['init']['register_classes'] = false;

    return $ret;
  }

  public function register_inis(){
    $ret = true;

    //check if it can load the iterator class correctly
    $backup_iterator = Config::$settings['classes']['ini_directory_iterator'];
    $backup_listings = Config::$settings['listings'];

    Config::$settings['classes']['ini_directory_iterator'] = array(
      'class'=>'TestAutoloaderIteratorDummyClass',
      'base'=>SITE_DIR,
      'component'=>'plugins',
      'module'=>'teth-test',
    );
    Config::$settings['listings'] = array();

    Autoloader::register_inis();

    if(!class_exists('TestAutoloaderIteratorDummyClass', false)) $this->results['register_inis']['iterator_class_loads_correctly'] = $ret = false;
    else $this->results['register_inis']['iterator_class_loads_correctly'] = true;

    Config::$settings['classes']['ini_directory_iterator'] = $backup_iterator;
    Config::$settings['listings'] = $backup_listings;

    return $ret;
  }

  public function register_classes(){
    $ret = true;
    //copy over the current settings
    $original_classes = Autoloader::$classes;
    $original_comps = Autoloader::$components;
    //wipe them ready for testing
    Autoloader::$classes = Autoloader::$components = array();
    $dir = array('testing_register_classes'=>dirname(__FILE__));
    //register all the test classes directory
    Autoloader::register_classes($dir);
    $class = get_class($this);
    $class_path = __FILE__;

    if(!isset(Autoloader::$classes[$class])) $this->results['register_classes']['added_to_array'] = $ret = false;
    else $this->results['register_classes']['added_to_array'] = true;
    //as long as the class path matches then its all ok!
    if(Autoloader::$classes[$class] != $class_path) $this->results['register_classes']['correct_path'] = $ret = false;
    else $this->results['register_classes']['correct_path'] = true;

    //reset these back to originals so not to pollute other tests
    Autoloader::$classes = $original_classes;
    Autoloader::$components = $original_comps;

    return $ret;
  }

  public function add_component(){
    $ret = true;

    $original_comps = Autoloader::$components;
    Autoloader::$components = array();
    //the setup for teth tests...
    $comp_name = 'teth-test';
    $comp_dir = realpath(dirname(__FILE__)."/../../")."/";

    Autoloader::add_component($comp_name, $comp_dir);

    if(!isset(Autoloader::$components[$comp_name])) $this->results['add_component']['added_to_array'] = $ret = false;
    else $this->results['add_component']['added_to_array'] = true;

    if(!is_dir(Autoloader::$components[$comp_name])) $this->results['add_component']['is_dir'] = $ret = false;
    else $this->results['add_component']['is_dir'] = true;

    if(!is_readable(Autoloader::$components[$comp_name])) $this->results['add_component']['is_readable'] = $ret = false;
    else $this->results['add_component']['is_readable'] = true;

    if(Autoloader::$components[$comp_name] != ($comp_dir.$comp_name)) $this->results['add_component']['matching_path'] = $ret = false;
    else $this->results['add_component']['matching_path'] = true;

    Autoloader::$components = $original_comps;

    return $ret;
  }

  public function remove_component(){
    $ret = true;

    $original_comps = Autoloader::$components;
    $comp_name = 'core';
    Autoloader::remove_component('core');

    if(isset(Autoloader::$components[$comp_name])) $this->results['remove_component']['removed'] = $ret = false;
    else $this->results['remove_component']['removed'] = true;

    Autoloader::$components = $original_comps;

    return $ret;
  }

  public function load(){return true;}
  public function fetch_controllers(){return true;}
  public function go(){return true;}
  public function index(){return true;}



  public function pre_init_hook_test(){
    $GLOBALS['pre_init_hooks_test_value'] = true;
  }
}?>