<template>
    <div>
        <h5 class="text-warning">Ticket Resolutions ({{ totalAll }})</h5>
        <table class="table table-sm">
            <tbody>
                <tr v-for="{ resolved_by, total } in stats" :key="resolved_by">
                <td>{{ resolved_by }}</td>
                <td>{{ total }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
  props: {
    StatsList: null
  },

  computed: {
    totalAll: function() {
      return this.stats.reduce((acc, cur) => acc + cur.total, 0);
    }
  },

  data() {
    return {
      stats: this.StatsList
    };
  },

  mounted() {
    Echo.channel("stats").listen(".StatsChanged", e => {
      this.stats = e.stat;
    });
  }
};
</script>
