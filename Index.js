"use strict";
const INPUT_1 = document.getElementById('currency-one');
const INPUT_2 = document.getElementById('currency-two');
const SELECT_1 = document.getElementById('select-one');
const SELECT_2 = document.getElementById('select-two');
function checkErrors() {
    const ERROR_MESSAGE = document.getElementById('error-message');
    ERROR_MESSAGE.innerHTML = '';
    ERROR_MESSAGE.innerHTML += !INPUT_1.value && !INPUT_2.value ? 'At least one input must be filled.<br />' : '';
    ERROR_MESSAGE.innerHTML += SELECT_1.value ? '' : 'Select 1 must have a currency chosen.<br />';
    ERROR_MESSAGE.innerHTML += SELECT_2.value ? '' : 'Select 2 must have a currency chosen.<br />';
    return !!ERROR_MESSAGE.innerHTML;
}
function updateCurrency(selectElement) {
    let id = 'currency-' + (selectElement.id === 'select-one' ? 'one' : 'two');
    const INPUT = document.getElementById(id);
    INPUT.dataset.rateNew = selectElement.value;
    if (SELECT_1.value && SELECT_2.value)
        if (INPUT.value)
            calculateValue(INPUT);
        else if (INPUT_1.value)
            calculateValue(INPUT_1);
        else if (INPUT_2.value)
            calculateValue(INPUT_2);
}
function calculateValue(inputElement) {
    if (checkErrors())
        return;
    const OTHER_INPUT = inputElement.id === 'currency-one' ? INPUT_2 : INPUT_1;
    let valueInPounds = parseFloat(inputElement.value);
    valueInPounds /= parseFloat(inputElement.dataset.rateNew ?? '1');
    OTHER_INPUT.value = (valueInPounds * parseFloat(OTHER_INPUT.dataset.rateNew ?? '1')) + '';
    populateExtraResults(valueInPounds);
}
function populateExtraResults(valueInPounds) {
    const EXTRA_RESULTS = document.getElementById('extra-container');
    EXTRA_RESULTS.innerHTML = '';
    DB.forEach(element => {
        const INP1_CURR = parseFloat(INPUT_1.dataset.rateNew ?? '');
        const INP2_CURR = parseFloat(INPUT_2.dataset.rateNew ?? '');
        if (element.rateNew === INP1_CURR || element.rateNew === INP2_CURR)
            return;
        EXTRA_RESULTS.innerHTML += `
            <div class="card">
                <h3>${element.countryName} :: ${element.currencyCode}</h3>
                <p>${valueInPounds * element.rateNew}</p>
            </div>
        `;
    });
}
