@extends('layouts.app')

@section('title', 'Frame Editor')

@section('content')
<div class="min-h-[calc(100vh-140px)] bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-8">
    <div class="max-w-[1400px] mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-semibold text-white tracking-tight">Frame Editor</h1>
                <p class="text-sm text-slate-300 mt-1">Design QR frames with drag, drop, and live preview.</p>
            </div>
            <div class="flex items-center gap-2">
                <button
                    id="finish-btn"
                    type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-emerald-500/60 bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-200 hover:bg-emerald-500/20 transition"
                >
                    Finish
                </button>
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-900/70 px-4 py-2 text-sm font-medium text-slate-100 hover:bg-slate-800 transition">
                    <span>←</span>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-4 grid grid-cols-1 lg:grid-cols-12 gap-6">
        <aside class="lg:col-span-3 rounded-2xl border border-slate-700 bg-slate-900/80 p-4 space-y-4 shadow-2xl shadow-black/20">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300 mb-2" for="frame_name">Frame name</label>
                <input id="frame_name" type="text" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500" value="{{ $frame->name ?? 'My Frame' }}">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300 mb-2">Canvas size</label>
                <div class="grid grid-cols-2 gap-2">
                    <input id="canvas_width" type="number" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500" value="{{ $frame->design_json['canvas_width'] ?? 400 }}" min="100" max="1000">
                    <input id="canvas_height" type="number" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500" value="{{ $frame->design_json['canvas_height'] ?? 500 }}" min="100" max="1200">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300 mb-2" for="canvas_bg">Background</label>
                <input id="canvas_bg" type="color" class="h-10 w-full rounded-xl border border-slate-700 bg-slate-950" value="{{ $frame->design_json['background'] ?? '#ffffff' }}">
            </div>

            <div class="rounded-2xl border border-slate-700 bg-slate-950/60 p-3 space-y-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-100">Templates</h3>
                    <p class="text-xs text-slate-400 mt-1">Start from ready frame styles, then customize.</p>
                </div>
                <div id="template_categories" class="grid grid-cols-2 gap-2">
                    <button type="button" class="template-category-btn rounded-xl border border-violet-500 bg-violet-500/10 px-3 py-2 text-xs font-semibold text-violet-300" data-template-category="minimal">Minimal</button>
                    <button type="button" class="template-category-btn rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-800 transition" data-template-category="floral">Floral</button>
                    <button type="button" class="template-category-btn rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-800 transition" data-template-category="luxury">Luxury</button>
                    <button type="button" class="template-category-btn rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-800 transition" data-template-category="wedding">Wedding</button>
                    <button type="button" class="template-category-btn rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-800 transition" data-template-category="birthday">Birthday</button>
                    <button type="button" class="template-category-btn rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-800 transition" data-template-category="tech">Tech</button>
                </div>
                <div id="template_list" class="space-y-2"></div>
            </div>

            <div class="rounded-2xl border border-slate-700 bg-slate-950/60 p-3">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-slate-100">Elements</h3>
                    <button type="button" onclick="document.getElementById('image_layer_input').click()" class="rounded-lg border border-slate-600 px-2.5 py-1 text-xs font-medium text-slate-200 hover:bg-slate-800 transition">Upload</button>
                </div>
                <input id="image_layer_input" type="file" accept=".png,.jpg,.jpeg,image/png,image/jpeg" class="hidden">

                <div class="mb-3">
                    <label for="element_search" class="sr-only">Search elements</label>
                    <input id="element_search" type="text" placeholder="Search elements" class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>

                <div class="mb-3">
                    <label for="element_region" class="block text-xs font-semibold uppercase tracking-wide text-slate-300 mb-2">Place new element in region</label>
                    <select id="element_region" class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <option value="top">Top</option>
                        <option value="bottom">Bottom</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                        <option value="corner-tl">Corner TL</option>
                        <option value="corner-tr">Corner TR</option>
                        <option value="corner-bl">Corner BL</option>
                        <option value="corner-br">Corner BR</option>
                        <option value="overlay">Overlay center</option>
                        <option value="background">Background layer</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <button type="button" class="rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-sm font-medium text-slate-200 hover:bg-slate-800 transition">Generate</button>
                    <button type="button" class="rounded-xl bg-gradient-to-r from-violet-600 to-fuchsia-600 px-3 py-2 text-sm font-medium text-white hover:opacity-90 transition">Search</button>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <button type="button" class="element-category-btn rounded-xl border border-violet-500 bg-violet-500/10 px-3 py-2 text-xs font-semibold text-violet-300" data-category="shapes">Shapes</button>
                    <button type="button" class="element-category-btn rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-800 transition" data-category="arrows">Lines</button>
                    <button type="button" class="element-category-btn rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-800 transition" data-category="labels">Labels</button>
                    <button type="button" class="element-category-btn rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-800 transition" data-category="icons">Icons</button>
                </div>

                <div id="element_sections" class="max-h-80 overflow-y-auto space-y-4"></div>
            </div>

            <div class="space-y-2">
                <button type="button" onclick="addTextLayer()" class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-sm font-medium text-slate-100 hover:bg-slate-800 transition">+ Quick Text</button>
                <button type="button" onclick="addRectLayer()" class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-sm font-medium text-slate-100 hover:bg-slate-800 transition">+ Quick Rectangle</button>
                <button type="button" onclick="addCircleLayer()" class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-sm font-medium text-slate-100 hover:bg-slate-800 transition">+ Quick Circle</button>
            </div>

            <button id="save-btn" type="button" onclick="saveFrameDesign()" class="w-full rounded-xl bg-gradient-to-r from-violet-600 to-fuchsia-600 px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">Save Frame</button>
            <div id="save_status" class="text-xs text-slate-300"></div>
        </aside>

        <section class="lg:col-span-6 rounded-2xl border border-slate-700 bg-slate-900/80 p-4 md:p-5 shadow-2xl shadow-black/20">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-slate-300">Drag layers on canvas. QR safe area is locked and centered by default.</p>
                <div class="flex items-center gap-2">
                    <button id="view_toggle_canvas" type="button" class="rounded-lg border border-violet-500 bg-violet-500/10 px-3 py-1.5 text-xs font-medium text-violet-300">Canvas</button>
                    <button id="view_toggle_preview" type="button" class="rounded-lg border border-slate-600 px-3 py-1.5 text-xs font-medium text-slate-200 hover:bg-slate-800 transition">Preview</button>
                    <button id="refresh-preview-btn" type="button" class="rounded-lg border border-slate-600 px-3 py-1.5 text-xs font-medium text-slate-200 hover:bg-slate-800 transition">Refresh Preview</button>
                </div>
            </div>
            <div id="editor_canvas_panel">
                <div id="editor_canvas_dropzone" class="overflow-x-auto pb-2 relative flex justify-center">
                    <canvas id="editor_canvas" class="border border-slate-600 rounded-xl mx-auto"></canvas>
                </div>
            </div>
            <div id="editor_preview_panel" class="hidden">
                <div class="overflow-x-auto pb-2 flex justify-center">
                    <canvas id="renderer_preview" class="border border-slate-600 rounded-xl mx-auto bg-white"></canvas>
                </div>
            </div>
            <div class="mt-4 rounded-xl border border-amber-500/40 bg-amber-500/10 px-3 py-2">
                <p class="text-xs text-amber-100">
                    Keep decorative elements outside the QR safe area for best scan reliability.
                </p>
            </div>
        </section>

        <aside class="lg:col-span-3 rounded-2xl border border-slate-700 bg-slate-900/80 p-4 space-y-3 shadow-2xl shadow-black/20">
            <div class="rounded-2xl border border-slate-700 bg-slate-950/60 p-3">
                <h3 class="text-sm font-semibold text-slate-100 mb-2">Warnings</h3>
                <ul id="safety_warnings" class="space-y-1 text-xs text-slate-300">
                    <li class="rounded-lg border border-emerald-500/40 bg-emerald-500/10 px-2 py-1 text-emerald-200">No warnings detected.</li>
                </ul>
            </div>

            <div class="rounded-2xl border border-slate-700 bg-slate-950/60 p-3">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-slate-100">Layers</h3>
                    <span class="text-[11px] text-slate-400">Drag to reorder</span>
                </div>
                <ul id="layer-list" class="space-y-1 max-h-48 overflow-y-auto"></ul>
            </div>

            <h3 class="font-semibold text-slate-100 mb-1">Selected element</h3>
            <div id="props_empty" class="text-sm text-slate-400">Select an element to edit.</div>
            <div id="props_panel" class="hidden space-y-2">
                <div id="text_props" class="hidden space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300">Text</label>
                    <input id="prop_text" type="text" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500" placeholder="Text content">

                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300">Font size</label>
                    <input id="prop_font_size" type="number" min="8" max="200" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>

                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300">Fill</label>
                <input id="prop_fill" type="color" class="h-10 w-full rounded-xl border border-slate-700 bg-slate-950">

                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300">Stroke</label>
                <input id="prop_stroke" type="color" class="h-10 w-full rounded-xl border border-slate-700 bg-slate-950">

                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300">Stroke width</label>
                <input id="prop_stroke_width" type="range" min="0" max="24" step="1" class="w-full">

                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300">Opacity</label>
                <input id="prop_opacity" type="range" min="0" max="1" step="0.05" class="w-full">

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="moveSelectedLayerUp()" class="rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-sm font-medium text-slate-100 hover:bg-slate-800 transition">Layer Up</button>
                    <button type="button" onclick="moveSelectedLayerDown()" class="rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-sm font-medium text-slate-100 hover:bg-slate-800 transition">Layer Down</button>
                </div>
                <button type="button" onclick="deleteSelectedLayer()" class="w-full rounded-xl border border-red-500/60 bg-red-500/10 px-3 py-2 text-sm font-medium text-red-200 hover:bg-red-500/20 transition">Delete</button>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.__FRAME_EDITOR_CONFIG__ = {
    existingFrame: @json($frame ?? null),
    storeUrl: @json(route('frames.store')),
    updateBaseUrl: @json(url('/frames')),
    returnTo: @json(request()->query('return_to')),
    returnStep: @json((int) request()->query('return_step', 2)),
    returnFrameMode: @json(request()->query('return_frame_mode', 'custom')),
};
</script>
@endpush
