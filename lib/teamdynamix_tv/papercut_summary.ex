defmodule TeamdynamixTv.PapercutSummary do
  use Ecto.Schema
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
end
