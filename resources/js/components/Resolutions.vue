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

  methods: {
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
    Echo.channel("BroadcastingModelEvent").listen(".App\\Resolution", e => {
      if (e.model.period == this.period) {
        let index = this.findWithAttr(this.resolutions, "name", e.model.name);
        if (index == -1) {
          this.resolutions.push(e.model);
        } else {
          this.$set(this.resolutions, index, e.model);
        }
      }
    });
  }
};
</script>
