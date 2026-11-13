{{--
    A file field for an image with a live preview (public/js/app.js) and the current image, if any.
    The form around it needs enctype="multipart/form-data", otherwise the file is not sent.
--}}
@props(['name', 'label', 'current' => null])

<div @class(['field', 'has-error' => $errors->has($name)])>
    <label for="{{ $name }}">{{ $label }}</label>
    <div class="upload">
        <img id="{{ $name }}-preview" src="{{ $current }}" alt="" class="thumb" @if (! $current) hidden @endif>
        <input type="file" id="{{ $name }}" name="{{ $name }}" accept="image/jpeg,image/png,image/webp"
               data-preview="{{ $name }}-preview">
    </div>
    <x-error :name="$name" />
</div>
