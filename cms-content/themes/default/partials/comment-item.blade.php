<li class="cms-comment-item" id="comment-{{ $comment->id }}" style="margin-bottom: 24px;">
    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
            <div>
                <strong style="font-size: 15px; color: var(--cms-color-secondary);">
                    @if($comment->author_url)
                        <a href="{{ $comment->author_url }}" target="_blank" rel="nofollow" style="color: inherit; text-decoration: none;">{{ $comment->author_name }}</a>
                    @else
                        {{ $comment->author_name }}
                    @endif
                </strong>
                <span style="font-size: 12px; color: #9ca3af; margin-left: 8px;">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <button type="button" onclick="replyToComment({{ $comment->id }}, '{{ addslashes($comment->author_name) }}')" style="background: transparent; border: none; color: var(--cms-color-primary); font-size: 12px; font-weight: 600; cursor: pointer; padding: 0;">Reply</button>
        </div>
        <div style="font-size: 14px; color: #4b5563; line-height: 1.5; white-space: pre-line;">{{ $comment->content }}</div>
    </div>

    @if($comment->children->isNotEmpty())
        <ul class="cms-comment-children" style="list-style: none; padding-left: 32px; margin-top: 16px; border-left: 2px solid #e5e7eb;">
            @foreach($comment->children as $child)
                @include('theme::partials.comment-item', ['comment' => $child])
            @endforeach
        </ul>
    @endif
</li>
