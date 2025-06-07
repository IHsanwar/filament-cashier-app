<x-filament::button
    color="primary"
    wire:click.prevent="$dispatch('calculate-total')"
>
    Hitung Total
</x-filament::button>

<script>
    document.addEventListener('alpine:init', () => {
        window.Alpine.data('calculateTotal', () => ({
            init() {
                window.addEventListener('calculate-total', () => {
                    let items = @json($get('items'));
                    let total = items.reduce((sum, item) => sum + (item.subtotal ?? 0), 0);
                    $set('total', total);
                });
            }
        }))
    });
</script>
