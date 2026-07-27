@php
    $comments = $post->comments()->whereNull('parent_id')->where('status', 'approved')->with('children')->get();
@endphp

<div class="cms-comments-area" style="margin-top: 40px; border-t: 1px solid #e5e7eb; padding-top: 30px;">
    <h3 style="font-size: 20px; margin-bottom: 20px;">{{ $post->comments()->where('status', 'approved')->count() }} Comments</h3>

    @if(session('success'))
        <div style="background-color: #d1fae5; border: 1px solid #34d399; color: #047857; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #fee2e2; border: 1px solid #f87171; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Comments List --}}
    <ul class="cms-comments-list" style="list-style: none; padding: 0; margin: 0 0 40px 0;">
        @foreach($comments as $comment)
            @include('theme::partials.comment-item', ['comment' => $comment])
        @endforeach
    </ul>

    {{-- Comment Form --}}
    <div class="cms-comment-form-wrap" id="respond" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px;">
        <h4 id="reply-title" style="font-size: 18px; margin-top: 0; margin-bottom: 16px;">Leave a Comment <small><a id="cancel-comment-reply-link" href="#respond" style="display:none; font-size: 12px; color: #ef4444; margin-left: 10px; font-weight: normal;">[Cancel Reply]</a></small></h4>
        <form action="{{ route('comments.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="hidden" name="parent_id" id="comment_parent_id" value="">

            {{-- Honeypot field --}}
            <input type="text" name="website_verify" style="display: none !important;" tabindex="-1" autocomplete="off">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Name *</label>
                    <input type="text" name="author_name" required style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Email *</label>
                    <input type="email" name="author_email" required style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Website (Optional)</label>
                <input type="url" name="author_url" style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
            </div>

            <div>
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Comment *</label>
                <textarea name="content" required rows="5" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
            </div>

            <button type="submit" style="background: var(--cms-color-primary); color: #fff; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; align-self: flex-start; transition: opacity 0.2s;">Submit Comment</button>
        </form>
    </div>
</div>

<script>
    function replyToComment(commentId, authorName) {
        document.getElementById('comment_parent_id').value = commentId;
        document.getElementById('reply-title').firstChild.textContent = 'Reply to ' + authorName;
        document.getElementById('cancel-comment-reply-link').style.display = 'inline-block';
        window.location.hash = 'respond';
    }

    document.getElementById('cancel-comment-reply-link').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('comment_parent_id').value = '';
        document.getElementById('reply-title').firstChild.textContent = 'Leave a Comment';
        this.style.display = 'none';
    });
</script>
