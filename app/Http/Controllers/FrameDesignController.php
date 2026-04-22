<?php

namespace App\Http\Controllers;

use App\Models\FrameDesign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FrameDesignController extends Controller
{
    public function editor()
    {
        return view('frames.editor');
    }

    public function edit(FrameDesign $frame)
    {
        $user = request()->user();
        if (!$user || !$frame->isOwnedByOrTemplate($user)) {
            abort(403);
        }

        return view('frames.editor', ['frame' => $frame]);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $frames = FrameDesign::query()
            ->where('is_template', true)
            ->orWhere('user_id', $user->id)
            ->orderByDesc('is_template')
            ->orderByDesc('updated_at')
            ->get(['id', 'name', 'thumbnail_url', 'is_template', 'design_json', 'svg_content', 'background_image', 'background_image_fit']);

        return response()->json(['frames' => $frames]);
    }

    public function show(FrameDesign $frame): JsonResponse
    {
        $user = request()->user();
        if (!$user || !$frame->isOwnedByOrTemplate($user)) {
            abort(403);
        }

        return response()->json([
            'id' => $frame->id,
            'name' => $frame->name,
            'design_json' => $frame->design_json,
            'svg_content' => $frame->svg_content,
            'background_image' => $frame->background_image,
            'background_image_fit' => $frame->background_image_fit,
            'thumbnail_url' => $frame->thumbnail_url,
            'is_template' => $frame->is_template,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->validationRules());

        $thumbnailUrl = null;
        if (!empty($validated['thumbnail_data_url'])) {
            $thumbnailUrl = $this->storeThumbnailFromDataUrl($validated['thumbnail_data_url']);
        }

        $frame = FrameDesign::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'design_json' => $validated['design_json'],
            'svg_content' => $validated['svg_content'] ?? null,
            'background_image' => $validated['background_image'] ?? ($validated['design_json']['background_image'] ?? null),
            'background_image_fit' => $validated['background_image_fit'] ?? ($validated['design_json']['background_image_fit'] ?? 'cover'),
            'thumbnail_url' => $thumbnailUrl,
            'is_template' => false,
        ]);

        return response()->json([
            'success' => true,
            'frame_id' => $frame->id,
            'message' => 'Frame saved.',
        ]);
    }

    public function update(Request $request, FrameDesign $frame): JsonResponse
    {
        $user = $request->user();
        if (!$user || $frame->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate($this->validationRules());

        $thumbnailUrl = $frame->thumbnail_url;
        if (!empty($validated['thumbnail_data_url'])) {
            $this->deleteThumbnailFromUrl($thumbnailUrl);
            $thumbnailUrl = $this->storeThumbnailFromDataUrl($validated['thumbnail_data_url']);
        }

        $frame->update([
            'name' => $validated['name'],
            'design_json' => $validated['design_json'],
            'svg_content' => array_key_exists('svg_content', $validated)
                ? $validated['svg_content']
                : $frame->svg_content,
            'background_image' => $validated['background_image'] ?? ($validated['design_json']['background_image'] ?? null),
            'background_image_fit' => $validated['background_image_fit'] ?? ($validated['design_json']['background_image_fit'] ?? 'cover'),
            'thumbnail_url' => $thumbnailUrl,
        ]);

        return response()->json([
            'success' => true,
            'frame_id' => $frame->id,
            'message' => 'Frame updated.',
        ]);
    }

    public function destroy(FrameDesign $frame): JsonResponse
    {
        $user = request()->user();
        if (!$user || $frame->user_id !== $user->id) {
            abort(403);
        }

        if ($frame->thumbnail_url) {
            $this->deleteThumbnailFromUrl($frame->thumbnail_url);
        }

        $frame->delete();

        return response()->json([
            'success' => true,
            'message' => 'Frame deleted.',
        ]);
    }

    public function destroyAll(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            abort(401);
        }

        $frames = FrameDesign::query()
            ->where('user_id', $user->id)
            ->where('is_template', false)
            ->get(['id', 'thumbnail_url']);

        foreach ($frames as $frame) {
            if ($frame->thumbnail_url) {
                $this->deleteThumbnailFromUrl($frame->thumbnail_url);
            }
        }

        FrameDesign::query()
            ->where('user_id', $user->id)
            ->where('is_template', false)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'All custom frames deleted.',
            'deleted_count' => $frames->count(),
        ]);
    }

    private function storeThumbnailFromDataUrl(string $dataUrl): ?string
    {
        if (!preg_match('/^data:image\/png;base64,/', $dataUrl)) {
            return null;
        }

        $raw = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1), true);
        if ($raw === false) {
            return null;
        }

        $path = 'frame-thumbnails/' . Str::uuid() . '.png';
        Storage::disk('public')->put($path, $raw);

        return asset('storage/' . $path);
    }

    private function deleteThumbnailFromUrl(?string $url): void
    {
        if (!$url) {
            return;
        }

        $relativePath = str_replace(asset('storage') . '/', '', $url);
        if ($relativePath !== $url && Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    private function validationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'design_json' => 'required|array',
            'design_json.version' => 'required|integer|min:1',
            'design_json.canvas_width' => 'required|integer|min:100|max:1000',
            'design_json.canvas_height' => 'required|integer|min:100|max:1200',
            'design_json.background' => 'nullable|string|max:20',
            'design_json.qr_primary_color' => 'nullable|string|max:20',
            'design_json.qr_secondary_color' => 'nullable|string|max:20',
            'design_json.background_image' => 'nullable|string|max:2000000',
            'design_json.background_image_fit' => 'nullable|string|in:cover,contain',
            'background_image' => 'nullable|string|max:2000000',
            'background_image_fit' => 'nullable|string|in:cover,contain',
            'design_json.qr_zone' => 'required|array',
            'design_json.qr_zone.x_pct' => 'required|numeric|min:0|max:100',
            'design_json.qr_zone.y_pct' => 'required|numeric|min:0|max:100',
            'design_json.qr_zone.w_pct' => 'required|numeric|min:5|max:100',
            'design_json.qr_zone.h_pct' => 'required|numeric|min:5|max:100',
            'design_json.layers' => 'nullable|array|max:40',
            'design_json.layers.*.type' => 'required_with:design_json.layers|string|in:rect,circle,text,image,polygon',
            'design_json.layers.*.opacity' => 'nullable|numeric|min:0|max:1',
            'design_json.layers.*.z_index' => 'nullable|integer|min:0|max:999',
            'design_json.layers.*.x' => 'nullable|numeric|min:-2000|max:2000',
            'design_json.layers.*.y' => 'nullable|numeric|min:-2000|max:2000',
            'design_json.layers.*.width' => 'nullable|numeric|min:0|max:4000',
            'design_json.layers.*.height' => 'nullable|numeric|min:0|max:4000',
            'design_json.layers.*.radius' => 'nullable|numeric|min:0|max:2000',
            'design_json.layers.*.text' => 'nullable|string|max:500',
            'design_json.layers.*.fill' => 'nullable|string|max:80',
            'design_json.layers.*.stroke' => 'nullable|string|max:80',
            'design_json.layers.*.stroke_width' => 'nullable|numeric|min:0|max:200',
            'design_json.layers.*.border_radius' => 'nullable|numeric|min:0|max:2000',
            'design_json.layers.*.color' => 'nullable|string|max:80',
            'design_json.layers.*.font_size' => 'nullable|numeric|min:1|max:400',
            'design_json.layers.*.font_family' => 'nullable|string|max:120',
            'design_json.layers.*.font_weight' => 'nullable|string|max:40',
            'design_json.layers.*.text_align' => 'nullable|string|in:left,center,right,start,end',
            'design_json.layers.*.src' => 'nullable|string|max:2000000',
            'design_json.layers.*.points' => 'nullable|array|max:200',
            'design_json.layers.*.points.*.x' => 'required_with:design_json.layers.*.points|numeric|min:-4000|max:4000',
            'design_json.layers.*.points.*.y' => 'required_with:design_json.layers.*.points|numeric|min:-4000|max:4000',
            'svg_content' => 'nullable|string|max:2000000',
            'thumbnail_data_url' => 'nullable|string|max:250000',
        ];
    }
}
