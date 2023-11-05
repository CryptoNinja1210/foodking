<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidJsonOrder implements Rule
{
    public $message = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {

        $requestItems = json_decode($value);
        if (count($requestItems) == 0) {
            $this->message = 'This :attribute must be json.';
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}