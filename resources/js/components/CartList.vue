<template>
    <div class="row justify-content-between">
        <div class="column col-lg-8">
            <p class="lead text-danger mb-1" v-if="caution_message">{{caution_message}}</p>
            <!-- メインカラム -->
            <div v-if="isCart">
                <cart-list-item
                    v-for="cart in carts"
                    :cart="cart"
                    :key="cart.id"
                />
            </div>
            <div v-else>
                <p class="text-center">カートはからっぽです。</p>
            </div>
        </div>
        <!-- サイドカラム -->
        <div class="side col-lg-3 bg-white p-4">
            <p>小計({{cartInfo.count}}点)</p>
            <p>{{cartInfo.total}}円 (税込)</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isCart: Object.keys(this.$props.carts).length ? true : false,
            cartInfo: {
                count: this.$props.carts_info.count,
                total: this.$props.carts_info.total
            }
        }
    },
    props: {
        carts: Object,
        caution_message: String,
        carts_info: Object
    },
};
</script>

<style></style>