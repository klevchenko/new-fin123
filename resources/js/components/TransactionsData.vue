<template>
    <div>

        <div class="row">
            <div class="col-6 col-lg-3">
                <div class="btn btn-light my-2 d-block">{{uah_total}} ₴</div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="btn btn-light my-2 d-block">{{usd_total}} $</div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="btn btn-dark my-2 d-block">Загалом {{total_sum_uah}} грн.</div>
            </div>
        </div>

        <form @submit.prevent="addTransaction">
            <div class="form-row">
                <div class="col-6 my-2">
                    <input type="number"
                           v-model="amount"
                           @keypress="isNumber($event)"
                           required="required"
                           name="amount"
                           class="form-control"
                           placeholder="Amount"
                           autocomplete="off"
                           spellcheck="false"
                    />
                </div>
                <div class="col-6 my-2">
                    <div class="currency_tgl">
                        <input type="radio" name="currency" value="uah" id="uah" checked="checked">
                        <label for="uah">UAH</label>
                    </div>
                    <div class="currency_tgl">
                        <input type="radio" value="usd" name="currency" id="usd">
                        <label for="usd">USD</label>
                    </div>
                </div>

                <div class="col-12 my-2 btn-group" role="group">
                    <input type="submit" @click="action = true"  class="btn btn-primary" value="+">
                    <input type="submit" @click="action = false" class="btn btn-danger" value="-">
                </div>

            </div>
        </form>

        <ul class="list-group mt-4">
            <li
                v-if="loading === false && transactions.length > 0"
                v-for="tr in transactions"
                class="list-group-item d-flex justify-content-between align-items-center">
                <span style="text-transform: uppercase">
                    {{tr.amount}} {{tr.currency}}
                    <span v-if="tr.currency === 'usd'">
                         -> {{tr.uah_amount}} UAH
                    </span>
                </span>

                <span v-on:click="deleteTransaction(tr.id)" class="badge badge-danger badge-pill">del</span>
            </li>
            <li
                v-else
                class="list-group-item d-flex justify-content-between align-items-center">
                <p style="text-align: center">---///---</p>
            </li>
        </ul>

        <div class="row" v-if="loading === false && transactions.length > 0">
            <div class="col-12">
                <button v-on:click="deleteTransaction(0)" class="btn btn-danger mt-2 w-100">Очистити список</button>
            </div>
        </div>

    </div>
</template>

<script>
    export default {

        data() {
            return {
                transactions: [],
                total_sum_uah : 0,
                uah_total : 0,
                usd_total : 0,
                loading: true,
                action: true,

                curren : 'uah',
                amount : '',
            }
        },

        mounted() {
            console.log('Component mounted.');
            this.loadTransactions();
        },

        methods: {
            addTransaction(submitEvent) {

                this.amount = submitEvent.target.elements.amount.value;
                this.curren = submitEvent.target.elements.currency.value;

                if(this.action === false ){
                    this.amount = this.amount * -1;
                }

                axios.post('/transactions/add', {
                    amount: this.amount,
                    currency: this.curren
                }).then(()=>{
                    this.loadTransactions();
                    this.amount = "";
                }).catch(function (error) {
                    console.log(error);
                });
            },
            loadTransactions(){
                axios.get('/transactions/all')
                    .then((response)=>{
                        this.transactions = response.data.transactions;
                        this.total_sum_uah = response.data.total_sum_uah;
                        this.usd_total = response.data.usd_total;
                        this.uah_total = response.data.uah_total;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => this.loading = false);
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },

            deleteTransaction(id = 0) {

                id = (parseInt(id)) ? id : 0;

                console.log(id);

                axios.post('/transactions/truncate', {
                    id: id
                }).then(()=>{
                    this.loadTransactions();
                    this.amount = "";
                }).catch(function (error) {
                    console.log(error);
                });
            },

        },

    }
</script>
