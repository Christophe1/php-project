<?php


$contacts1 = array(
    array(
        "cat_id" => "Peter Parker",
        "cat_name" => "peterparker@mail.com",
    ),
    array(
        "cat_id" => "Clark Kent",
        "cat_name" => "clarkkent@mail.com",
    ),
    array(
        "cat_id" => "Harry Potter",
        "cat_name" => "harrypotter@mail.com",
    )
);

$contacts2 = array(
    array(
        "cat_id" => "Peter Parker",
        "cat_name" => "peterparker@mail.com",
    ),
    array(
        "cat_id" => "Clark Kent",
        "cat_name" => "clarkkent@mail.com",
    ),
    array(
        "cat_id" => "Harry Potter",
        "cat_name" => "harrypotter@mail.com",
    ),
    array(
        "cat_id" => "Simon Cowell",
        "cat_name" => "simoncowell@mail.com",
    )
);


$array1AndArray2 = array_merge_recursive($contacts1, $contacts2);

echo json_encode($array1AndArray2);
echo "-----<br>";
echo "-----<br>";

//$uniqueArray = json_encode(array_unique($array1AndArray2, SORT_REGULAR));

$uniqueArray = array_values(array_unique($array1AndArray2, SORT_REGULAR));


echo json_encode($uniqueArray);
//echo "the merged array is" . json_encode(array_unique($array1AndArray2AndArray3));

//$input = array_map("unserialize", array_unique(array_map("serialize", $array1AndArray2AndArray3)));

//echo json_encode($input);



?>