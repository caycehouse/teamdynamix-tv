<template>
    <table class="table table-sm">
          <thead>
            <th>Name</th>
            <th>Status</th>
          </thead>
          <tbody>
              <tr v-for="{name, status} in printers" :key="name">
                <td>{{ name }}</td>
                <td>{{ status }}</td>
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