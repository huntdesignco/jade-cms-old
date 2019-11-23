<?php

//////////////////////////////
// Init functions
//////////////////////////////

register_asset('css', 'bootstrap4-styles', get_jade_dir() . '/assets/css/bootstrap.min.css');
register_asset('css', 'jade-styles', get_jade_dir() . '/assets/css/styles.css');
register_asset('css', 'fontawesome-styles', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css');

register_asset('js', 'popper-scripts', get_jade_dir() . '/assets/js/popper.min.js');
register_asset('js', 'jquery-scripts', get_jade_dir() . '/assets/js/jquery-3.3.1.slim.min.js');
register_asset('js', 'bootstrap4-scripts', get_jade_dir() . '/assets/js/bootstrap.min.js');
