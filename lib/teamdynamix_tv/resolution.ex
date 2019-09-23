defmodule TeamdynamixTv.Resolution do
  use Ecto.Schema
  use EctoConditionals, repo: TeamdynamixTv.Repo
  import Ecto.Changeset

  schema "resolutions" do
    field :closes, :integer
    field :name, :string
    field :resolved_date, :utc_datetime
    field :resp_group, :string

    timestamps()
  end

  @doc false
  def changeset(resolution, attrs) do
    resolution
    |> cast(attrs, [:name, :closes, :resp_group, :resolved_date])
    |> validate_required([:name, :closes, :resp_group, :resolved_date])
  end

  @doc "Gets all resolutions from TeamDynamix"
  def get() do
    # First get our auth token.
    auth_token = get_auth_token()

    # Then get resolutions with our auth token.
    get_resolutions(auth_token)
  end

  def get_resolutions(auth_token) do
    # Start up HTTPoison so we can make requests.
    HTTPoison.start()

    # Make our request for resolutions.
    url = Application.get_env(:teamdynamix_tv, :teamdynamix_settings)[:resolutions_url]
    headers = [Authorization: "Bearer #{auth_token}"]

    HTTPoison.get!(url, headers, params: %{withData: true}).body
    |> Jason.decode!()
    |> Map.get("DataRows")
    |> Enum.each(fn resolution ->
      # Map our resolution string values into atoms.
      resolution_data = Enum.map(resolution, fn {k, v} -> {String.to_atom(k), v} end)

      # Parse our closed date into the correct format.
      parsed_date = Timex.parse!(resolution_data[:"ClosedDate-WeekYear"], "%B %-d, %Y", :strftime)

      # Upsert our resolution.
      case TeamdynamixTv.Repo.get_by(TeamdynamixTv.Resolution,
             name: resolution_data[:ClosedByFullName],
             resp_group: resolution_data[:ResponsibleGroupName],
             resolved_date: parsed_date
           ) do
        nil -> %TeamdynamixTv.Resolution{}
        resolution -> resolution
      end
      |> TeamdynamixTv.Resolution.changeset(%{
        name: resolution_data[:ClosedByFullName],
        closes: resolution_data[:CountTicketID],
        resp_group: resolution_data[:ResponsibleGroupName],
        resolved_date: parsed_date
      })
      |> TeamdynamixTv.Repo.insert_or_update()
    end)
  end

  def get_auth_token() do
    # Start up HTTPoison so we can make requests.
    HTTPoison.start()

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
