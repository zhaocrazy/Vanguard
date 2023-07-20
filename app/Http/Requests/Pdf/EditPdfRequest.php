<?php

namespace Vanguard\Http\Requests\Pdf;

use Vanguard\Http\Requests\Request;

class EditPdfRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'product_id' => 'required|numeric|min:10',
            'quantity' => 'required|numeric',
            'stock_location' => 'required|string|min:8|max:8',
            'ean_number' => 'required|string|max:14',
        ];

        return $rules;
    }

}