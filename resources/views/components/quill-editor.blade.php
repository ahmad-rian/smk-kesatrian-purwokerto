@props(['wireModel', 'label' => '', 'hint' => '', 'placeholder' => 'Tulis konten di sini...'])

<div>
    @if($label)
        <label class="label">
            <span class="label-text font-medium">{{ $label }}</span>
        </label>
    @endif

    <div
        x-data="{
            content: @entangle($wireModel),
            exec(cmd, val = null) {
                this.$refs.editor.focus();
                document.execCommand(cmd, false, val);
                this.sync();
            },
            sync() {
                let html = this.$refs.editor.innerHTML;
                if (html === '<br>' || html === '<div><br></div>') html = '';
                this.content = html;
            },
            setHeading(tag) {
                this.$refs.editor.focus();
                document.execCommand('formatBlock', false, tag);
                this.sync();
            },
            insertLink() {
                const url = prompt('Masukkan URL:');
                if (url) this.exec('createLink', url);
            },
            insertImage() {
                const url = prompt('Masukkan URL gambar:');
                if (url) this.exec('insertImage', url);
            },
            init() {
                if (this.content) {
                    this.$refs.editor.innerHTML = this.content;
                }
                this.$watch('content', (val) => {
                    if (val !== this.$refs.editor.innerHTML) {
                        this.$refs.editor.innerHTML = val || '';
                    }
                });
            }
        }"
        wire:ignore
    >
        {{-- Toolbar --}}
        <div class="flex flex-wrap items-center gap-1 p-2 bg-base-200 border border-base-300 rounded-t-lg">
            {{-- Heading --}}
            <select x-on:change="setHeading($event.target.value); $event.target.value = ''" class="select select-ghost select-sm w-28 text-sm">
                <option value="" disabled selected>Heading</option>
                <option value="h1">Heading 1</option>
                <option value="h2">Heading 2</option>
                <option value="h3">Heading 3</option>
                <option value="p">Normal</option>
            </select>

            <div class="divider divider-horizontal mx-0.5 h-6"></div>

            {{-- Text Format --}}
            <div class="join">
                <button type="button" x-on:click="exec('bold')" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg>
                </button>
                <button type="button" x-on:click="exec('italic')" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Italic">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
                </button>
                <button type="button" x-on:click="exec('underline')" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg>
                </button>
                <button type="button" x-on:click="exec('strikeThrough')" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Strikethrough">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="4" y1="12" x2="20" y2="12"/><path d="M17.5 7.5c0-2-1.5-3.5-5.5-3.5S6.5 5.5 6.5 7.5c0 4 11 4 11 8 0 2-1.5 3.5-5.5 3.5S6.5 18 6.5 16"/></svg>
                </button>
            </div>

            <div class="divider divider-horizontal mx-0.5 h-6"></div>

            {{-- Lists --}}
            <div class="join">
                <button type="button" x-on:click="exec('insertOrderedList')" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Ordered List">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>
                </button>
                <button type="button" x-on:click="exec('insertUnorderedList')" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Bullet List">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1.5" fill="currentColor"/><circle cx="4" cy="12" r="1.5" fill="currentColor"/><circle cx="4" cy="18" r="1.5" fill="currentColor"/></svg>
                </button>
            </div>

            {{-- Blockquote --}}
            <button type="button" x-on:click="setHeading('blockquote')" class="btn btn-ghost btn-sm tooltip" data-tip="Blockquote">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>
            </button>

            <div class="divider divider-horizontal mx-0.5 h-6"></div>

            {{-- Alignment --}}
            <div class="join">
                <button type="button" x-on:click="exec('justifyLeft')" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Align Left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="18" y2="18"/></svg>
                </button>
                <button type="button" x-on:click="exec('justifyCenter')" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Align Center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                </button>
                <button type="button" x-on:click="exec('justifyRight')" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Align Right">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="9" y1="12" x2="21" y2="12"/><line x1="6" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>

            <div class="divider divider-horizontal mx-0.5 h-6"></div>

            {{-- Link & Image --}}
            <div class="join">
                <button type="button" x-on:click="insertLink()" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Insert Link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                </button>
                <button type="button" x-on:click="insertImage()" class="btn btn-ghost btn-sm join-item tooltip" data-tip="Insert Image">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </button>
            </div>

            <div class="divider divider-horizontal mx-0.5 h-6"></div>

            {{-- Clear --}}
            <button type="button" x-on:click="exec('removeFormat')" class="btn btn-ghost btn-sm tooltip" data-tip="Clear Formatting">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 7h7.5L7 19"/><line x1="1" y1="1" x2="23" y2="23" stroke-width="1.5"/><path d="M13.5 7H20"/></svg>
            </button>
        </div>

        {{-- Editor --}}
        <div
            x-ref="editor"
            x-on:input="sync()"
            x-on:blur="sync()"
            contenteditable="true"
            class="rte-content p-4 bg-base-100 border border-base-300 border-t-0 rounded-b-lg focus:outline-none focus:border-primary prose prose-sm max-w-none"
            data-placeholder="{{ $placeholder }}"
        ></div>
    </div>

    @if($hint)
        <label class="label">
            <span class="label-text-alt text-base-content/60">{{ $hint }}</span>
        </label>
    @endif
</div>
