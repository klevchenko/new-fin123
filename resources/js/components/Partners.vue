<template>
    <div>
        <div v-if="loaded">

            <p>{{travelpayouts}} руб. / {{convert(travelpayouts, 'RUB', 'UAH')}} грн.</p>

            <p>{{yandex_partner}}</p>

        </div>
        <div v-else>
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                converter_cur_key: 'USD',
                loaded : false,
                courses : 0,
                buy_or_sell : [],

                travelpayouts : 0,
                yandex_partner : 0,

            }
        },

        mounted() {
            axios.get('/courses').then((resp)=>{
                this.courses = resp.data;
                this.buy_or_sell = resp.data.buy;
                this.loaded = true;
            }).catch(function (error) {
                console.log(error);
            });

            axios.get('/partner/travelpayouts/get').then((resp)=>{
                this.travelpayouts = resp.data;
                this.loaded = true;
            }).catch(function (error) {
                console.log(error);
            });

            axios.get('/partner/yandex-partner/get').then((resp)=>{
                this.yandex_partner = resp.data;
                this.loaded = true;
            }).catch(function (error) {
                console.log(error);
            });

        },

        methods: {

            convert(amount, from, to){

                // first ve convert to UAH
                amount = amount * this.buy_or_sell[from];

                // convert from UAH to any cyr
                amount = amount / this.buy_or_sell[to];

                return amount.toFixed(2);
            },

        },


    }
</script>
