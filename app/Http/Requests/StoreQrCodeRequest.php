<?php

namespace App\Http\Requests;

use App\Rules\RecaptchaRule;
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
            'type' => 'required|in:url,email,text,pdf,menu,coupon,event,app,location,wifi,phone,business_card,personal_vcard',
            'name' => 'required|string|max:255',
            'primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'qr_logo' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:2048', // Step 2 logo, JPG/PNG only
            'frame' => 'nullable|string|max:50',
            'review_frame_line1' => 'nullable|string|max:100',
            'review_frame_line2' => 'nullable|string|max:100',
            'review_frame_line3' => 'nullable|string|max:100',
            'review_frame_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'review_frame_text_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'review_frame_logo' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:2048', // Review-us frame custom icon, JPG/PNG only
            // Honeypot – must be empty (bots often fill these)
            'hp_url' => 'nullable|string|max:0',
            'hp_comment' => 'nullable|string|max:0',
            // reCAPTCHA v3 token (validated when recaptcha is enabled)
            'recaptcha_token' => config('services.recaptcha.enabled')
                ? ['required', 'string', new RecaptchaRule()]
                : ['nullable', 'string'],
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
                'pdf_file' => 'required|file|mimes:pdf|mimetypes:application/pdf|pdf_signature|max:5120', // 5MB, PDF only
            ],
            'menu' => [
                'menu_file' => 'nullable|file|mimes:pdf|mimetypes:application/pdf|pdf_signature|max:5120', // 5MB, PDF only
                'menu_url' => ['nullable', 'url', 'max:2048', 'regex:/^https:\/\//'],
                'menu_primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'menu_secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'menu_font_family' => 'nullable|string|max:100',
                'menu_restaurant_image' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:5120',
                'restaurant_name' => 'nullable|string|max:255',
                'restaurant_description' => 'nullable|string|max:1000',
                'menu_restaurant_name_font_size' => 'nullable|integer|min:12|max:28',
                'menu_restaurant_description_font_size' => 'nullable|integer|min:10|max:20',
                'menu_restaurant_name_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'menu_restaurant_description_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'menu_sections' => 'nullable|array',
                'menu_sections.*.section_name' => 'nullable|string|max:255',
                'menu_sections.*.section_description' => 'nullable|string|max:1000',
                'menu_sections.*.products' => 'nullable|array',
                'menu_sections.*.products.*.product_name' => 'nullable|string|max:255',
                'menu_sections.*.products.*.product_description' => 'nullable|string|max:500',
                'menu_sections.*.products.*.price' => 'nullable|string|max:50',
                'menu_sections.*.products.*.allergens' => 'nullable|string|max:255',
                'menu_sections.*.products.*.product_image' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:3072', // 3MB, JPG/PNG only
            ],
            'coupon' => [
                'coupon_image' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:5120', // 5MB, JPG/PNG only
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:2048', // 2MB, JPG/PNG only
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
                'coupon_barcode_image' => 'required_if:coupon_use_barcode,1|nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:2048', // 2MB, JPG/PNG only
                'coupon_valid_until' => 'required|date',
                'coupon_view_more_text' => 'nullable|string|max:255',
                'coupon_view_more_website' => ['required_without:coupon_barcode_image', 'nullable', 'url', 'max:2048', 'regex:/^https:\/\//'], // required if no barcode
            ],
            'event' => [
                'event_image' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:5120',
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
                'event_primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'event_secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'event_font_family' => 'nullable|string|max:100',
            ],
            'app' => [
                'app_image' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:5120',
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
                'address' => 'nullable|string|max:500',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'location_url' => 'nullable|string|max:2048',
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
            'business_card' => [
                'business_card_company_name' => 'required|string|max:255',
                'business_card_subtitle' => 'nullable|string|max:255',
                'business_card_primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'business_card_secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'business_card_font_family' => 'nullable|string|max:100',
                'business_card_buttons' => 'nullable|array',
                'business_card_buttons.*.label' => 'nullable|string|max:255',
                'business_card_buttons.*.url' => 'nullable|url|max:2048',
                'business_card_about' => 'required|string|max:5000',
                'business_card_contact_name' => 'nullable|string|max:255',
                'business_card_phone' => 'required|string|max:50',
                'business_card_email' => 'required|email|max:255',
                'business_card_address' => 'nullable|string|max:500',
                'business_card_maps_link' => 'nullable|url|max:2048',
                'business_card_working_hours' => 'nullable|string|max:2000',
                'business_card_socials' => 'nullable|array',
                'business_card_socials.*.platform' => 'nullable|string|max:50',
                'business_card_socials.*.url' => 'nullable|url|max:2048',
                'business_card_logo' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:2048',
            ],
            'personal_vcard' => [
                'personal_vcard_name' => 'required|string|max:255',
                'personal_vcard_title' => 'nullable|string|max:255',
                'personal_vcard_primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'personal_vcard_secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'personal_vcard_font_family' => 'nullable|string|max:100',
                'personal_vcard_about' => 'nullable|string|max:2000',
                'personal_vcard_phone' => 'required|string|max:50',
                'personal_vcard_email' => 'required|email|max:255',
                'personal_vcard_address' => 'nullable|string|max:500',
                'personal_vcard_maps_link' => 'nullable|url|max:2048',
                'personal_vcard_socials' => 'nullable|array',
                'personal_vcard_socials.*.platform' => 'nullable|string|max:50',
                'personal_vcard_socials.*.url' => 'nullable|url|max:2048',
                'personal_vcard_profile_image' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|image_signature|max:2048',
            ],
            default => [],
        };

        return array_merge($baseRules, $typeSpecificRules);
    }

    /**
     * Configure the validator for menu: at least one of (sections, PDF, URL) is required.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->input('type') === 'menu') {
                $sections = $this->input('menu_sections');
                $hasSections = !empty($sections) && is_array($sections) && count($sections) > 0;
                $hasFile = $this->hasFile('menu_file');
                $hasUrl = !empty(trim((string) $this->input('menu_url', '')));
                if (!$hasSections && !$hasFile && !$hasUrl) {
                    $validator->errors()->add(
                        'menu_content',
                        'Please add at least one menu section, or upload a PDF, or enter a menu URL.'
                    );
                }
                return;
            }
            if ($this->input('type') === 'location') {
                $address = trim((string) $this->input('address', ''));
                $lat = $this->input('latitude');
                $lng = $this->input('longitude');
                $locationUrl = trim((string) $this->input('location_url', ''));
                $hasAddress = $address !== '';
                $hasCoords = is_numeric($lat) && is_numeric($lng);
                $hasLocationUrl = $locationUrl !== '' && preg_match('/^https:\/\//i', $locationUrl);
                if (!$hasAddress && !$hasCoords && !$hasLocationUrl) {
                    $validator->errors()->add(
                        'location_content',
                        'Please enter an address or search for a location, paste a Google Maps link, or use your current location.'
                    );
                }
            }
        });
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
            'pdf_file.mimetypes' => 'Only PDF files are allowed.',
            'pdf_file.pdf_signature' => 'The file is not a valid PDF (invalid file signature).',
            'pdf_file.max' => 'PDF file cannot exceed 5MB.',
            'menu_file.mimes' => 'Only PDF files are allowed.',
            'menu_file.mimetypes' => 'Only PDF files are allowed.',
            'menu_file.pdf_signature' => 'The file is not a valid PDF (invalid file signature).',
            'coupon_image.required' => 'Please upload a presentation image for your coupon.',
            'coupon_image.image' => 'Coupon presentation file must be an image.',
            'coupon_image.mimes' => 'Only JPG and PNG images are allowed.',
            'coupon_image.mimetypes' => 'Only JPG and PNG images are allowed.',
            'coupon_image.image_signature' => 'The file is not a valid image (invalid file signature).',
            'coupon_image.max' => 'Coupon image cannot exceed 5MB.',
            'coupon_company.required' => 'Company name is required.',
            'coupon_title.required' => 'Coupon title is required.',
            'coupon_sales_badge.required' => 'Sales badge is required.',
            'coupon_valid_until.required' => 'Valid until date is required.',
            'coupon_valid_until.date' => 'Please enter a valid date.',
            'coupon_view_more_website.required_without' => 'Please enter a website URL or upload a barcode (at least one is required).',
            'coupon_view_more_website.regex' => 'Website URL must start with https://',
            'coupon_barcode_image.required_if' => 'Please upload a barcode image when "Use barcode" is enabled.',
            'coupon_barcode_image.mimes' => 'Only JPG and PNG images are allowed.',
            'coupon_barcode_image.mimetypes' => 'Only JPG and PNG images are allowed.',
            'coupon_barcode_image.image_signature' => 'The file is not a valid image (invalid file signature).',
            'logo.mimes' => 'Only JPG and PNG images are allowed.',
            'logo.mimetypes' => 'Only JPG and PNG images are allowed.',
            'logo.image_signature' => 'The file is not a valid image (invalid file signature).',
            'qr_logo.mimes' => 'Only JPG and PNG images are allowed.',
            'qr_logo.mimetypes' => 'Only JPG and PNG images are allowed.',
            'qr_logo.image_signature' => 'The file is not a valid image (invalid file signature). Only JPG and PNG are allowed.',
            'review_frame_logo.image_signature' => 'The file is not a valid image (invalid file signature). Only JPG and PNG are allowed.',
            'menu_restaurant_image.mimes' => 'Only JPG and PNG images are allowed.',
            'menu_restaurant_image.mimetypes' => 'Only JPG and PNG images are allowed.',
            'menu_restaurant_image.image_signature' => 'The file is not a valid image (invalid file signature).',
            'event_image.mimes' => 'Only JPG and PNG images are allowed.',
            'event_image.mimetypes' => 'Only JPG and PNG images are allowed.',
            'event_image.image_signature' => 'The file is not a valid image (invalid file signature).',
            'app_image.mimes' => 'Only JPG and PNG images are allowed.',
            'app_image.mimetypes' => 'Only JPG and PNG images are allowed.',
            'app_image.image_signature' => 'The file is not a valid image (invalid file signature).',
            'menu_sections.*.products.*.product_image.mimes' => 'Only JPG and PNG images are allowed.',
            'menu_sections.*.products.*.product_image.mimetypes' => 'Only JPG and PNG images are allowed.',
            'menu_sections.*.products.*.product_image.image_signature' => 'The file is not a valid image (invalid file signature).',
            'business_card_logo.image_signature' => 'The file is not a valid image (invalid file signature). Only JPG and PNG are allowed.',
            'personal_vcard_profile_image.image_signature' => 'The file is not a valid image (invalid file signature). Only JPG and PNG are allowed.',
            'event_name.required' => 'Please enter an event name.',
            'ssid.required' => 'Please enter the WiFi network name.',
            'encryption.required' => 'Please select an encryption type.',
            'password.required_unless' => 'Password is required for encrypted networks.',
            'address.required' => 'Please enter an address.',
            'phone_number.required' => 'Please enter a phone number.',
            'menu_content.required' => 'Please add at least one menu section, or upload a PDF, or enter a menu URL.',
            'menu_url.regex' => 'Menu URL must start with https://',
            'website_url.regex' => 'Website URL must start with https://',
            'app_store_link.regex' => 'App Store Link must start with https://apps.apple.com/',
            'play_store_link.regex' => 'Google Play Store Link must start with https://play.google.com/store/apps/',
            'hp_url.max' => 'An error occurred while submitting the form. Please try again later.',
            'hp_comment.max' => 'An error occurred while submitting the form. Please try again later.',
            'recaptcha_token.required' => 'Please complete the security check and try again.',
        ];
    }
}
