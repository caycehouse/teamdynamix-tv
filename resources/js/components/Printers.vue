<template>
    <table class="table table-sm">
        <caption>Printers in Error</caption>
          <thead>
            <th>Name</th>
            <th>Status</th>
            <th>Held Jobs</th>
          </thead>
          <tbody>
              <tr v-for="{name, status, held_jobs, print_server} in printers" :key="name" :class="print_server">
                <td>{{ name }}</td>
                <td>{{ status }}</td>
                <td>{{ held_jobs }}</td>
              </tr>
          </tbody>
        </table>
</template>

<script>
    export default {
        props: {
            PrintersList: null
        },

        data(){
            return {
                printers: this.PrintersList,
                printer: '',
            }
        },

        mounted() {
            Echo.channel('printers')
                .listen('.PrintersChanged', (e) => {
                    this.printers = e.printer
                });
        }
    }
</script>
