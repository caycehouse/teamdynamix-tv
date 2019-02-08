<template>
  <div>
    <h5 class="text-warning">Ticket Resolutions {{ humanizePeriod(period) }} ({{ totalAll }})</h5>
    <table class="table table-sm">
      <tbody>
        <tr v-for="{ name, closes } in listItems" :key="name">
          <td>{{ name }}</td>
          <td>{{ closes }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    ResolutionsList: null,
    period: null
  },

  computed: {
    listItems() {
      return _.orderBy(this.resolutions, "closes", "desc");
    },
    totalAll: function() {
      return this.resolutions.reduce((acc, cur) => acc + cur.closes, 0);
    }
  },

  data() {
    return {
      resolutions: this.ResolutionsList
    };
  },

  methods: {
    humanizePeriod: function(period) {
      if (period == "this_week") {
        return "This Week";
      } else {
        return "Last Week";
      }
    }
  },

  mounted() {
    Echo.channel("BroadcastingModelEvent").listen(".App\\Resolution", e => {
      this.resolutions = e.resolutions;
    });
  }
};
</script>
