@props(['label', 'name', 'type' => 'text', 'required' => false, 'options' => [], 'value' => '', 'placeholder' => ''])

<div style="margin-bottom: 1.5rem;">
    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">
        {{ $label }}
        @if($required) <span style="color: var(--error);">*</span> @endif
    </label>
    
    @if($type === 'select')
        <select name="{{ $name }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none; background: var(--white);" {{ $required ? 'required' : '' }}>
            <option value="">{{ $placeholder ?: 'Select option' }}</option>
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ $value == $optionValue ? 'selected' : '' }}>{{ $optionLabel }}</option>
            @endforeach
        </select>
    @elseif($type === 'textarea')
        <textarea name="{{ $name }}" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}>{{ $value }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}>
    @endif
</div>