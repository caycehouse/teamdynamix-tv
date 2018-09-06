<template>
    <table class="table table-sm">
        <thead>
        <th>Tech Name</th>
        <th>Resolved Tickets</th>
        </thead>
        <tbody>
            <tr v-for="{ resolved_by, total } in stats" :key="resolved_by">
            <td>{{ resolved_by }}</td>
            <td>{{ total }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
  props: {
    StatsList: null
  },

  data() {
    return {
      stats: this.StatsList
    };
  },

  mounted() {
    Echo.channel("stats").listen(".StatsChanged", e => {
      this.stats = e;
    });
  }
};
</script>