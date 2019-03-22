defmodule Mix.Tasks.Devices.Get do
  use Mix.Task
  use EctoConditionals, repo: TeamdynamixTv.Repo

  @shortdoc "Gets all devices from Papercut"

  def run(_args) do
    # Start up HTTPoison so we can make requests.
    HTTPoison.start

    # Make our request for devices.
    url = Application.get_env(:teamdynamix_tv, :papercut_settings)[:devices_url]
    auth_token = Application.get_env(:teamdynamix_tv, :papercut_settings)[:api_token]
    headers = ["Authorization": "#{auth_token}"]
    HTTPoison.get!(url, headers).body
    |> Jason.decode!
    |> Map.get("devices")
    |> Enum.each(fn device ->
      # Map our device string values into atoms.
      device_data = Enum.map(device, fn({k, v}) -> {String.to_atom(k), v} end)

      # Start up our app before we access the database.
      Mix.Task.run("app.start")

      # Upsert our device.
      %TeamdynamixTv.Device{name: device_data[:name], status: device_data[:state]["status"]}
      |> upsert_by(:name)
    end)
  end
end
