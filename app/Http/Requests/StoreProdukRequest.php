<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreProdukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isOwner();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_produk' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'deskripsi' => ['nullable', 'string'],
            'harga_jual' => ['required', 'integer', 'min:1'],
            'harga_beli' => ['nullable', 'integer', 'min:1'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimal' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages()
    {
        return [
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.min' => 'Harga jual tidak boleh minus.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
