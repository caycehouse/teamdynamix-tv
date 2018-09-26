<template>
    <table class="table table-sm">
          <thead>
            <th>ID</th>
            <th>Title</th>
            <th>Lab</th>
            <th>Status</th>
            <th>Created</th>
          </thead>
          <tbody>
              <tr v-for="{id, ticket_id, title, lab, status, ticket_created_at, color_code} in tickets" :key="id" v-on:click="openTicket(ticket_id)" :class="color_code">
                <td>{{ ticket_id }}</td>
                <td>{{ title }}</td>
                <td>{{ lab }}</td>
                <td>{{ status }}</td>
                <td>{{ ticket_created_at }}</td>
              </tr>
          </tbody>
        </table>
</template>

<script>
export default {
  props: {
    TicketsList: null
  },

  data() {
    return {
      tickets: this.TicketsList
    };
  },

  methods: {
    openTicket: function(ticket_id) {
      window.open(
        `https://ecu.teamdynamix.com/TDNext/Apps/217/Tickets/TicketDet.aspx?TicketID=${ticket_id}`
      );
    }
  },

  mounted() {
    Echo.channel("tickets").listen(".TicketsChanged", e => {
      this.tickets = e.ticket;
    });
  }
};
</script>
