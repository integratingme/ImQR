@if(auth()->check())
<div class="mt-4 rounded-2xl border border-dark-100 bg-white p-4 shadow-sm">
    <div class="mb-3 flex items-center justify-between">
        <p class="text-sm font-semibold tracking-tight text-dark-600">Custom Frames</p>
        <div class="flex items-center gap-2">
            <button
                type="button"
                id="custom-frames-delete-all-btn"
                class="hidden inline-flex items-center rounded-md border border-dark-200 bg-white px-2.5 py-1 text-[11px] font-medium text-dark-500 transition-colors hover:bg-dark-100 hover:text-dark-600"
                onclick="deleteAllCustomFrames()"
            >
                Delete all custom frames
            </button>
            <a
                href="{{ route('frames.editor', ['return_to' => url()->full(), 'return_step' => 2, 'return_frame_mode' => 'custom']) }}"
                class="inline-flex items-center rounded-md border border-primary-200 bg-primary-50 px-2.5 py-1 text-[11px] font-medium text-primary-700 transition-colors hover:border-primary-300 hover:bg-primary-100 hover:text-primary-800"
                target="_blank"
                rel="opener"
            >
                Create new frame
            </a>
        </div>
    </div>

    <p id="custom-frames-loading" class="text-xs text-dark-400">Loading custom frames...</p>
    <p id="custom-frames-empty" class="hidden text-xs text-dark-400">No custom frames yet. Create one in the editor.</p>

    <div id="custom-frames-grid" class="hidden grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3"></div>
</div>
@endif
