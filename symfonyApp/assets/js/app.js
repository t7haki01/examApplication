/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');
import axios from 'axios';


console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

let submitButton = document.getElementById('form_save');
submitButton.forEach(button => button.addEventListener('click', examplesNumber));

function examplesNumber(event){
    let exampleValue = document.getElementsByName('form[examples]').value;
    let exampleArray = exampleValue.split(",");
    document.getElementsByName('form[examples]').value = exampleArray;

    let answerValue = document.getElementsByName('form[answers]').value;
    let answerArray = answerValue.split(",");
    document.getElementsByName('form[answers]').value = answerArray;
}

