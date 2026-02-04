<?php
return [
    'public' => [
        'retrieved' => 'Data retrieved successfully.',
        'stored' => 'The item was stored successfully.',
        'deleted' => 'The item was removed successfully.',
        'updated' => 'The item was updated successfully.',
        'status' => ':item :status successfully.',
        'invalid' => 'Item not found!',
    ],
    'auth' => [
        'not_verified' => 'The user is not verified.',
        'not_found' => 'User not found.',
        'invalid_register_method' => 'The registration method is invalid.',
        'already_registered' => 'The user is already registered.',
        'login' => 'The user logged in successfully.',
        'registered' => 'The user registered successfully.',
        'invalid_oauth_token' => 'The provided OAuth token is invalid or expired.',
        'email_already_registered' => 'An account with this email already exists.',
        'oauth_error' => 'Unable to authenticate with the provider.',
        'validation_error' => 'The provided data is invalid.',
    ],
    
    'payment' => [
        'payment_successful' => 'Payment completed successfully.',
        'payment_pending' => 'Payment is being processed.',
        'payment_declined' => 'Payment was declined.',
        'gateway_error' => 'Payment gateway error occurred.',
        'gateway_not_found' => 'Payment gateway not available.',
        'order_not_found' => 'Order not found.',
        'status_retrieved' => 'Payment status retrieved successfully.',
        'not_enough_credit' => 'Insufficient account balance.',
        'disabled_gateway' => 'This payment method is currently unavailable.',
        'paid' => 'Payment completed successfully.',
        'empty_cart' => 'Your cart is empty.',
    ],
    
    'support' => [
        'closed' => 'Support ticket closed successfully.',
        'updated' => 'Support ticket updated successfully.',
    ],

    'not_access_to_this_item' => 'You don\'t have access to this item.',
];
