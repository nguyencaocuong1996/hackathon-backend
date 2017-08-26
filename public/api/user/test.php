<form action="">
    <input type="hidden" name="action" value="create" id="">
    <input type="text" name="userN" id="">
    <input type="submit" value="sub">
</form>

<?php
$a = array("a"=>1, "b"=>3);
array_walk($a, function(&$k, $v){
    $k = 1111;
});
print_r($a);
?>