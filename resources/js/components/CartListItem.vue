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
            <button type="button" @click="deleteItem" class="btn btn-light">削除</button>
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
                this.quantityUpdate(value);
                this.$emit("change", value);
            }
        },
    },
    methods: {
        async quantityUpdate(value) {
            try {
                const res = await axios.put('/api/quantity_update/', {quantity: value, row_id: this.$props.cart.rowId});
                this.$emit('updateCart', res.data);
            } catch (error) {
                // const {status, statusText } = error.response;
                // console.log(`Error! HTTP Status: ${status} ${statusText}`);
                this.$emit('setError');
            }
        },
        async deleteItem() {
            try {
                const res = await axios.put('/api/delete_item/', {row_id: this.$props.cart.rowId});
                this.$emit('updateCart', res.data);
                this.$destroy();
                this.$el.parentNode.removeChild(this.$el);
            } catch (error) {
                // const {status, statusText } = error.response;
                // console.log(`Error! HTTP Status: ${status} ${statusText}`);
                this.$emit('setError');
            }
        }
    }
};
</script>

<style></style>