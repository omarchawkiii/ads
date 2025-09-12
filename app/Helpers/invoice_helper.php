<?php

if (! function_exists('invoice_number')) {
    function invoice_number($number)
    {
        return 'INV-' . $number;
    }
}


?>
