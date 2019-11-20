//css
import '../css/app.css';


//js
import $ from 'jquery';
import 'bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';

//several initialisations
$(document).ready(function () {
    bsCustomFileInput.init();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

});