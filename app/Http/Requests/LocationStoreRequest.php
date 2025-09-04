<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'folder_title' => [ 'string', 'max:255'],
            'connection_ip' => ['required', 'string', 'max:255'],
            'tms_system' => ['required', 'string', 'max:255'],
            'rentrak_id' => ['string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'type'=>[],
            'hostname'=>[],
            'email'=>[],
            'password'=>[],
            'port'=>[],
            'location_email'=>[],
            'phone'=>[],
            'support_email'=>[],
            'company'=>[],
            'language'=>[],
            'latitude'=>[],
            'longitude'=>[],
            'ip'=>[],

        ];
    }
}
