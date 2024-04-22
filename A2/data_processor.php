<?php
//I, Xuan Huy Pham, 000899551, certify that this material is my original work. 
//No other person's work has been used without suitable acknowledgment, and I have not made my work available to anyone else.

/**
 * @author Xuan Huy Pham
 * @version 20231104.00
 * @package COMP 10260 Assignment 2
 */


 /**
 * define the functions to read and parse the pokemon data
 * @return $pokemon item
 */
function readPokemon() {
    //read the data from the local file, ignoring empty lines
    //reference: https://www.php.net/manual/en/function.file.php
    $data = file("pokemon.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);//read the data file
    $pokemon = [];//create an array for pokemons
    $i = 0;
    //adding images and their names in array
    while ($i < count($data)) {
        $name = $data[$i];
        $image = $data[$i + 1];
        $pokemon[] = ['name' => $name, 'image' => $image];
        $i += 2;
    }
    return $pokemon;
}


//define the functions to read and parse the movie data
function readMovies() {
    $data = file_get_contents("movies.json");//get data from movies.json file
    $movies = json_decode($data, true);//decode json into an array
    return $movies;
}


//check if there are 2 parameters in the URL 'choice' and 'sort'
//get the 'choice' parameter from the URL
$choice = isset($_GET['choice']) ? $_GET['choice'] : '';
//get the 'sort' parameter from the URL
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';


//choose the appropriate data file and read the data
if ($choice === 'pokemon') {
    $data = readPokemon();//use the pokemon data if 'choice' is 'pokemon'
} elseif ($choice === 'movies') {
    $data = readMovies();//use the movie data if 'choice' is 'movies'
} else {
    $errorResponse = ['error' => 'Invalid choice'];
    echo json_encode($errorResponse);
    exit;
}



//sort the data based on the selected sorting option
//'a' stands for ascending order, 'd' stands for descending order
if ($sort === 'a') {
    //ascending sorting by name (A - Z)
    usort($data, function ($a, $b) {
        return strcmp($a['name'], $b['name']);//return a negative value
    });
} elseif ($sort === 'd') {
    //descending sorting by name (Z - A)
    usort($data, function ($a, $b) {
        return strcmp($b['name'], $a['name']);//return a positive value
    });
} else {
    $errorResponse = ['error' => 'Invalid, use "a" for ascending or "d" for descending sorting.'];
    echo json_encode(($errorResponse));
}

//encode the sorted data as json and then send it to the client side as a response 
echo json_encode($data);
