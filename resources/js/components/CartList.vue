<template>
    <div>
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
            <p>aaaa</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isCart: Object.keys(this.$props.carts).length ? true : false
        }
    },
    props: {
        carts: Object,
        caution_message: String
    },
};
</script>

<style></style>