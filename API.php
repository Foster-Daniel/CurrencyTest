<?php

// Pass a valid PHP onject to retrieve a printable JavaScript object and variable.
function phpToJavaScript(object | array $phpObject) : string {
    $javascriptObject = '[ 
    {
        countryName:  "United Kingdom",
        countryCode:  "UK",
        currencyName: "Pound Sterling",
        currencyCode: "GBP",
        rateNew: 1
    }
    ';
    
    /* Iterate through the object and convert the syntax to JavaScript syntax.
       We will later print this into the DOM in a script tag. */
    $i = 0;
    foreach ($phpObject as $php) {
        // Strip double quotes from the string just incase.
        $countryName  = str_replace('"', '\"', $php->countryName);
        $countryCode  = str_replace('"', '\"', $php->countryCode);
        $currencyName = str_replace('"', '\"', $php->currencyName);
        $currencyCode = str_replace('"', '\"', $php->currencyCode);
        $javascriptObject .= 
        "
        , {
            countryName:  \"$countryName\",
            countryCode:  \"$countryCode\",
            currencyName: \"$currencyName\",
            currencyCode: \"$currencyCode\",
            rateNew: {$php->rateNew}
        }
        ";
    }
    return $javascriptObject . ']';
}

function getData() {
    require_once 'Database.php';
    // Do a check to see if we already have a populated database and if we do retrieve data from it.
    if (isDataBasePopulated()) $currencies = retrieveAsObject();
    
    // If the database isn't populated, we retrieve the data from an API and then poplate the database
    else {
        $currencies = file_get_contents('http://www.hmrc.gov.uk/softwaredevelopers/rates/exrates-monthly-0122.XML');
        $currencies = new SimpleXMLElement($currencies);
        populateDatabase($currencies);
    }
    return $currencies;
}

$currencies = getData();
$javascriptObject = phpToJavaScript($currencies);