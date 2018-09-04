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
              <tr v-for="{id, ticket_id, title, lab, status, ticket_created_at} in tickets" :key="id">
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

        data(){
            return {
                tickets: this.TicketsList,
                ticket: '',
            }
        },

        mounted() {
            Echo.channel('tickets')
                .listen('.TicketCreated', (e) => {
                    console.log(e.ticket);
                    this.tickets = e.ticket
                });
        }
    }
</script>