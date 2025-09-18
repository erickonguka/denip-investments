@props([
    'text' => 'Invest in your future',
    'href' => '#',
    'onclick' => '',
    'type' => 'button',
    'state' => 'normal', // normal, loading, error, outline, bounce
    'size' => 'normal' // normal, small, large
])

@php
$classes = 'invest-button';
if ($state !== 'normal') {
    $classes .= ' ' . $state;
}
if ($size !== 'normal') {
    $classes .= ' ' . $size;
}
@endphp

<style>
.invest-button {
    background: linear-gradient(135deg, #ff9500 0%, #ff8800 100%);
    color: #000;
    font-size: clamp(18px, 3vw, 32px);
    font-weight: bold;
    padding: clamp(15px, 3vw, 25px) clamp(30px, 6vw, 50px);
    border: none;
    cursor: pointer;
    position: relative;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(255, 149, 0, 0.3);
    clip-path: polygon(2rem 0%, calc(75% + 2rem) 0%, 100% 35%, 100% 100%, 0% 100%);
    min-width: 250px;
    width: auto;
    white-space: nowrap;
    outline: none;
}

.invest-button:before {
    content: '';
    position: absolute;
    bottom: 0;
    left: calc(30px + 1rem);
    width: 100px;
    height: 0.8rem;
    background-color: #000;
    z-index: 1;
}

.invest-button-text {
    display: block;
    position: relative;
    z-index: 2;
}

.invest-button:hover:not(.loading):not(.error) {
    background: linear-gradient(135deg, #ffaa00 0%, #ff9900 100%);
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(255, 149, 0, 0.4);
}

.invest-button:active:not(.loading):not(.error) {
    transform: translateY(0px);
    box-shadow: 0 4px 15px rgba(255, 149, 0, 0.3);
}

.invest-button:focus {
    box-shadow: 0 8px 20px rgba(255, 149, 0, 0.3), 0 0 0 4px rgba(255, 149, 0, 0.4);
    outline: none;
}

.invest-button.loading {
    background: linear-gradient(135deg, #cccccc 0%, #999999 100%);
    cursor: wait;
    pointer-events: none;
}

.invest-button.loading:before {
    background-color: #666;
}

.invest-button.loading .invest-button-text:after {
    content: '';
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-left: 10px;
    border: 2px solid #000;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

.invest-button.error {
    background: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
    color: #fff;
    animation: shake 0.5s ease-in-out;
}

.invest-button.error:before {
    background-color: #800000;
}

.invest-button.outline {
    background: transparent;
    border: 3px solid #ff9500;
    color: #ff9500;
    box-shadow: none;
}

.invest-button.outline:before {
    display: none;
}

.invest-button.outline:hover {
    background: rgba(255, 149, 0, 0.1);
    border-color: #ffaa00;
    color: #ffaa00;
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(255, 149, 0, 0.2);
}

.invest-button.bounce {
    animation: bounce 2s ease-in-out infinite;
}

.invest-button.small {
    font-size: clamp(14px, 2.5vw, 24px);
    padding: clamp(10px, 2vw, 18px) clamp(20px, 4vw, 35px);
    min-width: 180px;
}

.invest-button.large {
    font-size: clamp(24px, 4vw, 48px);
    padding: clamp(20px, 4vw, 40px) clamp(40px, 8vw, 80px);
    min-width: 300px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

@keyframes bounce {
    0%, 20%, 53%, 80%, 100% { transform: translateY(0); }
    40%, 43% { transform: translateY(-15px); }
    70% { transform: translateY(-8px); }
    90% { transform: translateY(-3px); }
}

@media (max-width: 768px) {
    .invest-button {
        font-size: clamp(16px, 4vw, 28px);
        padding: clamp(12px, 2.5vw, 20px) clamp(25px, 5vw, 40px);
        min-width: 200px;
    }
    .invest-button:before {
        left: calc(25px + 0.8rem);
        width: 80px;
        height: 0.6rem;
    }
}
</style>

@if($href !== '#')
    <a href="{{ $href }}" class="{{ $classes }}" @if($onclick) onclick="{{ $onclick }}" @endif>
        <span class="invest-button-text">{{ $text }}</span>
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }}" @if($onclick) onclick="{{ $onclick }}" @endif>
        <span class="invest-button-text">{{ $text }}</span>
    </button>
@endif