defmodule TeamdynamixTv.Resolution do
  use Ecto.Schema
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
end
