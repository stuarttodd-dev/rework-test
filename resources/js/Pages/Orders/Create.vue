<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    products: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    product_uuid: '',
    quantity: 1,
});

const submit = () => {
    form.post(route('orders.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('quantity'),
    });
};
</script>

<template>
    <Head title="Place order" />
    <div class="py-6 px-4">
        <h1 class="text-xl font-semibold mb-4">Place order</h1>

        <p v-if="$page.props.flash?.success" class="mb-4 text-green-600">
            {{ $page.props.flash.success }}
        </p>
        <div v-if="Object.keys(form.errors).length" class="mb-4 text-red-600">
            <ul class="list-disc list-inside">
                <li v-for="(msg, key) in form.errors" :key="key">{{ msg }}</li>
            </ul>
        </div>

        <p class="mb-4">
            <Link :href="route('products.index')">Cancel</Link>
        </p>

        <form @submit.prevent="submit" class="max-w-sm space-y-4">
            <div>
                <label for="product_uuid">Product</label>
                <select
                    id="product_uuid"
                    v-model="form.product_uuid"
                    class="mt-1 block w-full border border-gray-300 px-2 py-1"
                    required
                >
                    <option value="">Select product</option>
                    <option v-for="p in products" :key="p.uuid" :value="p.uuid">
                        {{ p.title }}
                    </option>
                </select>
            </div>

            <div>
                <label for="quantity">Quantity</label>
                <input
                    id="quantity"
                    v-model.number="form.quantity"
                    type="number"
                    min="1"
                    class="mt-1 block w-full border border-gray-300 px-2 py-1"
                />
            </div>

            <div>
                <button type="submit" :disabled="form.processing">Place order</button>
            </div>
        </form>
    </div>
</template>
