<template>
    <div>
        <ul v-for="{status_name, status} in papercutStatuses" :key="status_name" class="list-unstyled">
            <li class="text-center">{{ status_name }}<br><span class="text-success">{{ status }}</span></li>
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
                papercutStatuses: this.PapercutStatusesList,
                papercutStatus: '',
            }
        },

        mounted() {
            Echo.channel('printers')
                .listen('.PrintersChanged', (e) => {
                    this.papercutStatuses = e.papercutStatus
                });
        }
    }
</script>
