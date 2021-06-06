<template>
    <div class="row justify-content-between">
        <div class="column col-lg-8">
            <p class="lead text-danger mb-1" v-show="cautionMessage">{{cautionMessage}}</p>
            <!-- メインカラム -->
            <div v-if="isCart" key="existItem">
                <cart-list-item
                    v-for="cart in carts"
                    :cart="cart"
                    :key="cart.id"
                    @setError="setError"
                    @updateCart="updateCart"
                />
            </div>
            <div v-else key="notExistItem">
                <p class="text-center">カートはからっぽです。</p>
            </div>
        </div>
        <!-- サイドカラム -->
        <div class="side col-lg-3 bg-white p-4">
            <p>小計({{cartInfo.count}}点)</p>
            <p>{{cartInfo.total}}円 (税込)</p>

            <div v-if="is_login">
                <a href="/order" v-if="isCart" class="btn btn-outline-primary">レジに進む</a>
                <button type="button" class="btn btn-outline-secondary" disabled v-else>レジに進む</button>
            </div>
            <div v-else>
                <a href="/register?reference=cart" v-if="isCart" class="btn btn-outline-primary">会員登録を行って購入する</a>
                <a href="/register" v-else class="btn btn-outline-primary">会員登録を行って購入する</a>
            </div>
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
            },
            cautionMessage: this.$props.caution_message
        }
    },
    props: {
        carts: Object,
        caution_message: String,
        carts_info: Object,
        is_login: Number
    },
    methods: {
        setError() {
            this.cautionMessage = this.$props.carts_info.update_error_message;
        },
        updateCart(cartInfo) {
            this.cartInfo.count = cartInfo.count;
            this.cartInfo.total = cartInfo.total;
            if (cartInfo.count === 0) {
                this.isCart = false;
            }
        }
    }
};
</script>

<style></style>