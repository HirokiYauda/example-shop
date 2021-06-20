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
                <option v-for="count in max_qty" :key="count" :value="count">
                    {{ count }}
                </option>
            </select>
            <p class="lead text-danger mb-1" v-if="cart.options.stock_info_message">
                {{ cart.options.stock_info_message }}
            </p>
            <p class="lead text-danger mb-1" v-if="max_qty_caution_message">
                {{ max_qty_caution_message }}
            </p>
            <p class="lead text-danger mb-1">
                {{ cart.price ? cart.price + "円" : "" }}
            </p>
            <button type="button" @click="deleteItem" class="btn btn-light">削除</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            max_qty_caution_message: this.$props.cart.options.max_qty_caution_message, // 商品購入可能数超過メッセージ
            qty: this.$props.cart.qty // カート内商品の数量
        }
    },
    props: {
        cart: Object,
        max_qty: Number
    },
    computed: {
        // プルダウン(数量)の処理
        innerSearchText: {
            get() {
                return this.qty;
            },
            set(value) {
                this.quantityUpdate(value);
                this.$emit("change", value);
            }
        },
    },
    methods: {
        // 数量更新処理
        async quantityUpdate(value) {
            try {
                const res = await axios.put('/api/quantity_update/', {quantity: value, row_id: this.$props.cart.rowId});
                // API側の、try, catch を分岐
                if(res.data.result) {
                    this.$emit('updateCart', res.data);
                    this.max_qty_caution_message = res.data.cart.options.max_qty_caution_message;
                    this.qty = value;
                } else {
                    this.$emit('setError');
                }
            // Javascript側でのエラー時
            } catch (error) {
                // const {status, statusText } = error.response;
                // console.log(`Error! HTTP Status: ${status} ${statusText}`);
                this.$emit('setError');
            }
        },
        // カート内商品削除処理
        async deleteItem() {
            try {
                const res = await axios.put('/api/delete_item/', {row_id: this.$props.cart.rowId});
                // API側の、try, catch を分岐
                if(res.data.result) {
                    this.$emit('updateCart', res.data);
                    this.$destroy();
                    this.$el.parentNode.removeChild(this.$el);
                } else {
                    this.$emit('setError');
                }
            // Javascript側でのエラー時
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