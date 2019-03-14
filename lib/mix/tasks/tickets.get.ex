defmodule Mix.Tasks.Tickets.Get do
  use Mix.Task
  use EctoConditionals, repo: TeamdynamixTv.Repo

  @shortdoc "Gets all new tickets from TeamDynamix"

  def run(_args) do
    # First get our auth token.
    auth_token = get_auth_token()

    # Then get new tickets with our auth token.
    get_new_tickets(auth_token)
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

      # Upsert our ticket.
      %TeamdynamixTv.Ticket{days_old: ticket_data[:DaysOld],
        resp_group: ticket_data[:ResponsibleGroupName], status: ticket_data[:StatusName],
        ticket_id: ticket_data[:TicketID], title: ticket_data[:Title]}
      |> upsert_by(:ticket_id)
    end)
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
    {:ok, %HTTPoison.Response{body: body}} = HTTPoison.post url, body, headers

    # Return our auth token.
    body
  end
end
