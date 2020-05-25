<template>
    <div>
        <div v-if="loaded">

            <table class="table table-bordered ">
                <thead>
                <tr>
                    <th></th>
                    <th><span style="font-weight:500;font-style:normal">Покупка</span></th>
                    <th><span style="font-weight:500;font-style:normal">Продаж</span></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><span style="font-weight:400;font-style:normal">USD</span></td>
                    <td>{{to_fixed(courses.buy.USD)}}</td>
                    <td>{{to_fixed(courses.sell.USD)}}</td>
                </tr>
                <tr>
                    <td><span style="font-weight:400;font-style:normal">EUR</span></td>
                    <td>{{to_fixed(courses.buy.EUR)}}</td>
                    <td>{{to_fixed(courses.sell.EUR)}}</td>
                </tr>
                <tr>
                    <td><span style="font-weight:400;font-style:normal">RUB</span></td>
                    <td>{{to_fixed(courses.buy.RUB)}}</td>
                    <td>{{to_fixed(courses.sell.RUB)}}</td>
                </tr>
                <tr>
                    <td><span style="font-weight:400;font-style:normal">BTC</span></td>
                    <td>{{to_fixed(courses.buy.BTC)}}</td>
                    <td>{{to_fixed(courses.sell.BTC)}}</td>
                </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-12 my-2">
                    <div data-toggle="buttons" class="w-100 btn-group btn-group-toggle">
                        <label id="buy" class="btn btn-light active">
                            <input
                                v-on:click="changeSorceSellBy('buy')"
                                type="radio" name="action" checked="checked" value="buy">
                            Покупка
                        </label>
                        <label id="sell" class="btn btn-light">
                            <input
                                v-on:click="changeSorceSellBy('sell')"
                                type="radio" name="action" value="sell">
                            Продаж
                        </label>
                    </div>
                </div>

                <div class="col-12 my-2">
                    <div class="w-100 btn-group btn-group-toggle" data-toggle="buttons">
                        <label
                            v-for="(val, key) in buy_or_sell"
                            style="padding: 0.375rem 0.25rem;"
                            class="btn btn-light"
                            v-bind:id="key"
                            v-bind:class="key === converter_cur_key ? 'active' : ''"
                        >
                            <input
                                type="radio"
                                name="curren"
                                v-on:click="setConverterCurKey(key)"
                                v-bind:class="key === converter_cur_key ? 'active' : ''"
                                v-bind:value="val"
                                v-bind:checked="key === converter_cur_key ? 'checked' : ''"
                            >
                            {{key}}
                        </label>
                    </div>
                </div>

                <div class="col-12 my-2">
                    <input
                        type="number"
                        v-model="amount" class="form-control" placeholder="Amount" style="text-align: center">
                </div>

                <div class="my-2 col-12">
                    <div class="row">
                        <div
                            class="col"
                            v-bind:class="key === converter_cur_key ? 'd-none' : ''"
                            style="min-width: 80px;"
                            v-for="(val, key) in buy_or_sell">
                            <p class="m-1 text-center">{{key}}</p>
                            <p class="m1 text-center">{{convert(amount, converter_cur_key, key)}}</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div v-else>
            Loading
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
                amount : 100,
                buy_or_sell : []
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

        },

        methods: {

            convert(amount, from, to){

                // first ve convert to UAH
                amount = amount * this.buy_or_sell[from];

                // convert from UAH to any cyr
                amount = amount / this.buy_or_sell[to];

                return amount.toFixed(2);
            },

            changeSorceSellBy(key){
                this.buy_or_sell = this.courses[key];
            },

            setConverterCurKey(key){
                this.converter_cur_key = key;
            },

            to_fixed(val){
                return parseFloat(val).toFixed(2);
            }

        },


    }
</script>
