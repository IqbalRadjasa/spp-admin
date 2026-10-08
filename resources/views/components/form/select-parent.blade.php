@props([
    'disabled' => false,
    'value' => null,
    'selected' => null, // Object/Array containing { id, fullname, email, phone }
])

<div x-data="{
    value: '{{ old('parent_id', $value ?? '') }}',
    selectedParent: {{ Js::from($selected) }},
    initTomSelect() {
        this.$nextTick(() => {
            if (typeof TomSelect === 'undefined') {
                console.error('TomSelect is not loaded.');
                return;
            }

            const ts = new TomSelect(this.$refs.select, {
                valueField: 'id',
                labelField: 'fullname',
                searchField: ['fullname', 'phone', 'email'],
                controlInput: '<input class=&quot;focus:outline-none focus:ring-0 border-none p-0 text-sm shadow-none&quot;>',
                load: (query, callback) => {
                    if (!query.length) return callback();
                    fetch('{{ route('students.searchParent') }}?q=' + encodeURIComponent(query))
                        .then(res => res.json())
                        .then(json => callback(json))
                        .catch(() => callback());
                },
                render: {
                    option: (item, escape) => '<div class=&quot;py-1&quot;><div class=&quot;font-medium text-stone-800&quot;>' + escape(item.fullname) + '</div><div class=&quot;text-xs text-stone-500&quot;>Email: ' + escape(item.email || '-') + ' | HP: ' + escape(item.phone || '-') + '</div></div>',
                    item: (item, escape) => '<div>' + escape(item.fullname) + ' (' + escape(item.phone || 'No HP') + ')</div>'
                }
            });

            // If an initial selected parent object is provided, inject it into TomSelect options
            if (this.selectedParent) {
                ts.addOption(this.selectedParent);
            }

            // Set the active value (prioritizes old input after validation error)
            if (this.value) {
                ts.setValue(this.value);
            }
        });
    }
}" x-init="initTomSelect()">
    <select x-ref="select" name="parent_id" placeholder="Cari Orang Tua (Nama / No. HP / Email)..."
        @disabled($disabled)
        {{ $attributes->merge([
            'class' =>
                'block w-full text-sm rounded-md border border-[#91AC67] shadow-sm transition duration-150 ease-in-out focus:outline-none focus:border-[#597928] focus:ring-2 focus:ring-[#597928]/30 focus:ring-offset-1 disabled:opacity-60 disabled:bg-stone-100 disabled:cursor-not-allowed',
        ]) }}>
    </select>
</div>
