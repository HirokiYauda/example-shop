<template>
    <div>
        <h1 class="h3 text-center mb-4">注文確認</h1>
        <div class="row justify-content-between">
            <div class="column col-lg-8">
                <p class="lead text-danger mb-1" v-show="cautionMessage">{{cautionMessage}}</p>
                <!-- メインカラム -->
                <div key="existItem">

                    <!-- 注文する商品 -->
                    <div class="row justify-content-between bg-white px-2 py-3 mb-3" v-for="cart in carts" :key="cart.id">
                        <div class="col-lg-4 bg-white px-2">
                            <img
                                class="obj-fit"
                                :src="`/images/${cart.options.imgpath}`"
                                alt=""
                            />
                        </div>
                        <div class="col-lg-8 bg-white px-2">
                            <h2>{{ cart.name }}</h2>
                            <p class="lead text-danger mb-1">
                                {{ cart.price ? cart.price + "円" : "" }}
                            </p>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- サイドカラム -->
            <div class="side col-lg-3 bg-white p-4">
                <p>お支払い金額({{cartInfo.count}}点)</p>
                <p>{{cartInfo.total}}円 (税込)</p>

                <p>注文を確定する</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            cartInfo: {
                count: this.$props.carts_info.count,
                total: this.$props.carts_info.total
            },
            cautionMessage: ""
        }
    },
    props: {
        carts: Object,
        carts_info: Object,
    },
    methods: {
    }
};
</script>

<style></style>