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
                'url' => ['required', 'url', 'max:2048', 'regex:/^https:\/\//'],
            ],
            'email' => [
                'email' => 'required|email|max:255',
                'subject' => 'nullable|string|max:255',
                'message' => 'required|string|max:1000',
            ],
            'text' => [
                'text' => 'required|string|max:500',
            ],
            'pdf' => [
                'pdf_file' => 'required|file|mimes:pdf|max:10240', // 10MB
            ],
            'menu' => [
                'menu_file' => 'nullable|file|mimes:pdf|max:10240',
                'menu_url' => ['nullable', 'url', 'max:2048', 'regex:/^https:\/\//'],
            ],
            'coupon' => [
                'coupon_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120', // 5MB presentation image
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048', // 2MB
                'coupon_primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'coupon_secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'coupon_button_text' => 'nullable|string|max:255',
                'coupon_button_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'coupon_button_text_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'coupon_font_family' => 'nullable|string|max:100',
                'coupon_company' => 'required|string|max:255',
                'coupon_title' => 'required|string|max:255',
                'coupon_description' => 'nullable|string|max:1000',
                'coupon_sales_badge' => 'required|string|max:100',
                'coupon_sales_badge_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'coupon_sales_badge_text_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'coupon_code_button_text' => 'nullable|string|max:255',
                'coupon_use_barcode' => 'nullable',
                'coupon_barcode_image' => 'required_if:coupon_use_barcode,1|nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048', // 2MB; required when use barcode
                'coupon_valid_until' => 'required|date',
                'coupon_view_more_text' => 'nullable|string|max:255',
                'coupon_view_more_website' => ['required_without:coupon_barcode_image', 'nullable', 'url', 'max:2048', 'regex:/^https:\/\//'], // required if no barcode
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
                'app_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
                'app_name' => 'nullable|string|max:255',
                'app_description' => 'nullable|string|max:1000',
                'app_primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'app_secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'app_button_text' => 'nullable|string|max:255',
                'app_button_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'app_font_family' => 'nullable|string|max:100',
                'app_text_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'app_text_font_size' => 'nullable|integer|min:12|max:24',
                'app_icon_size' => 'nullable|integer|min:64|max:128',
                'website_url' => ['nullable', 'url', 'max:2048', 'regex:/^https:\/\//'],
                'app_store_link' => ['nullable', 'url', 'max:2048', 'regex:/^https:\/\/apps\.apple\.com\//'],
                'play_store_link' => ['nullable', 'url', 'max:2048', 'regex:/^https:\/\/play\.google\.com\/store\/apps\//'],
                'app_store_button_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'app_store_button_text_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
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
                'full_name' => 'nullable|string|max:255',
                'phone_number' => 'required|string|max:50',
                'phone_background_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'phone_font_family' => 'nullable|string|max:100',
            ],
            'mp3' => [
                'mp3_file' => 'required|file|mimes:mp3,m4a,audio/mpeg,audio/mp3,audio/m4a,audio/x-m4a|max:20480', // 20MB
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
            'url.regex' => 'Website URL must start with https://',
            'email.required' => 'Please enter an email address.',
            'email.email' => 'Please enter a valid email address.',
            'message.required' => 'Please enter a message.',
            'text.required' => 'Please enter some text.',
            'text.max' => 'Text cannot exceed 500 characters.',
            'pdf_file.required' => 'Please upload a PDF file.',
            'pdf_file.mimes' => 'Only PDF files are allowed.',
            'pdf_file.max' => 'PDF file cannot exceed 10MB.',
            'coupon_image.required' => 'Please upload a presentation image for your coupon.',
            'coupon_image.image' => 'Coupon presentation file must be an image.',
            'coupon_image.max' => 'Coupon image cannot exceed 5MB.',
            'coupon_company.required' => 'Company name is required.',
            'coupon_title.required' => 'Coupon title is required.',
            'coupon_sales_badge.required' => 'Sales badge is required.',
            'coupon_valid_until.required' => 'Valid until date is required.',
            'coupon_valid_until.date' => 'Please enter a valid date.',
            'coupon_view_more_website.required_without' => 'Please enter a website URL or upload a barcode (at least one is required).',
            'coupon_view_more_website.regex' => 'Website URL must start with https://',
            'coupon_barcode_image.required_if' => 'Please upload a barcode image when "Use barcode" is enabled.',
            'event_name.required' => 'Please enter an event name.',
            'ssid.required' => 'Please enter the WiFi network name.',
            'encryption.required' => 'Please select an encryption type.',
            'password.required_unless' => 'Password is required for encrypted networks.',
            'address.required' => 'Please enter an address.',
            'phone_number.required' => 'Please enter a phone number.',
            'mp3_file.required' => 'Please upload an audio file.',
            'mp3_file.mimes' => 'Only audio files are allowed (MP3, M4A formats).',
            'mp3_file.max' => 'Audio file cannot exceed 20MB.',
            'song_name.required' => 'Please enter a song name.',
            'artist_name.required' => 'Please enter an artist name.',
            'menu_url.regex' => 'Menu URL must start with https://',
            'website_url.regex' => 'Website URL must start with https://',
            'app_store_link.regex' => 'App Store Link must start with https://apps.apple.com/',
            'play_store_link.regex' => 'Google Play Store Link must start with https://play.google.com/store/apps/',
        ];
    }
}
