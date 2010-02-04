<?
class TestExampleClass{
  
  public function __construct($flagged=false){
    if($flagged) $GLOBALS['application_has_run'] = true;
  }
}
?>