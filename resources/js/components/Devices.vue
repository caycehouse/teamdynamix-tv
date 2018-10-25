<template>
    <div>
        <h5 class="text-warning">Devices in Error ({{ devices.length }})</h5>
        <table class="table table-sm">
            <tbody>
                <tr v-for="{name, status} in devices" :key="name" class="text-danger">
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
    DevicesList: null
  },

  data() {
    return {
      devices: this.DevicesList
    };
  },

  methods: {
    remove(index) {
      this.$delete(this.devices, index);
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
    Echo.channel("BroadcastingModelEvent").listen(".App\\Device", e => {
      if (e.eventType == "created" || e.eventType == "updated") {
        if (e.model.status == "OK") {
          let index = this.findWithAttr(this.devices, "name", e.model.name);
          this.remove(index);
        } else {
          this.devices.push(e.model);
        }
      }
    });
  }
};
</script>
