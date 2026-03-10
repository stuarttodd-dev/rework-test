<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    products: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <Head title="Products" />
    <div class="py-6 px-4">
        <h1 class="text-xl font-semibold mb-4">Products</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr class="border-b border-gray-300 bg-gray-100">
                        <th class="px-4 py-2 text-left">Product</th>
                        <th class="px-4 py-2 text-left">Total quantity</th>
                        <th class="px-4 py-2 text-left">Allocated to orders</th>
                        <th class="px-4 py-2 text-left">Physical quantity</th>
                        <th class="px-4 py-2 text-left">Total threshold</th>
                        <th class="px-4 py-2 text-left">Immediate despatch</th>
                        <th class="px-4 py-2 text-left">Warehouse breakdown</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="product in products"
                        :key="product.uuid"
                        class="border-b border-gray-200"
                    >
                        <td class="px-4 py-2">{{ product.title }}</td>
                        <td class="px-4 py-2">{{ product.total_quantity }}</td>
                        <td class="px-4 py-2">{{ product.allocated_to_orders }}</td>
                        <td class="px-4 py-2">{{ product.physical_quantity }}</td>
                        <td class="px-4 py-2">{{ product.total_threshold }}</td>
                        <td class="px-4 py-2">{{ product.immediate_despatch }}</td>
                        <td class="px-4 py-2">
                            <span
                                v-for="(row, i) in product.warehouse_breakdown"
                                :key="i"
                                class="block"
                            >
                                {{ row.warehouse_name }}: {{ row.quantity }}
                            </span>
                            <span v-if="product.warehouse_breakdown.length === 0">—</span>
                        </td>
                    </tr>
                    <tr v-if="products.length === 0">
                        <td colspan="7" class="px-4 py-4 text-gray-500">No products.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
