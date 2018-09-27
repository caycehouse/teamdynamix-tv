<template>
    <table class="table table-sm">
        <caption>Papercut Statuses</caption>
        <thead>
            <th>System</th>
            <th>Status</th>
        </thead>
        <tbody>
            <tr v-for="{status_name, status, status_color} in papercutStatuses" :key="status_name">
                <td>{{ status_name }}</td>
                <td :class="status_color">{{ status }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
  props: {
    PapercutStatusesList: null
  },

  data() {
    return {
      papercutStatuses: this.PapercutStatusesList
    };
  },

  mounted() {
    Echo.channel("printers").listen(".PrintersChanged", e => {
      this.papercutStatuses = e.papercutStatuses;
    });
  }
};
</script>
