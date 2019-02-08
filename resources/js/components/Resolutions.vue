<template>
  <div>
    <h5 class="text-warning">Ticket Resolutions {{ title }} ({{ totalAll }})</h5>
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
    title: null
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
    if (this.title == "This Week") {
      Echo.channel("BroadcastingModelEvent").listen(".App\\Resolution", e => {
        let resp_group = _.replace(
          document.URL.split("/")[3],
          new RegExp("%20", "g"),
          " "
        );
        if (e.model.resp_group === resp_group) {
          let index = this.findWithAttr(this.resolutions, "name", e.model.name);
          if (index == -1) {
            this.resolutions.push(e.model);
          } else {
            this.$set(this.resolutions, index, e.model);
          }
        }
      });
    } else {
      // TODO: Refresh last week's tickets regularly.
    }
  }
};
</script>
