<template>
    <div class="row justify-content-between">
        <div class="column col-lg-8">
            <ul v-if="cautionMessages">
                <li v-for="(message, index) in cautionMessages" :key="index" class="lead text-danger mb-1">{{message}}</li>
            </ul>

            <!-- メインカラム -->
            <div v-if="isCart" key="existItem">
                <cart-list-item
                    v-for="cart in carts"
                    :cart="cart"
                    :max_qty="max_qty"
                    :key="cart.id"
                    @setError="setError"
                    @updateCart="updateCart"
                />
            </div>
            <div v-else key="notExistItem">
                <p class="text-center">{{templete_messages.not_exist_product_in_cart}}</p>
            </div>
        </div>
        <!-- サイドカラム -->
        <div class="side col-lg-3 bg-white p-4">
            <p>小計({{cartInfo.count}}点)</p>
            <p>{{cartInfo.total}}円 (税込)</p>

            <div v-if="is_login">
                <a href="/order" v-if="isCart && isAvailable" class="btn btn-outline-primary">レジに進む</a>
                <button type="button" class="btn btn-outline-secondary" disabled v-else>レジに進む</button>
            </div>
            <div v-else>
                <a href="/register?reference=cart" v-if="isCart && isAvailable" class="btn btn-outline-primary">会員登録を行って購入する</a>
                <a href="/register" v-else class="btn btn-outline-primary">会員登録を行って購入する</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            cart_list:  this.$props.carts, // 更新用のカート
            isCart: (Object.keys(this.$props.carts).length ? true : false), // カートに商品が入っているかチェック
            cartInfo: {
                count: this.$props.carts_info.count, // カートに入っている商品数をカウント
                total: this.$props.carts_info.total // カートの合計金額
            },
            cautionMessages: this.$props.caution_messages, // エラーメッセージ用配列
            isAvailable: this.$props.carts_info.is_available.result // 購入可能な状態かチェック
        }
    },
    watch: {
        // カート内商品が持っている購入可能フラグを一つ一つチェックして、dataのisAvailableを更新
        cart_list: {
            handler(){
                if(this.cart_list) {
                    for(let key in this.cart_list) {
                        if(!this.cart_list[key].options.isAvailable) {
                            this.isAvailable = false;
                            return false;
                        }
                        this.isAvailable = true;
                    }
                }
            },
            deep: true
        }
    },
    props: {
        carts: Object,
        max_qty: Number,
        caution_messages: Object,
        templete_messages: Object,
        carts_info: Object,
        is_login: Number
    },
    methods: {
        // API実行時のエラー処理
        setError() {
            this.cautionMessages = {...this.cautionMessages, 'update_error': this.$props.templete_messages.update_error}
        },
        // API実行時のコンポーネント更新処理
        updateCart({cart, cartInfo}) {
            this.cartInfo.count = cartInfo.count;
            this.cartInfo.total = cartInfo.total;
            if (cartInfo.count === 0) {
                this.isCart = false;
            }

            if(cart.options.max_qty_caution_message) {
                this.cart_list[cart.rowId].options = {...this.cart_list[cart.rowId].options, isAvailable: false};
            } else {
                this.cart_list[cart.rowId].options = {...this.cart_list[cart.rowId].options, isAvailable: true};
            }
        }
    }
};
</script>

<style></style>