defmodule Mix.Tasks.Tickets.Get do
  use Mix.Task

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
    {:ok, %HTTPoison.Response{body: body}} = HTTPoison.get url, headers, params: %{withData: true}

    body
    |> Poison.decode!
    |> Map.take(~w(DataRows))
    |> Enum.map(fn({k, v}) -> {String.to_atom(k), v} end)
    |> Enum.each(fn ticket ->
      IO.inspect ticket
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
    body = Poison.encode!(%{"username" => username, "password" => password})
    headers = [{"Content-Type", "application/json"}]
    {:ok, %HTTPoison.Response{body: body}} = HTTPoison.post url, body, headers

    # Return our auth token.
    body
  end
end
