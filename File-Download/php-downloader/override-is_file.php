<?php
function override_is_file($file) {
        $f = pathinfo($file, PATHINFO_EXTENSION);
        return (strlen($f) > 0) ? true : false;
}
# $helloWorld = 'echo "Redefined Hello World: $word\n</br>";';
# hello_world('test1');
runkit_function_redefine('is_file', '$file', 'override_is_file');
# hello_world('test2');
?>
