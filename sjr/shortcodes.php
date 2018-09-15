<?php
foreach(['common.php',
         'state.php',
         'input.php',
         'form.php',
         'form_display.php',
] as $f)
{
    require_once( dirname( __FILE__ ) . "/shortcodes/$f" );
}
