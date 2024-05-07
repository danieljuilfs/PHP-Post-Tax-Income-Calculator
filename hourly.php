<?php 
    if (isset($_SERVER['HTTP_HX_REQUEST'])) {
        $selected = $_REQUEST['paytype'];
        if ($selected === 'Salary') {
            echo '<label for="interval">Salary Interval </label><br>'; 
            echo '<select name="interval" id="paymethod">';
            echo '<option>Per Year</option>';
            echo '<option>Per Pay Period </option>'; 
            echo '</select>'; 
        }
        else {
            echo '<label for="hours">Hours</label><br>';
            echo '<input type="text" id="hours" name="hours" value="0">';
        }
}
?>
