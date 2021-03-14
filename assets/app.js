/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
// import './bootstrap';

// const $ = require('jquery');
// require('popper.js');
require('bootstrap');
import bsCustomFileInput from 'bs-custom-file-input';
bsCustomFileInput.init();

let iframes = document.getElementsByTagName('iframe');
iframes.forEach(element => {
    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    var match = element.src.match(regExp);

    if (match && match[2].length == 11) {
        element.src = "//www.youtube.com/embed/" + match[2];
    }
});