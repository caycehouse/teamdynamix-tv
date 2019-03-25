defmodule Mix.Tasks.Printers.Get do
  use Mix.Task
  use EctoConditionals, repo: TeamdynamixTv.Repo

  @shortdoc "Gets all printers from Papercut"

  def run(_args) do
    # Start up HTTPoison so we can make requests.
    HTTPoison.start

    # Make our request for printers.
    url = Application.get_env(:teamdynamix_tv, :papercut_settings)[:printers_url]
    auth_token = Application.get_env(:teamdynamix_tv, :papercut_settings)[:api_token]
    headers = ["Authorization": "#{auth_token}"]
    HTTPoison.get!(url, headers).body
    |> Jason.decode!
    |> Map.get("printers")
    |> Enum.each(fn printer ->
      # Map our printer string values into atoms.
      printer_data = Enum.map(printer, fn({k, v}) -> {String.to_atom(k), v} end)

      # Start up our app before we access the database.
      Mix.Task.run("app.start")

      # Get our print server.
      print_server = String.split(printer_data[:name], "\\")
      |> Enum.at(0)

      if print_server != "typo" do
        # Calculate our status color.
        status_color = cond do
          print_server == "papercut" -> "text-danger"
          true -> "text-white"
        end

        # Upsert our printer.
        %TeamdynamixTv.Printer{name: printer_data[:name], print_server: print_server,
          status: printer_data[:status], status_color: status_color}
        |> upsert_by(:name)
      end
    end)
  end
end
