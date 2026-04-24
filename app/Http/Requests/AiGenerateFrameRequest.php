<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiGenerateFrameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $canvasMin     = (int) config('ai.canvas_min', 200);
        $canvasMax     = (int) config('ai.canvas_max', 2000);
        $promptMax     = (int) config('ai.prompt_max_length', 2000);
        $allowedProviders = implode(',', config('ai.allowed_providers', ['openai', 'anthropic']));

        return [
            'prompt'         => ['required', 'string', 'min:3', "max:{$promptMax}"],
            'width'          => ['required', 'integer', "min:{$canvasMin}", "max:{$canvasMax}"],
            'height'         => ['required', 'integer', "min:{$canvasMin}", "max:{$canvasMax}"],
            'provider'       => ['required', 'string', "in:{$allowedProviders}"],
            'generate_image' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'prompt.required'   => 'Please describe the frame style.',
            'prompt.min'        => 'The description must be at least 3 characters.',
            'width.required'    => 'Canvas width is required.',
            'height.required'   => 'Canvas height is required.',
            'provider.in'       => 'Please select a valid AI provider.',
        ];
    }
}
