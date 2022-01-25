declare const DB:Array<any>;
const INPUT_1 = document.getElementById('currency-one') as HTMLInputElement
const INPUT_2 = document.getElementById('currency-two') as HTMLInputElement
const SELECT_1 = document.getElementById('select-one') as HTMLSelectElement 
const SELECT_2 = document.getElementById('select-two') as HTMLSelectElement

function checkErrors() : boolean {
    // Geab HTML element we are going to 
    const ERROR_MESSAGE = document.getElementById('error-message') as HTMLParagraphElement
    ERROR_MESSAGE.innerHTML = ''
    ERROR_MESSAGE.innerHTML += !INPUT_1.value && !INPUT_2.value ? 'At least one input must be filled.<br />' : ''
    ERROR_MESSAGE.innerHTML += SELECT_1.value ? '' : 'Select 1 must have a currency chosen.<br />'
    ERROR_MESSAGE.innerHTML += SELECT_2.value ? '' : 'Select 2 must have a currency chosen.<br />'
    return !!ERROR_MESSAGE.innerHTML
}

function updateCurrency(selectElement:HTMLSelectElement) {
    let id:string = 'currency-' + (selectElement.id === 'select-one' ? 'one' : 'two')
    const INPUT = document.getElementById(id) as HTMLInputElement
    INPUT.dataset.rateNew = selectElement.value


    if(SELECT_1.value && SELECT_2.value)
        if (INPUT.value) calculateValue(INPUT)
        else if (INPUT_1.value) calculateValue(INPUT_1)
        else if (INPUT_2.value) calculateValue(INPUT_2)
}

function calculateValue(inputElement:HTMLInputElement) : void {
    // Both currencies must be selected and there must be data in the input field.
    if (checkErrors()) return

    // Declaring and assigning HTML Input fields to variables.
    const OTHER_INPUT = inputElement.id === 'currency-one' ? INPUT_2 : INPUT_1
    
    /* Check to see if inputElement is set to British Pounds, if not convert it.
       In this first instance we take the value regardless of what it is and assign it */
    let valueInPounds:number = parseFloat(inputElement.value)

    /* Now we divide it by `rateNew` which holds the value of the exchahnge rate.
       If it is already British then we divide it by `1.0` which doesn't change the value.
       We default to 1 if it is null because that is the value of the default element when starting*/
    valueInPounds /= parseFloat(inputElement.dataset.rateNew ?? '1')
    
    /* Assign the calculated valeu back to the other input field.
       The `+ ''` is there to quickly convert it to a string. */
    OTHER_INPUT.value = (valueInPounds * parseFloat(OTHER_INPUT.dataset.rateNew ?? '1')) + ''

    populateExtraResults(valueInPounds)
}

function populateExtraResults(valueInPounds:number) : void {
    const EXTRA_RESULTS = document.getElementById('extra-container') as HTMLDivElement
    EXTRA_RESULTS.innerHTML = ''
    DB.forEach(element => {
        /* We don't want to repeat data in the extra section, therefore if the currency exists already in the interface part
           of the website then skip over it in this loop */
        const INP1_CURR:number = parseFloat(INPUT_1.dataset.rateNew ?? '')
        const INP2_CURR:number = parseFloat(INPUT_2.dataset.rateNew ?? '') // Assigned for readaility purposes
        if (element.rateNew === INP1_CURR || element.rateNew === INP2_CURR) return
 
        EXTRA_RESULTS.innerHTML += `
            <div class="card">
                <h3>${element.countryName} :: ${element.currencyCode}</h3>
                <p>${valueInPounds * element.rateNew}</p>
            </div>
        `
    });
}