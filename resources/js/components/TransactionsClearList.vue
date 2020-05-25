<template>
    <div class="container">

        <button v-on:click="deleteAllTransaction()" class="btn btn-danger mt-2 w-100">Очистити список</button>

    </div>
</template>

<script>
    export default {

        mounted() {
            console.log('Component mounted.');
        },

        methods: {
            deleteAllTransaction() {

                axios.post('/transactions/truncate', {
                    id: 0
                }).then(()=>{
                    window.location.reload();
                }).catch(function (error) {
                    console.log(error);
                });
            },
        }

    }
</script>
