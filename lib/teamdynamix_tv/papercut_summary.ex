defmodule TeamdynamixTv.PapercutSummary do
  use Ecto.Schema
  use EctoConditionals, repo: TeamdynamixTv.Repo
  import Ecto.Changeset

  schema "papercutsummary" do
    field :name, :string
    field :status, :string
    field :status_color, :string

    timestamps()
  end

  @doc false
  def changeset(papercut_summary, attrs) do
    papercut_summary
    |> cast(attrs, [:name, :status, :status_color])
    |> validate_required([:name, :status, :status_color])
    |> unique_constraint(:name)
  end

  @doc "Gets all summaries from Papercut"
  def get() do
    get_summary(
      Application.get_env(:teamdynamix_tv, :papercut_settings)[:system_url],
      "Papercut System"
    )

    get_summary(
      Application.get_env(:teamdynamix_tv, :papercut_settings)[:print_provider_url],
      "Print Providers"
    )

    get_summary(
      Application.get_env(:teamdynamix_tv, :papercut_settings)[:webprint_url],
      "Web-Print Servers"
    )

    get_summary(
      Application.get_env(:teamdynamix_tv, :papercut_settings)[:mobilityprint_url],
      "Mobility-Print Servers"
    )
  end

  def get_summary(url, name) do
    # Start up HTTPoison so we can make requests.
    HTTPoison.start()

    # Make our request for the summary.
    auth_token = Application.get_env(:teamdynamix_tv, :papercut_settings)[:api_token]
    headers = [Authorization: "#{auth_token}"]

    %{"status" => status} =
      HTTPoison.get!(url, headers).body
      |> Jason.decode!()

    # Calculate our status color.
    status_color =
      cond do
        status != "OK" -> "text-danger"
        true -> "text-success"
      end

    # Upsert our papercut summary.
    %TeamdynamixTv.PapercutSummary{name: name, status: status, status_color: status_color}
    |> upsert_by(:name)
  end
end
