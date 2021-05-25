<a href="{{ route('banner.click', $banner) }}" target="_blank">
    <img alt=""
        width="{{ $banner->getWidth() }}"
        height="{{ $banner->getHeight() }}"
        src="{{ Storage::disk('public')->url($banner->file) }}">
</a>
