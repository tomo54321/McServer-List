<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ServerIP implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $invalid_ip = ["localhost", "127.0.0.1", "0.0.0.0", "::1"];

        return !in_array($value, $invalid_ip) 
        && substr( $value, 0, 8 ) !== "192.168."
        && substr( $value, 0, 3 ) !== "10."
        && substr( $value, 0, 7 ) !== "172.16.";
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The IP address is a local IP and cannot be used.';
    }
}
