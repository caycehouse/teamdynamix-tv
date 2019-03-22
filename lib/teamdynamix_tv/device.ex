defmodule TeamdynamixTv.Device do
  use Ecto.Schema
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
end
