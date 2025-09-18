@extends('layouts.app')

@section('title', 'User Activities')

@section('content')
<x-data-table 
        title="User Activities"
        :headers="['User', 'Action', 'Description', 'IP Address', 'Date']"
        :pagination="$activities">
        
        @foreach($activities as $activity)
        <tr style="border-bottom: 1px solid var(--gray-200);">
            <td style="padding: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    @if($activity->user && $activity->user->profile_photo)
                        <img src="{{ asset('storage/' . $activity->user->profile_photo) }}" alt="{{ $activity->user->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div style="width: 32px; height: 32px; background: var(--primary-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; font-weight: 600;">
                            {{ $activity->user ? strtoupper(substr($activity->user->name, 0, 2)) : 'SY' }}
                        </div>
                    @endif
                    <div>
                        <div style="font-weight: 600; color: var(--deep-blue);">
                            {{ $activity->user?->name ?? 'System' }}
                            @if($activity->user && $activity->user->isClient())
                                <span style="background: var(--warning); color: white; padding: 0.125rem 0.375rem; border-radius: 8px; font-size: 0.7rem; margin-left: 0.5rem;">Client</span>
                            @elseif($activity->user)
                                <span style="background: var(--primary-blue); color: white; padding: 0.125rem 0.375rem; border-radius: 8px; font-size: 0.7rem; margin-left: 0.5rem;">Team</span>
                            @endif
                        </div>
                        <div style="font-size: 0.8rem; color: var(--gray-600);">
                            {{ $activity->user?->email ?? 'system@denip.com' }}
                        </div>
                    </div>
                </div>
            </td>
            <td style="padding: 1rem;">
                <span style="padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600; 
                    background: {{ $activity->action === 'login' ? 'var(--success)' : ($activity->action === 'logout' ? 'var(--warning)' : 'var(--primary-blue)') }}; 
                    color: white;">
                    {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                </span>
            </td>
            <td style="padding: 1rem;">
                <div>{{ $activity->description }}</div>
                @if($activity->model_type)
                <div style="font-size: 0.8rem; color: var(--gray-600); margin-top: 0.25rem;">
                    {{ class_basename($activity->model_type) }} #{{ $activity->model_id }}
                </div>
                @endif
            </td>
            <td style="padding: 1rem;">
                <div style="font-family: monospace; font-size: 0.9rem;">{{ $activity->ip_address }}</div>
            </td>
            <td style="padding: 1rem;">
                <div>{{ $activity->created_at->format('M j, Y') }}</div>
                <div style="font-size: 0.8rem; color: var(--gray-600);">{{ $activity->created_at->format('g:i A') }}</div>
            </td>
        </tr>
        @endforeach
</x-data-table>
@endsection