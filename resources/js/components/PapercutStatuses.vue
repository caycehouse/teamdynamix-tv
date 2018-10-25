<template>
    <div>
        <h5 class="text-warning">Papercut Summary</h5>
        <table class="table table-sm">
            <tbody>
                <tr v-for="{status_name, status, status_color} in papercutStatuses" :key="status_name">
                    <td>{{ status_name }}</td>
                    <td :class="status_color">{{ status }}</td>
                </tr>
            </tbody>
        </table>
    </div>
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
    Echo.channel("BroadcastingModelEvent").listen(
      ".App\\PapercutStatuses",
      e => {
        if (e.eventType == "created" || e.eventType == "updated") {
          this.papercutStatuses.push(e.model);
        }
      }
    );
  }
};
</script>
