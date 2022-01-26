<?php
/**/
const HOST     = 'localhost:3307';
const PASSWORD = 'Qwe123.';
const USER     = 'root';
const DATABASE = 'currencies';

class CurrencyObject {
    public string $countryName;
    public string $countryCode;
    public string $currencyName;
    public string $currencyCode;
    public float $rateNew;

    function __construct(string $countryName, string $countryCode, string $currencyName, string $currencyCode, float $rateNew) {
        $this->countryName  = $countryName;
        $this->countryCode  = $countryCode;
        $this->currencyName = $currencyName;
        $this->currencyCode = $currencyCode;
        $this->rateNew      = $rateNew;
    }
}

// We make a request to retrieve information from the database, if it exists then we know the database is populated.
function isDataBasePopulated() : bool | null { 
    $db = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        return null;
    }

    // Get the number of rows and return it as a boolean data type. 0 = false
    $count = $db->query("SELECT * FROM currencies.currencies");
    return (bool)$count->num_rows;
}

function createTable() {
    // If the table has already been created then then return()
    $db = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    // If there was an error, report said error and exit the program.
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        return null;
    }

    // If the table already exists then don't attempt to create it.
    $result = $db->query("SHOW TABLES LIKE 'currencies'");
    while($row = mysqli_fetch_assoc($result))
        if ($row['Tables_in_currencies (currencies)'] === 'currencies') return false;

    // Create table if it doesn't exist.
    $db->query("
        CREATE TABLE `currencies` (
            `id`            INT         NOT NULL AUTO_INCREMENT,
            `country_name`  VARCHAR(30) NOT NULL,
            `country_code`  VARCHAR(5)  NOT NULL,
            `currency_name` VARCHAR(30) NOT NULL,
            `currency_code` VARCHAR(5)  NOT NULL,
            `rate_new`      VARCHAR(15) NOT NULL,
            PRIMARY KEY (`id`));
    ");
}


function populateDatabase(object $currencies) : bool | null {
    // Connect to the database
    $db = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    // If there was an error, report said error and exit the program.
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        return null;
    }
    
    // Clean Database
    $db->query("DELETE FROM currencies.currencies");
    $db->query("ALTER TABLE currencies.currencies AUTO_INCREMENT = 1");

    // Iterate through the provided object and add it to the database.
    foreach ($currencies as $currency) {
        $countryName  = mysqli_real_escape_string($db, $currency->countryName);
        $countryCode  = mysqli_real_escape_string($db, $currency->countryCode);
        $currencyName = mysqli_real_escape_string($db, $currency->currencyName);
        $currencyCode = mysqli_real_escape_string($db, $currency->currencyCode);

        $result = $db->query("INSERT INTO currencies.currencies(country_name, country_code, currency_name, currency_code, rate_new)
                              VALUES('$countryName', '$countryCode', '$countryName', '$countryCode', '{$currency->rateNew}');");
    }

    // Close Database
    $db->close();
    return true;
}
function retrieveAsObject() : array {
    // Connect to the database
    $db = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    // If there was an error, report said error adn exit the program.
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        return null;
    }

    // Retrieve all elements from the database.
    $result = $db->query("SELECT * FROM currencies.currencies GROUP BY country_code");
    
    // Iterate through $result and apply all information to an object array.
    $phpObject = [];
    while ($row = mysqli_fetch_array($result))
        $phpObject[] = new CurrencyObject($row['country_name'], $row['country_code'] , $row['currency_name'], $row['currency_code'], $row['rate_new']);

    // Close Database
    $db->close();
    return $phpObject;
}