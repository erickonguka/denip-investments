<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->title }} - Denip Investments</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-blue: #1e40af;
            --deep-blue: #0f172a;
            --light-blue: #3b82f6;
            --yellow: #fbbf24;
            --light-yellow: #fef3c7;
            --dark-yellow: #d97706;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-700);
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, var(--deep-blue) 0%, var(--primary-blue) 100%);
            color: var(--white);
            padding: 2rem 0;
            text-align: center;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .project-info {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: var(--gray-200);
            border-radius: 10px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-blue), var(--light-blue));
            transition: width 0.3s ease;
        }

        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .media-item {
            background: var(--white);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .media-item img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 6px;
        }

        .file-icon {
            font-size: 3rem;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 0.5rem;
            }
            
            .project-info {
                padding: 1rem;
                margin: 1rem 0;
            }
            
            .media-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>{{ $project->title }}</h1>
            <p>Project Progress & Information</p>
        </div>
    </div>

    <div class="container">
        <div class="project-info">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div>
                    <h2 style="color: var(--deep-blue); margin-bottom: 1rem;">Project Details</h2>
                    
                    <div class="status-badge" style="background: {{ $project->status === 'completed' ? '#dcfce7' : ($project->status === 'active' ? 'var(--light-yellow)' : '#fef2f2') }}; color: {{ $project->status === 'completed' ? 'var(--success)' : ($project->status === 'active' ? 'var(--dark-yellow)' : 'var(--error)') }};">
                        {{ ucfirst($project->status) }}
                    </div>
                    
                    <p><strong>Client:</strong> {{ $project->client->name }}</p>
                    <p><strong>Start Date:</strong> {{ $project->start_date->format('F j, Y') }}</p>
                    @if($project->end_date)
                    <p><strong>End Date:</strong> {{ $project->end_date->format('F j, Y') }}</p>
                    @endif
                    @if($project->budget)
                    <p><strong>Budget:</strong> ${{ number_format($project->budget, 2) }}</p>
                    @endif
                </div>
                
                <div>
                    <h3 style="color: var(--deep-blue); margin-bottom: 1rem;">Progress</h3>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $project->progress }}%;"></div>
                    </div>
                    <p style="text-align: center; font-weight: 600; color: var(--deep-blue);">{{ $project->progress }}% Complete</p>
                </div>
            </div>
            
            @if($project->description)
            <div style="margin-top: 2rem;">
                <h3 style="color: var(--deep-blue); margin-bottom: 1rem;">Description</h3>
                <p>{{ $project->description }}</p>
            </div>
            @endif
            
            <div style="margin-top: 2rem;">
                <h3 style="color: var(--deep-blue); margin-bottom: 1rem;">Project Team</h3>
                @if($project->assigned_users && count($project->assigned_users) > 0)
                <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                    @foreach($assignedUsers as $user)
                    <div style="display: flex; align-items: center; gap: 0.5rem; background: var(--gray-100); padding: 0.5rem 1rem; border-radius: 20px;">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-blue); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <span>{{ $user->name }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="color: var(--gray-600); font-style: italic;">No team members assigned to this project</div>
                @endif
            </div>
            
            <div style="margin-top: 2rem;">
                <h3 style="color: var(--deep-blue); margin-bottom: 1rem;">Project Media</h3>
                @if($project->media && count($project->media) > 0)
                <div class="media-grid">
                    @foreach($project->media as $media)
                    <div class="media-item">
                        @if(str_starts_with($media['type'], 'image/'))
                            <img src="{{ asset('storage/' . $media['path']) }}" alt="{{ $media['name'] }}">
                        @else
                            <div class="file-icon">
                                @if($media['type'] === 'application/pdf')
                                    <i class="fas fa-file-pdf"></i>
                                @elseif(str_contains($media['type'], 'word'))
                                    <i class="fas fa-file-word"></i>
                                @elseif(str_contains($media['type'], 'excel') || str_contains($media['type'], 'sheet'))
                                    <i class="fas fa-file-excel"></i>
                                @else
                                    <i class="fas fa-file"></i>
                                @endif
                            </div>
                        @endif
                        <p style="font-size: 0.9rem; margin-top: 0.5rem;">{{ $media['name'] }}</p>
                        <p style="font-size: 0.8rem; color: var(--gray-600); margin-bottom: 0.5rem;">{{ number_format($media['size'] / 1024, 1) }} KB</p>
                        <a href="{{ asset('storage/' . $media['path']) }}" download="{{ $media['name'] }}" style="display: inline-block; background: var(--primary-blue); color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; font-size: 0.8rem;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="color: var(--gray-600); font-style: italic; text-align: center; padding: 2rem;">
                    No media files available for this project
                </div>
                @endif
            </div>
        </div>
        
        <div style="text-align: center; padding: 2rem; color: var(--gray-600);">
            <p>Powered by <strong>Denip Investments Ltd</strong></p>
        </div>
    </div>
</body>
</html>