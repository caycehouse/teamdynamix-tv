<template>
  <div>
    <h5 class="text-warning">Ticket Resolutions This Week ({{ totalAll }})</h5>
    <table class="table table-sm">
      <tbody>
        <tr v-for="{ name, closes } in resolutions" :key="name">
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
    totalAll: function() {
      return this.resolutions.reduce((acc, cur) => acc + cur.closes, 0);
    }
  },

  data() {
    return {
      resolutions: this.ResolutionsList
    };
  },

  mounted() {
    Echo.channel("resolutions").listen(
      `.ResolutionsChanged\\\\${this.period}`,
      e => {
        this.resolutions = e.resolutions;
      }
    );
  }
};
</script>
