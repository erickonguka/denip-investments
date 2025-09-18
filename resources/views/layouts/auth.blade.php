<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Denip Investments</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
    <style>
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--deep-blue) 0%, var(--primary-blue) 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .mfa-step h3 {
            font-size: 1.25rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo">
                <svg viewBox="0 0 1605 502" xmlns="http://www.w3.org/2000/svg">
                    <g transform="matrix(1,0,0,1,-698.678,-1249.19)">
                        <g transform="matrix(21.3645,0,0,21.3645,-449.868,-765.599)">
                            <g transform="matrix(0.818597,0,0,0.818597,15.8776,15.9463)">
                                <path d="M46.277,108.659L68.821,108.617L68.821,103.451L50.495,103.451L46.277,108.659Z" fill="currentColor"/>
                                <path d="M52.076,100.89L78.037,100.89L78.015,111.2L68.778,111.2L68.778,116.366L83.05,116.366L83.05,100.955L77.818,95.724L56.607,95.724L52.076,100.89Z" fill="currentColor"/>
                                <path d="M56.979,111.178L66.019,111.178L66.019,116.235L53.145,116.235L56.979,111.178Z" fill="currentColor"/>
                                <path d="M98.009,100.966L95.199,104.371L89.697,104.371L89.697,106.94L97.215,106.94L94.458,110.222L89.759,110.222L89.759,112.745L97.978,112.745L95.139,116.366L85.595,116.336L85.595,100.95L98.009,100.966Z" fill="currentColor"/>
                                <path d="M100.408,116.344L100.408,100.89L104.173,100.89L110.893,109.471L110.893,100.89L115.118,100.89L115.118,116.333L111.233,116.333L104.403,108.156L104.403,116.351L100.408,116.344Z" fill="currentColor"/>
                                <path d="M118.049,116.332L118.03,116.351L118.038,100.89L122.186,100.89L122.186,111.039L118.049,116.332Z" fill="currentColor"/>
                                <path d="M125.096,100.89L132.805,100.89C132.805,100.89 138.022,100.668 138.029,106.227C138.035,111.421 133.548,111.847 133.548,111.847L129.399,111.847L129.399,116.351L125.096,116.351L125.096,100.89ZM132.127,109.129C133.845,109.129 134.839,108.021 134.839,106.303C134.839,104.585 133.845,103.476 132.127,103.476L129.367,103.476L129.367,109.129L132.127,109.129Z" fill="currentColor"/>
                            </g>
                        </g>
                    </g>
                </svg>
                Denip Investments
            </div>
            <div class="auth-subtitle">@yield('subtitle', 'Admin Portal')</div>
        </div>

        <div class="auth-body">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>