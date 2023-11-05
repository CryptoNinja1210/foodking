<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IniAmount implements Rule
{
    public $message = '';
    public $zero;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($zero = false)
    {
        $this->zero = $zero;
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
        if (!is_numeric($value)) {
            $this->message = 'This :attribute must be integer.';
            return false;
        }

        if ($this->zero) {
            if ($value < 0) {
                $this->message = 'This :attribute negative amount not allow.';
                return false;
            }
        } else {
            if ($value <= 0) {
                $this->message = 'This :attribute negative amount not allow.';
                return false;
            }
        }



        $replaceValue = str_replace('.', '', $value);
        if (strlen($replaceValue) > 12) {
            $this->message = 'This :attribute length can\'t be greater than 12 digit.';
            return false;
        }

        if (!preg_match("/^\d{1,10}(\.\d{1,6})?$/", $value)) {
            $this->message = 'This :attribute amount provide invalid.';
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
