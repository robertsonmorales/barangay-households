<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use Crypt;
use Auth;

class CheckCurrentEmailAddress implements Rule
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
        return $value != Crypt::decryptString(Auth::user()->email);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The new email address has the same value of your current email address, please try something else.';
    }
}
