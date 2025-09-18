<div style="background: white; border-radius: 16px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 8px 32px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; transition: all 0.3s ease;"
    onmouseover="this.style.boxShadow='0 12px 40px rgba(0,0,0,0.1)'; this.style.transform='translateY(-2px)'" 
    onmouseout="this.style.boxShadow='0 8px 32px rgba(0,0,0,0.06)'; this.style.transform='translateY(0)'">
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary), #e67e22); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(243,156,18,0.3);">
                {{ strtoupper(substr($comment->name, 0, 2)) }}
            </div>
            <div>
                <div style="font-weight: 700; color: var(--secondary); font-size: 1.1rem;">{{ $comment->name }}</div>
                <div style="font-size: 0.9rem; color: #6b7280; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-clock" style="font-size: 0.8rem;"></i>
                    {{ $comment->created_at->format('M j, Y \a\t g:i A') }}
                </div>
            </div>
        </div>
        <button onclick="replyToComment({{ $comment->id }}, '{{ $comment->name }}')" 
            style="background: var(--primary); color: white; border: none; font-size: 0.85rem; cursor: pointer; padding: 0.6rem 1rem; border-radius: 20px; transition: all 0.3s ease; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;"
            onmouseover="this.style.background='#e67e22'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(243,156,18,0.3)'" 
            onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <i class="fas fa-reply"></i> Reply
        </button>
    </div>
    
    <div style="color: #374151; line-height: 1.7; font-size: 1rem; margin-bottom: 1rem;">
        {{ $comment->comment }}
    </div>
    
    <!-- Nested Replies -->
    @if($comment->replies->count() > 0)
    <div style="margin-left: 2.5rem; border-left: 3px solid var(--primary); padding-left: 1.5rem; margin-top: 1.5rem;">
        @foreach($comment->replies as $reply)
        <div style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--secondary), #34495e); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem; box-shadow: 0 3px 8px rgba(44,62,80,0.3);">
                        {{ strtoupper(substr($reply->name, 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--secondary); font-size: 1rem;">{{ $reply->name }}</div>
                        <div style="font-size: 0.85rem; color: #6b7280; display: flex; align-items: center; gap: 0.3rem;">
                            <i class="fas fa-clock" style="font-size: 0.7rem;"></i>
                            {{ $reply->created_at->format('M j, Y \a\t g:i A') }}
                        </div>
                    </div>
                </div>
                <button onclick="replyToComment({{ $comment->id }}, '{{ $reply->name }}')" 
                    style="background: var(--secondary); color: white; border: none; font-size: 0.8rem; cursor: pointer; padding: 0.5rem 0.8rem; border-radius: 15px; transition: all 0.3s ease; font-weight: 600; display: flex; align-items: center; gap: 0.3rem;"
                    onmouseover="this.style.background='#34495e'; this.style.transform='translateY(-1px)'" 
                    onmouseout="this.style.background='var(--secondary)'; this.style.transform='translateY(0)'">
                    <i class="fas fa-reply"></i> Reply
                </button>
            </div>
            <div style="color: #374151; line-height: 1.6; font-size: 0.95rem;">
                {{ $reply->comment }}
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>