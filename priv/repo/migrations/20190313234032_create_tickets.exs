defmodule TeamdynamixTv.Repo.Migrations.CreateTickets do
  use Ecto.Migration

  def change do
    create table(:tickets) do
      add :ticket_id, :integer
      add :title, :string
      add :status, :string
      add :days_old, :integer
      add :resp_group, :string

      timestamps()
    end

  end
end
