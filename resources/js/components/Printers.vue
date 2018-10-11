<template>
    <div>
        <h5 class="text-warning">Printers in Error ({{ printers.length }})</h5>
        <table class="table table-sm">
            <tbody>
                <tr v-for="{name, status, print_server} in printers" :key="name" :class="print_server">
                    <td>{{ name }}</td>
                    <td>{{ status }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
  props: {
    PrintersList: null
  },

  data() {
    return {
      printers: this.PrintersList
    };
  },

  mounted() {
    Echo.channel("printers").listen(".PrintersChanged", e => {
      this.printers = e.printer;
    });
  }
};
</script>
