<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class createCodeLicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => [
                'required',
                'min:5',
                function ($attribute, $value, $fail) {
                    $license = DB::table('licenses')
                        ->whereRaw('BINARY `code` = ?', $value)->first();
                    if($license) {
                        $fail('A location named '. $value .' already exists');
                    };
                }]
        ];
    }
}
