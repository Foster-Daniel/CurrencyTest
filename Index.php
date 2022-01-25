<?php
    // Everything will be mapped to the following variable $db
    require_once('./API.php')
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Exchange</title>
    <link rel="stylesheet" href="Index.css">
    <script src="Index.js" defer></script>
</head>
<body>
    <main>
        <h1>Currency Exchange</h1>
        <div id="interface">
            <div class="currency-input-container">
                <label for="currency-one">Currency One</label>
                <div class="input-container">
                    <select name="select-one" id="select-one" onchange="updateCurrency(this)">
                        <option value="1">United Kingdom - Pound Sterling | GBP</option>
                        <?php
                            foreach ($currencies as $currency)
                                echo "<option value=\"{$currency->rateNew}\">{$currency->countryName} - {$currency->currencyName} | {$currency->currencyCode}</option>"
                        ?>
                    </select>
                    <input type="number" name="currency-one" id="currency-one" placeholder="Enter an amount" oninput="calculateValue(this)" data-rate-new="1">
                </div>
            </div>
            <div class="currency-input-container">
                <label for="currency-two">Currency Two</label>
                <div class="input-container">
                    <select name="select-two" id="select-two" onchange="updateCurrency(this)">
                        <option value="" selected>Select a Currency</option>
                        <option value="1">United Kingdom - Pound Sterling | GBP</option>
                        <?php
                            foreach ($currencies as $currency)
                            echo "<option value=\"{$currency->rateNew}\">{$currency->countryName} - {$currency->currencyName} | {$currency->currencyCode}</option>"
                                
                        ?>
                    </select>
                    <input type="number" name="currency-two" id="currency-two" placeholder="Enter an amount" oninput="calculateValue(this)">
                </div>
            </div>
        </div>
        <p id="error-message"></p>
        <hr>
        <div id="extra">
            <h2>Extra Conversions</h2>
            <div id="extra-container">More Results will appear here when you search.</div>
        </div>
    </main>
    <script>
        const DB = <?= $javascriptObject; ?>;
    </script>
</body>
</html>