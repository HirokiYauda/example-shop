<template>
    <div class="row justify-content-between bg-white px-2 py-3 mb-3">
        <div class="col-lg-4 bg-white px-2">
            <img
                class="obj-fit"
                :src="`/images/${cart.options.imgpath}`"
                alt=""
            />
        </div>
        <div class="col-lg-8 bg-white px-2">
            <h2>{{ cart.name }}</h2>
            <select
                name="qty"
                v-model="innerSearchText"
                class="form-select form-select-sm"
                aria-label=".form-select-sm example"
            >
                <option v-for="count in 10" :key="count" :value="count">
                    {{ count }}
                </option>
            </select>
            <p class="lead text-danger mb-1">
                {{ cart.price ? cart.price + "円" : "" }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        cart: Object,
    },
    computed: {
        innerSearchText: {
            get() {
                return this.$props.cart.qty;
            },
            set(value) {
                this.$emit("change", value);
            }
        },
    },
};
</script>

<style></style>