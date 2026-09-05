<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\Cursor;

class ListSantriForSheetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>> */
    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'between:1,100'],
            'cursor' => ['sometimes', 'string', 'max:512', function (string $attribute, mixed $value, \Closure $fail): void {
                try {
                    $cursor = Cursor::fromEncoded($value);
                    if ($cursor === null || ! ctype_digit((string) $cursor->parameter('id')) || ! $cursor->pointsToNextItems()) {
                        $fail('Cursor tidak valid.');
                    }
                } catch (\Throwable) {
                    $fail('Cursor tidak valid.');
                }
            }],
        ];
    }
}
