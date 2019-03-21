defmodule TeamdynamixTv.Ticket do
  use Ecto.Schema
  import Ecto.Changeset


  schema "tickets" do
    field :days_old, :integer
    field :resp_group, :string
    field :status, :string
    field :ticket_id, :integer
    field :title, :string
    field :url, :string
    field :status_color, :string

    timestamps()
  end

  @doc false
  def changeset(ticket, attrs) do
    ticket
    |> cast(attrs, [:ticket_id, :title, :status, :days_old, :resp_group, :url, :status_color])
    |> validate_required([:ticket_id, :title, :status, :days_old, :resp_group, :url, :status_color])
    |> unique_constraint(:ticket_id)
  end
end
