@props(['comment'])

<div class="flex space-x-3 text-sm">
    <div class="flex-shrink-0">
        @if($comment->user->profile_photo)
            <img src="{{ $comment->user->profile_photo }}" class="h-6 w-6 rounded-full object-cover">
        @else
            <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs">
                {{ substr($comment->user->name, 0, 1) }}
            </div>
        @endif
    </div>
    <div class="flex-1">
        <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl px-4 py-2">
            <span class="font-bold">{{ $comment->user->name }}</span>
            <p class="text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
        </div>
        
        <!-- Reply Action -->
        <div class="flex items-center space-x-2 mt-1 ml-2">
            <button onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('hidden')" class="text-xs text-gray-500 hover:text-indigo-500 font-bold">Balas</button>
            <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
        </div>

        <!-- Reply Form -->
        <div id="reply-{{ $comment->id }}" class="hidden mt-2">
            <form action="{{ route('comments.store') }}" method="POST" class="flex space-x-2">
                @csrf
                <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <input type="text" name="content" placeholder="Balas {{ $comment->user->name }}..." class="flex-1 bg-gray-100 border-none rounded-full px-3 py-1 text-xs focus:ring-indigo-500">
                <button type="submit" class="text-indigo-600 text-xs font-bold">Kirim</button>
            </form>
        </div>

        <!-- Nested Replies -->
        @if($comment->replies->count() > 0)
            <div class="mt-2 space-y-2">
                @foreach($comment->replies as $reply)
                    @include('components.comment-item', ['comment' => $reply])
                @endforeach
            </div>
        @endif
    </div>
</div>
