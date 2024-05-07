<?php 
require 'dbmodel.php'; 
require 'datamodel.php'; 

//start the session of passing data
session_start(); 

//check to see if the post array is empty
if (!empty($_POST)) {
    //make sure paymethod exists, will update if necessary
    $paymethod;

    //make sure hours exists, update if necessary
    $hours; 

    //create salary to be updated in if
    $salary; 

    //define spousal salary for use. 
    $spouse = 0; 

    //getting all the values out of the array when applicable
    $paytype = $_POST['paytype'];
    $amount = $_POST['amount']; 

    //validate that inputtt
    if (!(is_numeric($amount))) {
        $amount = 0; 
    }
    
    $payperiod = $_POST['payperiod'];
    $state = $_POST['states']; 
    $insurance = $_POST['insurance'];

    //input validation!
    if (!(is_numeric($insurance))) {
        $insurance = 0; 
    }
    $filingstatus = $_POST['filingstatus']; 

    $pretax= $_POST['pretax']; 

    //input validation!
    if (!(is_numeric($pretax))) {
        $pretax = 0; 
    }

    $additional = $_POST['additional']; 

    if (!(is_numeric($additional))) {
        $additional = 0; 
    }
    $period = period($payperiod); 

    //if married get the spouses salary
    if ($filingstatus === "Married filing jointly") {
        $spouse = $_POST['spousesalary']; 
    }
   
    //call the stored procedure to get some information about stateid, and the deduction amounts
   $stateresults = state($state);  
   $stateid = $stateresults['stateID'];
   $statesingle = $stateresults['stdDedSing'];
   $statemarried = $stateresults['stdDedMar']; 


    //for separation of concerns we're using MVC. passing data to the datamodel to do calculations
    //if the user is salaried, get their payment interval, then call the earnings salary calc. 
    
    if ($paytype === 'Salary') {
        $interval = $_POST['interval']; 
        $salary = earningsSalary($interval,  $amount, $payperiod); 
    }
    else {
        $hours = $_POST['hours'];
        $salary = earningsHourly($hours, $amount, $payperiod); 
    }


    //remove the pretax deductions from income before calculating tax
    $salary = pretax($salary, $pretax, $period); 

    //call the function to get the various state tax rates; 
    $taxrates = stateRates($stateid);
    
    //call the function to calculate the state tax amount
    $statetax = stateTax($salary, $filingstatus, $spouse, $taxrates, $statesingle, $statemarried);

    //calculate the various taxes
    $fedtax = fedTax($salary,  $filingstatus, $spouse); 
    $medicare = medTax($salary); 
    $socialsec = socTax($salary);
    
    //get the tax total
    $totaltax = totalTax($fedtax, $medicare, $socialsec, $statetax); 

    //get salary per period
    $salaryPeriod = salaryPeriod($salary, $period);
    
    //get tax per period
    $taxPeriod = taxPeriod($totaltax, $period); 

    //get the total post tax deductions
    $deductions = posttax($insurance, $additional); 

    $takehome = takehome($salaryPeriod, $taxPeriod, $deductions); 
    
    //update the output display at the bottom of the page
    echo ' <div class="earnings outputme">';
    echo '<span class="purptext">$' . round($salaryPeriod,2) . '</span>';
    echo ' <p><b>Pre-tax Pay</b></p><br>';
    echo '</div>';
    echo '<div class="taxes outputme">';
    echo ' <span class="purptext">$' . round($taxPeriod, 2) . '</span>';
    echo ' <p><b>Total Taxes</b></p><br>';
    echo '</div>';
    echo '<div class="deductions outputme">';
    echo '<span class="purptext">$' . round($deductions, 2) . '</span>';
    echo '<p><b>Deductions</b></p><br>';
    echo '</div>';
    echo '<div class="takehome outputme">';
    echo '<span class="purptext">$' . round($takehome, 2) . '</span>';
    echo '<p><b>Take Home</b></p><br>';
    echo '</div>';
    echo '</div>';






}

