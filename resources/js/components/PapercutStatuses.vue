<template>
    <div class="row">
        <ul v-for="{status_name, status, status_color} in papercutStatuses" :key="status_name" class="col list-unstyled">
            <li class="text-center">{{ status_name }}<br><span :class="status_color">{{ status }}</span></li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            PapercutStatusesList: null
        },

        data(){
            return {
                papercutStatuses: this.PapercutStatusesList
            }
        },

        mounted() {
            Echo.channel('printers')
                .listen('.PrintersChanged', (e) => {
                    this.papercutStatuses = e.papercutStatuses
                });
        }
    }
</script>
