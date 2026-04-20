@if(auth()->check())
<div class="mt-4 border border-dark-200 rounded-xl p-4 bg-dark-50">
    <div class="flex items-center justify-between mb-3">
        <p class="text-sm font-medium text-dark-600">Custom Frames</p>
        <a
            href="{{ route('frames.editor', ['return_to' => url()->full(), 'return_step' => 2, 'return_frame_mode' => 'custom']) }}"
            class="btn btn-outline btn-sm"
            target="_blank"
            rel="opener"
        >
            Create new frame
        </a>
    </div>

    <p id="custom-frames-loading" class="text-xs text-dark-400">Loading custom frames...</p>
    <p id="custom-frames-empty" class="hidden text-xs text-dark-400">No custom frames yet. Create one in the editor.</p>

    <div id="custom-frames-grid" class="hidden grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3"></div>
</div>
@endif
