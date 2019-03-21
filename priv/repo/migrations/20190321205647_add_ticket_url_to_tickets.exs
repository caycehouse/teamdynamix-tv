defmodule TeamdynamixTv.Repo.Migrations.AddTicketUrlToTickets do
  use Ecto.Migration

  def change do
    alter table("tickets") do
      add :url, :string
    end
  end
end
