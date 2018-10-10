<template>
    <table class="table table-sm">
        <caption class="text-warning">Devices in Error</caption>
          <thead class="text-warning">
            <th>Name</th>
            <th>Status</th>
          </thead>
          <tbody>
              <tr v-for="{name, status} in devices" :key="name" class="text-danger">
                <td>{{ name }}</td>
                <td>{{ status }}</td>
              </tr>
          </tbody>
        </table>
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

  mounted() {
    Echo.channel("devices").listen(".DevicesChanged", e => {
      this.devices = e.device;
    });
  }
};
</script>
