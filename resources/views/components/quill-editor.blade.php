@props(['wireModel', 'label' => '', 'hint' => '', 'placeholder' => ''])

<div>
    @if($label)
        <label class="label">
            <span class="label-text font-medium">{{ $label }}</span>
        </label>
    @endif

    <div
        x-data="{
            content: @entangle($wireModel),
            quill: null,
            init() {
                this.quill = new Quill(this.$refs.editor, {
                    theme: 'snow',
                    placeholder: '{{ $placeholder }}',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'font': [] }],
                            [{ 'size': ['small', false, 'large', 'huge'] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'script': 'sub'}, { 'script': 'super' }],
                            ['blockquote', 'code-block'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            [{ 'direction': 'rtl' }],
                            [{ 'align': [] }],
                            ['link', 'image', 'video'],
                            ['clean']
                        ]
                    }
                });

                // Set initial content
                if (this.content) {
                    this.quill.root.innerHTML = this.content;
                }

                // Listen for text changes
                this.quill.on('text-change', () => {
                    let html = this.quill.root.innerHTML;
                    // Quill produces <p><br></p> for empty content
                    if (html === '<p><br></p>') {
                        html = '';
                    }
                    this.content = html;
                });

                // Watch for external content changes (e.g. from Livewire)
                this.$watch('content', (value) => {
                    if (value !== this.quill.root.innerHTML) {
                        this.quill.root.innerHTML = value || '';
                    }
                });
            }
        }"
        wire:ignore
    >
        <div x-ref="editor" class="bg-base-100" style="min-height: 250px;"></div>
    </div>

    @if($hint)
        <label class="label">
            <span class="label-text-alt text-base-content/60">{{ $hint }}</span>
        </label>
    @endif
</div>
