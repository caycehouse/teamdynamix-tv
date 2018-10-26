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
    Echo.channel("BroadcastingModelEvent").listen(
      ".App\\PapercutStatuses",
      e => {
        if (e.eventType == "created" || e.eventType == "updated") {
          let index = this.findWithAttr(
            this.papercutStatuses,
            "name",
            e.model.name
          );
          if (index == -1) {
            this.papercutStatuses.push(e.model);
          } else {
            this.$set(this.papercutStatuses, index, e.model);
          }
        }
      }
    );
  }
};
</script>
