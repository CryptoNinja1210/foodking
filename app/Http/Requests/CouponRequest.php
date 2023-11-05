<?php

namespace App\Http\Requests;

use App\Enums\DiscountType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'        => [
                'required',
                'string',
                'max:190',
                Rule::unique("coupons", "name")->ignore($this->route('coupon.id'))
            ],
            'description'      => ['nullable', 'string', 'max:900'],
            'code'             => ['required', 'string', 'max:24', Rule::unique("coupons", "code")->ignore($this->route('coupon.id'))],
            'discount'         => ['required', 'numeric'],
            'discount_type'    => ['required', 'numeric', 'max:24'],
            'start_date'       => ['required', 'string',],
            'end_date'         => ['required', 'string',],
            'minimum_order'    => ['required', 'numeric'],
            'maximum_discount' => ['required', 'numeric'],
            'limit_per_user'   => ['nullable', 'numeric'],
            'image'            => $this->route('coupon.id') ? ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'] : ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!$this->isNotNull(request('start_date'))) {
                $validator->errors()->add('start_date', 'The start date field is required');
            }

            if (!$this->isNotNull(request('end_date'))) {
                $validator->errors()->add('end_date', 'The end date field is required');
            }

            if ($this->isPercentage() && request('discount') > 100) {
                $validator->errors()->add('discount', 'Percentage amount can\'t be greater than 100.');
            }

            if ($this->isNotNull(request('start_date')) && strtotime(request('end_date')) < strtotime(request('start_date'))) {
                $validator->errors()->add('end_date', 'To date can\'t be older than Start date.');
            }

            if ($this->isNotNull(request('start_date')) && $this->checkToDate()) {
                $validator->errors()->add('end_date', 'To date can\'t be older than now.');
            }
        });
    }

    private function isPercentage()
    {
        return request('discount_type') == DiscountType::PERCENTAGE ? true : false;
    }

    public function checkToDate()
    {
        $today = strtotime(date('Y-m-d H:i:s'));
        if (strtotime(request('end_date')) < $today) {
            return true;
        }
    }

    private function isNotNull($value)
    {
        if ($value === 'null') {
            return false;
        }
        return true;
    }
}
