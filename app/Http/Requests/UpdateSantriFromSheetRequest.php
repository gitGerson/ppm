<?php

namespace App\Http\Requests;

use App\Models\DetailSantri;
use App\Support\SantriSheetSchema;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSantriFromSheetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>> */
    public function rules(): array
    {
        $rules = [
            'base_revision' => ['required', 'string', 'regex:/^[a-f0-9]{64}$/'],
            'changes' => ['required', Rule::array(SantriSheetSchema::editableDbColumns()), 'min:1'],
        ];

        foreach (DetailSantri::sheetUpdateRules() as $field => $fieldRules) {
            $rules['changes.'.$field] = $fieldRules;
        }

        return $rules;
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return DetailSantri::sheetValidationMessages();
    }
}
