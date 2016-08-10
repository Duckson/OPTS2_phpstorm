<?php
var_dump($_GET);
?>

<form action="test.php" method="get">
    <label>
        1
        <input type="checkbox" name="test[]" value="1">
    </label>
    <label>
        2
        <input type="checkbox" name="test[]" value="2">
    </label>
    <label>
        3
        <input type="checkbox" name="test[]" value="3">
    </label>
    <label>
        4
        <input type="checkbox" name="test[]" value="4">
    </label>
    <button type="submit">Submit</button>
</form>
