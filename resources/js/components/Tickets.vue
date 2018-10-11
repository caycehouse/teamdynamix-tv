<template>
    <div>
        <h5 class="text-warning">Unresolved Tickets</h5>
        <table class="table table-sm">
            <tbody>
                <tr v-for="{id, ticket_id, title, lab, status, age, color_code} in tickets" :key="id" v-on:click="openTicket(ticket_id)" :class="color_code">
                    <td>{{ ticket_id }}</td>
                    <td>{{ title }}</td>
                    <td>{{ lab }}</td>
                    <td>{{ status }}</td>
                    <td>{{ age }}</td>
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
