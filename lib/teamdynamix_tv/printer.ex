defmodule TeamdynamixTv.Printer do
  use Ecto.Schema
  import Ecto.Changeset

  schema "printers" do
    field :name, :string
    field :print_server, :string
    field :status, :string

    timestamps()
  end

  @doc false
  def changeset(printer, attrs) do
    printer
    |> cast(attrs, [:name, :status, :print_server])
    |> validate_required([:name, :status, :print_server])
    |> unique_constraint(:name)
  end
end
