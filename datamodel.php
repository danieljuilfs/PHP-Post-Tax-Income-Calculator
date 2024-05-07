<?php 
    $salary = 0; 

    //calculating the yearly salary for salaried workers
function earningsSalary($interval,  $amount, $payperiod) {
    
    //if the payment amount is yearly salary is just the amount
    if ($interval === 'Per Year') {
        $salary = $amount; 
    }
    //otherwise calculate the amount based on the payperiod given by the user
    else {
        switch ($payperiod) {
            case "Weekly":
                $salary = 52 * $amount; 
                break; 
            case "Bi-Weekly":
                $salary = 26 * $amount; 
                break;
            case "Monthly":
                $salary = 12 * $amount;
                break;
            case "Semi-monthly":  
                $salary = 24 * $amount;
                break; 
            }    
    }
    return $salary; 
}

//calculating the yearly salary for hourly workers based on the 
function earningsHourly($hours, $amount, $payperiod) {
    switch ($payperiod) {
        case "Weekly":
            $salary = 52 * $amount * $hours; 
            break; 
        case "Bi-Weekly":
            $salary = 26 * $amount * $hours; 
            break;
        case "Monthly":
            $salary = 12 * $amount * $hours;
            break;
        case "Semi-monthly":  
            $salary = 24 * $amount * $hours;
            break;        
    }
    return $salary; 
}

function fedTax($salary, $filingstatus, $spouse) {
 
    $originalSalary = $salary; 
    //single standard deduction
    $deduction = 14600; 
  
    //array holding all the upper bounds of the brackets
    $brackets = [11600, 47150, 100525, 191950, 243725, 609351];

    //if filing as maried double the deduction, add the spouses salary, and double all the tax brackets
    if ($filingstatus === 'Married filing jointly') {
        $salary += $spouse;
        $deduction *= 2;

        //loop through the arrays and double all the brackets if married filing jointly
       for ($i=0; $i < count($brackets); $i++) 
            if ($i == 5) {
                $brackets[$i] *= 1.2; 
            }
            else $brackets[$i] *=2; 
         }
    
    //remove deduction to adjust income
    $salary -= $deduction;  


    //set tax amount to zero then update it in the giant if
    $taxamt = 0; 

    //calculating the federal tax amount. This is hideous. But it works. 
    //You have to subtract the highest tax bracket from the lowest tax bracket at each point. 
    if ($salary < $brackets[0]) {
        $taxamt = $salary * .1;  
    }
    else if ($salary < $brackets[1]) {
        $taxamt = ($brackets[0] * .1) + (($salary-$brackets[0]) * .12);
    }
    else if ($salary < $brackets[2]) {
        $taxamt = ($brackets[0] * .1) + (($brackets[1] - $brackets[0]) * .12) + (($salary - $brackets[1]) * .22);
    }
    else if ($salary < $brackets[3]) {
        $taxamt = ($brackets[0] * .1) + (($brackets[1] - $brackets[0]) * .12) + (($brackets[2] - $brackets[1])* .22) + (($salary - $brackets[2]) * .24) ;
    }
    else if ($salary < $brackets[4]) {
        $taxamt = ($brackets[0] * .1) + (($brackets[1] - $brackets[0]) * .12) + (($brackets[2] - $brackets[1])* .22) + (($brackets[3] - $brackets[2]) * .24) + 
        (($salary - $brackets[3]) * .32) ;
    }
    else if ($salary < $brackets[5]) {
        $taxamt = ($brackets[0] * .1) + (($brackets[1] - $brackets[0]) * .12) + (($brackets[2] - $brackets[1])* .22) + (($brackets[3] - $brackets[2]) * .24) + 
        (($brackets[4] - $brackets[3]) * .32) + (($salary - $brackets[4]) * .35); 
    }
    else {
        $taxamt = ($brackets[0] * .1) + (($brackets[1] - $brackets[0]) * .12) +  (($brackets[2] - $brackets[1])* .22)  + (($brackets[3] - $brackets[2]) * .24) + 
        (($brackets[4] - $brackets[3]) * .32) + (($brackets[5] - $brackets[4]) * .35) + (($salary - $brackets[5]) *.37); 
    }
    
    //adjust tax amount for what percentage of the total income your taxes are. 
    //For instance if you make 100k and spouse makes 150k, you get 100/250k in tax burden. 
    if ($filingstatus === 'Married filing jointly') {
        //echo $taxamt . ' preadjust' . '<br>'; 
        $taxamt = ($originalSalary/($originalSalary + $spouse)) * $taxamt; 
        //echo $taxamt . ' postadjust' . '<br>'; 
        
    }
    return $taxamt; 
}

function socTax($salary) {
    //social security is only taxed on the first 168600 in 2024
    if ($salary > 168600) {
        return (168600 * .062);
    }
    else return ($salary * .062); 
}

function medTax($salary) {
    //medicare is always 1.45% of income
    return $salary * .0145; 
}

//calculate the total tax and return it to the calling method
function totalTax($fedtax, $medtax, $soctax, $statetax) {
    $totalTax = $fedtax + $medtax + $soctax + $statetax; 
    if ($totalTax < 0) {
        $totalTax = 0; 
    }

    return $totalTax;
}

//calculate the pay period for use in other functions
function period($payperiod) {
    $period = 0; 
    switch ($payperiod) {
        case "Weekly":
            $period = 52; 
            break; 
        case "Bi-Weekly":
            $period = 26; 
            break;
        case "Monthly":
            $period = 12; 
            break;
        case "Semi-monthly":  
            $period = 24; 
            break;        
    }
    return $period; 
}

//return the salary/pay period for display on the front end
function salaryPeriod($salary, $period) {
    return $salary/$period; 
}

function taxPeriod($totaltax, $period) {
    return $totaltax/$period;
}

function stateTax($salary, $filingstatus, $spouse, $taxrates, $sinDed, $marDed){
 
    //keeping track of marriage status using this. 
    $status = false; 

    //grab the starting salary for use later if the user is married. 
    $originalSalary = $salary; 
    if ($filingstatus === 'Married filing jointly') {
        $salary += $spouse;
        //remove the deduction from pretax income if married
        $salary -= $marDed; 
        $status = true; 
        $mar = $salary; 
    }
    else {
        $salary -= $sinDed; 
       
    }
    
    //arrays to sort values into
    $single = [];
    $married = [];
    $rates = [];

    //loop over the array of arrays, sort the values into specific type arrays for handling rather than trying to work with nested arrays. 
    for($i=0; $i < count($taxrates); $i++) {
        $single[$i] = $taxrates[$i]['singleAmt'];
        $married[$i] = $taxrates[$i]['marriedAmt'];
        $rates[$i] = $taxrates[$i]['taxRate']; 
    }

    //initialize tax amount
    $taxamt = 0;

    //calculate the taxes, if married use the first calc if not married use the second
    if ($status) {
        //starting at the end of the array for ease, adding the taxes for each phase and moving on. 
        for ($i=count($married)-1; $i >= 0; $i--) {
            if ($salary < $married[$i]){
                //if salary is lower than current bracket move to the next iteration of the loop
                continue; 
            } 
            //tax brackets technically start at the bracket + 1 so adding 1. 
            else {
                $calc = ($salary - ($married[$i] + 1)) * ($rates[$i]/100);
                $taxamt += $calc;

                //deduct the amount taxed from the salary for use in the next step of the loop
                $salary -= ($salary - ($married[$i])); 
            }
        }
    }
    else {
        for ($i=count($single)-1; $i >= 0; $i--) {
            if ($salary < $single[$i]){
                //if salary is lower than current bracket move to the next iteration of the loop
                continue; 
            } 
            //tax brackets technically start at the bracket + 1 so adding 1.
             
            else {
                //the rate is a whole number and should be a decimal so dividing by 100
                $calc = ($salary - ($single[$i] + 1)) * ($rates[$i]/100);
                $taxamt += $calc; 
                //deduct the amount taxed from the salary for use in the next step of the loop
                $salary -= ($salary - ($single[$i])); 
            }
        }
    } 
        //adjust tax amount for what percentage of the total income your taxes are. 
    //For instance if you make 100k and spouse makes 150k, you get 100/250k in tax burden. 
    if ($filingstatus === 'Married filing jointly') {
        
        $taxamt = ($originalSalary/$mar) * $taxamt; 
    }

    return $taxamt;  

}

//remove pretax deductions from the tax amount
function pretax($salary, $pretax, $period) {

    if (!($pretax >= 0)) {
        $pretax = 0;
    }
    if (!($salary >= 0)) {
        $salary = 0; 
    }

    return $salary - ($pretax * $period); 
}

function posttax($insurance, $additional) {
    if (!($insurance >= 0)) {
        $insurance = 0; 
    }
    if (!($additional >=0 )) {
        $additional = 0; 
    }
    return $insurance + $additional; 
}

function takehome($salaryPeriod, $taxPeriod, $deductions) {
    return $salaryPeriod - $taxPeriod - $deductions; 
}