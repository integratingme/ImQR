<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQrCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users for now
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $type = $this->input('type');

        $baseRules = [
            'type' => 'required|in:url,email,text,pdf,menu,coupon,event,app,location,wifi,phone,mp3',
            'name' => 'required|string|max:255',
            'primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ];

        $typeSpecificRules = match($type) {
            'url' => [
                'url' => 'required|url|max:2048',
            ],
            'email' => [
                'email' => 'required|email|max:255',
                'subject' => 'nullable|string|max:255',
                'message' => 'nullable|string|max:1000',
            ],
            'text' => [
                'text' => 'required|string|max:500',
            ],
            'pdf' => [
                'pdf_file' => 'required|file|mimes:pdf|max:10240', // 10MB
            ],
            'menu' => [
                'menu_file' => 'nullable|file|mimes:pdf|max:10240',
                'menu_url' => 'nullable|url|max:2048',
            ],
            'coupon' => [
                'coupon_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120', // 5MB
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048', // 2MB
            ],
            'event' => [
                'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
                'company_name' => 'nullable|string|max:255',
                'event_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'date' => 'nullable|date',
                'time' => 'nullable|string|max:50',
                'location' => 'nullable|string|max:500',
                'amenities' => 'nullable|array',
                'amenities.*' => 'string|max:100',
                'dress_code_color' => 'nullable|string|max:50',
                'contact' => 'nullable|string|max:255',
            ],
            'app' => [
                'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'app_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
                'app_name' => 'nullable|string|max:255',
                'website_url' => 'nullable|url|max:2048',
                'app_store_link' => 'nullable|url|max:2048',
                'play_store_link' => 'nullable|url|max:2048',
            ],
            'location' => [
                'address' => 'required|string|max:500',
            ],
            'wifi' => [
                'ssid' => 'required|string|max:255',
                'encryption' => 'required|in:WPA,WPA2,WEP,nopass',
                'password' => 'required_unless:encryption,nopass|string|max:255',
            ],
            'phone' => [
                'phone_number' => 'required|string|max:50',
            ],
            'mp3' => [
                'mp3_file' => 'required|file|mimes:mp3,audio/mpeg,audio/mp3|max:20480', // 20MB
                'song_name' => 'required|string|max:255',
                'artist_name' => 'required|string|max:255',
            ],
            default => [],
        };

        return array_merge($baseRules, $typeSpecificRules);
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'url.required' => 'Please enter a website URL.',
            'url.url' => 'Please enter a valid URL.',
            'email.required' => 'Please enter an email address.',
            'email.email' => 'Please enter a valid email address.',
            'text.required' => 'Please enter some text.',
            'text.max' => 'Text cannot exceed 500 characters.',
            'pdf_file.required' => 'Please upload a PDF file.',
            'pdf_file.mimes' => 'Only PDF files are allowed.',
            'pdf_file.max' => 'PDF file cannot exceed 10MB.',
            'coupon_image.required' => 'Please upload a coupon image.',
            'coupon_image.image' => 'Coupon file must be an image.',
            'coupon_image.max' => 'Coupon image cannot exceed 5MB.',
            'event_name.required' => 'Please enter an event name.',
            'ssid.required' => 'Please enter the WiFi network name.',
            'encryption.required' => 'Please select an encryption type.',
            'password.required_unless' => 'Password is required for encrypted networks.',
            'address.required' => 'Please enter an address.',
            'phone_number.required' => 'Please enter a phone number.',
            'mp3_file.required' => 'Please upload an MP3 file.',
            'mp3_file.mimes' => 'Only MP3 audio files are allowed.',
            'mp3_file.max' => 'MP3 file cannot exceed 20MB.',
            'song_name.required' => 'Please enter a song name.',
            'artist_name.required' => 'Please enter an artist name.',
        ];
    }
}
