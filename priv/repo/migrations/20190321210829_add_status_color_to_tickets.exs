defmodule TeamdynamixTv.Repo.Migrations.AddStatusColorToTickets do
  use Ecto.Migration

  def change do
    alter table("tickets") do
      add :status_color, :string
    end
  end
end
