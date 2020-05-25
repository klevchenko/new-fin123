<template>
    <div class="container">
        <form @submit.prevent="addTransaction">
            <div class="form-row">
                <div class="col-12 col-lg-7">
                    <input type="number" name="amount" class="form-control" placeholder="Amount">
                </div>
                <div class="col-6 col-lg-3">

                    <div class="btn-group currency-toggler">
                        <input type="radio" name="currency" class="d-none" value="uah" id="uah" checked="checked">
                        <label for="uah" type="button" class="btn btn-secondary">
                            UAH
                        </label>
                        <input type="radio" value="usd" name="currency" class="d-none" id="usd">
                        <label for="usd" type="button" class="btn btn-secondary">
                            USD
                        </label>
                    </div>

                </div>
                <div class="col-6 col-lg-2">
                    <button type="submit" class="btn btn-primary btn-block">Add</button>
                </div>

            </div>
        </form>

    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component add-transaction-component mounted.')
        },

        methods: {
            addTransaction(submitEvent) {

                let amount = submitEvent.target.elements.amount.value;
                let curren = submitEvent.target.elements.currency.value;

                axios.post('/transactions/add', {
                    amount: amount,
                    currency: curren
                }).then(function (response) {
                    console.log(response);
                }).catch(function (error) {
                    console.log(error);
                });
            }
        }
    }
</script>
