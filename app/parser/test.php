<?php
class Foo
{
    public $bar = 2;
    public $var2 = 3;
    public $var3 = 4;
    function func1($i,$x) {
      $res=5+4+5;
      $num;
      return $res;
    }
    function func2($a) {
      $res=5+4;
      $k = 6/3;
      $i=4;
      while($i>0){
        echo("ok");
        $i--;
      }
      return $res;
    }
    function fonc3(){
      foreach ($variable as $key => $value) {
        echo("yes");
      }
    }
    function finalFonction(){
       return $x;
    }

}
$test=newFoo();
$test->func1($i,$x);
?>
