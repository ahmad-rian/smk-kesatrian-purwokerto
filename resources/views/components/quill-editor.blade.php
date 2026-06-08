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
                        toolbar: this.$refs.toolbar
                    }
                });

                if (this.content) {
                    this.quill.root.innerHTML = this.content;
                }

                this.quill.on('text-change', () => {
                    let html = this.quill.root.innerHTML;
                    if (html === '<p><br></p>') html = '';
                    this.content = html;
                });

                this.$watch('content', (value) => {
                    if (value !== this.quill.root.innerHTML) {
                        this.quill.root.innerHTML = value || '';
                    }
                });
            }
        }"
        wire:ignore
        class="quill-wrapper"
    >
        {{-- Custom Toolbar --}}
        <div x-ref="toolbar" class="quill-custom-toolbar">
            <span class="ql-formats">
                <select class="ql-header">
                    <option value="1">Heading 1</option>
                    <option value="2">Heading 2</option>
                    <option value="3">Heading 3</option>
                    <option selected>Normal</option>
                </select>
            </span>
            <span class="ql-formats">
                <button class="ql-bold" title="Bold"></button>
                <button class="ql-italic" title="Italic"></button>
                <button class="ql-underline" title="Underline"></button>
                <button class="ql-strike" title="Strikethrough"></button>
            </span>
            <span class="ql-formats">
                <select class="ql-color" title="Text Color"></select>
                <select class="ql-background" title="Background Color"></select>
            </span>
            <span class="ql-formats">
                <button class="ql-list" value="ordered" title="Ordered List"></button>
                <button class="ql-list" value="bullet" title="Bullet List"></button>
                <button class="ql-indent" value="-1" title="Decrease Indent"></button>
                <button class="ql-indent" value="+1" title="Increase Indent"></button>
            </span>
            <span class="ql-formats">
                <select class="ql-align" title="Alignment"></select>
            </span>
            <span class="ql-formats">
                <button class="ql-blockquote" title="Blockquote"></button>
                <button class="ql-code-block" title="Code Block"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-link" title="Insert Link"></button>
                <button class="ql-image" title="Insert Image"></button>
                <button class="ql-video" title="Insert Video"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-clean" title="Clear Formatting"></button>
            </span>
        </div>

        {{-- Editor Area --}}
        <div x-ref="editor"></div>
    </div>

    @if($hint)
        <label class="label">
            <span class="label-text-alt text-base-content/60">{{ $hint }}</span>
        </label>
    @endif
</div>
