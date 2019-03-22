defmodule TeamdynamixTv.Repo.Migrations.AddStatusColorToPrinters do
  use Ecto.Migration

  def change do
    alter table("printers") do
      add :status_color, :string
    end
  end
end
