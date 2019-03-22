defmodule TeamdynamixTv.Repo.Migrations.CreateDevices do
  use Ecto.Migration

  def change do
    create table(:devices) do
      add :name, :string
      add :status, :string

      timestamps()
    end

    create unique_index(:devices, [:name])
  end
end
