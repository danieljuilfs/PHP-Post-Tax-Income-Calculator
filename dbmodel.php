<?php 

//function to get the state id and deduction amounts
function state($state) {
  $servername = "localhost";
  $username = "itp246";
  $password = "itp246"; 

  try {
    //try to connect to the database
    $conn = new PDO("mysql:host=$servername;dbname=taxdatabase", $username, $password); 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //if connected, prepare the statement
    $statement = $conn->prepare("CALL getStateInfo(?)"); 
    //pass it the correct parameters
    $statement->bindParam(1, $state); 
    //execute it
    $statement->execute(); 
    //fetch the result set. this set should only ever be one row. 
    $results = $statement->fetch(PDO::FETCH_ASSOC); 
    return $results; 

    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
}

//gets the tax brackets
function stateRates($stateID) {
  $servername = "localhost";
  $username = "itp246";
  $password = "itp246"; 

  try {
    //try to connect to the database
    $conn = new PDO("mysql:host=$servername;dbname=taxdatabase", $username, $password); 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //if connected, prepare the statement
    $statement = $conn->prepare("CALL getTaxRates(?)"); 
    //pass it the correct parameters
    $statement->bindParam(1, $stateID); 
     //execute it
    $statement->execute(); 

    //fetch the results sets
    $results = $statement->fetchAll(PDO::FETCH_ASSOC); 
    
    //return the array of arrays
    return $results; 
    
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
}


?>