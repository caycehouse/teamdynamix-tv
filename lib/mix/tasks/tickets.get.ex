defmodule Mix.Tasks.Tickets.Get do
  use Mix.Task
  use EctoConditionals, repo: TeamdynamixTv.Repo
  alias TeamdynamixTv.Ticket

  @shortdoc "Gets all new tickets from TeamDynamix"

  def run(_args) do
    # First get our auth token.
    auth_token = get_auth_token()

    # Then get new tickets with our auth token.
    get_new_tickets(auth_token)

    # Finally update every unresolved ticket one by one.
    # Imports only from/2 of Ecto.Query.
    import Ecto.Query, only: [from: 2]

    # Query for our tickets.
    query = from t in "tickets",
              where: t.status != "Closed",
              where: t.status != "Cancelled",
              select: [:id, :ticket_id, :resp_group]

    tickets = TeamdynamixTv.Repo.all(query)

    for t <- tickets do
      # Sleep to prevent going over the rate limit.
      Process.sleep(1001)

      # Update our ticket.
      update_ticket(auth_token, t)
    end
  end

  def get_new_tickets(auth_token) do
    # Start up HTTPoison so we can make requests.
    HTTPoison.start

    # Make our request for new tickets.
    url = Application.get_env(:teamdynamix_tv, :teamdynamix_settings)[:new_tickets_url]
    headers = ["Authorization": "Bearer #{auth_token}"]
    HTTPoison.get!(url, headers, params: %{withData: true}).body
    |> Jason.decode!
    |> Map.get("DataRows")
    |> Enum.each(fn ticket ->
      # Map our ticket string values into atoms.
      ticket_data = Enum.map(ticket, fn({k, v}) -> {String.to_atom(k), v} end)

      # Start up our app before we access the database.
      Mix.Task.run("app.start")

      # Get our ticket url.
      ticket_url = Application.get_env(:teamdynamix_tv, :teamdynamix_settings)[:ticket_url] <> "?TicketID=" <> Integer.to_string(ticket_data[:TicketID])

      # Calculate our status color.
      status_color = cond do
        ticket_data[:StatusName] == "New" && ticket_data[:DaysOld] > 1 -> "text-danger"
        ticket_data[:StatusName] == "New" -> "text-warning"
        ticket_data[:StatusName] == "On Hold" -> "text-muted"
        true -> "text-white"
      end

      # Upsert our ticket.
      %TeamdynamixTv.Ticket{days_old: ticket_data[:DaysOld],
        resp_group: ticket_data[:ResponsibleGroupName], status: ticket_data[:StatusName],
        ticket_id: ticket_data[:TicketID], title: ticket_data[:Title], url: ticket_url, status_color: status_color}
      |> upsert_by(:ticket_id)
    end)
  end

  def update_ticket(auth_token, t) do
    # Start up HTTPoison so we can make requests.
    HTTPoison.start

    # Make our request for the ticket.
    url = Application.get_env(:teamdynamix_tv, :teamdynamix_settings)[:api_ticket_url] <> Integer.to_string(t.ticket_id)
    headers = ["Authorization": "Bearer #{auth_token}"]

    ticket_data = HTTPoison.get!(url, headers).body
    |> Jason.decode!
    |> Enum.map(fn({k, v}) -> {String.to_atom(k), v} end)

    # Start up our app before we access the database.
    Mix.Task.run("app.start")

    # Get our ticket url.
    ticket_url = Application.get_env(:teamdynamix_tv, :teamdynamix_settings)[:ticket_url] <> "?TicketID=" <> Integer.to_string(t.ticket_id)


    # Calculate our status color.
    status_color = cond do
      ticket_data[:StatusName] == "New" && ticket_data[:DaysOld] > 1 -> "text-danger"
      ticket_data[:StatusName] == "New" -> "text-warning"
      ticket_data[:StatusName] == "On Hold" -> "text-muted"
      true -> "text-white"
    end

    # If the ticket no longer belongs to the group delete it.
    if ticket_data[:ResponsibleGroupName] != t.resp_group do
      ticket = TeamdynamixTv.Repo.get!(Ticket, t.id)
      TeamdynamixTv.Repo.delete!(ticket)
    else
      # Upsert our ticket.
      %TeamdynamixTv.Ticket{days_old: ticket_data[:DaysOld],
        resp_group: ticket_data[:ResponsibleGroupName], status: ticket_data[:StatusName],
        ticket_id: t.ticket_id, title: ticket_data[:Title], url: ticket_url, status_color: status_color}
      |> upsert_by(:ticket_id)
    end
  end

  def get_auth_token() do
    # Start up HTTPoison so we can make requests.
    HTTPoison.start

    # Get our TeamDynamix auth secrets.
    url = Application.get_env(:teamdynamix_tv, :teamdynamix_settings)[:auth_url]
    username = Application.get_env(:teamdynamix_tv, :teamdynamix_settings)[:username]
    password = Application.get_env(:teamdynamix_tv, :teamdynamix_settings)[:password]

    # Make our request for an auth token.
    body = Jason.encode!(%{"username" => username, "password" => password})
    headers = [{"Content-Type", "application/json"}]
    HTTPoison.post!(url, body, headers).body
  end
end
