defmodule TeamdynamixTv.Device do
  use Ecto.Schema
  use EctoConditionals, repo: TeamdynamixTv.Repo
  import Ecto.Changeset

  schema "devices" do
    field :name, :string
    field :status, :string

    timestamps()
  end

  @doc false
  def changeset(device, attrs) do
    device
    |> cast(attrs, [:name, :status])
    |> validate_required([:name, :status])
    |> unique_constraint(:name)
  end

  @doc "Gets all devices from Papercut"
  def get() do
    # Start up HTTPoison so we can make requests.
    HTTPoison.start()

    # Make our request for devices.
    url = Application.get_env(:teamdynamix_tv, :papercut_settings)[:devices_url]
    auth_token = Application.get_env(:teamdynamix_tv, :papercut_settings)[:api_token]
    headers = [Authorization: "#{auth_token}"]

    HTTPoison.get!(url, headers).body
    |> Jason.decode!()
    |> Map.get("devices")
    |> Enum.each(fn device ->
      # Map our device string values into atoms.
      device_data = Enum.map(device, fn {k, v} -> {String.to_atom(k), v} end)

      # Upsert our device.
      %TeamdynamixTv.Device{name: device_data[:name], status: device_data[:state]["status"]}
      |> upsert_by(:name)
    end)
  end
end
