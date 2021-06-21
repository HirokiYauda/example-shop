export default {
    filters:{
        // 3桁区切りで、カンマをつけて金額を表示
        priceLocaleString(value){
            if(value) {
                return value.toLocaleString() + "円";
            }
            return "";
        },
    }
};