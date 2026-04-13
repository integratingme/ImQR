@if(auth()->check())
<div class="mt-4 border border-dark-200 rounded-xl p-4 bg-dark-50">
    <div class="flex items-center justify-between mb-3">
        <p class="text-sm font-medium text-dark-600">Custom Frames</p>
        <a href="{{ route('frames.editor') }}" class="btn btn-outline btn-sm">Create new frame</a>
    </div>

    @if(($frameDesigns ?? collect())->isEmpty())
        <p class="text-xs text-dark-400">No custom frames yet. Create one in the editor.</p>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($frameDesigns as $frameDesign)
                <button
                    type="button"
                    class="custom-frame-option border-2 border-dark-200 hover:border-primary-500 rounded-lg p-2 text-left"
                    data-frame-design-id="{{ $frameDesign->id }}"
                    onclick='selectCustomFrame(this, {{ $frameDesign->id }})'
                >
                    @if($frameDesign->thumbnail_url)
                        <img src="{{ $frameDesign->thumbnail_url }}" alt="{{ $frameDesign->name }}" class="w-full h-20 object-contain border border-dark-200 rounded">
                    @else
                        <div class="w-full h-20 border border-dark-200 rounded bg-white flex items-center justify-center text-xs text-dark-400">No preview</div>
                    @endif
                    <p class="mt-2 text-xs text-dark-500 truncate">{{ $frameDesign->name }}</p>
                    @if($frameDesign->is_template)
                        <p class="text-[10px] text-primary-600">Template</p>
                    @endif
                </button>
            @endforeach
        </div>
    @endif
</div>
@endif
