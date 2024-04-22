<?php
//I, Xuan Huy Pham, 000899551, certify that this material is my original work. 
//No other person's work has been used without suitable acknowledgment, and I have not made my work available to anyone else.

/**
 * @author Xuan Huy Pham
 * @version 20231104.00
 * @package COMP 10260 Assignment 2
 */

//check if a POST request with a file is received
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['csvFile'])) {
    //if it's not, exit and return a message
    exit("Invalid POST request");
}

//sanitize and validate the sortColumn parameter
//check if sortColumn is set in the POST data
if (isset($_POST['sortColumn'])) {
    //if it is set, convert it to an integer
    $sortColumn = (int)$_POST['sortColumn'];
} else {
    //if not, use a default value (in this case, 1)
    $sortColumn = 1;
}

//sanitize and validate the uploaded file
$uploadedFile = $_FILES['csvFile'];
$fileName = $uploadedFile['name'];

//check if the uploaded file is in correct format CSV
$fileFormat = pathinfo($fileName, PATHINFO_EXTENSION);
if ($fileFormat !== 'csv') {
    //if it's not a CSV file, exit and display an error message
    exit("Invalid file format, CSV file only");
}


//process the uploaded CSV file
//read the contents of the CSV file, process line by line and parses each line 
//into an array
$csvData = array_map('str_getcsv', file($uploadedFile['tmp_name']));

//check if the file contains data
//if data has no rows, exit with an errpr message
if (count($csvData) === 0) {
    exit("File is empty");
}

//extract the header row
//take the first header row from the $csvData and assign it to $headerRow
$headerRow = array_shift($csvData); 

//validate the selected sort column
//check if the sorting column is a valid int within the range of available columns in the CSV
if ($sortColumn < 1 || $sortColumn > count($headerRow)) {
    exit("Invalid sortColumn parameter");
}

//sort the data stored in $vscData array based on the selected column ($sortColumn)
//ref: https://www.php.net/manual/en/function.usort.php
usort($csvData, function ($a, $b) use ($sortColumn) {
    //compare $a amnd $b
    //because array indices are 0-based, -1 means one-based
    return strnatcasecmp($a[$sortColumn - 1], $b[$sortColumn - 1]);
});

//create an array using the header row as keys
//for each row in CSV file, create an associative array where the keys are based on the header row
//the value is from the current row
$resultData = [];
foreach ($csvData as $row) {
    $resultData[] = createAssociativeArray($headerRow, $row);//add these arrays to $resultData array
}

/**
 * this function takes 2 arrays keys and values and combine them to create an association array
 * @param array $keys an array containing keys
 * @param array $values an array containing corresponding values
 * @return array | null an array combining keys and values 
 */
function createAssociativeArray($keys, $values) {
    return array_combine($keys, $values);
}
//encode the sorted data as json and send it as the response
echo json_encode($resultData);
?>
