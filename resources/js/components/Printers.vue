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

  methods: {
    remove(index) {
      this.$delete(this.printers, index);
    },
    findWithAttr(array, attr, value) {
      for (var i = 0; i < array.length; i += 1) {
        if (array[i][attr] === value) {
          return i;
        }
      }
      return -1;
    }
  },

  mounted() {
    Echo.channel("BroadcastingModelEvent").listen(".App\\Printer", e => {
      if (e.eventType == "created" || e.eventType == "updated") {
        if (e.model.status == "OK") {
          let index = this.findWithAttr(this.printers, "name", e.model.name);
          this.remove(index);
        } else {
          this.printers.push(e.model);
        }
      }
    });
  }
};
</script>
