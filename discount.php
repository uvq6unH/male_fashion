<?php // Start the session

// Initialize selected discounts if not already set
if (!isset($_SESSION['selected_discounts'])) {
    $_SESSION['selected_discounts'] = [
        'DISCOUNT5' => 5,
        'DISCOUNT10' => 10,
        'DISCOUNT15' => 15,
        'DISCOUNT20' => 20,
        'DISCOUNT25' => 25,
    ];
}
