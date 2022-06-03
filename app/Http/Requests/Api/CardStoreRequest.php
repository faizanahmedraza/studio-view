<?php
namespace App\Http\Requests\Api;


use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;
use Illuminate\Foundation\Http\FormRequest;

class CardStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card_number' => ['required', new CardNumber],
            'exp_year' => ['required', new CardExpirationYear($this->get('exp_month'))],
            'exp_month' => ['required', new CardExpirationMonth($this->get('exp_year'))],
            'cvc' => ['required', new CardCvc($this->get('card_number'))],
            'name' => "required|string"
        ];
    }
}
