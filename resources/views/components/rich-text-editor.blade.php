@props(['name', 'value' => '', 'height' => '300px', 'id' => null])

@php
    $editorId = $id ?? 'editor-' . uniqid();
@endphp

<div>
    <div id="{{ $editorId }}" style="height: {{ $height }};"></div>
    <textarea name="{{ $name }}" id="{{ $editorId }}-input" style="display: none;">{{ $value }}</textarea>
</div>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Quill !== 'undefined') {
        const editor{{ str_replace('-', '_', $editorId) }} = new Quill('#{{ $editorId }}', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'header': [1, 2, 3, false] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });
        
        const textarea = document.getElementById('{{ $editorId }}-input');
        if (textarea.value.trim()) {
            editor{{ str_replace('-', '_', $editorId) }}.root.innerHTML = textarea.value;
        }
        
        editor{{ str_replace('-', '_', $editorId) }}.on('text-change', function() {
            textarea.value = editor{{ str_replace('-', '_', $editorId) }}.root.innerHTML;
        });
        
        window.{{ str_replace('-', '_', $editorId) }} = editor{{ str_replace('-', '_', $editorId) }};
    }
});
</script>
@endpush