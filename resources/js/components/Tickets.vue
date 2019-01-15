<template>
  <div>
    <h5 class="text-warning">Unresolved Tickets ({{ tickets.length }})</h5>
    <table class="table table-sm">
      <tbody>
        <tr
          v-for="{id, ticket_id, title, lab, status, age} in listItems.slice(0, 20)"
          :key="id"
          v-on:click="openTicket(ticket_id)"
          :class="colorCode(age, status)"
        >
          <td>{{ ticket_id }}</td>
          <td>{{ title }}</td>
          <td>{{ lab }}</td>
          <td>{{ status }}</td>
          <td>{{ age }}d</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    TicketsList: null
  },

  computed: {
    listItems() {
      return _.orderBy(this.tickets, "ticket_id", "desc");
    }
  },

  data() {
    return {
      tickets: this.TicketsList
    };
  },

  methods: {
    colorCode: function(age, status) {
      if (status == "New") {
        if (age > 1) {
          return "text-danger";
        } else {
          return "text-warning";
        }
      } else if (status == "On Hold") {
        return "text-muted";
      }
    },
    openTicket: function(ticket_id) {
      window.open(
        `https://ecu.teamdynamix.com/TDNext/Apps/217/Tickets/TicketDet.aspx?TicketID=${ticket_id}`
      );
    },
    remove(index) {
      this.$delete(this.tickets, index);
    },
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
    Echo.channel("BroadcastingModelEvent").listen(".App\\Ticket", e => {
      let index = this.findWithAttr(
        this.tickets,
        "ticket_id",
        e.model.ticket_id
      );
      if (e.model.status == "Closed" || e.eventType == "deleted") {
        this.remove(index);
      } else {
        if (index == -1) {
          this.tickets.push(e.model);
        } else {
          this.$set(this.tickets, index, e.model);
        }
      }
    });
  }
};
</script>
