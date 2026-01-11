<x-app-layout>
    <div class="py-6 relative h-full" 
         x-data="{ 
            lightboxOpen: false, 
            lightboxImages: [], 
            lightboxIndex: 0,
            get activeImage() { return this.lightboxImages[this.lightboxIndex] || ''; },
            next() { this.lightboxIndex = (this.lightboxIndex + 1) % this.lightboxImages.length; },
            prev() { this.lightboxIndex = (this.lightboxIndex - 1 + this.lightboxImages.length) % this.lightboxImages.length; }
         }" 
         @keydown.escape.window="lightboxOpen = false"
         @keydown.arrow-right.window="if(lightboxOpen) next()"
         @keydown.arrow-left.window="if(lightboxOpen) prev()">

        <!-- Lightbox Modal -->
        <div x-show="lightboxOpen" 
             style="display: none;"
             class="fixed inset-0 z-[200] flex items-center justify-center p-4 touch-none"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-none"
             x-transition:enter-end="opacity-100 backdrop-blur-xl"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 backdrop-blur-xl"
             x-transition:leave-end="opacity-0 backdrop-blur-none">
            
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/90" @click="lightboxOpen = false"></div>

            <!-- Close Button -->
            <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-white/50 hover:text-white transition transform hover:rotate-90 duration-300 z-50 p-2">
                <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <!-- Navigation Buttons (Desktop & Mobile) -->
            <button x-show="lightboxImages.length > 1" @click.stop="prev()" class="absolute left-2 md:left-8 top-1/2 -translate-y-1/2 text-white/50 hover:text-white hover:bg-white/10 p-3 rounded-full transition z-50 focus:outline-none">
                <svg class="w-8 h-8 md:w-12 md:h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <button x-show="lightboxImages.length > 1" @click.stop="next()" class="absolute right-2 md:right-8 top-1/2 -translate-y-1/2 text-white/50 hover:text-white hover:bg-white/10 p-3 rounded-full transition z-50 focus:outline-none">
                <svg class="w-8 h-8 md:w-12 md:h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" /></svg>
            </button>

            <!-- Image Container -->
            <div class="relative z-10 max-w-7xl w-full max-h-screen p-2 flex items-center justify-center pointer-events-none" 
                 x-swipe:left="next()" 
                 x-swipe:right="prev()">
                <img :src="activeImage" 
                     class="max-w-full max-h-[85vh] md:max-h-[90vh] object-contain rounded-lg shadow-2xl shadow-neon-purple/20 pointer-events-auto transition-all duration-300 selection:bg-transparent"
                     x-transition:enter="transition ease-out duration-300 delay-100 transform"
                     x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     draggable="false">
                 
                 <!-- Counter -->
                 <div x-show="lightboxImages.length > 1" class="absolute bottom-[-40px] md:bottom-[-50px] left-1/2 -translate-x-1/2 bg-black/50 backdrop-blur px-4 py-1.5 rounded-full text-white/80 text-sm font-medium border border-white/10">
                    <span x-text="lightboxIndex + 1"></span> / <span x-text="lightboxImages.length"></span>
                 </div>
            </div>
        </div>

        <!-- 3-Column Layout Container -->
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 h-full">
                
                <!-- LEFT COLUMN: Notifications (Independently Scrollable) -->
                <div class="{{ request('mobile_view') == 'activity' ? 'block col-span-1 w-full' : 'hidden lg:block lg:col-span-1' }} h-full overflow-hidden">
                    <div class="h-full overflow-y-auto no-scrollbar pr-2 pt-8 pb-20 [mask-image:linear-gradient(to_bottom,transparent,black_20px)]">
                        <!-- Activity Card -->
                        <div class="glass-card rounded-3xl p-6 relative overflow-hidden">
                            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                <span class="bg-neon-blue/20 p-2 rounded-lg text-neon-blue">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                </span>
                                Aktivitas Terbaru
                            </h3>
                            
                            <div class="space-y-4">
                                @forelse($recent_comments as $comment)
                                    <div onclick="const el = document.getElementById('post-card-{{ $comment->post_id }}'); if(el){ el.scrollIntoView({ behavior: 'smooth', block: 'center' }) } else { window.location.href = '{{ route('profile.show', $comment->post->user->username) }}#post-{{ $comment->post_id }}' }" 
                                         class="group flex gap-3 items-start p-3 rounded-xl hover:bg-white/5 transition border border-transparent hover:border-white/5 cursor-pointer">
                                        <div class="shrink-0 relative">
                                            @if($comment->user->profile_photo)
                                                <img src="{{ $comment->user->profile_photo }}" loading="lazy" class="h-10 w-10 rounded-full object-cover ring-2 ring-white/10 group-hover:ring-neon-blue/50 transition">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-midnight-800 flex items-center justify-center text-gray-400 font-bold text-xs ring-2 ring-white/10 group-hover:ring-neon-blue/50 transition">
                                                    {{ substr($comment->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div class="absolute -bottom-1 -right-1 bg-neon-blue p-0.5 rounded-full border-2 border-midnight-900">
                                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-300">
                                                <span class="font-bold text-white hover:text-neon-blue transition">{{ $comment->user->name }}</span>
                                                mengomentari postingan Anda:
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1 line-clamp-2 italic">"{{ $comment->content }}"</p>
                                            <p class="text-[10px] text-gray-500 mt-2 font-mono">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-500">
                                        <p class="text-sm">Belum ada aktivitas baru.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Mini Footer -->
                        <div class="text-xs text-gray-600 px-4 text-center">
                            &copy; {{ date('Y') }} Komunitas AU.
                        </div>
                    </div>
                </div>

                <!-- CENTER COLUMN: Feed (Independently Scrollable) -->
                <div class="{{ request()->has('mobile_view') ? 'hidden lg:block lg:col-span-2' : 'lg:col-span-2' }} h-full overflow-y-auto no-scrollbar pt-8 pb-32 [mask-image:linear-gradient(to_bottom,transparent,black_20px)]" id="post-feed">
                    @foreach($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach

                     <!-- Pagination -->
                    <div class="mt-8 px-4">
                        {{ $posts->links() }}
                    </div>
                </div>

                <!-- RIGHT COLUMN: Creation Studio (Independently Scrollable) -->
                <div class="{{ request('mobile_view') == 'create' ? 'block col-span-1 w-full' : 'hidden lg:block lg:col-span-1' }} h-full overflow-hidden">
                    <div class="h-full overflow-y-auto no-scrollbar pl-2 pt-8 pb-20 space-y-6 [mask-image:linear-gradient(to_bottom,transparent,black_20px)]">
                        <!-- Creation Studio Card -->
                        <div class="glass-card rounded-3xl p-1 relative group overflow-hidden transition-all duration-300 hover:shadow-neon-purple/20">
                            <div class="absolute inset-0 bg-gradient-to-r from-neon-purple via-neon-pink to-neon-blue opacity-20 blur-xl group-hover:opacity-40 transition duration-700"></div>
                            <div class="bg-midnight-800/90 backdrop-blur-xl rounded-[20px] p-5 relative">
                                <h3 class="text-lg font-bold text-white mb-4">Buat Postingan</h3>
                                <form id="post-form" class="space-y-4">
                                    <div class="space-y-4">
                                        <div class="flex gap-3">
                                            <div class="shrink-0">
                                                @if(Auth::user()->profile_photo)
                                                    <img src="{{ Auth::user()->profile_photo }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-white/20">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-800 to-black flex items-center justify-center text-white font-bold text-sm ring-2 ring-white/20">
                                                        {{ substr(Auth::user()->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <textarea name="content" id="post-content" rows="3" class="w-full bg-midnight-900/50 border border-white/5 rounded-2xl focus:ring-2 focus:ring-neon-purple/50 focus:border-transparent p-3 text-sm text-gray-200 placeholder-gray-500 resize-none transition-all focus:bg-midnight-900/80" placeholder="Apa cerita serumu? ✨"></textarea>
                                        </div>
                                        
                                        <!-- Image Preview Container -->
                                        <div id="image-preview-container" class="hidden mt-3">
                                            <div id="image-preview" class="grid grid-cols-2 gap-2"></div>
                                        </div>

                                        <div class="flex justify-between items-center pt-2 border-t border-white/5">
                                            <div class="relative">
                                                <input type="file" name="images[]" id="images" multiple class="hidden" accept="image/*">
                                                <label for="images" class="cursor-pointer p-2 text-neon-blue hover:text-white transition hover:bg-neon-blue/20 rounded-lg flex items-center gap-2 text-xs font-bold" title="Upload Foto">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    <span>Media</span>
                                                </label>
                                            </div>
                                            <button type="submit" id="submit-btn" class="bg-gradient-to-r from-neon-purple to-neon-pink text-white px-6 py-2 rounded-full font-bold shadow-lg shadow-neon-purple/20 hover:shadow-neon-purple/40 hover:scale-105 active:scale-95 transition-all duration-300 text-xs tracking-wide flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                                <span>POSTING 🚀</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        const form = document.getElementById('post-form');
                                        const input = document.getElementById('images');
                                        const previewContainer = document.getElementById('image-preview-container');
                                        const preview = document.getElementById('image-preview');
                                        const submitBtn = document.getElementById('submit-btn');
                                        const feedContainer = document.getElementById('post-feed');
                                        
                                        const dataTransfer = new DataTransfer();

                                        // 1. Image Handling
                                        input.addEventListener('change', (e) => {
                                            const newFiles = Array.from(e.target.files);
                                            newFiles.forEach(file => {
                                                const exists = Array.from(dataTransfer.files).some(f => f.name === file.name && f.size === file.size);
                                                if (!exists && dataTransfer.files.length < 4) {
                                                    dataTransfer.items.add(file);
                                                }
                                            });
                                            input.files = dataTransfer.files;
                                            renderPreview();
                                        });

                                        window.removeImage = (index) => {
                                            dataTransfer.items.remove(index);
                                            input.files = dataTransfer.files;
                                            renderPreview();
                                        };

                                        function renderPreview() {
                                            preview.innerHTML = '';
                                            if (dataTransfer.files.length > 0) {
                                                previewContainer.classList.remove('hidden');
                                                Array.from(dataTransfer.files).forEach((file, index) => {
                                                    const reader = new FileReader();
                                                    reader.onload = (e) => {
                                                        const div = document.createElement('div');
                                                        div.className = 'relative aspect-square rounded-xl overflow-hidden group/item border border-white/10 shadow-lg';
                                                        div.innerHTML = `
                                                            <img src="${e.target.result}" class="w-full h-full object-cover transition duration-500 group-hover/item:scale-110">
                                                            <div class="absolute inset-0 bg-black/20 group-hover/item:bg-black/40 transition"></div>
                                                            <button type="button" onclick="removeImage(${index})" class="absolute top-1 right-1 bg-red-500/80 hover:bg-red-600 text-white p-1 rounded-full backdrop-blur-sm transition-all transform hover:scale-110 shadow-md opacity-0 group-hover/item:opacity-100 focus:opacity-100">
                                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                                                            </button>
                                                        `;
                                                        preview.appendChild(div);
                                                    };
                                                    reader.readAsDataURL(file);
                                                });
                                            } else {
                                                previewContainer.classList.add('hidden');
                                            }
                                        }

                                        // 2. AJAX Submission
                                        form.addEventListener('submit', async (e) => {
                                            e.preventDefault();
                                            
                                            // Validate
                                            const content = document.getElementById('post-content').value;
                                            if (!content.trim() && dataTransfer.files.length === 0) return;

                                            // Loading State
                                            submitBtn.disabled = true;
                                            const originalText = submitBtn.innerHTML;
                                            submitBtn.innerHTML = `
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Mengirim...
                                            `;

                                            const formData = new FormData(form);
                                            // Append images manually from DataTransfer
                                            formData.delete('images[]'); // Clear default
                                            Array.from(dataTransfer.files).forEach(file => {
                                                formData.append('images[]', file);
                                            });

                                            try {
                                                const response = await fetch('{{ route('posts.store') }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                });

                                                if (response.ok) {
                                                    const data = await response.json();
                                                    
                                                    // Reset All
                                                    form.reset();
                                                    dataTransfer.items.clear();
                                                    input.files = dataTransfer.files;
                                                    renderPreview();
                                                    
                                                    // Inject New Post (Animation)
                                                    const tempDiv = document.createElement('div');
                                                    tempDiv.innerHTML = data.html;
                                                    const newPost = tempDiv.firstElementChild;
                                                    
                                                    newPost.classList.add('animate-slide-up', 'opacity-0');
                                                    feedContainer.insertBefore(newPost, feedContainer.firstChild);

                                                    // Trigger browser reflow for animation
                                                    requestAnimationFrame(() => {
                                                        newPost.classList.remove('opacity-0');
                                                    });

                                                } else {
                                                    throw new Error('Upload failed');
                                                }
                                            } catch (error) {
                                                console.error(error);
                                                alert('Gagal memposting: ' + (error.message || 'Terjadi kesalahan.'));
                                            } finally {
                                                submitBtn.disabled = false;
                                                submitBtn.innerHTML = originalText;
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="glass-card rounded-3xl p-6 border border-white/5 relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-neon-pink/10 rounded-full blur-2xl"></div>
                            <h4 class="font-bold text-white mb-2">Tips Pro 💡</h4>
                            <ul class="text-xs text-gray-400 space-y-2 list-disc list-inside">
                                <li>Upload foto rasio 1:1 untuk hasil terbaik.</li>
                                <li>Gunakan hashtag #keren.</li>
                                <li>Saling sapa di komentar!</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
