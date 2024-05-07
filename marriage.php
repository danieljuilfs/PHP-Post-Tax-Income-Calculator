<?php 
    if (isset($_SERVER['HTTP_HX_REQUEST'])) {
            $selected = $_REQUEST['filingstatus'];
            if ($selected === 'Married filing jointly') {
                echo '<label for="spousesalary">Spouse Salary </label><br>'; 
                echo '<input type="text" id="spousesalary" name="spousesalary" value="0">'; 
            }
        }
    else 'echo ""'
?>