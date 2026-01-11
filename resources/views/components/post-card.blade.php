@props(['post'])

<!-- Feed Item Component -->
<div x-data="{ 
        liked: {{ $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}, 
        count: {{ $post->likes->count() }},
        editOpen: false,
        deleteOpen: false,
        isEditting: false,
        menuOpen: false,
        toggleLike() {
            this.liked = !this.liked;
            this.count += this.liked ? 1 : -1;
            fetch('{{ route('likes.toggle', $post) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).catch(() => { this.liked = !this.liked; this.count += this.liked ? 1 : -1; });
        },
        share() {
            const url = '{{ $post->user->username ? route('profile.show', $post->user->username) : '#' }}#post-{{ $post->id }}';
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(() => {
                    window.toast('Link berhasil disalin! 🚀');
                }).catch(() => {
                    this.fallbackCopy(url);
                });
            } else {
                this.fallbackCopy(url);
            }
        },
        fallbackCopy(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed'; // Avoid scrolling
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                window.toast('Link berhasil disalin! 🚀');
            } catch (err) {
                window.toast('Gagal menyalin link', 'error');
            }
            document.body.removeChild(textArea);
        },
        commentContent: '',
        async submitComment() {
            if (!this.commentContent.trim()) return;
            const list = document.getElementById('comment-list-{{ $post->id }}');
            const tempId = 'temp-' + Date.now();
            // Optimistic Comment UI
            list.insertAdjacentHTML('beforeend', `
                <div id='${tempId}' class='flex space-x-3 text-sm opacity-70 animate-pulse'>
                    <div class='flex-shrink-0'><div class='h-6 w-6 rounded-full bg-gray-600'></div></div>
                    <div class='flex-1'><div class='bg-gray-800 rounded-2xl px-4 py-2'><p class='text-gray-300'>${this.commentContent}</p></div></div>
                </div>`);
            const payload = this.commentContent;
            this.commentContent = ''; 
            try {
                const res = await fetch('{{ route('comments.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ post_id: {{ $post->id }}, content: payload })
                });
                const html = await res.text();
                document.getElementById(tempId).outerHTML = html;
            } catch (e) { 
                document.getElementById(tempId).remove(); 
                this.commentContent = payload; 
                window.toast('Gagal mengirim komentar', 'error');
            }
        },
        async deletePost() {
            if(!confirm('Yakin ingin menghapus postingan ini?')) return;
            try {
                const res = await fetch('{{ route('posts.destroy', $post) }}', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if(res.ok) { 
                    $el.remove(); 
                    window.toast('Postingan dihapus');
                }
            } catch(e) { window.toast('Gagal menghapus', 'error'); }
        }
    }" 
    id="post-card-{{ $post->id }}"
    class="glass-card rounded-[2.5rem] p-6 mb-8 relative overflow-visible transition-all duration-500 hover:shadow-2xl hover:shadow-neon-blue/5 border border-white/5 group bg-midnight-800/40 backdrop-blur-md">

    <!-- Header: User, Time & Menu -->
    <div class="flex justify-between items-start mb-5 px-2 relative">
        <div class="flex gap-4 items-center">
            <a href="{{ $post->user->username ? route('profile.show', $post->user->username) : '#' }}" class="relative group/avatar">
                <div class="absolute inset-0 bg-gradient-to-tr from-neon-blue to-neon-purple rounded-full blur opacity-50 group-hover/avatar:opacity-100 transition duration-500"></div>
                @if($post->user->profile_photo)
                    <img src="{{ $post->user->profile_photo }}" loading="lazy" class="h-12 w-12 rounded-full object-cover ring-2 ring-white/10 relative z-10 group-hover/avatar:ring-white/30 transition">
                @else
                    <div class="h-12 w-12 rounded-full bg-midnight-900 flex items-center justify-center text-white font-bold ring-2 ring-white/10 relative z-10">
                        {{ substr($post->user->name, 0, 1) }}
                    </div>
                @endif
            </a>
            <div class="flex flex-col">
                <a href="{{ $post->user->username ? route('profile.show', $post->user->username) : '#' }}" class="font-bold text-white text-lg hover:text-neon-blue transition leading-tight line-clamp-1 max-w-[140px] sm:max-w-[200px]" title="{{ $post->user->name }}">
                    {{ $post->user->name }}
                </a>
                <span class="text-gray-500 text-xs font-medium flex items-center gap-1">
                    <span>{{ '@' . ($post->user->username ?? Str::slug($post->user->name)) }}</span>
                    <!-- Mobile Timestamp -->
                    <span class="sm:hidden text-white/20">•</span>
                    <span class="sm:hidden text-gray-400">{{ $post->created_at->diffForHumans(null, true, true) }}</span>
                </span>
            </div>
        </div>

        <!-- Centered Metadata (Time & Status) - Hidden on Mobile, Visible on Desktop -->
        <div class="hidden sm:flex absolute left-1/2 -translate-x-1/2 top-1">
            <div class="px-3 py-1 rounded-full bg-white/5 border border-white/10 backdrop-blur-md flex items-center gap-2 shadow-lg hover:bg-white/10 transition cursor-help" title="{{ $post->created_at->format('d M Y H:i') }}">
                @if($post->created_at->diffInMinutes() < 30)
                    <span class="flex h-1.5 w-1.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-neon-pink opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-neon-pink"></span>
                    </span>
                    <span class="text-[9px] font-bold text-neon-pink tracking-wider uppercase">BARU</span>
                    <span class="text-white/20 text-[9px]">•</span>
                @endif
                <span class="text-[10px] font-mono text-gray-400">
                    {{ $post->created_at->diffForHumans(null, true, true) }}
                </span>
            </div>
        </div>

        <!-- 3-Dot Menu -->
        @if(Auth::id() === $post->user_id)
            <div class="relative" @click.outside="menuOpen = false">
                <button @click="menuOpen = !menuOpen" class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-full transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                </button>
                
                <div x-show="menuOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     style="display: none;"
                     class="absolute right-0 mt-2 w-48 bg-midnight-900/90 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden">
                    <button @click="editOpen = true; menuOpen = false" class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:bg-white/10 hover:text-white transition flex items-center gap-3">
                        <svg class="w-4 h-4 text-neon-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        Edit Postingan
                    </button>
                    <button @click="deletePost(); menuOpen = false" class="w-full text-left px-4 py-3 text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 transition flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Hapus Permanen
                    </button>
                </div>
            </div>

            <!-- Edit Modal (Portal to Body for z-index) -->
            <template x-teleport="body">
                <div x-show="editOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
                    <!-- Removed @click="editOpen = false" to prevent accidental close -->
                    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
                    <div class="relative bg-midnight-800 border border-white/10 rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden animate-slide-up max-h-[90vh] overflow-y-auto">
                        <div class="p-6" x-data="{ 
                            newPreviewImages: [], 
                            imageFiles: [],
                            addFiles(e) {
                                const files = Array.from(e.target.files);
                                files.forEach(file => {
                                    this.imageFiles.push(file);
                                    const reader = new FileReader();
                                    reader.onload = e => this.newPreviewImages.push(e.target.result);
                                    reader.readAsDataURL(file);
                                });
                                e.target.value = ''; // Reset input
                            },
                            removeFile(index) {
                                this.imageFiles.splice(index, 1);
                                this.newPreviewImages.splice(index, 1);
                            }
                        }">
                            <h3 class="text-xl font-bold text-white mb-4">Edit Postingan</h3>
                            <form @submit.prevent="
                                const formData = new FormData($event.target);
                                // Remove auto-captured images and append from array
                                formData.delete('images[]');
                                imageFiles.forEach(file => formData.append('images[]', file));

                                const submitBtn = $el.querySelector('button[type=submit]');
                                const originalText = submitBtn.innerText;
                                
                                submitBtn.disabled = true;
                                submitBtn.innerText = 'Menyimpan...';

                                fetch('{{ route('posts.update', $post) }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: { 'Accept': 'application/json' }
                                })
                                .then(async response => {
                                    const data = await response.json();
                                    if (!response.ok) {
                                        throw new Error(data.message || Object.values(data.errors).flat().join('\n'));
                                    }
                                    return data;
                                })
                                .then(data => {
                                    document.getElementById('post-card-{{ $post->id }}').outerHTML = data.html; 
                                    editOpen = false;
                                    window.toast('Postingan berhasil diperbarui! ✨');
                                })
                                .catch(error => {
                                    console.error(error);
                                    window.toast('Gagal update: ' + error.message, 'error');
                                })
                                .finally(() => {
                                    submitBtn.disabled = false;
                                    submitBtn.innerText = originalText;
                                });
                            ">
                                @method('PUT')
                                @csrf
                                <!-- Content -->
                                <textarea name="content" class="w-full bg-black/30 border border-white/10 rounded-xl p-4 text-gray-200 mb-4 focus:ring-1 focus:ring-neon-blue resize-none" rows="4">{{ $post->content }}</textarea>
                                
                                <!-- Existing Images -->
                                @if($post->images->count() > 0)
                                    <div class="mb-4">
                                        <label class="text-xs text-gray-500 mb-2 block">Foto Saat Ini (Pilih untuk hapus)</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            @foreach($post->images as $img)
                                                <div class="relative group cursor-pointer" x-data="{ marked: false }">
                                                    
                                                    <!-- Active State -->
                                                    <div x-show="!marked" class="relative group/item aspect-square rounded-lg overflow-hidden transition-all duration-300">
                                                        <img src="{{ $img->image_path }}" class="w-full h-full object-cover">
                                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/item:opacity-100 transition flex items-center justify-center backdrop-blur-[1px]" 
                                                             @click="marked = true">
                                                            <div class="bg-red-500 p-2 rounded-full text-white shadow-lg transform hover:scale-110 transition pointer-events-none">
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Deleted / Undo State -->
                                                    <div x-show="marked" style="display: none;" class="aspect-square rounded-lg border border-red-500/30 bg-red-500/10 flex flex-col items-center justify-center p-2 text-center relative gap-2">
                                                        <span class="text-[10px] text-red-300 font-bold uppercase tracking-wider">Terhapus</span>
                                                        <button type="button" @click="marked = false" class="text-xs bg-red-500/20 hover:bg-red-500 hover:text-white text-red-300 px-2 py-1 rounded transition">
                                                            Batal
                                                        </button>
                                                    </div>

                                                    <input type="hidden" name="remove_images[]" value="{{ $img->id }}" :disabled="!marked">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Add New Images (Modern UI) -->
                                <div class="mb-6">
                                    <label class="block text-xs text-gray-500 mb-2 font-medium uppercase tracking-wider">Tambah Foto Baru</label>
                                    
                                    <div class="space-y-4">
                                        <!-- Custom File Button -->
                                        <div class="relative group">
                                            <label for="edit-images-{{ $post->id }}" 
                                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-white/20 rounded-2xl cursor-pointer hover:border-neon-blue hover:bg-neon-blue/5 transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(0,243,255,0.1)]">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <div class="p-3 rounded-full bg-white/5 mb-3 group-hover:bg-neon-blue/20 group-hover:scale-110 transition-transform duration-300">
                                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-neon-blue transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                    </div>
                                                    <p class="mb-1 text-sm text-gray-400 font-medium group-hover:text-white transition-colors">Tambah Foto</p>
                                                    <p class="text-xs text-gray-500">Klik untuk memilih (Max 2MB)</p>
                                                </div>
                                                <input id="edit-images-{{ $post->id }}" type="file" multiple accept="image/*" class="hidden"
                                                    @change="addFiles($event)">
                                            </label>
                                        </div>
                                        
                                        <!-- Preview Grid -->
                                        <div class="grid grid-cols-3 gap-3" x-show="newPreviewImages.length > 0">
                                            <template x-for="(img, index) in newPreviewImages" :key="index">
                                                <div class="relative aspect-square rounded-xl overflow-hidden border border-white/10 group/preview shadow-lg">
                                                    <img :src="img" class="w-full h-full object-cover">
                                                    <!-- Remove Button -->
                                                    <button type="button" @click="removeFile(index)"
                                                            class="absolute top-1 right-1 bg-black/60 hover:bg-red-500 text-white p-1 rounded-full transition opacity-0 group-hover/preview:opacity-100">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/preview:opacity-100 transition flex items-center justify-center pointer-events-none">
                                                        <!-- Badge handled by button hover/style -->
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-4 border-t border-white/5">
                                    <button type="button" @click="editOpen = false" class="px-4 py-2 rounded-full text-sm font-bold text-gray-400 hover:text-white transition bg-transparent hover:bg-white/5">Batal</button>
                                    <button type="submit" class="px-6 py-2 rounded-full bg-neon-blue text-white text-sm font-bold hover:bg-neon-blue/80 transition shadow-lg shadow-neon-blue/20">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </template>
        @endif
    </div>

    <!-- Post Content -->
    <div class="prose prose-invert max-w-none mb-6 px-2">
        <p class="text-gray-200 text-[1.05rem] leading-relaxed whitespace-pre-line font-light tracking-wide">{{ $post->content }}</p>
    </div>

    <!-- Image Carousel (Refined) -->
    @if($post->images->count() > 0)
        <div x-data="{ 
                activeSlide: 0, 
                slides: {{ $post->images->count() }},
                next() { this.activeSlide = (this.activeSlide + 1) % this.slides },
                prev() { this.activeSlide = (this.activeSlide - 1 + this.slides) % this.slides },
            }" class="mb-6 relative rounded-[2rem] overflow-hidden group/carousel ring-1 ring-white/10 bg-black aspect-[4/3] shadow-2xl">
            
            <div class="relative w-full h-full">
                @foreach($post->images as $index => $image)
                    <div x-show="activeSlide === {{ $index }}" 
                        x-transition:enter="transition transform duration-500 ease-out"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="absolute inset-0 flex items-center justify-center bg-black">
                        <img src="{{ $image->image_path }}" 
                                class="w-full h-full object-cover cursor-zoom-in hover:scale-105 transition duration-[2s]"
                                @click="lightboxImages = {{ $post->images->pluck('image_path') }}; lightboxIndex = {{ $index }}; lightboxOpen = true">
                    </div>
                @endforeach
            </div>

            <!-- Controls -->
            <div x-show="slides > 1" class="absolute inset-0 flex items-center justify-between px-4 pointer-events-none">
                <button @click.stop="prev()" class="pointer-events-auto bg-black/40 hover:bg-black/70 text-white p-2.5 rounded-full backdrop-blur-md transition-all shadow-lg transform active:scale-95 group-hover/carousel:opacity-100 opacity-0 border border-white/10">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button @click.stop="next()" class="pointer-events-auto bg-black/40 hover:bg-black/70 text-white p-2.5 rounded-full backdrop-blur-md transition-all shadow-lg transform active:scale-95 group-hover/carousel:opacity-100 opacity-0 border border-white/10">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>

            <!-- Dots -->
            <div x-show="slides > 1" class="absolute bottom-5 left-0 right-0 flex justify-center gap-2 z-10 pointer-events-none">
                <template x-for="i in slides">
                    <div class="transition-all duration-300 rounded-full shadow-lg border border-black/10" 
                            :class="activeSlide === i - 1 ? 'bg-white w-2.5 h-2.5' : 'bg-white/40 w-1.5 h-1.5'"></div>
                </template>
            </div>
        </div>
    @endif

    <!-- Action Bar -->
    <div class="flex items-center gap-6 border-t border-white/5 pt-4 px-2">
        <button class="flex items-center gap-2 text-gray-400 hover:text-neon-blue transition group" onclick="document.getElementById('comment-{{ $post->id }}').classList.toggle('hidden')">
            <div class="p-2.5 rounded-full bg-white/5 group-hover:bg-neon-blue/10 transition ring-1 ring-white/5 group-hover:ring-neon-blue/30">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            </div>
            <span class="text-sm font-bold">{{ $post->comments->count() }}</span>
        </button>

        <button @click="toggleLike()" class="flex items-center gap-2 transition group" :class="liked ? 'text-neon-pink' : 'text-gray-400 hover:text-neon-pink'">
            <div class="p-2.5 rounded-full bg-white/5 transition relative transform active:scale-125 duration-200 ring-1 ring-white/5" :class="liked ? 'bg-neon-pink/10 ring-neon-pink/30' : 'group-hover:bg-neon-pink/10 group-hover:ring-neon-pink/30'">
                <svg class="w-5 h-5 transition-all duration-300" :class="liked ? 'fill-current scale-110 drop-shadow-glow' : 'fill-none'" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <span class="text-sm font-bold" x-text="count"></span>
        </button>
        
        <div class="flex-1"></div>
        
        <button @click="share()" class="text-gray-500 hover:text-white transition group relative" title="Bagikan">
            <div class="p-2.5 rounded-full group-hover:bg-white/10 transition ring-1 ring-transparent group-hover:ring-white/10">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
            </div>
        </button>
    </div>

    <!-- Comments -->
    <div id="comment-{{ $post->id }}" class="hidden mt-6 pt-6 border-t border-white/5 animate-slide-up px-2">
        <form @submit.prevent="submitComment()" class="mb-6 relative">
            <input type="text" x-model="commentContent" placeholder="Ketik balasan..." class="w-full bg-midnight-900/50 border border-white/10 rounded-full py-3 pl-5 pr-24 text-sm focus:ring-1 focus:ring-neon-blue focus:border-neon-blue transition-all shadow-inner" required>
            <button type="submit" class="absolute right-1 top-1 bottom-1 bg-white/5 hover:bg-neon-blue hover:text-white text-gray-400 rounded-full px-5 text-xs font-bold transition">Kirim</button>
        </form>

        <div class="space-y-4 pl-0" id="comment-list-{{ $post->id }}">
            @foreach($post->comments->where('parent_id', null) as $comment)
                @include('components.comment-item', ['comment' => $comment])
            @endforeach
        </div>
    </div>
</div>
