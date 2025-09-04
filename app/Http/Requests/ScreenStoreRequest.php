<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScreenStoreRequest extends FormRequest
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
            'seat' => ['required', 'integer'],
            'api_namespace' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'seat' => ['required', 'integer'],
            'api_namespace' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'masking_movement' => ['required', 'string', 'max:255'],
            'screen_h' => ['required', 'numeric', 'gt:0', 'between:0,999999.99'],
            'screen_w' => ['required', 'numeric', 'gt:0', 'between:0,999999.99'],
            'screen_d' => ['required', 'numeric', 'gt:0', 'between:0,999999.99'],
            'projector_brand' => ['required', 'string', 'max:255'],
            'projector_model' => ['required', 'string', 'max:255'],
            'projector_ip_lan' => ['required', 'string', 'max:255'],
            'lens_model' => ['required', 'string', 'max:255'],
            'installed' => ['required'],
            'server_brand' => ['required', 'string', 'max:255'],
            'server_model' => ['required', 'string', 'max:255'],
            'server_ip_lan' => ['required', 'string', 'max:255'],
            'ingest_capabilities' => ['required', 'string', 'max:255'],
            '3d_brand' => ['nullable', 'string', 'max:255'],
            '3d_model' => ['nullable', 'string', 'max:255'],
            'automation_brand' => ['required', 'string', 'max:255'],
            'automation_model' => ['required', 'string', 'max:255'],
            'automation_ip_lan' => ['required', 'string', 'max:255'],
            'satelite_or_live' => ['required', 'string', 'max:255'],
            'transmission_brand' => ['required', 'string', 'max:255'],
            'transmission_model' => ['required', 'string', 'max:255'],
            'transmission_ip_lan' => ['required', 'string', 'max:255'],
            'processor_brand' => ['required', 'string', 'max:255'],
            'processor_model' => ['required', 'string', 'max:255'],
            'processor_ip_lan' => ['required', 'string', 'max:255'],
            'audio_type' => ['required', 'string', 'max:255'],
            'audio_brand' => ['required', 'string', 'max:255'],
            'audio_model' => ['required', 'string', 'max:255'],
            'audio_channel' => ['required', 'string', 'max:255'],
            'audio_frequency' => ['required', 'string', 'max:255'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'masking_movement' => ['required', 'string', 'max:255'],
            'screen_h' => ['required', 'numeric', 'gt:0', 'between:0,999999.99'],
            'screen_w' => ['required', 'numeric', 'gt:0', 'between:0,999999.99'],
            'screen_d' => ['required', 'numeric', 'gt:0', 'between:0,999999.99'],
            'projector_brand' => ['required', 'string', 'max:255'],
            'projector_model' => ['required', 'string', 'max:255'],
            'projector_ip_lan' => ['required', 'string', 'max:255'],
            'lens_model' => ['required', 'string', 'max:255'],
            'installed' => ['required'],
            'server_brand' => ['required', 'string', 'max:255'],
            'server_model' => ['required', 'string', 'max:255'],
            'server_ip_lan' => ['required', 'string', 'max:255'],
            'ingest_capabilities' => ['required', 'string', 'max:255'],
            'automation_brand' => ['required', 'string', 'max:255'],
            'automation_model' => ['required', 'string', 'max:255'],
            'automation_ip_lan' => ['required', 'string', 'max:255'],
            'satelite_or_live' => ['required', 'string', 'max:255'],
            'transmission_brand' => ['required', 'string', 'max:255'],
            'transmission_model' => ['required', 'string', 'max:255'],
            'transmission_ip_lan' => ['required', 'string', 'max:255'],
            'processor_brand' => ['required', 'string', 'max:255'],
            'processor_model' => ['required', 'string', 'max:255'],
            'processor_ip_lan' => ['required', 'string', 'max:255'],
            'audio_type' => ['required', 'string', 'max:255'],
            'audio_brand' => ['required', 'string', 'max:255'],
            'audio_model' => ['required', 'string', 'max:255'],
            'audio_channel' => ['required', 'string', 'max:255'],
            'audio_frequency' => ['required', 'string', 'max:255'],
        ];
    }
}
