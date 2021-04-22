<a href="{{ route('banner.click', $banner) }}" target="_blank">
    <img alt=""
        width="{{ $banner->getWidth() }}"
        height="{{ $banner->getHeight() }}"
        src="{{ asset('storage/' . $banner->file) }}">
</a>
