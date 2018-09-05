<template>
    <div class="jumbotron">

    </div>
</template>

<script>
    export default {
        props: {
            StatsList: null
        },

        data(){
            return {
                stats: this.StatsList
            }
        },

        mounted() {
            Echo.channel('stats')
                .listen('.StatsChanged', (e) => {
                    this.stats = e
                });
        }
    }
</script>