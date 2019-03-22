defmodule TeamdynamixTv.Repo.Migrations.CreatePrinters do
  use Ecto.Migration

  def change do
    create table(:printers) do
      add :name, :string
      add :status, :string
      add :print_server, :string

      timestamps()
    end

    create unique_index(:printers, [:name])
  end
end
